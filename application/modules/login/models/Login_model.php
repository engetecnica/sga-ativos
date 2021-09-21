<?php 

class Login_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }	

	public function verificar_login($username = null, $senha = null, $senha_sha1 = false){
		if (!$username) {
			$username = $this->input->post('usuario');
		}

		if (!$senha) {
			$senha = $this->input->post('senha');
			if ($senha_sha1 == false) {
				$senha = sha1($this->input->post('senha'));
			}
		}

		$usuario = $this->db->where('usuario', $username)
								->where('senha', $senha)
								->get('usuario')->row();
		if (!$usuario) {
			$usuario = $this->db
						->where('email', $username)
						->where('senha', $senha)
						->get('usuario')->row();
		}

		if($usuario){
			if((int) $usuario->situacao == 1){
				$this->session->set_flashdata('msg_erro', "Usuário inativo, favor contatar um administrado do Sistema!");
				echo redirect(base_url("login"));		
			}

			if($usuario->nivel == 2){
				$this->db->where("id_obra", $usuario->id_obra);
				$usuario->obra = $this->db->select('id_obra, codigo_obra')->get('obra')->row();
			}

			$modulos = $this->db
						->select('id_modulo')
						->where("id_usuario_nivel", $usuario->nivel)
						->get('usuario_modulo')
						->result();

			$this->session->set_userdata('modulos', json_encode($modulos));
			$this->session->set_userdata('logado', $usuario);
			redirect(base_url());
			return;

		} else {
			$this->session->set_flashdata('msg_erro', "Senha digitada está incorreta.");
			echo redirect(base_url("login"));
			return;				
		}
	}

}