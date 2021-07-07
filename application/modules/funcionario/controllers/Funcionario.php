<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class funcionario  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('funcionario_model');

        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login        
    }

    function index($subitem=null) {
        $data['lista'] = $this->funcionario_model->get_lista();
    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar($data = null){
        $data['estados'] = $this->get_estados();
    	$this->get_template('index_form', $data);
    }

    function editar($id_funcionario=null){
        $data['detalhes'] = $this->funcionario_model->get_funcionario($id_funcionario);
        //$data['veiculos_cadastrados'] = $this->funcionario_model->get_veiculos_cadastrados($id_funcionario);
        $data['estados'] = $this->get_estados(); 
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_funcionario'] = !is_null($this->input->post('id_funcionario')) ? $this->input->post('id_funcionario') : '';
        $data['nome'] = $this->input->post('nome');
        $data['rg'] = $this->input->post('rg');
        $data['cpf'] = $this->input->post('cpf');
        $data['data_nascimento'] = $this->input->post('data_nascimento');
        $data['endereco'] = $this->input->post('endereco');
        $data['endereco_numero'] = $this->input->post('endereco_numero');
        $data['endereco_complemento'] = $this->input->post('endereco_complemento');
        $data['endereco_bairro'] = $this->input->post('endereco_bairro');
        $data['endereco_cep'] = $this->input->post('endereco_cep');
        $data['endereco_cidade'] = $this->input->post('endereco_cidade');
        $data['endereco_estado'] = $this->input->post('endereco_estado');
        $data['telefone'] = $this->input->post('telefone');
        $data['celular'] = $this->input->post('celular');
        $data['email'] = $this->input->post('email');
        $data['observacao'] = $this->input->post('observacao');
        $data['situacao'] = $this->input->post('situacao');

        $funcionario = $this->funcionario_exists($data);
        if(!$funcionario && ($data['id_funcionario'] == '')){
            $this->funcionario_model->salvar_formulario($data);
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
            echo redirect(base_url("funcionario")); 
            return;
        }

        if(!$funcionario && ($data['id_funcionario'] != '')){
            $this->funcionario_model->salvar_formulario($data);
            $this->session->set_flashdata('msg_retorno', "Registro atualizado com sucesso!");
            echo redirect(base_url("funcionario/editar/{$data['id_funcionario']}")); 
            return;
        }

        if($funcionario){
            $this->session->set_flashdata('msg_erro', "Dados registrados já existem na base de dados!");
            if($data['id_funcionario'] == ''){
                return $this->adicionar(['detalhes' => (object) $data]);
            } else {
                echo redirect(base_url("funcionario/editar/{$data['id_funcionario']}"));     
            }
        }
    }

    function deletar($id=null){
        $this->db->where('id_funcionario', $id);
        return $this->db->delete('funcionario');
    }

    function funcionario_exists($data = []){
        $funcionario = $this->db
            ->where('email', $data['email'])
            ->where('id_funcionario !=', $data['id_funcionario'])
            ->get('funcionario')->result();

        if ($funcionario) {
            return $funcionario;
        }
        return false;
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */