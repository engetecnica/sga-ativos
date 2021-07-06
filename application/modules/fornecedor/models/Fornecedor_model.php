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

	public function get_lista(){
		$this->db->order_by('razao_social', 'ASC');
		return $this->db->get('fornecedor')->result();
	}

	public function get_fornecedor($id_fornecedor=null){
		$this->db->where('id_fornecedor', $id_fornecedor);
		$fornecedor = $this->db->get('fornecedor')->row();

		return $fornecedor;
	}
}