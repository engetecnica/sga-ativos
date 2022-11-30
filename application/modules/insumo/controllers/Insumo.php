<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ferramental_estoque
 *
 * @author https://github.com/srandrebaill
 */
class Insumo extends MY_Controller {

    function __construct() {
        parent::__construct();
        if ($this->is_auth()) {
            $this->load->model('insumo_model');
            $this->load->model('insumo_configuracao/insumo_configuracao_model');
            $this->load->model('fornecedor/fornecedor_model');
        }
    }

    function index()
    {

        $this->get_template('index');
    }



    /*
        Adicionar Insumo
    */
    public function adicionar()
    {
        $data['tipo_insumo'] = $this->insumo_configuracao_model->get_lista();
        $data['fornecedor'] = $this->fornecedor_model->get_lista();
        $data['detalhes'] = $this->insumo_model->get_insumos();

        $this->get_template('index_form', $data);
    }

    public function salvar(){
        $data['id_insumo'] = !is_null($this->input->post('id_insumo')) ? $this->input->post('id_insumo') : '';
        $data['id_insumo_configuracao'] = $this->input->post('tipo_insumo');
        $data['id_fornecedor'] = $this->input->post('fornecedor');
        $data['titulo'] = $this->input->post('titulo');
        $data['codigo_insumo'] = $this->input->post('cod_insumo');
        $data['quantidade'] = $this->input->post('quantidade');
        $data['valor'] = $this->input->post('valor');
        $data['funcao'] = $this->input->post('funcao');
        $data['composicao'] = $this->input->post('composicao');
        $data['descricao'] = $this->input->post('descricao_insumo');
        $data['situacao'] = $this->input->post('situacao');

        $this->insumo_model->salvar_formulario($data);

        if ($data['id_insumo'] == '') {
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso");
        }else {
            $this->session->set_flashdata('msg_success', "Registro inserido com sucesso");
        }
        echo redirect(base_url("insumo"));
    }




}