<?php 

class usuario_model extends MY_Model {

	public function salvar_formulario($data=null){
		$this->db->where('usuario', $data['usuario']);
		$usuario = $this->db->get('usuario')->row();

		if($data['id_usuario'] == null){
			$data['data_criacao'] = date('Y-m-d H:i:s', strtotime('now'));
			if ($usuario) {
				return "salvar_error";
			}

			$this->db->insert('usuario', $data);
			return "salvar_ok";
		} else {

			if ($usuario && ($usuario->id_usuario != $data['id_usuario'])) {
				return "salvar_error";
			}

			$this->db->where('id_usuario', $data['id_usuario']);
			$this->db->update('usuario', $data);
			return "salvar_ok";
		}
	}

	public function get_lista(){
		$lista = $this->db->select('usuario.*, ob.codigo_obra, ob.id_obra, ep.razao_social, ep.nome_fantasia, un.nivel')
		->from('usuario')
		->order_by('usuario', 'ASC')
		->join("empresa ep", "ep.id_empresa=usuario.id_empresa", "left")
		->join("obra ob", "ob.id_obra=usuario.id_obra", "left")
		->join("usuario_nivel un", "un.id_usuario_nivel=usuario.nivel", "left")
		->group_by('usuario.id_usuario')
		->get()->result();
		return $lista;
	}

	public function get_usuario($id=null, $included_pass = false){
		$this->db->where('id_usuario', $id);
		$usuario = $this->db->get('usuario')->row();
		if ($included_pass == false) {
			unset($usuario->senha);
		}
		return $usuario;
	}
}