<?php 

class Ativo_externo_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function salvar_formulario($data=null){
		if($data['id_ativo_externo'] == ''){
			$this->db->insert('ativo_externo', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_ativo_externo', $data['id_ativo_externo']);
			$this->db->update('ativo_externo', $data);
			return "salvar_ok";
		}

	}

	public function ativos($out_kit = true){
		$this->db->reset_query();
		$ativos = $this->db->select('atv.*')
							->select('obra.codigo_obra as obra, obra.endereco as endereco, obra.id_obra')
							->select('kit.*')
							->from('ativo_externo atv')
							->join("obra", "obra.id_obra = atv.id_obra", "left");
		if ($out_kit) {
			$ativos->join("ativo_externo_kit kit", "kit.id_ativo_externo_kit IS NULL", "left");
		} else {
			$ativos->join("ativo_externo_kit kit", "kit.id_ativo_externo_item = atv.id_ativo_externo", "left");
		}

		return $ativos;
	}

	public function search_ativos($search, $out_kit = true){
		return $this->ativos()
			->group_by('atv.id_ativo_externo')
			->order_by('atv.codigo')
			->like('nome', $search)
			->or_like('codigo', $search)
			->or_like('id_ativo_externo', $search)
			->or_like('data_inclusao', $search)
			->or_like('data_descarte', $search)
			->get()->result();
	}


	public function get_ativos($id_obra=null, $situacao=null){
		$ativos = $this->ativos(false)
				->group_by('atv.id_ativo_externo')
				->order_by('atv.codigo');
		
		if ($id_obra) {
			$ativos->where("atv.id_obra = {$id_obra}");
		}

		if ($situacao) {
			if (is_array($situacao)) {
				$ativos->where("situacao IN (".implode(',',$situacao).")");
			} else {
				$ativos->where("situacao = {$situacao}");
			}
		}

		return $ativos->get()->result();
	}

	public function get_ativo($id_ativo_externo, $situacao=null){
		$ativo = $this->ativos()
				->where('id_ativo_externo', $id_ativo_externo);

		if ($situacao) {
			if (is_array($situacao)) {
				$ativo->where("situacao IN (".implode(',',$situacao).")");
			} else {
				$ativo->where("situacao = $situacao");
			}
		}

		return $ativo->group_by('atv.id_ativo_externo')->get()->row();
	}

	public function get_grupos($id_obra = null){
		$grupos = $this->ativos();

		if ($id_obra){
			$grupos->where("atv.id_obra = {$id_obra}");
		}

		$grupos = $grupos
		->order_by('nome', 'ASC')
		->group_by('atv.id_ativo_externo_grupo')
		->get()
		->result();

		foreach($grupos as $g => $grupo) {
			$grupos[$g]->total = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo);
			$grupos[$g]->estoque = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 12);
			$grupos[$g]->liberado = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 2);
			$grupos[$g]->recebido = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 4);
			$grupos[$g]->emoperacao = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 5);
			$grupos[$g]->transito = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 6);
			$grupos[$g]->transferido = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 7);
			$grupos[$g]->comdefeito = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 8);
			$grupos[$g]->foradeoperacao = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 10);
			$grupos[$g]->ativos = $this->get_estoque($id_obra, $grupo->id_ativo_externo_grupo);
		}
		return $grupos;
	}

	public function get_grupo($id_ativo_externo_grupo, $id_obra=null, $count=false){
		$grupo_query =	$this->ativos()
						->where('id_ativo_externo_grupo', $id_ativo_externo_grupo)
						->order_by('nome', 'asc')
						->group_by('atv.id_ativo_externo_grupo')->get();

		if (!$count) {
			$grupo = 	$grupo_query->row();
			if($grupo) {
				$grupo->total = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo);
				$grupo->estoque = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 12);
				$grupo->liberado = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 2);
				$grupo->recebido = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 4);
				$grupo->emoperacao = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 5);
				$grupo->transito = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 6);
				$grupo->transferido = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 7);
				$grupo->comdefeito = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 8);
				$grupo->foradeoperacao = $this->count_estoque($id_obra, $grupo->id_ativo_externo_grupo, 10);
				$grupo->ativos = $this->get_estoque($id_obra, $grupo->id_ativo_externo_grupo);
			}
			return 	$grupo;
		}
		return $grupo_query->num_rows();
	}

	public function get_estoque($id_obra = null, $id_ativo_externo_grupo = null, $status = null, $out_kit = true){
		$estoque = $this->ativos($out_kit);
		if ($id_obra) {
			$estoque->where("atv.id_obra = {$id_obra}");
		}

		if ($id_ativo_externo_grupo){
			$estoque->where("atv.id_ativo_externo_grupo = {$id_ativo_externo_grupo}");
		}

		if ($status) {
			if(is_array($status)) {
				$estoque->where("atv.situacao IN ('".implode(',', $status)."')");
			} else {
				$estoque->where("atv.situacao = {$status}");
			}
		}

		return $estoque->order_by('atv.id_ativo_externo', 'ASC')
						->group_by('atv.id_ativo_externo')
						->get()->result();
	}


	public function count_estoque($id_obra = null, $id_ativo_externo_grupo = null, $situacao = null){
			$estoque = $this->ativos();
			
			if ($id_obra) {
				$estoque->where("atv.id_obra = {$id_obra}");
			}

			if ($id_ativo_externo_grupo) {
				$estoque->where("atv.id_ativo_externo_grupo = {$id_ativo_externo_grupo}");
			}

			if($situacao) {
				if (is_array($situacao)) {
					$estoque->where("atv.situacao IN ('".implode(',', $situacao)."')");
				} else {
					$estoque->where("atv.situacao = {$situacao}");
				}
			}
			return $estoque->group_by('atv.id_ativo_externo')->get()->num_rows();
	}

	public function get_kit_items($id_ativo_externo_kit){
		return $this->db->select('kit.*, ativo_externo.*,  obra.*')
		->from('ativo_externo_kit kit')
		->order_by('ativo_externo.codigo', 'ASC')
		->where("kit.id_ativo_externo_kit = {$id_ativo_externo_kit}")
		->join("ativo_externo", "ativo_externo.id_ativo_externo=kit.id_ativo_externo_item", "left")
		->join("obra", "obra.id_obra=ativo_externo.id_obra")
		->group_by('ativo_externo.id_ativo_externo')
		->get()->result();
	}

	public function get_out_kit_items($id_ativo_externo_kit, array $items_ids, $id_obra = null){
		$not_items_array = array_merge($items_ids, [$id_ativo_externo_kit]);
		return $this->db->select('ativo_externo.*, kit.*')
		->from('ativo_externo')
		->order_by('codigo', 'ASC')
		->join('ativo_externo_kit kit', "kit.id_ativo_externo_item = ativo_externo.id_ativo_externo", 'left')
		->where("kit.id_ativo_externo_kit IS NULL")
		->where("ativo_externo.id_ativo_externo NOT IN (".implode(',', $not_items_array).")")
		->where('ativo_externo.situacao = 12')
		->group_by('ativo_externo.id_ativo_externo')
		->get()->result();
	}

	# Busca da Obra
	public function get_obra(){
		return $this->db
		->order_by('codigo_obra', 'asc')
		->group_by('id_obra')
		->get('obra')
		->result();
	}	

	# Busca Categoria
	public function get_categoria(){
		return $this->db
		->order_by('nome', 'asc')
		->group_by('id_ativo_externo_categoria')
		->get('ativo_externo_categoria')
		->result();
	}

	public function get_proximo_grupo(){
		$grupos =  $this->ativos()
		->order_by('id_ativo_externo_grupo')
		->group_by('id_ativo_externo_grupo')
		->get()
		->result();

		if (count($grupos) >= 1) {
			return $grupos[count($grupos) - 1]->id_ativo_externo_grupo + 1;
		}
		return 1;
	}

	public function permit_edit_situacao($id_ativo_externo){
		$retiradas = $this->db
			->join("ativo_externo_retirada_ativo rat", "atv.id_ativo_externo = rat.id_ativo_externo")
			->join("ativo_externo_retirada_ativo rit", "rit.id_retirada_item = rit.id_retirada_item")
			->where('atv.id_ativo_externo', $id_ativo_externo)
			->where('rat.status != 9 AND rit.status NOT IN (8,9)')
			->get("ativo_externo atv")
			->num_rows();

		$requisicoes = $this->db
			->join("ativo_externo_requisicao_ativo rat", "atv.id_ativo_externo = rat.id_ativo_externo")
			->where('atv.id_ativo_externo', $id_ativo_externo)
			->where('rat.status != 3')
			->get("ativo_externo atv")
			->num_rows();

		return ($retiradas + $requisicoes) === 0;
	}

	public function permit_delete_grupo($id_ativo_externo_grupo){
		return $this->db
				->where('id_ativo_externo_grupo', $id_ativo_externo_grupo)
				->where('situacao != 12')
				->get("ativo_externo")
				->num_rows() === 0;
	}

	public function get_manutencao($id_ativo_externo, $id_manutencao){
		return $this->db
		->where('id_manutencao', $id_manutencao)
		->where('id_ativo_externo', $id_ativo_externo)
		->get('ativo_externo_manutencao')
		->row();
	}

	public function get_lista_manutencao($id_ativo_externo = null, $situacao = null, $obs = false){
		$manutencoes = $this->db
		->select('manutencao.*, manutencao.valor as manutencao_valor, manutencao.situacao as manutencao_situacao')
		->select('ativo.id_ativo_externo, ativo.nome, ativo.codigo, ativo.id_obra, ativo.situacao as ativo_situacao')
		->select('ob.id_obra, ob.codigo_obra')
		->order_by('manutencao.id_manutencao', 'desc')
		->from('ativo_externo_manutencao manutencao');

		if ($id_ativo_externo) $manutencoes->where("manutencao.id_ativo_externo = {$id_ativo_externo}");
		if ($this->user->nivel == 2 && $this->user->id_obra) $manutencoes->where("ativo.id_obra = {$this->user->id_obra}");
		if ($situacao) {
			if (is_array($situacao)) {
				$situacao_string = "";
			 	foreach($situacao as $i => $sit) {
					$situacao_string .= "'{$sit}'";
					if($i < count($situacao) - 1)   $situacao_string .= ',';
				}

				$manutencoes->where("manutencao.situacao IN ({$situacao_string})");
			} else {
				$manutencoes->where("manutencao.situacao = {$situacao}");
			}
		}

		$manutencoes = $manutencoes
			->join('ativo_externo ativo', "ativo.id_ativo_externo = manutencao.id_ativo_externo")
			->join('obra ob', "ob.id_obra = ativo.id_obra", "left")
			->group_by('manutencao.id_manutencao')
			->get()->result();

		if ($obs) {
			foreach($manutencoes as $k => $manutencao) $manutencoes[$k]->observacoes = $this->get_lista_manutencao_obs($manutencao->id_manutencao);
		}
		return $manutencoes;
	}


	public function certificado_de_calibracao_query(){
		$hoje = date("Y-m-d");
		return $this->db
		->from('ativo_externo_certificado_de_calibracao certificado')
		->select('ativo.id_ativo_externo, ativo.nome as ativo_nome, ativo.codigo as ativo_codigo, ativo.id_obra, ativo.situacao as ativo_situacao')
		->select("certificado.*, (certificado.data_vencimento > '{$hoje}') as vigencia, ob.id_obra, ob.codigo_obra")
		->select('anexo.anexo as certificado_de_calibracao')
		->join('ativo_externo ativo', "ativo.id_ativo_externo = certificado.id_ativo_externo")
		->join('anexo', "anexo.id_anexo = certificado.id_anexo", 'left')
		->join('obra ob', "ob.id_obra = ativo.id_obra", "left")
		->order_by('certificado.id_certificado', 'desc')
		->group_by('certificado.id_certificado');
	}

	public function get_certificado_de_calibracao($id_ativo_externo, $id_certificado){
		return $this->certificado_de_calibracao_query()
				->where('certificado.id_certificado', $id_certificado)
				->where('certificado.id_ativo_externo', $id_ativo_externo)
				->get()->row();
	}


	public function get_lista_certificado($id_ativo_externo = null, $vigencia = null){
		$certificados = $this->certificado_de_calibracao_query();
		if ($id_ativo_externo) $certificados->where("certificado.id_ativo_externo = {$id_ativo_externo}");
		if ($this->user->nivel == 2 && $this->user->id_obra) $certificados->where("ativo.id_obra = {$this->user->id_obra}");

		if ($vigencia != null) {
			if (is_array($vigencia))$certificados->where("vigencia IN (".implode(',', $vigencia).")");
			else $certificados->where("vigencia = {$vigencia}");
		}
		return $certificados->get()->result();
	}


	public function get_obs($id_manutencao, $id_obs){
		return $this->db
		->where('id_manutencao', $id_manutencao)
		->where('id_obs', $id_obs)
		->get('ativo_externo_manutencao_obs')
		->row();
	}

	public function get_lista_manutencao_obs($id_manutencao) {
			return $this->db->select('obs.*, usuario.*')
			->from('ativo_externo_manutencao_obs obs')
			->order_by('obs.data_inclusao', 'desc')
			->where('id_manutencao', $id_manutencao)
			->join('usuario', 'usuario.id_usuario=obs.id_usuario')
			->order_by('id_manutencao', 'desc')
			->group_by('obs.id_obs')
			->get()->result();
	}

	public function permit_create_manutencao($id_ativo_externo){
		return $this->db
			->where('id_ativo_externo', $id_ativo_externo)
			->where("situacao != 2")
			->get('ativo_externo_manutencao')->num_rows() == 0;
	}


	public function permit_edit_manutencao($id_ativo_externo, $id_manutencao){
		$manutencao = $this->db
			->where('id_ativo_externo', $id_ativo_externo)
			->order_by('id_manutencao', 'desc')
			->limit(1)
			->get('ativo_externo_manutencao')->row();
		return $manutencao && $manutencao->id_manutencao == $id_manutencao;
	}

	public function permit_delete_manutencao($id_ativo_externo, $id_manutencao) {
		$manutencao = $this->db
			->where('id_ativo_externo', $id_ativo_externo)
			->where('id_manutencao', $id_manutencao)
			->get('ativo_externo_manutencao')->row();
		return $this->permit_edit_manutencao($id_ativo_externo, $id_manutencao) && ($manutencao && $manutencao->situacao == 0);
	}
}