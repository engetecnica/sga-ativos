<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ativo_interno
 *
 * @author https://www.roytuts.com
 */
class Ativo_interno  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ativo_interno_model');

        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login        
    }

    function index($subitem=null) {

        $data['lista'] = $this->ativo_interno_model->get_lista();

    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar(){
        $data['estados'] = $this->get_estados();
    	$this->get_template('index_form', $data);
    }

    function editar($id_ativo_interno=null){
        $data['detalhes'] = $this->ativo_interno_model->get_ativo_interno($id_ativo_interno);
        $data['estados'] = $this->get_estados();
        $this->get_template('index_form', $data);
    }

    function salvar(){

        $data['id_ativo_interno'] = !is_null($this->input->post('id_ativo_interno')) ? $this->input->post('id_ativo_interno') : '';
        $data['nome'] = $this->input->post('nome');

            $valor = str_replace("R$ ", "", $this->input->post('valor'));
            $valor = str_replace(".", "", $valor);
            $valor = str_replace(",", ".", $valor); 

        $data['valor'] = $valor;
        $data['quantidade'] = $this->input->post('quantidade');
        $data['observacao'] = $this->input->post('observacao');
        $data['situacao'] = $this->input->post('situacao');

        $tratamento = $this->ativo_interno_model->salvar_formulario($data);
        if($data['id_ativo_interno']==''){
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_retorno', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("ativo_interno"));

    }

    function deletar($id=null){
        $this->db->where('id_ativo_interno', $id);
        return $this->db->delete('ativo_interno');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */