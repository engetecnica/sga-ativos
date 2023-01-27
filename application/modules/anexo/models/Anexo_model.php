<?php
class Anexo_model extends MY_Model
{

	public $tipos, $modulos;
	use MY_Trait;

	public function __construct()
	{
		parent::__construct();


		$this->modulos = [
			[
				"nome" => "Ativos Externos - Ferramentas",
				"rota" => "ativo_externo",
			],
			[
				"nome" => "Ativos Internos - Equipamentos",
				"rota" => "ativo_interno",
			],
			[
				"nome" => "Ativos Veículos - Veículos",
				"rota" => "ativo_veiculo",
			],
			[
				"nome" => "Ferramental Estoque",
				"rota" => "ferramental_estoque",
			]
		];

		$this->tipos = [
			[
				"nome" => "Recibo Compra",
				"slug" => "compra",
				"modulos" =>  ['ativo_interno', 'ativo_externo', 'ativo_veiculo']
			],
			[
				"nome" => "Recibo Manutenção",
				"slug" => "manutencao",
				"modulos" =>  ['ativo_interno', 'ativo_externo', 'ativo_veiculo']
			],
			[
				"nome" => "Declaração de Descarte",
				"slug" => "descarte",
				"modulos" =>  ['ativo_interno', 'ativo_externo', 'ativo_veiculo']
			],
			[
				"nome" => "Certificado de Calibação/Aferiação",
				"slug" => "certificado_de_calibracao",
				"modulos" =>  ['ativo_externo']
			],
			[
				"nome" => "Comprovante de Quilometragem",
				"slug" => "quilometragem",
				"modulos" =>  ['ativo_veiculo']
			],
			[
				"nome" => "Recibo de Abastecimento",
				"slug" => "abastecimento",
				"modulos" =>  ['ativo_veiculo']
			],
			[
				"nome" => "Recibo Pagamento de IPVA",
				"slug" => "ipva",
				"modulos" =>  ['ativo_veiculo']
			],
			[
				"nome" => "Contrato de Seguro",
				"slug" => "seguro",
				"modulos" =>  ['ativo_veiculo']
			],
			[
				"nome" => "Termo de Responsabilidade",
				"slug" => "termo_de_responsabilidade",
				"modulos" => ['ferramental_estoque']
			],
			[
				"nome" => "Ordem de Serviço",
				"slug" => "ordem_de_servico",
				"modulos" =>  ['ativo_veiculo']
			],
			[
				"nome" => "Romaneio de Requisição",
				"slug" => "romaneio",
				"modulos" =>  ['ferramental_requisicao']
			],
			[
				"nome" => "Outro",
				"slug" => "outro",
				"modulos" => ['ativo_interno', 'ativo_externo', 'ativo_veiculo', 'ferramental_requisicao', 'ferramental_estoque']
			]
		];

		$this->load->model("ativo_veiculo/ativo_veiculo_model");
	}

	public function salvar_formulario($data)
	{

		if (!isset($data['id_anexo'])) {

			# Log
			$this->salvar_log(($data['id_modulo_item'] ?? null), $data['id_anexo'], 'adicionar', $data, $data['tipo']);

			# Insere Anexo no Banco de Dados
			$this->db->insert('anexo', $data);
			return $this->db->affected_rows() ? $this->db->insert_id() : null;

		} else {

			//	$data['id_anexo_pai'] = $this->input->post('id_anexo');

			# Atualiza Anexo no Banco de Dados
			$this->db->where('id_anexo', $data['id_anexo']);
			$update = $this->db->update('anexo', $data);

			if ($update) {

				# Log 
				$this->salvar_log(($data['id_modulo_item'] ?? null), $data['id_anexo'], 'editar', $data, $data['tipo']);

				# Retorno
				return $this->input->post('id_anexo');
			} else {
				return null;
			}
			
		}
	}


	public function query_anexos()
	{
		$this->db->reset_query();
		return $this->db
			->from('anexo')
			->select('anexo.*')
			->join("modulo md", "md.id_modulo = anexo.id_modulo", "left")
			->select('md.titulo as modulo_titulo, md.rota as modulo_rota')
			->group_by('id_anexo')->order_by('id_anexo', 'desc');
	}

	public function anexos(
		$id_modulo = null,
		$id_modulo_item = null,
		$tipo = null,
		$id_modulo_subitem = null
	) {
		$anexos = $this->query_anexos();
		if ($tipo) $anexos->where("anexo.tipo = '{$tipo}'");

		if ($id_modulo) {
			if (is_array($id_modulo)) {
				$anexos->where("anexo.id_modulo IN (" . implode(',', $id_modulo) . ")");
			} else {
				$anexos->where("anexo.id_modulo = {$id_modulo}");
			}
		}

		if ($id_modulo_item) {
			if (is_array($id_modulo_item)) {
				$anexos->where("anexo.id_modulo_item IN (" . implode(',', $id_modulo_item) . ")");
			} else {
				$anexos->where("anexo.id_modulo_item = {$id_modulo_item}");
			}
		}

		if ($id_modulo_subitem) {
			if (is_array($id_modulo_subitem)) {
				$anexos->where("anexo.id_modulo_subitem IN (" . implode(',', $id_modulo_subitem) . ")");
			} else {
				$anexos->where("anexo.id_modulo_subitem = {$id_modulo_subitem}");
			}
		}
		$anexos->where("anexo.id_anexo_pai IS NULL");

		return $anexos;
	}


	public function get_anexos(
		$id_modulo = null,
		$id_modulo_item = null,
		$tipo = null,
		$id_modulo_subitem = null,
		$pagina = null,
		$limite = 50
	) {
		$anexos = $this->anexos($id_modulo, $id_modulo_item, $tipo, $id_modulo_subitem);
		if (($limite && $pagina) && ($pagina >= 1)) $anexos->limit($limite, (($limite * $pagina) - 1));
		$lista = $anexos->get()->result();	

			foreach($lista as &$valor){
				$valor->historico = $this->db->where('id_anexo_pai', $valor->id_anexo)->get('anexo')->result();
			}
		return $lista;
	}

	public function get_anexo($id_anexo)
	{
		return $this->anexos()->where('id_anexo', $id_anexo)->get()->row();
	}

	public function get_anexo_by_name($anexo)
	{
		return $this->query_anexos()->like('anexo', $anexo)->get()->row();
	}

	public function get_anexo_tipo($slug)
	{
		foreach ($this->tipos as $tipo) {
			if ($tipo['slug'] === $slug) {
				return $tipo;
			}
		}
		return ['nome' => '-'];
	}

	public function getOrphans($dir = "anexo", $table = "anexo")
	{
		$path = __DIR__ . "/../../../../assets/uploads/{$dir}";
		$anexos = $this->db->get($table)->result();
		$anexos_on_db = [];
		$anexos_on_dir = [];

		foreach (glob("{$path}/*.*") as $key => $anexo) $anexos_on_dir[] = pathinfo($anexo)['basename'];
		foreach ($anexos as $anexo) if (isset($anexo->$dir)) $anexos_on_db[] = pathinfo($anexo->$dir)['basename'];

		foreach ($anexos_on_dir as $key => $file) {
			if (in_array($file, array_values($anexos_on_db))) unset($anexos_on_dir[$key]);
			else $anexos_on_dir[$key] = "{$path}/$anexos_on_dir[$key]";
		}
		return $anexos_on_dir;
	}

	public function getData(
		$modulo_nome = null, //rota
		$id_item = null, //id item do modulo ex: id_ativo_externo
		$tipo = null, //tipo do anexo ex: manutencao, ipva, seguro
		$id_subitem = null, //id subitem do modulo ex: id_ativo_externo_manutencao
		$back_url = null,
		$pagina = null,
		$limite = null
	): array {
		$modulo = $this->db->where('rota', $modulo_nome)->get('modulo')->row();
		if ($modulo) {
			if (!$back_url) {
				$back_url = "/{$modulo->rota}";
				if ($id_item) $back_url .= "/{$id_item}";
				if ($tipo) $back_url .= "/{$tipo}";
				if ($id_subitem) $back_url .= "/{$id_subitem}";
			}
		}

		return [
			"upload_max_filesize" => ini_get('upload_max_filesize'),
			"id_modulo" => $modulo ? $modulo->id_modulo : null,
			"modulo" => $modulo,
			"tipo" => $tipo,
			"id_item" => $id_item,
			"id_subitem" => $id_subitem,
			"pagina" => $pagina,
			"limite" => $limite,
			"anexos" => $this->get_anexos(
				$modulo ? $modulo->id_modulo  : null,
				$id_item,
				$tipo,
				$id_subitem,
				$pagina,
				$limite
			),
			"anexo_modulos" => $this->modulos,
			"anexo_tipos" => $this->tipos,
			"veiculos" => $this->ativo_veiculo_model->get_tipo_servico(10, 'Serviços Mecânicos'),
			"veiculo_manutencao_servicos" => $this->ativo_veiculo_model->get_tipo_servico(10, 'Serviços Mecânicos'),
			"back_url" => $back_url ? $back_url : null,
		];
	}

	public function get_historico_anexo($id_anexo){	
		
		$anexo = new stdClass();
		$anexo->principal = $this->db
					->select('a.*, m.titulo as modulo, u.usuario')
					->where('a.id_anexo', $id_anexo)
					->join('modulo m', 'm.id_modulo=a.id_modulo')
					->join('usuario u', 'u.id_usuario=a.id_usuario')
					->get('anexo a')
					->result();
		
		$anexo->historico = $this->db
					->select('a.*, m.titulo as modulo, u.usuario')
					->where('a.id_anexo_pai', $id_anexo)
					->join('modulo m', 'm.id_modulo=a.id_modulo')
					->join('usuario u', 'u.id_usuario=a.id_usuario')
					->get('anexo a')
					->result();

					return $anexo;
	}
}
