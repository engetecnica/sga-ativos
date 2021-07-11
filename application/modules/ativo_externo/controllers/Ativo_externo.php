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
        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
        $this->get_template('index_form', $data);
    }

    function editar_itens($id_ativo_externo_kit){
        $data['detalhes'] = $this->ativo_externo_model->get_ativo_externo($id_ativo_externo_kit);
        $data['estados'] = $this->get_estados();
        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
        $data['itens'] = $this->ativo_externo_model->get_kit_items($id_ativo_externo_kit);
        $not_itens_array = array_map(function($item) {return $item->id_ativo_externo;}, $data['itens'] );
        $data['lista'] = $this->ativo_externo_model->get_out_kit_items($id_ativo_externo_kit, $not_itens_array);
        $this->get_template('index_kit_itens', $data);
    }

    function adicionar_item_kit($id_ativo_externo_kit, $id_ativo_externo_iten){
        $this->db->insert('ativo_externo_kit', [
            "id_ativo_externo_kit" => $id_ativo_externo_kit,
            "id_ativo_externo_iten" => $id_ativo_externo_iten,
        ]);
        echo redirect(base_url("ativo_externo/editar_itens/{$id_ativo_externo_kit}")); 
    }

    function remover_item_kit($id_ativo_externo_kit, $id_ativo_externo_iten){
        $this->db->where('id_ativo_externo_kit', $id_ativo_externo_kit)
                ->where('id_ativo_externo_iten', $id_ativo_externo_iten)
                ->delete('ativo_externo_kit');
        echo redirect(base_url("ativo_externo/editar_itens/{$id_ativo_externo_kit}")); 
    }

    function salvar(){
        if($this->input->post('quantidade') > 0) {
            for($i=1; $i<=$this->input->post('quantidade'); $i++) {
                if (!is_null( $this->input->post('id_ativo_externo'))) {
                    $data['item'][$i]['id_ativo_externo'] = $this->input->post('id_ativo_externo');
                }
                $data['item'][$i]['id_ativo_externo_categoria']     = $this->input->post('id_ativo_externo_categoria');
                $data['item'][$i]['tipo']                           = $this->input->post('tipo');
                $data['item'][$i]['id_obra']                        = $this->input->post('id_obra');
                $data['item'][$i]['nome']                           = $this->input->post('nome');
                $data['item'][$i]['observacao']                     = $this->input->post('observacao');
                $data['item'][$i]['codigo']                         = $this->input->post('codigo');
            }
            $this->get_template('index_form_item', $data);
        }
    }


    function gravar_itens()
    {
        $dados = array();
        $mode = 'insert';
        foreach($_POST['codigo'] as $k => $item){
            if($_POST['id_ativo_externo']) {                
                $dados[$k] = (array) $this->ativo_externo_model->get_ativo_externo($_POST['id_ativo_externo']);
                $mode = 'update';
            }

            if($_POST['codigo']){                
                $dados[$k]['codigo']                        = $_POST['codigo'][$k];
                $dados[$k]['nome']                          = $_POST['item'][$k];
                $dados[$k]['id_ativo_externo_categoria']    = $this->input->post('id_ativo_externo_categoria');
                $dados[$k]['id_obra']                       = $this->input->post('id_obra');
                $dados[$k]['observacao']                    = $this->input->post('observacao');

                if($mode == 'insert') {
                    $dados[$k]['situacao'] = 12; // Estoque
                    $dados[$k]['data_liberacao'] = "0000-00-00 00:00:00";
                    $dados[$k]['tipo'] = $this->input->post('tipo');
                }
            }
        }  

        if( $mode == 'update' && $this->db->update_batch("ativo_externo", $dados, 'id_ativo_externo'))
        {
            $this->session->set_flashdata('msg_retorno', "Registro atualizado com sucesso!");
            echo redirect(base_url("ativo_externo"));
            return;
        }   

        if( $mode == 'insert' && $this->db->insert_batch("ativo_externo", $dados))
        {
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
            echo redirect(base_url("ativo_externo"));
        }
        echo redirect(base_url("ativo_externo"));
    }

    function deletar($id=null){
        $this->db->where('id_ativo_externo', $id);
        return $this->db->delete('ativo_externo');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */