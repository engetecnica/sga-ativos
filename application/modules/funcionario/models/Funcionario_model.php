<?php 

class funcionario_model extends MY_Model {

	public function salvar_formulario($data=null){

		if($data['id_funcionario']==''){
			$this->db->insert('funcionario', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_funcionario', $data['id_funcionario']);
			$this->db->update('funcionario', $data);
			return "salvar_ok";
		}

	}


	public function get_lista(){
		$this->db->order_by('nome', 'ASC');
		return $this->db->get('funcionario')->result();
	}

	public function get_funcionario($id_funcionario=null){
		$this->db->where('id_funcionario', $id_funcionario);
		$funcionario = $this->db->get('funcionario')->row();
		return $funcionario;
	}

}