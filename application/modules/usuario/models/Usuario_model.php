<?php 

class usuario_model extends MY_Model {

	public function salvar_formulario($data=null){
		if($data['id_usuario'] == null){
			$this->db->insert('usuario', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_usuario', $data['id_usuario']);
			$this->db->update('usuario', $data);
			return "salvar_ok";
		}
	}

	public function get_lista(){
		$lista = $this->db->order_by('usuario', 'ASC')
		->join("empresa", "empresa.id_empresa=usuario.id_empresa")
		->join("obra", "obra.id_obra=usuario.id_obra")
		->join("usuario_nivel", "usuario_nivel.id_usuario_nivel=usuario.nivel")
		->get('usuario')->result();
		return $this->formatArrayReplied($lista, 'id_usuario');
	}

	public function get_usuario($id=null, $comSenha = false){
		$this->db->where('id_usuario', $id);
		$usuario = $this->db->get('usuario')->row();
		if ($comSenha == false) {
			unset($usuario->senha);
		}
		return $usuario;
	}
}