<?php 

class ferramental_requisicao_model extends MY_Model {

	public function __construct()
	{
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
	public function get_lista_requisicao($user=null)
	{
		$requisicoes =  $this->db->select('requisicao.*, ob.codigo_obra, ob.endereco, 
		ob.endereco_numero, ob.responsavel, ob.responsavel_celular, ob.responsavel_email, us.usuario, us.id_usuario')
		->from('ativo_externo_requisicao requisicao')
		->join('obra ob', 'ob.id_obra=requisicao.id_obra')
		->join('usuario us', "us.id_usuario = requisicao.id_usuario");

		if ($user && $user->nivel == 2) {
			$requisicoes->where("requisicao.id_usuario = {$user->id_usuario}");
		}

		return $requisicoes->group_by('requisicao.id_requisicao')->get()->result();
	}


	public function get_requisicao($id_requisicao, $user)
	{
			$requisicao = $this->db->select(
				'requisicao.*, ob.codigo_obra, ob.endereco, 
				ob.endereco_numero, ob.responsavel, ob.responsavel_celular, 
				ob.responsavel_email, us.usuario as usuario_solicitante, us.id_usuario'
			)->from('ativo_externo_requisicao requisicao')
			->where("requisicao.id_requisicao={$id_requisicao}")
			->join('obra ob', 'ob.id_obra=requisicao.id_obra');

			switch ($user->nivel) {
				case 1:
					$requisicao->join('usuario us', 'us.id_usuario=requisicao.id_usuario');
				break;
				case 2:
					$requisicao->join('usuario us', "us.id_usuario={$user->id_usuario}");
				break;
			}

			return $requisicao->group_by('requisicao.id_requisicao')->get()->row();
	}

	public function get_requisicao_itens($id_requisicao, $status=null){
			$this->db->reset_query();
			$items_query = $this->db->select('item.*, atv.id_ativo_externo, atv.codigo, atv.nome, atv.id_ativo_externo_grupo')
			->from('ativo_externo_requisicao_item item')
			->where("item.id_requisicao={$id_requisicao}");

			if ($status) {
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
				$requisicao_item->ativos = $this->db->select('item.*, atv.*')
				->from('ativo_externo_requisicao_item item')
				->where("item.id_requisicao={$id_requisicao}")
				->join('ativo_externo atv', "atv.id_requisicao_item={$id_requisicao_item}")
				->group_by('atv.id_ativo_externo')
				->get()
				->result();
			}
			return $requisicao_item;
	}

	public function count_grupo_estoque($itens_requisicao = []){
		$itens_estoque = [];
		if (count($itens_requisicao) > 0) {
			foreach($itens_requisicao as $item) {
				$itens_estoque[$item->id_ativo_externo_grupo] = $this->ativo_externo_model->count_estoque($item->id_ativo_externo_grupo);
			}
		}
		return $itens_estoque;
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

	public function get_requisicao_com_items($id_requisicao, $id_ativo_externo_grupo = null)
	{
		$requisicao = $this->db->where('id_requisicao', $id_requisicao)->get('ativo_externo_requisicao')->row();
		$items_query = $this->db->select('atv.*, atv.observacao as atv_observacao, item.id_requisicao, item.id_requisicao_item')
				->from('ativo_externo atv')
				->join('ativo_externo_requisicao_item item', "item.id_requisicao = {$id_requisicao}")
				->where("atv.id_requisicao_item = item.id_requisicao_item");

		if ($id_ativo_externo_grupo) { 
			$items_query->where("atv.id_ativo_externo_grupo = {$id_ativo_externo_grupo}");
		} else {
			$items_query->where("atv.id_ativo_externo_grupo = item.id_ativo_externo_grupo");
		}

		$requisicao->items = $this->get_requisicao_itens($id_requisicao);
		return $requisicao;
	}

	public function aceite_itens_requisicao($id_requisicao, $id_requisicao_item = null, $status = 4, array $ativos = []){
			$requisicao_item = $this->get_requisicao_item($id_requisicao, $id_requisicao_item);
			
			if ($requisicao_item) {
				if (count($ativos) == 0) {
					$ativos = array_map(
						function($ativo){
							return $ativo->id_ativo_externo;
						},
						$requisicao_item->ativos
					);
				}
			
				$data = [];
				foreach ($ativos as $k => $id_ativo_externo) {
					$ativo = $this->ativo_externo_model->get_ativo_externo($id_ativo_externo);
		
					$st = $status;
					if (is_array($status)) {
						$st = $status[$k];
					}
					$ativo->situacao = $st;
					unset($ativo->codigo_obra, $ativo->endereco);
					$data[] = (array) $ativo;

					if ($ativo->tipo == 1) {
						$this->aceite_kit_requisicao($ativo, $data);
					}
				}
				$this->db->update_batch('ativo_externo', $data, 'id_ativo_externo');
				return true;
			}
			return false;
	}


	public function aceite_kit_requisicao($ativo, &$data = []) {
		$items = $this->ativo_externo_model->get_kit_items($ativo->id_ativo_externo);

		foreach($items as $item) {
				$item->situacao = $ativo->situacao;
				unset($item->codigo_obra, $item->endereco);
				$data[] = (array) $item;

				if ($item->tipo == 1) {
					$this->aceite_kit_requisicao($item, $data);
				}
		}
	}
}
