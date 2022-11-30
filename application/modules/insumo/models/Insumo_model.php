<?php 

class Insumo_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->log = new Syslog();
		$this->load->model('ativo_externo/ativo_externo_model'); 

	}
	
	public function salvar_formulario($data = []){
		if ($data['id_insumo'] == '') {
			
			$this->db->insert('insumo', $data);
			return $this->db->insert_id();
		}

		$this->db
			->where('id_insumo', $data['id_insumo'])
			->update('insumo', $data);
	}

	public function query(){
		return $this->db
			->from('insumo')
			->select('id_insumo');
	}

	public function get_insumos(){
		return $this->query();
	}
	


}