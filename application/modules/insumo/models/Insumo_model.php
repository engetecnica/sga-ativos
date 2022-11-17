<?php 

class Insumo_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->log = new Syslog();
		$this->load->model('ativo_externo/ativo_externo_model'); 
	}
	
	public function salvar_formulario($data = []){
		if (!isset($data['id_retirada'])) {
			$this->db->insert('ativo_externo_retirada', $data);
			return $this->db->insert_id();
		}

		$this->db
			->where('id_retirada', $data['id_retirada'])
			->update('ativo_externo_retirada', $data);
	}

	


}