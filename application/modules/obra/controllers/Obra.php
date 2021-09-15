<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class Obra  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('obra_model');

        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login        
    }

    function index() {
        $data['lista'] = $this->obra_model->get_obras();
        $this->get_template('index', $data);
    }

    function adicionar(){
        $data['empresas'] = $this->obra_model->get_empresas();
        $data['estados'] = $this->get_estados();
    	$this->get_template('index_form', $data);
    }

    function editar($id_obra=null){
        $data['empresas'] = $this->obra_model->get_empresas();
        $data['detalhes'] = $this->obra_model->get_obra($id_obra);
        $data['estados'] = $this->get_estados();
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_obra'] = !is_null($this->input->post('id_obra')) ? $this->input->post('id_obra') : '';
        $data['id_empresa'] = !is_null($this->input->post('id_empresa')) ? $this->input->post('id_empresa') : null;
        $data['codigo_obra'] = $this->input->post('codigo_obra');
        $data['endereco'] = $this->input->post('endereco');
        $data['endereco_numero'] = $this->input->post('endereco_numero');
        $data['endereco_complemento'] = $this->input->post('endereco_complemento');
        $data['endereco_bairro'] = $this->input->post('endereco_bairro');
        $data['endereco_cep'] = $this->input->post('endereco_cep');
        $data['endereco_cidade'] = $this->input->post('endereco_cidade');
        $data['endereco_estado'] = $this->input->post('endereco_estado');
        $data['responsavel'] = $this->input->post('responsavel');
        $data['responsavel_telefone'] = $this->input->post('responsavel_telefone');
        $data['responsavel_celular'] = $this->input->post('responsavel_celular');
        $data['responsavel_email'] = $this->input->post('responsavel_email');
        $data['observacao'] = $this->input->post('observacao');
        $data['situacao'] = $this->input->post('situacao');
        $data['obra_base'] = $this->input->post('obra_base');
       
        $this->obra_model->salvar_formulario($data);
        if ($data['obra_base'] == 1){
            $this->obra_model->set_obra_base($data['id_obra']);
        }

        if($data['id_obra']==''){
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("obra"));
    }

    function deletar($id){
        return $this->db->where('id_obra', $id)->delete('obra');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */