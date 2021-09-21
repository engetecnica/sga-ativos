<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class Login  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('login_model'); 
        $this->load->model('usuario/usuario_model');        
    }

    function index($subitem=null) {
        //$this->db->query("SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        if($this->session->userdata('logado')==true){
            redirect(base_url());
        }
        $this->load->view('index');
    }

    function acessar(){
    	$this->login_model->verificar_login();
    }

    function logout(){
    	$this->session->sess_destroy();
    	$this->session->unset_userdata('logado');
    	echo redirect(base_url("login"));
    }

    function recuperar_senha(){
        $this->load->view('recuperar_senha_email');  
    }

    function enviar_codigo(){
        $usuario = $this->usuario_model->get_usuario_email($this->input->post('email'));
        
        if ($usuario) {
            $codigo = $this->usuario_model->gerar_codigo();
            $validade = date('Y-m-d H:i:s', strtotime("+60 minutes"));
            $enviado = $this->usuario_model->enviar_email_recuperacao($usuario, $codigo, $validade);

            if ($enviado) {
                $this->db
                ->where("id_usuario = {$usuario->id_usuario}")
                ->update('usuario', [
                    "codigo_recuperacao" => $codigo,
                    "codigo_recuperacao_validade" => $validade
                ]);
                $this->session->set_flashdata('msg_success', "Código enviado com sucesso!");
                echo redirect(base_url("login"));
            }
            $this->session->set_flashdata('msg_erro', "Ocorreu um erro ao tentar enviar o código de recuperação!");
            return;
        }

        $this->session->set_flashdata('msg_erro', "Usuário inválido ou Não encontrado!");
        echo redirect(base_url("login/recuperar_senha"));
    }

    function nova_senha($codigo){
        $usuario = $this->usuario_model->get_usuario_codigo($codigo);

        if ($usuario) {
            $this->load->view('recuperar_senha_redefinir', ["usuario" => $usuario, "codigo" => $codigo]); 
            return;
        }
    
        $this->session->set_flashdata('msg_erro', "Código inválido ou Usuário Não encontrado!");
        echo redirect(base_url("login/recuperar_senha")); 
    }

    function redefinir_senha(){ 
        $usuario = $this->usuario_model->get_usuario($this->input->post('id_usuario'));

        if ($usuario) {
            $senha = $this->usuario_model->verificaSenha($this->input->post('senha'), $this->input->post('confirmar_senha'));
            if ($senha) {
                $this->db
                ->where("id_usuario = {$usuario->id_usuario}")
                ->update('usuario', [
                    "codigo_recuperacao" => null,
                    "codigo_recuperacao_validade" => null,
                    "senha" => $senha
                ]);

                $this->session->set_flashdata('msg_success', "Senha redefinida com Sucesso!");
                $this->login_model->verificar_login($usuario->usuario, $senha, true);
                return;
            }

            $this->session->set_flashdata('msg_erro', "As senhas fornecidas não conferem!");
            echo redirect(base_url("login/redefinir_senha/".$this->input->post('codigo'))); 
            return;
        }

        $this->session->set_flashdata('msg_erro', "Código inválido ou Usuário Não encontrado!");
        echo redirect(base_url("login/recuperar_senha")); 
    }

    function confirmar_email($codigo){
       $usuario = $this->usuario_model->get_usuario_codigo($codigo, true);
       if ($usuario) {
            $this->db
            ->where("id_usuario = {$usuario->id_usuario}")
            ->update('usuario', [
                "email_confirmado_em" => date('Y-m-d H:i:s'),
                "codigo_recuperacao" => null,
                "codigo_recuperacao_validade" => null
            ]);
            $this->session->set_flashdata('msg_success', "Email confirmado com Sucesso!");

            if (!$this->user) {
                return $this->login_model->verificar_login($usuario->usuario, $usuario->senha, true);
            }

            echo redirect(base_url());
            return;
       }
    
       $this->session->set_flashdata('msg_erro', "Código inválido ou Usuário Não encontrado!");
       echo redirect(base_url("login"));
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */