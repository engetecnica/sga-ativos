<?php 

class Login_model extends MY_Model {

    function __construct() {
        parent::__construct();
    }	

	public function verificar_login(){

		$usuario = $this->input->post('usuario');
		$senha = sha1($this->input->post('senha'));

		// Verifica se o ID existe
		$this->db->where('usuario', $usuario);
		if($this->db->get('usuario')->num_rows()>0){

			$this->db->where('usuario', $usuario);
			$this->db->where('senha', $senha);
			if($this->db->get('usuario')->num_rows()>0){

				$this->db->where('usuario', $usuario);
				$this->db->where('senha', $senha);
				$consulta = $this->db->get('usuario')->row();

				if($consulta->nivel==1)
				{

				} 
					else 
				{
					$this->db->where("id_obra", $consulta->id_obra);
					$consulta->obra = $this->db->select('id_obra, codigo_obra')->get('obra')->row();
				}


				$modulos = $this->db
									->select('id_modulo')
									->where("id_usuario_nivel", $consulta->nivel)
									->get('usuario_modulo')
									->result();

				$this->session->set_userdata('modulos', json_encode($modulos));
				$this->session->set_userdata('logado', $consulta);
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