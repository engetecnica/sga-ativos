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

			// Salvar LOG
			$this->salvar_log(23, null, 'adicionar', $data);

			$this->db->insert('insumo', $data);
			return $this->db->insert_id();
		}

		$this->db
			->where('id_insumo', $data['id_insumo'])
			->update('insumo', $data);

			$this->salvar_log(23, $data['id_insumo'], 'editar', $data);

	}

	public function query(){
		return $this->db
			->from('insumo')
			->select('*');
	}

	public function get_insumo($id_insumo=null){
		return $this->query()
			->where('id_insumo', $id_insumo)
			->get()->row();
	}

	public function get_todos_insumos()
	{
		$query = $this->db->get('insumo');
		return $query->result();
	}	




}