<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ativo_externo
 *
 * @author https://www.roytuts.com
 */
class Ativo_externo  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ativo_externo_model');

        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login        
    }

    function verificar($id_ativo_externo=null)
    {
        if($id_ativo_externo==null)
        {

        }
        else 
        {
            $data['lista'] = $this->ativo_externo_model->get_lista_verificada($id_ativo_externo);
            $this->get_template("verificar", $data);            
        }
    }

    function index($subitem=null) {

        $data['lista'] = $this->ativo_externo_model->get_lista();

    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar(){

        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
    	$this->get_template('index_form', $data);

    }

    function editar($id_ativo_externo=null){
        $data['detalhes'] = $this->ativo_externo_model->get_ativo_externo($id_ativo_externo);
        $data['estados'] = $this->get_estados();
        $this->get_template('index_form', $data);
    }

    function salvar(){

        if($this->input->post('quantidade') > 0)
        {

            for($i=1; $i<=$this->input->post('quantidade'); $i++)
            {
                $data['item'][$i]['id_ativo_externo_categoria']     = $this->input->post('id_ativo_externo_categoria');
                $data['item'][$i]['id_obra']                        = $this->input->post('id_obra');
                $data['item'][$i]['nome']                           = $this->input->post('nome');
                $data['item'][$i]['observacao']                     = $this->input->post('observacao');
                $data['item'][$i]['data_atualizacao']               = "0000-00-00 00:00:00";
            }


            $this->get_template('index_form_item', $data);

            /*
            if($this->db->insert_batch("ativo_externo", $data))
            {
                echo $this->input->post('quantidade')." - Registros Inseridos";
            }
            */

        }

        //$this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
        //echo redirect(base_url("ativo_externo"));

    }


    function gravar_itens()
    {
        $dados = array();
        foreach($_POST['codigo'] as $k=>$item){            
            if($_POST['codigo']){                
                $dados[$k]['codigo']                        = $_POST['codigo'][$k];
                $dados[$k]['nome']                          = $_POST['item'][$k];
                $dados[$k]['id_ativo_externo_categoria']    = $this->input->post('id_ativo_externo_categoria');
                $dados[$k]['id_obra']                       = $this->input->post('id_obra');
                $dados[$k]['observacao']                    = $this->input->post('observacao');
                $dados[$k]['data_liberacao']                = "0000-00-00 00:00:00";
                #$dados[$k]['condicao']                      = "Liberado";
                $dados[$k]['situacao']                      = 12; // Estoque
            }
        }  


        if($this->db->insert_batch("ativo_externo", $dados))
        {
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
            echo redirect(base_url("ativo_externo"));
        }     



        /*
        echo "<pre>";
        print_r($dados);
        print_r($this->input->post());
        echo "</pre>";
        */
    }

    function deletar($id=null){
        $this->db->where('id_ativo_externo', $id);
        return $this->db->delete('ativo_externo');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */