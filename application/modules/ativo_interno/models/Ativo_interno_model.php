<?php 

class Ativo_interno_model extends MY_Model {

	public function salvar_formulario($data=null){

		if($data['id_ativo_interno']==''){
			$this->db->insert('ativo_interno', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_ativo_interno', $data['id_ativo_interno']);
			$this->db->update('ativo_interno', $data);
			return "salvar_ok";
		}

	}

	public function get_lista(){
		return $this->db->get('ativo_interno')->result();
	}

	public function get_ativo_interno($id_ativo_interno=null){
		$this->db->where('id_ativo_interno', $id_ativo_interno);
		$ativo_interno = $this->db->get('ativo_interno')->row();

		return $ativo_interno;
	}
}