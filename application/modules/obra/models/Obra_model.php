<?php 

class Obra_model extends MY_Model {

	public function salvar_formulario($data=null){

		if($data['id_obra']==''){
			$this->db->insert('obra', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_obra', $data['id_obra']);
			$this->db->update('obra', $data);
			return "salvar_ok";
		}

	}

	public function get_obras(){
		return $this->db
					->select('obra.*, ep.razao_social as empresa, ep.nome_fantasia')
					->from('obra')
					->order_by('id_obra', 'ASC')
					->join("empresa ep", "ep.id_empresa=obra.id_empresa")
					->group_by('obra.id_obra')
					->get()
					->result();
	}

	public function get_obra($id_obra=null){
		return $this->db->where('id_obra', $id_obra)->get('obra')->row();
	}

	public function get_empresas(){
		$this->db->order_by('razao_social', 'ASC');
		return $this->db->get('empresa')->result();
	}

	public function set_obra_base($id_obra){
		$obras = $this->db->select('obra.*')
						->from('obra')
						->order_by('id_obra', 'ASC')
						->where("obra_base = 1")
						->get()->result();
		
		if (count($obras) > 0) {
			foreach ($obras as $obra) {
				$obra->obra_base = null;
				$this->salvar_formulario((array) $obra);
			}
		}

		$obras = $this->db->select('obra.*')
		->from('obra')
		->where(["id_obra" => $id_obra])
		->get()->result();

		if (isset($obras[0])) {
			$obras[0]->obra_base = 1;
			$this->salvar_formulario((array) $obras[0]);
		}
	}

}