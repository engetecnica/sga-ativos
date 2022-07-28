<?php 

class ferramental_estoque_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->log = new Syslog();
		$this->load->model('ativo_externo/ativo_externo_model'); 
	}

	public function salvar_retirada_renovacao($data = []){

		if(isset($data['id_retirada_pai'])){			
			$this->db->insert('ativo_externo_retirada', $data);
			return $this->db->insert_id();
		}

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

	private function query_retirada($id_obra = null, $id_funcionario = null, $status = null){
		$retiradas = $this->db
						->select('retirada.*, ob.id_obra, ob.responsavel, ob.responsavel_celular, ob.codigo_obra as obra')
						->select('fn.cpf as funcionario_cpf, fn.rg as funcionario_rg, fn.celular as funcionario_celular, fn.nome as funcionario')
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

			$retiradas->where('id_retirada_pai', 0);

		return $retiradas
				->join('obra ob', 'retirada.id_obra = ob.id_obra', 'left')
				->join('funcionario fn', 'retirada.id_funcionario = fn.id_funcionario', 'left')
				->group_by('retirada.id_retirada')
				->order_by('retirada.id_retirada', 'desc');
	}
	
	public function get_lista_retiradas($id_obra = null, $id_funcionario = null, $status = null){
		return $this->query_retirada($id_obra, $id_funcionario, $status)->get()->result();
	}


	public function search_retiradas($search){
		return $this->query_retirada()
				->order_by('retirada.id_retirada', 'desc')
				->like('retirada.id_retirada', $search)
				->or_like('fn.nome', $search)
				->or_like('ob.codigo_obra', $search)
				->get()->result();
	}

	public function get_retirada($id_retirada, $id_obra = null, $id_funcionario = null){

		$retirada = $this->query_retirada($id_obra, $id_funcionario)->where("retirada.id_retirada = {$id_retirada}")->get()->row();
		if ($retirada) {
			if ($retirada->termo_de_responsabilidade && stripos($retirada->termo_de_responsabilidade, 'anexo/') === false) $retirada->termo_de_responsabilidade = "termo_de_responsabilidade/{$retirada->termo_de_responsabilidade}";
			
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

			

			if($items){
				foreach($items as $i => $item){
					$items[$i]->renovacao = $this->db
						->where(
							array(
								'id_retirada' => $id_retirada,
								'data_devolucao_prevista != ' => NULL
							)
						)
						->get('ativo_externo_retirada_item')
						->result();						
				}
			}


			if($items){
				foreach($items as $i => $item){
					$items[$i]->renovacao_pendente = $this->db
						->where(
							array(
								'id_retirada' => $id_retirada,
								'data_devolucao_prevista != ' => NULL,
								'status ' => 4
							)
						)
						->get('ativo_externo_retirada_item')
						->result();						
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

	public function update_item_para_devolvido($id_retirada_item){
		return $this->db
					->where('id_retirada_item', $id_retirada_item)
					->update('ativo_externo_retirada_item', 
						array(
							'status' => 9, 
							'data_devolucao' => date("Y-m-d H:i:s")
						)
					);
	}

	public function get_ativos_item($id_retirada_item=null)
	{

		if($id_retirada_item){
			return $this->db
						->where(
							array(
								'id_retirada_item' => $id_retirada_item,
								'status' => 9
							))
						->get('ativo_externo_retirada_ativo')
						->row();

		} else {
			return false;
		}


	}

	public function salvar_retirada_renovacao_item($data=null)
	{
		$this->db->insert("ativo_externo_retirada_item", $data);
		return $this->db->insert_id();
	}


	public function devolver_items_renovacao($id_retirada_item=null)
	{
		return $this->db
		->where('id_retirada_item', $id_retirada_item)
		->update('ativo_externo_retirada_item', 
			array(
				'status' => 9, 
				'data_devolucao' => date("Y-m-d H:i:s")
			)
		);
	}
}