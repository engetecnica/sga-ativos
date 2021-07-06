<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class fornecedor  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('fornecedor_model');

        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login        
    }

    function index($subitem=null) {

        $data['lista'] = $this->fornecedor_model->get_lista();

    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar(){
        $data['estados'] = $this->get_estados();
    	$this->get_template('index_form', $data);
    }

    function editar($id_fornecedor=null){
        $data['detalhes'] = $this->fornecedor_model->get_fornecedor($id_fornecedor);
        $data['estados'] = $this->get_estados();
        $this->get_template('index_form', $data);
    }

    function salvar(){

        $data['id_fornecedor'] = !is_null($this->input->post('id_fornecedor')) ? $this->input->post('id_fornecedor') : '';
        $data['razao_social'] = $this->input->post('razao_social');
        $data['nome_fantasia'] = $this->input->post('nome_fantasia');
        $data['cnpj'] = $this->input->post('cnpj');
        $data['inscricao_estadual'] = $this->input->post('inscricao_estadual');
        $data['inscricao_municipal'] = $this->input->post('inscricao_municipal');
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

        $tratamento = $this->fornecedor_model->salvar_formulario($data);
        if($data['id_fornecedor']==''){
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_retorno', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("fornecedor"));

    }

    function deletar($id=null){
        $this->db->where('id_fornecedor', $id);
        return $this->db->delete('fornecedor');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */