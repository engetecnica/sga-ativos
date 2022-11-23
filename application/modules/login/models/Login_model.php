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
				$this->session->set_flashdata('msg_erro', $this->config->item('messages_fallback')['login_inativo']);
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

			$this->session->set_flashdata('msg_success', $this->config->item('messages_fallback')['login_sucesso']);
			
			$this->registrar_log($usuario, 'login_sucesso');

			// Salvar LOG
			$this->salvar_log_usuarios($usuario->id_usuario, $usuario->nome);

			redirect(base_url($this->input->post('redirect_to')));
			$this->session->unset_userdata('redirect_to');

			
		} else {
			$this->session->set_flashdata('msg_erro', $this->config->item('messages_fallback')['login_incorreto']);
			echo redirect(base_url("login"));
			return;				
		}

	}

}