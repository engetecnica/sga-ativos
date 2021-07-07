<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class usuario  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('usuario_model');

        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login        
    }

    function index($subitem=null) {
        $data['lista'] = $this->usuario_model->get_lista();
    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar(){
        $data['detalhes'] =  (object) [
            'nivel' => null,
            'empresas' => $this->get_empresas(),
            'obras' => $this->get_obras(),
            'niveis' => $this->get_niveis()
        ];
    	$this->get_template('index_form', $data);
    }

    function verificaSenha($senha, $confirmar_senha) {
        $senha = strlen($senha) > 0 ? $senha : null;
        $confirmar_senha = strlen($confirmar_senha) > 0 ? $confirmar_senha : null;

        if($senha && $confirmar_senha) {
            if ($senha == $confirmar_senha ) {
                return sha1($confirmar_senha);
            }

            if ($senha != $confirmar_senha) {
                return null;
            }
        }
        return null;
    }

    function editar($id_usuario=null){
        $usuario = $this->usuario_model->get_usuario($id_usuario);
        $data = null;
        if ($usuario) {
            $data['detalhes'] = $usuario;
            $data['detalhes']->empresas = $this->get_empresas();
            $data['detalhes']->obras = $this->get_obras();
            $data['detalhes']->niveis = $this->get_niveis();
        }
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_usuario'] = $this->input->post('id_usuario');
        $data['usuario'] = $this->input->post('usuario');
        $data['nivel'] = $this->input->post('nivel');
        $data['id_empresa'] = $this->input->post('id_empresa');
        $data['id_obra'] = $this->input->post('id_obra');

        $senha = strlen($this->input->post('senha')) > 0 ? $this->input->post('senha') : null;
        $confirmar_senha = strlen($this->input->post('confirmar_senha')) > 0 ? $this->input->post('confirmar_senha') : null;
        $data['senha'] = $this->verificaSenha($senha, $confirmar_senha);

        if (($senha && $confirmar_senha) &&  ($data['senha'] === null)) {
            $this->session->set_flashdata('msg_erro', "As senhas fornecidas não conferem!");

            if($data['id_usuario'] == null){
                echo redirect(base_url("usuario/adicionar"));
            } else {
                echo redirect(base_url("usuario/editar/{$data['id_usuario']}"));          
            }
            return;
        }

        if ($data['id_usuario'] && !$data['senha']) {
            $usuario = $this->usuario_model->get_usuario($data['id_usuario'], true);
            if (isset($usuario->senha)) {
                $data['senha'] = $usuario->senha;
            }
        }

        $status = $this->usuario_model->salvar_formulario($data);
        if ($status === 'salvar_ok') {
            if($data['id_usuario'] == null){
                $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
            } else {
                $this->session->set_flashdata('msg_retorno', "Registro atualizado com sucesso!");            
            }
            echo redirect(base_url("usuario"));
        }

        if ($status === 'salvar_error') {
            $this->session->set_flashdata('msg_erro', "Nome de usuário já existe na base de dados!");
            if($data['id_usuario'] == null){
                echo redirect(base_url("usuario/adicionar"));
            } else {
                echo redirect(base_url("usuario/editar/{$data['id_usuario']}"));          
            }
        }
    }

    function deletar($id=null){
        $this->db->where('id_usuario', $id);
        return $this->db->delete('usuario');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */