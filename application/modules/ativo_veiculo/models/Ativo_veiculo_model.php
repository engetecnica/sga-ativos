<?php 
require_once __DIR__ . "/../controllers/Ativo_veiculo_trait.php";
require_once __DIR__ . "/Ativo_veiculo_quilometragem_model.php";
require_once __DIR__ . "/Ativo_veiculo_operacao_model.php";
require_once __DIR__ . "/Ativo_veiculo_manutencao_model.php";
require_once __DIR__ . "/Ativo_veiculo_abastecimento_model.php";
require_once __DIR__ . "/Ativo_veiculo_ipva_model.php";
require_once __DIR__ . "/Ativo_veiculo_seguro_model.php";
require_once __DIR__ . "/Ativo_veiculo_depreciacao_model.php";

class Ativo_veiculo_model extends MY_Model {
	use 
	MY_Trait, 
	Ativo_veiculo_trait, 
	Ativo_veiculo_quilometragem_model,
	Ativo_veiculo_manutencao_model, 
	Ativo_veiculo_operacao_model,
	Ativo_veiculo_abastecimento_model,
	Ativo_veiculo_ipva_model,
	Ativo_veiculo_seguro_model,
	Ativo_veiculo_depreciacao_model;

	public $tipos, $tipos_pt, $tipos_vetor;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('configuracao/configuracao_model');
		$this->tipos =  $this->config->item('veiculos_tipos');
		$this->tipos_pt =  $this->config->item('veiculos_tipos_pt');
		$this->tipos_vetor  = $this->config->item('veiculos_tipos_vetor');
	}

	public function salvar_formulario($data=null){
		if($data['id_ativo_veiculo']==''){

			$this->db->insert('ativo_veiculo', $data);
			$data['id_ativo_veiculo'] = $this->db->insert_id();

			/* Salvar LOG */
			$this->salvar_log(7, $data['id_ativo_veiculo'], 'adicionar', $data);

		} else {
			$this->db->where('id_ativo_veiculo', $data['id_ativo_veiculo'])->update('ativo_veiculo', $data);

			/* Salvar LOG */
			$this->salvar_log(7, $data['id_ativo_veiculo'], 'editar', $data);			
		}

		if ($data['id_interno_maquina'] == '' && $data['tipo_veiculo'] == 'maquina') {
			$this->db
				->where('id_ativo_veiculo', $data['id_ativo_veiculo'])
				->update('ativo_veiculo', ["id_interno_maquina" => $this->get_id_maquina($data['id_ativo_veiculo'])]);

				/* Salvar LOG */
				$this->salvar_log(7, $data['id_ativo_veiculo'], 'editar', $data);	
		}
		return $data['id_ativo_veiculo'];
	}

	public function query(){
		$this->db->reset_query();

		$where_limit = "where id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo";
		$order_quilometragem = "order by `id_ativo_veiculo_quilometragem` desc limit 1";
		$order_operacao =  "order by `id_ativo_veiculo_operacao` desc limit 1";
		$order_depreciacao =  "order by `id_ativo_veiculo_depreciacao` desc limit 1";
		$select_km_atual = "(select `veiculo_km` from ativo_veiculo_quilometragem {$where_limit} {$order_quilometragem})";
		$select_km_atual_data = "(select `data` from ativo_veiculo_quilometragem {$where_limit} {$order_quilometragem})";
		$select_horimetro_atual = "(select `veiculo_horimetro` from ativo_veiculo_operacao {$where_limit} {$order_operacao})";
		$select_horimetro_atual_data = "(select `data` from ativo_veiculo_operacao {$where_limit} {$order_operacao})";
		$select_fipe_valor_atual = "(select `fipe_valor` from ativo_veiculo_depreciacao {$where_limit} {$order_depreciacao})";
		$select_fipe_valor_atual_data = "(select `data` from ativo_veiculo_depreciacao {$where_limit} {$order_depreciacao})";

		$query = $this->db
		->from('ativo_veiculo')
		->select("*")
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
		->select("concat(marca,' - ',modelo) as veiculo_descricao")
		->select("
			(
				CASE
					WHEN veiculo_placa IS NOT NULL THEN veiculo_placa
					ELSE id_interno_maquina
				END
			) as veiculo_identificacao
		")
		->group_by('id_ativo_veiculo');

		$this->join_obra($query, 'ativo_veiculo.id_obra');

		return $query;
	}

	//@todo remove
	public function search_ativos($search){
		return $this->query()
			->group_by('id_ativo_veiculo')
			->order_by('id_ativo_veiculo', 'desc')
			->like('veiculo', $search)
			->or_like('veiculo_placa', $search)
			->or_like('id_interno_maquina', $search)
			->or_like('codigo_fipe', $search)
			->or_like('id_ativo_veiculo', $search)
			->or_like('data', $search)
			->get()->result();
	}

	public function get_categoria_lista(){
		return $this->query()
				->where('id_ativo_veiculo_vinculo', 0)
				->get()->result();
	}

	//@todo remove
	public function set_outros_dados_veiculo(stdClass $veiculo = null){
		if ($veiculo) {
			if ($veiculo->tipo_veiculo == "maquina" && !$veiculo->id_interno_maquina)
				$veiculo->id_interno_maquina = $this->get_id_maquina($veiculo->id_ativo_veiculo);
		}
		return $veiculo;
	}

	//@todo remove
	public function get_lista($page = null, $limit = null){
		$veiculos = $this->query();
		if ($page && $limit) $veiculos->limit(((int) $page * (int) $limit), (int) $page - 1);
		$lista = $veiculos->get()->result();
		return array_map(function($veiculo) {return $this->set_outros_dados_veiculo($veiculo);}, $lista);
	}

	public function get_ativo_veiculo($id_ativo_veiculo, $coluna = "id_ativo_veiculo"){
        return $this->set_outros_dados_veiculo($this->query()->where($coluna, $id_ativo_veiculo)->get()->row());
    }

	public function get_extrato($tipo = 'km', $id_ativo_veiculo = null, $returnObject = true){
		$column = "veiculo_km";
		$table = "ativo_veiculo_quilometragem";
		if($tipo === 'operacao') {
			$table = "ativo_veiculo_operacao";
			$column = "veiculo_horimetro";
		}

		$veiculo = $this->get_ativo_veiculo($id_ativo_veiculo);
		if ($veiculo) {
			if($veiculo->$column === 0 || $veiculo->$column === null) $veiculo->$column = 1;
			$credito_select = "((select {$column}_proxima_revisao from ativo_veiculo_manutencao where (id_ativo_veiculo = atv.id_ativo_veiculo AND ({$column}_proxima_revisao IS NOT NULL AND {$column}_proxima_revisao > 0)) order by id_ativo_veiculo_manutencao desc limit 1) - {$veiculo->$column})";
			$debito_select = "((select {$column} from {$table} where id_ativo_veiculo = atv.id_ativo_veiculo order by id_{$table} desc limit 1) - {$veiculo->{$column}})";
			$valor_atual = "(select {$column} from {$table} where id_ativo_veiculo = atv.id_ativo_veiculo order by id_{$table} desc limit 1)";
			
			$extrato = $this->db
				->select("atv.id_ativo_veiculo as id_ativo_veiculo, $credito_select as credito, $debito_select as debito")
				->select("($credito_select - $debito_select - 1) as saldo, $valor_atual as {$column}_atual")
				->where("atv.id_ativo_veiculo = {$id_ativo_veiculo}")
				->get('ativo_veiculo atv')->row();

			if ($extrato) {
				$extrato->tipo = $tipo === 'operacao' ? "Horas" : "KM";
				return $returnObject ? $extrato : $extrato->saldo;
			}
		}
		return null;
	}	
	
	public function get_tipo_servico($id_ativo_configuracao=null){
		$this->db
				->where("(id_ativo_configuracao_vinculo={$id_ativo_configuracao})")
				->where("situacao = '0'");

		return $this->db->group_by('id_ativo_configuracao')
										->get('ativo_configuracao')
										->result();
	}	

	//@todo remove
	public function get_fornecedores(){
		$this->db->order_by("razao_social", "asc")->where("situacao = '0'");;
		return $this->db->group_by('id_fornecedor')->get('fornecedor')->result();
	}

	//@todo remove
	public function get_combustiveis(){
		$configuracao = $this->configuracao_model->get_configuracao(1);
		try {
			require(APPPATH."/config/combustiveis.php");
			return get_combustiveis($configuracao);
		} catch (\Exception $e){
			return [];
		}
	}

	public function permit_delete($id_ativo_veiculo){
		return !$id_ativo_veiculo || !in_array(true, [
			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_manutencao")
				->num_rows() >= 1,

			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_ipva")
				->num_rows() >= 1,
			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_seguro")
				->num_rows() >= 1,
			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_quilometragem")
				->num_rows() >= 1,
			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_operacao")
				->num_rows() >= 1,
			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_abastecimento")
				->num_rows() >= 1,
		]);
	}

	public function get_id_maquina($id_ativo_veiculo) : string
	{
		return strtoupper("ENG-MAQ-".str_pad($id_ativo_veiculo, 4, '0', STR_PAD_LEFT));
	}

	public function get_historico_veiculo($id_ativo_veiculo){
		return $this->db
				->select('a.*, b.veiculo, b.marca, b.modelo, c.codigo_obra')
				->join('ativo_veiculo b', 'b.id_ativo_veiculo=a.id_veiculo')
				->join('obra c', 'c.id_obra=a.id_obra')
				->where('a.id_veiculo', $id_ativo_veiculo)
				->where('a.deleted_at', null)
				->order_by('a.created_at', 'DESC')
				->get('ativo_veiculo_obra a')
				->result();
	}

	public function excluir_historico($id_veiculo_obra){
		return $this->db->where('id_veiculo_obra', $id_veiculo_obra)->update('ativo_veiculo_obra', array('deleted_at' => date("Y-m-d H:i:s")));
	}

	public function formata_mes_referecia($mes = 1, $ano = null){
    	$referencia = null;
    	$meses_ano = $this->config->item('meses_ano');
    	array_filter($meses_ano, function($m) use ($mes, &$referencia) {
    	  if ($m['id'] === (int) $mes) $referencia = $m;
    	});
    	return isset($referencia['nome']) ? "{$referencia['nome']} de {$ano}" : $mes;
    }

    public function get_mes_referecia($mes_referencia){
		$referencia = explode("de", $mes_referencia);
		$meses_ano = $this->config->item('meses_ano');
		if(count($referencia) === 2) {
			$mes = trim(strtolower($referencia[0]));
			$ano = trim(strtolower($referencia[1]));  
			if($meses_ano[$mes]) {
			return (object) [
				"mes" => $meses_ano[$mes]['nome'],
				"id" =>$meses_ano[$mes]['id'],
				"ano" => $ano
			];
			}
		}
		return null;
    }
}