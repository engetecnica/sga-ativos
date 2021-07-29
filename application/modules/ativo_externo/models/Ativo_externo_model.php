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

	public function get_lista(){
		return $this->db->select('ativo_externo.*, obra.codigo_obra,
			 obra.endereco as endereco, obra.id_obra, kit.*')
		->from('ativo_externo')
		->order_by('ativo_externo.codigo', 'ASC')
		->join("obra", "obra.id_obra=ativo_externo.id_obra", "left")
		->join("ativo_externo_kit kit", "kit.id_ativo_externo_item=ativo_externo.id_ativo_externo", "left")
		->group_by('ativo_externo.id_ativo_externo')
		->get()->result();
	}

	public function get_lista_grupo($id_empresa = null, $id_obra = null, $count_estoque = false){
		$grupos = $this->db
						->select('atv.*')
						->from('ativo_externo atv');

		if ($id_empresa){
			$grupos->where("atv.id_obra = {$id_empresa}");
		}

		if ($id_obra){
			$grupos->where("atv.id_obra = {$id_obra}");
		}

		$grupos = $grupos
		->order_by('nome', 'ASC')
		->group_by('id_ativo_externo_grupo')
		->get()
		->result();

		if ($count_estoque) {
			foreach($grupos as $g => $grupo) {
				$grupos[$g]->count = $this->count_estoque($grupo->id_ativo_externo_grupo);
			}
		}
		return $grupos;
	}

	public function get_estoque($id_obra = null, $id_ativo_externo_grupo = null, $out_kit = false){
		$estoque = $this->db
		->select('atv.*, obra.codigo_obra, obra.endereco as endereco, obra.id_obra')
		->from('ativo_externo atv')
		->where('atv.situacao = 12');

		if (!$id_obra){
			$id_obra = (isset($this->user->id_obra) && $this->user->id_obra > 0) ? $this->user->id_obra : $this->get_obra_base()->id_obra;
		}

		if ($id_ativo_externo_grupo){
			$estoque->where("atv.id_ativo_externo_grupo = {$id_ativo_externo_grupo}");
		}

		if ($out_kit) {
			$estoque->join("ativo_externo_kit kit", "kit.id_ativo_externo_kit IS NULL", "left");
		}

		return $estoque->order_by('atv.id_ativo_externo', 'ASC')
			->join("obra", "obra.id_obra=atv.id_obra", "left")
			->where("atv.id_obra = {$id_obra}")
			->group_by('atv.id_ativo_externo')
			->get()
			->result();
	}


	public function count_estoque($id_ativo_externo_grupo = null){
		  $estoque = $this->db->select('item.*')->from('ativo_externo item');
			if ($id_ativo_externo_grupo) {
				$estoque->where("item.id_ativo_externo_grupo = {$id_ativo_externo_grupo}");
			}
			return $estoque->where("item.situacao = 12")->get()->num_rows();
	}

	public function get_kit_items($id_ativo_externo_kit){
		return $this->db->select('ativo_externo_kit.*, ativo_externo.*,  obra.*')
		->from('ativo_externo_kit')
		->order_by('ativo_externo.codigo', 'ASC')
		->where("ativo_externo_kit.id_ativo_externo_kit = {$id_ativo_externo_kit}")
		->join("ativo_externo", "ativo_externo.id_ativo_externo=ativo_externo_kit.id_ativo_externo_item", "left")
		->join("obra", "obra.id_obra=ativo_externo.id_obra")
		->group_by('ativo_externo.id_ativo_externo')
		->get()->result();
	}

	public function get_out_kit_items($id_ativo_externo_kit, array $items_ids, $id_obra = null){
		$not_items_array = array_merge($items_ids, [$id_ativo_externo_kit]);
		return $this->db->select('ativo_externo.*, ativo_externo_kit.*')
		->from('ativo_externo')
		->order_by('codigo', 'ASC')
		->join('ativo_externo_kit', "ativo_externo_kit.id_ativo_externo_item = ativo_externo.id_ativo_externo", 'left')
		->where("ativo_externo_kit.id_ativo_externo_kit IS NULL")
		->where("ativo_externo.id_ativo_externo NOT IN (".implode(',', $not_items_array).")")
		->where('ativo_externo.situacao = 12')
		->group_by('ativo_externo.id_ativo_externo')
		->get()->result();
	}

	public function get_lista_verificada($id_ativo_externo)
	{
		$k = 0;
		$obra = $this->db->get('obra')->result();

		foreach($obra as $valor)
		{
			//$arr[$k] = array();
			$this->db->where('id_ativo_externo', $id_ativo_externo);			
			$arr[$k]['item'] = $this->db->get('ativo_externo')->row('nome'); 

			$this->db->where('nome', $arr[$k]['item']);
			$this->db->where('condicao', 'Em Operação');
			$this->db->where('id_obra', $valor->id_obra);
			$arr[$k]['emuso'] = $this->db->get('ativo_externo')->num_rows();

			$this->db->where('nome', $arr[$k]['item']);
			$this->db->where('condicao', 'Liberado');
			$this->db->where('id_obra', $valor->id_obra);
			$arr[$k]['liberado'] = $this->db->get('ativo_externo')->num_rows();

			$this->db->where('nome', $arr[$k]['item']);
			$this->db->where('condicao', 'Liberado');
			$this->db->where('id_obra', $valor->id_obra);
			$this->db->where('situacao', '10'); // Fora de Operação
			$arr[$k]['foradeoperacao'] = $this->db->get('ativo_externo')->num_rows();
			$arr[$k]['obra'] = $valor->codigo_obra;	
			$k++;
		}	
		return $arr;
	}

	public function get_ativo_externo($id_ativo_externo=null){
		return $this->db
		->where('id_ativo_externo', $id_ativo_externo)
		->order_by('nome', 'asc')
		->group_by('id_ativo_externo')
		->get('ativo_externo')
		->row();
	}

	public function get_ativo_externo_grupo($id_ativo_externo_grupo, $count=false){
		$grupo = $this->db
		->where('id_ativo_externo_grupo', $id_ativo_externo_grupo)
		->order_by('nome', 'asc')
		->group_by('id_ativo_externo')
		->get('ativo_externo');
		
		if (!$count) {
			return 	$grupo->result();
		}
		return 	$grupo->num_rows();
	}

	public function get_ativos_externos($data){
		return $this->db
		->where($data)
		->order_by('nome', 'asc')
		->group_by('id_ativo_externo')
		->get('ativo_externo')
		->result();
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
		$grupos = $this->get_lista_grupo();
		if (count($grupos) >= 1) {
			return $grupos[count($grupos) - 1]->id_ativo_externo_grupo + 1;
		}
		return 1;
	}
}