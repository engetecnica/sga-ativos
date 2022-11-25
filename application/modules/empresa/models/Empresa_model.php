<?php 

class empresa_model extends MY_Model {

	public function salvar_formulario($data=null){
		if($data['id_empresa']==''){

			// Salvar LOG
			$this->salvar_log(4, null, 'adicionar', $data);

			$this->db->insert('empresa', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_empresa', $data['id_empresa']);
			$this->db->update('empresa', $data);

			// Salvar LOG
			$this->salvar_log(4, $data['id_empresa'], 'editar', $data);

			return "salvar_ok";
		}
	}

	public function query(){
		return $this->db
			->from('empresa')
			->select('*');
	}

	public function count(){
		return $this->query()
			->order_by('razao_social', 'ASC')
			->get()->num_rows();
	}

	public function get_empresas(){
		return $this->query()
			->order_by('razao_social', 'ASC')
			->get()->result();
	}

	public function get_empresa($id_empresa=null){
		return $this->query()
			->where('id_empresa', $id_empresa)
			->get()->row();
	}
}