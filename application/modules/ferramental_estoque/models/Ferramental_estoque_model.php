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
			
			// Salvar LOG
			$this->salvar_log(13, null, 'adicionar', $data);		
			
			// Insere na Base
			$this->db->insert('ativo_externo_retirada', $data);
			return $this->db->insert_id();

		}

		$this->db
			->where('id_retirada', $data['id_retirada'])
			->update('ativo_externo_retirada', $data);

			// Salvar LOG
			$this->salvar_log(13, $data['id_retirada'], 'editar', $data);
	}

	public function query($id_obra = null, $id_funcionario = null, $status = null){
		$retiradas = $this->db
						->select('retirada.*')
						->select("(SELECT anexo FROM anexo WHERE tipo = 'termo_de_responsabilidade' AND id_modulo_item = retirada.id_retirada ORDER BY id_anexo desc LIMIT 1) as termo_de_responsabilidade")
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

		$this->join_requisicao_status($retiradas, 'retirada.status');	
		$this->join_funcionario($retiradas, 'retirada.id_funcionario');
		$this->join_obra($retiradas, 'retirada.id_obra');

		return $retiradas->group_by('retirada.id_retirada');
	}
	
	public function get_lista_retiradas($id_obra = null, $id_funcionario = null, $status = null){
		return $this
			->query($id_obra, $id_funcionario, $status)
			->order_by('retirada.id_retirada', 'desc')
			->get()->result();
	}


	public function search_retiradas($search){
		return $this->query()
				->order_by('retirada.id_retirada', 'desc')
				->like('retirada.id_retirada', $search)
				->or_like('fn.nome', $search)
				->or_like('ob.codigo_obra', $search)
				->order_by('retirada.id_retirada', 'desc')
				->get()->result();
	}

	public function get_retirada($id_retirada, $id_obra = null, $id_funcionario = null){
		$retirada = $this->query($id_obra, $id_funcionario)
					->where("retirada.id_retirada = {$id_retirada}")
					->order_by('retirada.id_retirada', 'desc')
					->get()->row();
					
		if ($retirada) {
			if ($retirada->termo_de_responsabilidade && stripos($retirada->termo_de_responsabilidade, 'anexo/') === false) $retirada->termo_de_responsabilidade = "termo_de_responsabilidade/{$retirada->termo_de_responsabilidade}";
			$retirada->items = $this->get_retirada_items($id_retirada);
		}
		return $retirada;			
	}

	public function get_retirada_items($id_retirada){
		$items = $this->db->select('item.*, atv.id_ativo_externo_grupo, atv.nome, atv.codigo')
				->from('ativo_externo_retirada_item item')
				->where("item.id_retirada = {$id_retirada}")
				->join('ativo_externo atv', 'item.id_ativo_externo = atv.id_ativo_externo')
				->group_by('item.id_retirada_item')
				->get()
				->result();


			if ($items) {
				foreach($items as $i => $item){
					$items[$i]->ativos = $this->get_retirada_ativos($item->id_retirada_item);
					$items[$i]->estoque = $this->ativo_externo_model->get_grupo($item->id_ativo_externo_grupo)->estoque ?? 0;
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
				$item->estoque = $this->ativo_externo_model->get_grupo($item->id_ativo_externo_grupo)->estoque ?? 0;
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


	public function get_item_renovar($id_retirada_item){
		$consulta = $this->db->where('id_retirada_item', $id_retirada_item)->get('ativo_externo_retirada_item')->row();
		$consulta->ativo = $this->db->where('id_retirada_item', $id_retirada_item)->get('ativo_externo_retirada_ativo')->row();
		return $consulta;
	}

	public function get_patrimonio_by_code($patrimonio_code){
		if($patrimonio_code==null) return [];
		return $this->db->where('id_ativo_externo', $patrimonio_code)->get('ativo_externo')->row();
	}


}