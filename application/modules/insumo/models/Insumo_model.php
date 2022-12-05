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
			->from('insumo ')
			->select('*');
	}

	public function get_insumo($id_insumo=null){
		return $this->query()
			->where('id_insumo', $id_insumo)
			->get()->row();
	}

	public function get_insumos_by_obra($id_obra)
	{
		return $this->db
					->from('insumo')
					->select('insumo.id_insumo, insumo.titulo, insumo.codigo_insumo, insumo.codigo_insumo, insumo.quantidade, insumo.valor, insumo.funcao, insumo.composicao, insumo.situacao, obra.codigo_obra')
					->join('obra', 'obra.id_obra = insumo.id_obra')
					->where('insumo.id_obra', $id_obra)
					->get()
					->result();
	}	




}