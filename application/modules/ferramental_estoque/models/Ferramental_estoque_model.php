<?php 

class ferramental_estoque_model extends MY_Model {

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
	
	public function get_lista_retiradas($id_obra = null, $id_funcionario = null, $status = null){
			$retiradas = $this->db
						->select('retirada.*, ob.*, fn.*, fn.nome as funcionario, ob.codigo_obra as obra')
						->from('ativo_externo_retirada retirada');

			if ($id_obra) {
				$retiradas->where("retirada.id_obra = {$id_obra}");
			}

			if ($id_funcionario) {
				$retiradas->where("retirada.id_funcionario = {$id_funcionario}");
			}
			
			if ($status) {
				if (is_array($status)) {
					$retiradas->where("retirada.status IN (".implode(',', $status).")");
				} else {
					$retiradas->where("retirada.status = {$status}");
				}
			}	

			return $retiradas
						->join('obra ob', 'retirada.id_obra = ob.id_obra', 'left')
						->join('funcionario fn', 'retirada.id_funcionario = fn.id_funcionario', 'left')
						->group_by('retirada.id_retirada')
						->order_by('retirada.id_retirada', 'desc')
						->get()
						->result();
	}

	public function get_retirada($id_retirada, $id_obra = null, $id_funcionario = null){
		$retirada = $this->db
					->select('retirada.*, ob.id_obra, ob.responsavel, ob.responsavel_celular, ob.codigo_obra as obra')
					->select('fn.cpf as funcionario_cpf, fn.rg as funcionario_rg, fn.celular as funcionario_celular, fn.nome as funcionario')
					->from('ativo_externo_retirada retirada')
					->where("retirada.id_retirada = {$id_retirada}");

		if ($id_obra) {
			$retirada->where("retirada.id_obra = {$id_obra}");
		}
		
		if ($id_funcionario) {
			$retirada->where("retirada.id_funcionario = {$id_funcionario}");
		}	

		$retirada = $retirada
					->join('obra ob', 'retirada.id_obra = ob.id_obra', 'left')
					->join('funcionario fn', 'retirada.id_funcionario = fn.id_funcionario', 'left')
					->get()
					->row();

		if ($retirada) {
			$retirada->items = $this->get_retirada_items($id_retirada);
		}
		return $retirada;			
	}

	public function get_retirada_items($id_retirada){
		$items = $this->db->select('item.*, atv.id_ativo_externo_grupo, atv.nome')
				->from('ativo_externo_retirada_item item')
				->where("item.id_retirada = {$id_retirada}")
				->join('ativo_externo atv', 'item.id_ativo_externo_grupo = atv.id_ativo_externo_grupo')
				->group_by('item.id_retirada_item')
				->get()
				->result();

			if ($items) {
				foreach($items as $i => $item){
					$items[$i]->ativos = $this->get_retirada_ativos($item->id_retirada_item);
				}
			}
			return $items;
	}

	public function get_retirada_item($id_retirada_item){
			$item = $this->db->select('item.*, atv.id_ativo_externo_grupo, atv.nome')
				->from('ativo_externo_retirada_item item')
				->where("item.id_retirada_item = {$id_retirada_item}")
				->join('ativo_externo atv', 'item.id_ativo_externo_grupo = atv.id_ativo_externo_grupo')
				->group_by('item.id_retirada_item')
				->get()
				->row();

			if ($item) {
				$item->ativos = $this->get_retirada_ativos($id_retirada_item);
			}
			return $item;
	}

	public function get_retirada_ativos($id_retirada_item){
		return $this->db
				->select('ativo.*,  atv.id_ativo_externo, atv.nome, atv.codigo, atv.valor')
				->from('ativo_externo_retirada_ativo ativo')
				->where("ativo.id_retirada_item = {$id_retirada_item}")
				->join('ativo_externo atv', 'ativo.id_ativo_externo = atv.id_ativo_externo')
				->group_by('ativo.id_ativo_externo')
				->get()
				->result();
	}
}