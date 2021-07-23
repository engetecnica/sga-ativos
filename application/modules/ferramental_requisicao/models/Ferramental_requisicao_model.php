<?php 

class ferramental_requisicao_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->log = new Syslog();
		$this->load->model('ativo_externo/ativo_externo_model'); 
	}

	# Lista dos ativos externos
	function get_ativo_externo_lista()
	{
		return $this->ativo_externo_model->get_lista_grupo();
	}

	# Salvar Requisicao
	public function salvar_formulario($data)
	{
		if ($data['id_requisicao']) {
			return $this->db->where('id_requisicao', $data['id_requisicao'])
											->update('ativo_externo_requisicao', $data);
		}

		$this->db->insert('ativo_externo_requisicao', $data);
		return $this->db->insert_id();
	}

	# Listagem
	public function get_lista_requisicao($status = null, $offset = 0, $limite = null)
	{
		$requisicoes =  $this->db->select('requisicao.*, ob.codigo_obra, ob.endereco, 
		ob.endereco_numero, ob.responsavel, ob.responsavel_celular, ob.responsavel_email, us.usuario, us.id_usuario')
		->from('ativo_externo_requisicao requisicao')
		->join('obra ob', 'ob.id_obra=requisicao.id_obra')
		->join('usuario us', "us.id_usuario = requisicao.id_usuario");

		if ($this->user->nivel == 2) {
			$requisicoes->where("requisicao.id_usuario = {$this->user->id_usuario}");
		}

		if ($status) {
			if (is_array($status)) {
				$requisicoes->where("requisicao.status IN (".implode(',', $status).")");
			} else {
				$requisicoes->where("requisicao.status = {$status}");
			}
		}

		$requisicoes->group_by('requisicao.id_requisicao')->order_by('requisicao.data_inclusao', 'DESC');

			if ($limite) {
				$requisicoes->limit($limite, $offset);
			}
			return $requisicoes->get()->result();
	}

	public function lista_requisicao_count($status = null){
		$requisicoes = $this->db->from('ativo_externo_requisicao requisicao')->select('requisicao.*');

		if ($status) {
			if (is_array($status)) {
				$requisicoes->where("requisicao.status IN (".implode(',', $status).")");
			} else {
				$requisicoes->where("requisicao.status = {$status}");
			}
		}
		
		return $requisicoes->get()->num_rows();
	}

	public function get_requisicao($id_requisicao)
	{
			$requisicao = $this->db->select(
				'requisicao.*, ob.codigo_obra, ob.endereco, 
				ob.endereco_numero, ob.responsavel, ob.responsavel_celular, 
				ob.responsavel_email, us.usuario as usuario_solicitante, us.id_usuario'
			)->from('ativo_externo_requisicao requisicao')
			->where("requisicao.id_requisicao = {$id_requisicao}")
			->join('obra ob', 'ob.id_obra=requisicao.id_obra');

			switch ($this->user->nivel) {
				case 1:
					$requisicao->join('usuario us', 'us.id_usuario=requisicao.id_usuario');
				break;
				case 2:
					$requisicao->join('usuario us', "us.id_usuario={$this->user->id_usuario}");
				break;
			}

			return $requisicao->group_by('requisicao.id_requisicao')->get()->row();
	}

	public function get_requisicao_com_items($id_requisicao, $id_ativo_externo_grupo = null)
	{
		$requisicao = $this->get_requisicao($id_requisicao);
		if($requisicao) {
			$requisicao->items = $this->get_requisicao_items($id_requisicao);
			return $requisicao;
		}
		return null;
	}

	public function get_requisicao_items($id_requisicao, $status=null){
			$this->db->reset_query();
			$items_query = $this->db->select('item.*, atv.id_ativo_externo, atv.codigo, atv.nome, 
			atv.id_ativo_externo_grupo, COUNT(atv.id_ativo_externo) as estoque')
			->from('ativo_externo_requisicao_item item')
			->where("item.id_requisicao={$id_requisicao}");

			if ($status != null) {
				$items_query->where("item.status = {$status}");
			}

			$items = $items_query->join('ativo_externo atv', 'atv.id_ativo_externo_grupo=item.id_ativo_externo_grupo')
														->group_by('item.id_requisicao_item')->get()->result();

			foreach($items as $k => $item){
				$items[$k]->ativos = $this
														->get_requisicao_item($id_requisicao, $item->id_requisicao_item)
														->ativos;
			}
			return $items;
	}

	public function get_requisicao_item($id_requisicao, $id_requisicao_item){
			$this->db->reset_query();
			$requisicao_item = $this->db
			->select('item.*')
			->from('ativo_externo_requisicao_item item')
			->where("item.id_requisicao={$id_requisicao}")
			->where("item.id_requisicao_item={$id_requisicao_item}")
			->get()->row();

			if($requisicao_item) {
				$this->db->reset_query();
				$requisicao_item->ativos = $this->db->select('item.*, atv.*, kit.*')
				->from('ativo_externo_requisicao_item item')
				->where("item.id_requisicao={$id_requisicao}")
				->join('ativo_externo atv', "atv.id_requisicao_item={$id_requisicao_item}", 'left')
				->join('ativo_externo_kit kit', "kit.id_ativo_externo_kit = atv.id_ativo_externo", 'left')
				->group_by('atv.id_ativo_externo')
				->get()
				->result();
			}
			return $requisicao_item;
	}

	public function get_lista_status()
	{
		$this->db->order_by("id_requisicao_status", "ASC");
		return $this->db->get('ativo_externo_requisicao_status')->result();
	}

	public function get_status($id_requisicao_status)
	{
			return $this->db
			->select('ativo_externo_requisicao_status.*')
			->where("id_requisicao_status={$id_requisicao_status}")
			->get()
			->row();
	}

	public function aceite_items_requisicao($id_requisicao, $id_requisicao_item = null, $status = 4, array $ativos = []){
			$requisicao = $this->get_requisicao($id_requisicao);	
			$requisicao_item = $this->get_requisicao_item($id_requisicao, $id_requisicao_item);
			$obra_base = $this->get_obra_base();
			
			if ($requisicao && $requisicao_item) {
				$dados = [];
				foreach ($ativos as $k => $ativo) {
					$st = (int) $status;
					if (is_array($status)) {
						$st = (int) $status[$k];
					}

					$dados[] = [
						'id_requisicao_item' => $id_requisicao_item,
						'id_ativo_externo' => $ativo,
						'situacao' => $st
					];
				}

				$recebidos = 0;
				$devolvidos = 0;
				$item_status = 13;

				foreach ($dados as $d => $dado) {
					switch ($dado['situacao']) {
						case 4:
							$dado[$d]['id_obra'] = $requisicao->id_obra;
							$recebidos++;
						break;
						case 9:
							$dado[$d]['id_obra'] = $obra_base->id_obra;
							$devolvidos++;
						break;
					}
				}

				if ($recebidos == count($dados)) {
					$item_status = 4;
				} 
				
				if ($devolvidos == count($dados)) {
					$item_status = 9;
				}

				$this->db->update_batch('ativo_externo', $dados, 'id_ativo_externo');
				$this->db->where("id_requisicao_item", $id_requisicao_item)
												->update("ativo_externo_requisicao_item", ['status' => $item_status]);				
				return true;
			}
			return false;
	}

	public function get_requisicao_status($id = null){
		$status = $this->db->from('ativo_externo_requisicao_status');
		if ($id) {
			return $status->where("id_requisicao_status = {$id}")->get()->row();
		}
		return $status->group_by('id_requisicao_status')->get()->result();
	}
}
