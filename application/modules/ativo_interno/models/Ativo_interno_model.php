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

	public function get_lista(){
		return $this->db
		->order_by('nome', 'asc')
		->group_by('id_ativo_interno')
		->get('ativo_interno')
		->result();
	}

	public function get_ativo_interno($id_ativo_interno=null){
		return $this->db
		->where('id_ativo_interno', $id_ativo_interno)
		->get('ativo_interno')
		->row();
	}

	public function get_manutencao($id_ativo_interno, $id_manutencao){
		return $this->db
		->where('id_manutencao', $id_manutencao)
		->where('id_ativo_interno', $id_ativo_interno)
		->get('ativo_interno_manutencao')
		->row();
	}

	public function get_lista_manutencao($id_ativo_interno){
		return $this->db->select('manutencao.*, manutencao.valor as manutencao_valor, manutencao.situacao as manutencao_situacao, ativo.*')
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

	public function get_obras(){
		return $this->db->select('obra.*, ep.razao_social as empresa, ep.nome_fantasia')
		->from('obra')
		->order_by('id_obra', 'ASC')
		->join("empresa ep", "ep.id_empresa=obra.id_empresa")
		->group_by('obra.id_obra')
		->get()->result();
	}
}