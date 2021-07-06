<?php 

class Index_model extends MY_Model {

	public function get_lista(){
		$this->db->order_by('id', 'DESC');
		return $this->db->get('pagamento')->result();
	}

	public function get_estados_by($uf){
		$this->db->where('uf', $uf);
		return $this->db->get('estado')->row('id_estado');
	}

}