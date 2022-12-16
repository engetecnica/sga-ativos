<?php

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Request as HttpClientRequest;
use GuzzleHttp\Psr7\Response as HttpClientResponse;

trait MY_Trait
{
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

	protected $meses_ano = [];

	public function get_situacao($status = null, $case2 = 'Descartado', $case2_class = 'info')
	{
		$texto = "Ativo";
		$class = "success";

		switch ((int) $status) {
			case 1:
				$texto = "Inativo";
				$class = "danger";
				break;
			case 2:
				$texto = $case2;
				$class = $case2_class;
				break;
		}

		return [
			'texto' => $texto,
			'class' => $class
		];
	}

	public function get_usuario_nivel($nivel = null)
	{
		$texto = "Desconhecido";
		$class = "danger";

		switch ($nivel) {
			default:
			case 0:
				$class = "info";
				break;
			case 1:
				$texto = "Administrador";
				$class = "info";
				break;
			case 2:
				$texto = "Almoxarifado";
				$class = "warning";
				break;
		}

		return [
			'texto' => $texto,
			'class' => $class
		];
	}

	public function get_obra_base($status = null)
	{
		switch ($status) {
			case 0:
				$status = "NÃO";
				break;
			case 1:
				$status = "SIM";
				break;
		}
		return $status;
	}

	public function get_situacao_transporte($status = null)
	{
		switch ($status) {
			case 1:
				$status = "Pendente";
				break;
			case 2:
				$status = "Em Andamento";
				break;
			case 3:
				$status = "Entregue";
				break;
			case 4:
				$status = "Concluído";
				break;
		}
		return $status;
	}

	public function get_situacao_manutencao($status = null)
	{
		$texto = $class = "";
		switch ((int) $status) {
			default:
			case 0:
				$texto = "Em Manutenção";
				$class = "warning";
				break;
			case 1:
				$texto = "Manutenção OK";
				$class = "success";
				break;
			case 2:
				$texto =  "Manutenção Com Pedência";
				$class = "danger";
				break;
		}

		return [
			'texto' => $texto,
			'class' => $class
		];
	}

	public function get_situacao_insumo($status = null)
	{
		$texto = $class = "";
		switch ((int) $status) {
			default:
			case 0:
				$texto = "Pendente";
				$class = "warning";
				$slug = "pendente";
				break;
			case 1:
				$texto = "Entregue";
				$class = "success";
				$slug = "entregue";
				break;
			case 2:
				$texto =  "Devolvido";
				$class = "danger";
				$slug = "devolvido";
				break;
			case 3:
				$texto =  "Devolvido Parcialmente";
				$class = "danger";
				$slug = "devolvidoparcialmente";
				break;	
			case 5:
				$texto =  "Cancelado";
				$class = "danger";
				$slug = "cancelado";
				break;								
		}

		return [
			'texto' => $texto,
			'class' => $class,
			'slug'  => $slug
		];
	}	

	public function status_lista($type = 'object')
	{
		$lista = $this->session->status_lista;
		if (!$lista) {
			$lista = $this->ferramental_requisicao_model->get_requisicao_status();
			$this->session->status_lista = json_encode($lista);
		}

		return array_map(function ($item) use ($type) {
			$new_item = [
				'texto' => $item->texto,
				'class' => $item->classe,
				'slug' => $item->slug,
				'id_status' => $item->id_requisicao_status
			];

			if ($type === 'object') {
				return (object) $new_item;
			}
			return $new_item;
		}, is_string($lista) ? json_decode($lista) : $lista);
	}

	public function status($status = null)
	{
		$lista = $this->status_lista();
		$texto = "Desconhecido";
		$class = "muted";
		$slug = "desconhecido";

		foreach ($lista as $item) {
			if ($item->id_status == $status) {
				$texto = $item->texto;
				$class = $item->class;
				$slug = $item->slug;
			}
		}

		return [
			'texto' => $texto,
			'class' => $class,
			'slug' => $slug,
		];
	}

	public function status_by_name($status = null)
	{
		$lista = $this->status_lista();
		$texto = "Desconhecido";
		$class = "muted";
		$slug = "desconhecido";

		foreach ($lista as $item) {
			if ($item->slug == $status) {
				$texto = $item->texto;
				$class = $item->class;
				$slug = $item->slug;
			}
		}

		return [
			'texto' => $texto,
			'class' => $class,
			'slug' => $slug,
		];
	}

	public function status_is($id, $name): bool
	{
		$lista = $this->status_lista();
		foreach ($lista as $item) {
			if ($item->id_status == $id && $item->slug == $name) {
				return true;
			}
		}
		return false;
	}

	public function formata_moeda($valor = 0)
	{
		return "R$ " . number_format($valor, 2, ',', '.');
	}

	public function formata_moeda_float(string $valor)
	{
		$formatado = str_replace("R$ ", "", $valor);
		$formatado = str_replace(".", "", $formatado);
		return (float) str_replace(",", ".", $formatado);
	}

	public function formata_data_hora($data_hora = null)
	{
		return $data_hora == "0000-00-00 00:00:00" || !$data_hora ? "-" : date("d/m/Y H:i:s", strtotime($data_hora));
	}

	public function formata_data($data = null)
	{
		return $data == "0000-00-00" || !$data ? "-" : date("d/m/Y", strtotime($data));
	}

	public function get_ativo_externo_on_lista($lista, $id)
	{
		foreach ($lista as $item) {
			if ($item->id_ativo_externo == $id) {
				return $item;
			}
		}
		return null;
	}

	public function formata_posfix($valor = null, $posfix = "")
	{
		return isset($valor) ? "{$valor} {$posfix}" : "-";
	}

	public function formata_prefix($valor = null, $prefix = "")
	{
		return isset($valor) ? " {$prefix} {$valor}" : "-";
	}

	public function formata_array(array $valor = [], $separador = " ")
	{
		return isset($valor) ? implode($separador, $valor) : "-";
	}

	public function formata_mes_referecia($mes = 1, $ano = null)
	{
		$referencia = null;
		$meses_ano = $this->config->item('meses_ano');
		array_filter($meses_ano, function ($m) use ($mes, &$referencia) {
			if ($m['id'] === (int) $mes) $referencia = $m;
		});
		return isset($referencia['nome']) ? "{$referencia['nome']} de {$ano}" : $mes;
	}

	public function get_mes_referecia($mes_referencia)
	{
		$referencia = explode("de", $mes_referencia);
		$meses_ano = $this->config->item('meses_ano');
		if (count($referencia) === 2) {
			$mes = trim(strtolower($referencia[0]));
			$ano = trim(strtolower($referencia[1]));
			if ($meses_ano[$mes]) {
				return (object) [
					"mes" => $meses_ano[$mes]['nome'],
					"id" => $meses_ano[$mes]['id'],
					"ano" => $ano
				];
			}
		}
		return null;
	}

	public function sem_acesso()
	{
		$this->session->set_flashdata('msg_erro', "Você não possui acesso a este módulo.");
		echo redirect(base_url());
		return;
	}

	public function getRef($redirect_to = "/"): string
	{
		if ($redirect_to === "/" && (isset($_SERVER['SERVER_PORT']) && isset($_SERVER['HTTP_REFERER']))) {
			$redirect_to = str_replace(base_url(), "/", $_SERVER['HTTP_REFERER']);
			if (in_array($_SERVER['SERVER_PORT'], ['80', '443'])) {
				$redirect_to = str_replace(str_replace(":{$_SERVER['SERVER_PORT']}", "", base_url()), "/", $_SERVER['HTTP_REFERER']);
			}
		}
		return $redirect_to;
	}

	public function dd(...$data)
	{
		foreach ($data as $dt) {
			echo "<pre>";
			echo print_r($dt, true);
			echo "</pre>";
		}
		exit;
	}

	public function formata_moeda_model($valor = 0, $num_format = false){
        if($num_format){
            return  number_format($valor, 2, '.', '');
        }
        return "R$ ". number_format($valor, 2, ',', '.');
    }

	public function createHttpClient($base_uri = "")
	{
		$this->httpClientObject = new HttpClient(["base_uri" => $base_uri]);
	}

	public function httpClient(string $url, string $method = "GET", array $body = null, $headers = []): HttpClientResponse
	{
		if (!$this->httpClientObject) $this->createHttpClient();

		$headers = array_merge(["Content-type" => "application/json"], $headers);
		$request = new HttpClientRequest($method, $url, $headers, $body ? json_encode($body) : "");
		$response = $this->httpClientObject->sendAsync($request);

		if ($response instanceof Promise) {
			return Utils::unwrap([$url => $response])[$url];
		}
		return $response;
	}

	/* 
	@ Trait 
	@ Permissions
	*/

	public function get_user_permission()
	{

		$permissoes = json_encode([]);
		if (isset($this->user->permissoes)) {
			$permissoes = $this->user->permissoes;
		}
		return json_decode($permissoes);
	}

	public function get_modulo_permission()
	{
		$permissao = $this->get_user_permission();
		$modulo_atual = $this->uri->segment(1);
		$modulos_permitidos = [];
		$relatorios_permitidos = [];
		$acoes_permitidas = [];

		foreach (($permissao ?? []) as $model => $action) {
			if (is_numeric($model)) {
				$modulos_permitidos[$model] = $this->db->where('id_modulo', $model)->get('modulo')->row('rota');

				if ($modulos_permitidos[$model] == $modulo_atual) {
					$acoes_permitidas[$model] = $action;
				}
			} else {
				$relatorios_permitidos[] = $model;
			}
		}

		$modulo_permitido = array_search($modulo_atual, $modulos_permitidos);

		if ($modulo_permitido == true && $acoes_permitidas == true) {
			$dados['acao_permitida'] = $acoes_permitidas;
			$dados['modulo_permitido'] = $modulos_permitidos;
			return $dados;
		} else {
			return $relatorios_permitidos;
		}

		if ($modulo_permitido == null && $relatorios_permitidos == null) {
			$this->session->set_flashdata('msg_erro', "Você não possui acesso a este módulo.");
			echo redirect(base_url());
		}
	}

	public function permitido($permissoes = null, $id_modulo = null, $acao = null)
	{
		foreach (($permissoes['acao_permitida'] ?? []) as $action) {
			if (isset($action->$acao) && $action->$acao == "on") {
				return 1;
			}
			return 0;
		}
	}

	public function permitido_redirect($permitido)
	{
		if ($permitido == false) {
			$this->session->set_flashdata('msg_erro', "Ação não permitida!");
			echo redirect(base_url());
		}
	}

	public function modulos_permitidos()
	{
		$modulos = $this->db
			->where('id_vinculo', 0)
			->get('modulo')
			->result();

		$permissao = $this->get_user_permission();
		$modulos_permitidos = ['modulos' => []];
		$relatorios_permitidos = array(); 

		foreach (($permissao ?? []) as $model => $action) {
			if (is_numeric($model)) {
				$modulos_permitidos['modulos'][$model] = $this->db->where('id_modulo', $model)->get('modulo')->row('rota');
			} else {
				$relatorios_permitidos[$model] = 1;
			}
		}

		foreach ($modulos as &$modulo) {
			$submodulos = $this->db->where('id_vinculo', $modulo->id_modulo);
			if (!empty($modulos_permitidos['modulos'])) $submodulos->where_in('rota', $modulos_permitidos['modulos']);
			$modulo->submodulos = $submodulos->get('modulo')->result();
		}

		$modulos['relatorios_permitidos'] = $relatorios_permitidos;
		return $modulos;
	}


	/* 
	@ Trait 
	@ Logs
	*/	


	function registrar_log($data = null, $retorno = null){

		if($data==null) return [];


		$retorno = $this->config->item('messages_fallback')[$retorno];


		




	//	$this->dd($data, $retorno);


	}




	/* 
		@id_modulo
		@id_usuario
		@acao (adicionar, editar e excluir)
		@historico
		@created_at
	*/
	public function salvar_log($id_modulo = null, $id_item = null, $acao = null, $data = null, $extra = null, $retorno = null)
	{

		// Buscar nome do Módulo
		$modulo = (is_numeric($id_modulo)) ? $this->db->where('id_modulo', $id_modulo)->get('modulo')->row('titulo') : $id_modulo;
		$acao_texto = $this->salvar_log_condicao($acao, $id_item);
		$usuario = $this->user->usuario;

		$resumo['id_item'] = $id_item;
		$resumo['id_modulo'] = $id_modulo;
		$resumo['id_usuario'] = $this->user->id_usuario;
		$resumo['acao'] = $acao;
		$resumo['retorno'] = $retorno;
		
			if($acao=='retorno'){
				$resumo['historico'] = "";
			} else {
				$resumo['historico'] = "<b>{$usuario}</b> {$acao_texto} pertencente ao módulo de <b>{$modulo}</b> em ".date("d/m/Y H:i").". (".$extra.")";
			}
		
		$resumo['dados'] = json_encode($data);

		// Insere registro de Log
		return $this->db->insert('logs', $resumo);
		return 'sucesso';
	}

	public function salvar_log_condicao($condicao, $id_item = null){

		switch($condicao):

			case NULL:
				$condicao = "Desconhecido";
				break;

			case "adicionar":
				$condicao = "adicionou um novo registro";
				break;

			case "editar":
				$condicao = "editou o registro ID " . $id_item;
				break;

			case "excluir":
				$condicao = "excluiu o registro ID " . $id_item;
				break;

			case "descartar":
				$condicao = "descartou o registro ID " . $id_item;
				break;

			case "desfazer":
				  $condicao = "desfez o descarte do registro ID " . $id_item;
				  break;

			case "retorno":
				$condicao = "retornou um erro";
				break;

			case "adicionar_estoque":
				$condicao = "adicionou ao estoque ".$id_item;
				break;

			case "erro_estoque":
				$condicao = "erro ao adicionar estoque no ".$id_item;
				break;

			case "insumo_marcar_entregue_retirada":
				$condicao = "marcou como entregue o estoque da retirada ID ".$id_item;
				break;

			case 'insumo_marcar_entregue_retirada':
				$condicao = "marcou como entregue a retirada ID ".$id_item;
				break;			

			case 'insumo_devolvido_parcialmente':
				$condicao = "devolveu parcialmente insumo da retirada ID ".$id_item;
				break;		
			
			case 'insumo_cancelar_retirada':
				$condicao = "cancelou a retirada ID ".$id_item;
				break;					

		endswitch;

		return $condicao;

	}

	public function salvar_log_usuarios($usuario_id, $usuario_nome)
	{
		$resumo['id_item'] = null;
		$resumo['id_modulo'] = 2; //modulo USUARIOS
		$resumo['id_usuario'] = $usuario_id;
		$resumo['acao'] = 'acessou';
		$resumo['historico'] = "<b>{$usuario_nome}</b> acessou o <b>sistema</b> em ".date('d/m/Y H:i')."";
		$resumo['retorno'] = null;
		$resumo['dados'] = null;
		return $this->db->insert('logs', $resumo);
	}

}
