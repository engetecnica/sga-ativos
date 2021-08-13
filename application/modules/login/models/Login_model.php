<?php 

class Login_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }	

	public function verificar_login(){
		$username = $this->input->post('usuario');
		$senha = sha1($this->input->post('senha'));

		// Verifica se o ID existe
		$this->db->where('usuario', $username);
		if($this->db->get('usuario')->num_rows() > 0){
			$usuario = $this->db->where('usuario', $username)
													->where('senha', $senha)
													->get('usuario')->row();

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

			} else {
				$this->session->set_flashdata('msg_erro', "Senha digitada está incorreta.");
				echo redirect(base_url("login"));				
			}

		} else {
			$this->session->set_flashdata('msg_erro', "O Código de Acesso não possui validade.");
			echo redirect(base_url("login"));
		}
	}

}