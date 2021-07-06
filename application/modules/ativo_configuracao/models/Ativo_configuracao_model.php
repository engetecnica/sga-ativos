<?php 

class Ativo_configuracao_model extends MY_Model {

	public function salvar_formulario($data=null){

		if($data['id_ativo_configuracao']==''){
			$this->db->insert('ativo_configuracao', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_ativo_configuracao', $data['id_ativo_configuracao']);
			$this->db->update('ativo_configuracao', $data);
			return "salvar_ok";
		}

	}

	public function get_categoria_lista(){
		$this->db->where('id_ativo_configuracao_vinculo', 0);
		$this->db->order_by('titulo', 'ASC');
		return $this->db->get('ativo_configuracao')->result();
	}

	public function get_lista(){
		$this->db->select('ativo_configuracao.*, ac.titulo as id_ativo_configuracao_vinculo');
		$this->db->join("ativo_configuracao as ac", 'ac.id_ativo_configuracao=ativo_configuracao.id_ativo_configuracao_vinculo', "left");
		$this->db->order_by('ativo_configuracao.titulo', 'ASC');
		return $this->db->get('ativo_configuracao')->result();
	}

	public function get_ativo_configuracao($id_ativo_configuracao=null){
		$this->db->where('id_ativo_configuracao', $id_ativo_configuracao);
		$ativo_configuracao = $this->db->get('ativo_configuracao')->row();
		return $ativo_configuracao;
	}
}