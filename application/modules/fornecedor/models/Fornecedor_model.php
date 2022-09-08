<?php 

class fornecedor_model extends MY_Model {

	public function salvar_formulario($data=null){
		if($data['id_fornecedor']==''){
			$this->db->insert('fornecedor', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_fornecedor', $data['id_fornecedor']);
			$this->db->update('fornecedor', $data);
			return "salvar_ok";
		}
	}

	public function query(){
		return $this->db
			->from('fornecedor')
			->select("*")
			->group_by('id_fornecedor');
	}

	public function get_lista(){
		return $this->db->order_by('razao_social', 'ASC')->get('fornecedor')->result();
	}

	public function get_fornecedor($id_fornecedor=null){
		return $this->db
			->where('id_fornecedor', $id_fornecedor)
			->get('fornecedor')->row();
	}
}