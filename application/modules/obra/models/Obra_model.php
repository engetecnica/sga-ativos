<?php 

class Obra_model extends MY_Model {

	public function salvar_formulario($data=null){

		if($data['id_obra']==''){
			$this->db->insert('obra', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_obra', $data['id_obra']);
			$this->db->update('obra', $data);
			return "salvar_ok";
		}

	}

	public function get_lista(){
		$this->db->order_by('id_obra', 'ASC');
		return $this->db->get('obra')->result();
	}

	public function get_obra($id_obra=null){
		$this->db->where('id_obra', $id_obra);
		$obra = $this->db->get('obra')->row();
		return $obra;
	}

	public function get_empresas(){
		$this->db->order_by('razao_social', 'ASC');
		return $this->db->get('empresa')->result();
	}


}