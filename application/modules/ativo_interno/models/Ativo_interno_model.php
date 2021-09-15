<?php 

class Ativo_interno_model extends MY_Model {

	public function salvar_formulario($data=null){
		if($data['id_ativo_interno']==''){
			$this->db->insert('ativo_interno', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_ativo_interno', $data['id_ativo_interno']);
			$this->db->update('ativo_interno', $data);
			return "salvar_ok";
		}
	}

	public function ativos(){
		return $this->db
			->from('ativo_interno')
			->order_by('nome', 'asc')
			->group_by('id_ativo_interno')
			->select('ativo_interno.*');;
	}

	public function search_ativos($search){
		return $this->ativos()
			->group_by('id_ativo_interno')
			->order_by('nome')
			->like('nome', $search)
			->or_like('id_ativo_interno', $search)
			->or_like('data_inclusao', $search)
			->or_like('data_descarte', $search)
			->get()->result();
	}

	public function get_lista($id_obra = null, $situacao = null){
		$lista = $this->ativos()
							->join('obra', 'obra.id_obra = ativo_interno.id_obra', 'left')
							->select('codigo_obra as obra');

		if ($id_obra != null){
			$lista->where("ativo_interno.id_obra = $id_obra");
		}

		if ($situacao) {
			if (is_array($situacao)) {
				$lista->where("situacao IN (".implode(',',$situacao).")");
			} else {
				$lista->where("situacao = $situacao");
			}
		}
		return $lista->get()->result();
	}

	public function get_ativo_interno($id_ativo_interno=null, $situacao=null){
		$ativo = $this->ativos()
							->where('id_ativo_interno', $id_ativo_interno)
							->join('obra', 'obra.id_obra = ativo_interno.id_obra', 'left')
							->select('codigo_obra as obra');

		if ($situacao) {
			if (is_array($situacao)) {
				$ativo->where("situacao IN (".implode(',',$situacao).")");
			} else {
				$ativo->where("situacao = $situacao");
			}
		}
		return $ativo->get()->row();
	}

	public function get_manutencao($id_ativo_interno, $id_manutencao){
		return $this->db
		->where('id_manutencao', $id_manutencao)
		->where('id_ativo_interno', $id_ativo_interno)
		->get('ativo_interno_manutencao')
		->row();
	}

	public function get_lista_manutencao($id_ativo_interno){
		return $this->db
		->select('manutencao.*, manutencao.valor as manutencao_valor, manutencao.situacao as manutencao_situacao, ativo.*')
		->order_by('manutencao.id_manutencao', 'desc')
		->from('ativo_interno_manutencao manutencao')
		->where("manutencao.id_ativo_interno={$id_ativo_interno}")
		->join('ativo_interno ativo', "ativo.id_ativo_interno=manutencao.id_ativo_interno")
		->group_by('manutencao.id_manutencao')
		->get()->result();
	}

	public function get_obs($id_manutencao, $id_obs){
		return $this->db
		->where('id_manutencao', $id_manutencao)
		->where('id_obs', $id_obs)
		->get('ativo_interno_manutencao_obs')
		->row();
	}

	public function get_lista_manutencao_obs($id_manutencao) {
			return $this->db->select('obs.*, usuario.*')
			->from('ativo_interno_manutencao_obs obs')
			->order_by('obs.data_inclusao', 'DES')
			->where('id_manutencao', $id_manutencao)
			->join('usuario', 'usuario.id_usuario=obs.id_usuario')
			->group_by('obs.id_obs')
			->get()->result();
	}
}