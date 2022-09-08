<?php
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Request as HttpClientRequest;
use GuzzleHttp\Psr7\Response as HttpClientResponse;

class MY_model extends CI_Model {
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * @var HttpClient
     */
    protected $httpClientObject;

     /**
     * @var HttpClientRequest
     */
    protected $httpClientRequest;

    protected $user;

    use MY_Trait;

    public function __construct()
    {
        parent::__construct();
        $this->user = $this->buscar_dados_logado($this->session->userdata('logado'));
    }

    public function formatArrayReplied($items = [], $id_item = null){
        $lista = [];
        if ((count($items) > 0) && $id_item) {
            foreach($items as $item) {
                if (!isset($lista[$item->{$id_item}])) {
                    $lista[$item->{$id_item}] = (object) $item;
                }
            } 
        }
		return $lista;
    }

    public function buscar_dados_logado($logado=null){
        if($logado) {
            $user = $this->db
            ->select('usuario.*')
            ->select("empresa.razao_social, empresa.nome_fantasia, empresa.cnpj")
            ->where("usuario.id_usuario = {$logado->id_usuario}")
            ->join('empresa', "empresa.id_empresa = usuario.id_empresa")
            ->get('usuario')
            ->row();
        
            if ($user) {
                if($user->nivel == 1 && $user->id_obra_gerencia) $user->id_obra = $user->id_obra_gerencia;
                unset($user->senha);
                return $user;
            }

            unset($logado->senha);
            return $logado;
        }
        return null;
    }

    public function get_obra_base(){
        return $this->db
            ->select('ob.*')
            ->from('obra ob')
            ->where('obra_base = 1')
            ->group_by('ob.id_obra')
            ->get()
            ->row();
    }

    public function join_requisicao_status(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        return $query
            ->select('st.slug as status_slug, st.texto as status_texto, st.classe as status_classe')
            ->join('ativo_externo_requisicao_status st', "{$col} = st.id_requisicao_status", $join_type);
    }

    public function join_funcionario(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        return $query
        ->select('fn.cpf as funcionario_cpf, fn.rg as funcionario_rg, fn.celular as funcionario_celular, fn.nome as funcionario')
        ->join('funcionario fn', "{$col} = fn.id_funcionario", $join_type);
    }

    public function join_obra(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        return $query
        ->select('
            ob.id_obra, ob.responsavel, ob.responsavel_celular, 
            ob.codigo_obra as obra, ob.codigo_obra codigo_obra, 
            ob.endereco as obra_endereco'
        )
        ->join('obra ob', "{$col} = ob.id_obra", $join_type);
    }

    public function join_empresa(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        return $query
			->select('ep.razao_social as empresa_razao, ep.razao_social, ep.nome_fantasia as empresa') 
			->join("empresa ep", "{$col} = ep.id_empresa", $join_type);
    }

    public function join_fornecedor(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        return $query
			->select('fnc.razao_social as fornecedor_razao, fnc.razao_social, fnc.nome_fantasia as fornecedor') 
			->join("fornecedor fnc", "{$col} = fnc.id_fornecedor", $join_type);
    }

    public function join_usuario_nivel(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        return $query
			->select('un.nivel as nivel_nome, un.id_usuario_nivel as nivel')
			->join("usuario_nivel un", "{$col} = un.id_usuario_nivel", $join_type);
    }

    public function join_veiculo(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        $alias = 'veiculo';
        $where_limit = "where id_ativo_veiculo = veiculo.id_ativo_veiculo";
		$order_quilometragem = "order by `id_ativo_veiculo_quilometragem` desc limit 1";
		$order_operacao =  "order by `id_ativo_veiculo_operacao` desc limit 1";
		$order_depreciacao =  "order by `id_ativo_veiculo_depreciacao` desc limit 1";
		$select_km_atual = "(select `veiculo_km` from ativo_veiculo_quilometragem {$where_limit} {$order_quilometragem})";
		$select_km_atual_data = "(select `data` from ativo_veiculo_quilometragem {$where_limit} {$order_quilometragem})";
		$select_horimetro_atual = "(select `veiculo_horimetro` from ativo_veiculo_operacao {$where_limit} {$order_operacao})";
		$select_horimetro_atual_data = "(select `data` from ativo_veiculo_operacao {$where_limit} {$order_operacao})";
		$select_fipe_valor_atual = "(select `fipe_valor` from ativo_veiculo_depreciacao {$where_limit} {$order_depreciacao})";
		$select_fipe_valor_atual_data = "(select `data` from ativo_veiculo_depreciacao {$where_limit} {$order_depreciacao})";
        $table_alias = $alias ? "{$alias}." : '';
        
		$query = $this->db
		->select("
			$select_fipe_valor_atual as veiculo_valor_atual,
			$select_fipe_valor_atual_data as veiculo_valor_atual_data
		")
		->select("
			$select_km_atual as veiculo_km_atual, 
			$select_km_atual_data as veiculo_km_atual_data
		")
		->select("
			$select_horimetro_atual as veiculo_horimetro_atual,
		 	$select_horimetro_atual_data as veiculo_horimetro_atual_data
		")
		->select("concat({$table_alias}marca,' - ',{$table_alias}modelo) as veiculo_descricao")
		->select("
			(
				CASE
					WHEN {$table_alias}veiculo_placa IS NOT NULL THEN {$table_alias}veiculo_placa
					ELSE {$table_alias}id_interno_maquina
				END
			) as veiculo_identificacao
		")
        ->join("ativo_veiculo {$alias}", "{$col} = {$table_alias}id_ativo_veiculo", $join_type);

        return $query;
    }

    public function join_status(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        return $query
			->select('status.slug as status_slug, status.texto as status_texto, status.classe as status_classe')
			->join("ativo_externo_requisicao_status status", "{$col} = status.id_requisicao_status", $join_type);
    }
}