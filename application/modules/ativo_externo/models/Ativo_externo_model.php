<?php 

class Ativo_externo_model extends MY_Model {

	public function salvar_formulario($data=null){
		if($data['id_ativo_externo']==''){
			$this->db->insert('ativo_externo', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_ativo_externo', $data['id_ativo_externo']);
			$this->db->update('ativo_externo', $data);
			return "salvar_ok";
		}

	}

	public function get_lista(){
		//return $this->db->get('v_ativo_externo')->result();
		return $this->db->select('ativo_externo.*, obra.codigo_obra, obra.endereco as endereco, obra.id_obra')
		->from('ativo_externo')
		->order_by('codigo', 'ASC')
		->join("obra", "obra.id_obra=ativo_externo.id_obra", "left")
		->group_by('ativo_externo.id_ativo_externo')
		->get()->result();
	}

	public function get_kit_items($id_ativo_externo_kit, $id_obra = null){
		$kit = $this->db->select('ativo_externo_kit.*, ativo_externo.*,  obra.*')
		->from('ativo_externo_kit')
		->order_by('id_ativo_externo_iten', 'ASC')
		->where("ativo_externo_kit.id_ativo_externo_kit={$id_ativo_externo_kit}")
		->join("ativo_externo", "ativo_externo.id_ativo_externo=ativo_externo_kit.id_ativo_externo_iten", "left");

		if ($id_obra) {
			$kit->join("obra", "obra.id_obra={$id_obra}");
		} else {
			$kit->join("obra", "obra.id_obra=ativo_externo.id_obra");
		}
		
		return $kit->group_by('ativo_externo.id_ativo_externo')
		->get()->result();
	}

	public function get_out_kit_items($id_ativo_externo_kit, array $itens_ids, $id_obra = null){
		$not_itens_array = array_merge($itens_ids, [$id_ativo_externo_kit]);
		return $this->db->select('ativo_externo.*')
		->from('ativo_externo')
		->order_by('codigo', 'ASC')
		->where("ativo_externo.id_ativo_externo NOT IN (".implode(',', $not_itens_array).")")
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
		$this->db->where('id_ativo_externo', $id_ativo_externo);
		$ativo_externo = $this->db->get('ativo_externo')->row();
		return $ativo_externo;
	}

	public function get_ativos_externos($data){
		$this->db->where($data);
		return $this->db->get('ativo_externo')->result();
	}

	# Busca da Obra
	public function get_obra(){
		return $this->db->get('obra')->result();
	}	

	# Busca Categoria
	public function get_categoria(){
		$this->db->order_by('nome', 'asc');
		return $this->db->get('ativo_externo_categoria')->result();
	}
}