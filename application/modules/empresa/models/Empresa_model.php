<?php 

class empresa_model extends MY_Model {

	public function salvar_formulario($data=null){

		if($data['id_empresa']==''){
			$this->db->insert('empresa', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_empresa', $data['id_empresa']);
			$this->db->update('empresa', $data);
			return "salvar_ok";
		}

	}

	public function get_lista(){
		$this->db->order_by('razao_social', 'ASC');
		return $this->db->get('empresa')->result();
	}

	public function get_empresa($id_empresa=null){
		return $this->db->where('id_empresa', $id_empresa)->get('empresa')->row();
	}
}