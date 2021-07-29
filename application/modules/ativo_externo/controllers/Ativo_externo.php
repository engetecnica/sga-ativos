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
        $this->load->model('ferramental_requisicao/ferramental_requisicao_model');       
    }

    function index($subitem=null) {
        $data['lista'] = $this->ativo_externo_model->get_lista();
        $data['grupos'] = $this->ativo_externo_model->get_lista_grupo();
        $data['status_lista'] = $this->ferramental_requisicao_model->get_requisicao_status();
    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar($id_ativo_externo_grupo=null){
        $data['mode'] = "insert";
        $data['url'] = base_url("ativo_externo/salvar");
        
        if ($id_ativo_externo_grupo) {
            $data['url'] = base_url("ativo_externo/salvar_grupo");
            $grupo = $this->ativo_externo_model->get_ativo_externo_grupo($id_ativo_externo_grupo);
            $data['detalhes'] = (object) [
                'tipo' => $grupo[0]->tipo,
                'id_ativo_externo_categoria' => $grupo[0]->id_ativo_externo_categoria,
                'id_ativo_externo_grupo' => $grupo[0]->id_ativo_externo_grupo,
                'nome' => $grupo[0]->nome,
            ];
            $data['mode'] = "insert_grupo";
        }

        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
    	$this->get_template('index_form', $data);
    }

    function editar($id_ativo_externo=null){
        $data['mode'] = "update";
        $data['url'] = base_url("ativo_externo/salvar");
        $data['detalhes'] = $this->ativo_externo_model->get_ativo_externo($id_ativo_externo);
        $data['estados'] = $this->get_estados();
        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
        $this->get_template('index_form', $data);
    }


    function editar_grupo($id_ativo_externo_grupo){
        $data['estados'] = $this->get_estados();
        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
        $data['url'] = base_url("ativo_externo/salvar_grupo");
        $grupo = $this->ativo_externo_model->get_ativo_externo_grupo($id_ativo_externo_grupo);

        if ($grupo) {
            $data['detalhes'] = (object) [
                'quantidade' => count($grupo),
                'tipo' => $grupo[0]->tipo,
                'id_ativo_externo_categoria' => $grupo[0]->id_ativo_externo_categoria,
                'id_ativo_externo_grupo' => $grupo[0]->id_ativo_externo_grupo,
                'nome' => $grupo[0]->nome,
                'valor' => $grupo[0]->valor,
            ];
            $data['mode'] = "update_grupo";
            $this->get_template('index_form', $data);
            return;
        }

        echo redirect(base_url("ativo_externo#lista2")); 
    }

    function editar_items($id_ativo_externo_kit){
        $data['detalhes'] = $this->ativo_externo_model->get_ativo_externo($id_ativo_externo_kit);
        $data['estados'] = $this->get_estados();
        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
        $data['items'] = $this->ativo_externo_model->get_kit_items($id_ativo_externo_kit);
        $not_items_array = array_map(function($item) {return $item->id_ativo_externo;}, $data['items'] );
        $data['lista'] = $this->ativo_externo_model->get_out_kit_items($id_ativo_externo_kit, $not_items_array);
        $this->get_template('index_kit', $data);
    }

    function adicionar_item_kit($id_ativo_externo_kit, $id_ativo_externo_item){
        $this->db->insert('ativo_externo_kit', [
            "id_ativo_externo_kit" => $id_ativo_externo_kit,
            "id_ativo_externo_item" => $id_ativo_externo_item,
        ]);
        echo redirect(base_url("ativo_externo/editar_items/{$id_ativo_externo_kit}")); 
    }

    function remover_item_kit($id_ativo_externo_kit, $id_ativo_externo_item){
        $this->db->where('id_ativo_externo_kit', $id_ativo_externo_kit)
                ->where('id_ativo_externo_item', $id_ativo_externo_item)
                ->delete('ativo_externo_kit');
        echo redirect(base_url("ativo_externo/editar_items/{$id_ativo_externo_kit}")); 
    }


    function salvar(){
        $quantidade = $this->input->post('quantidade') ? (int) $this->input->post('quantidade') : 1;
        $data['mode'] = $this->input->post('mode');
        $id_grupo = $this->input->post('id_ativo_externo_grupo') ? $this->input->post('id_ativo_externo_grupo') : $this->ativo_externo_model->get_proximo_grupo();

        if ($quantidade > 0) {
            $ids_ativos_externos = $this->input->post('id_ativo_externo');
            for($i=0; $i < $quantidade; $i++) {
                if (isset($this->input->post('id_ativo_externo')[$i])) {
                    $data['item'][$i]['id_ativo_externo'] = $this->input->post('id_ativo_externo')[$i];
                }
                $data['item'][$i]['id_ativo_externo_grupo']         =  $id_grupo;
                $data['item'][$i]['id_ativo_externo_categoria']     = $this->input->post('id_ativo_externo_categoria');
                $data['item'][$i]['tipo']                           = $this->input->post('tipo');
                $data['item'][$i]['id_obra']                        = $this->input->post('id_obra') ? $this->input->post('id_obra') : $this->user->id_obra;
                $data['item'][$i]['nome']                           = ucwords($this->input->post('nome'));
                $data['item'][$i]['observacao']                     = $this->input->post('observacao');
                $data['item'][$i]['codigo']                         = strtoupper($this->input->post('codigo'));

                $valor = str_replace("R$ ", "", $this->input->post('valor'));
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor); 
                $data['item'][$i]['codigo'] = $valor;
            }
            $data['url'] = base_url("ativo_externo/gravar_items");
            $this->get_template('index_form_item', $data);
            return;
        }

        $this->session->set_flashdata('msg_success', "Nenhum registro modificado!");
        echo redirect(base_url("ativo_externo"));
    }


    function salvar_grupo(){
        $items = [];
        $quantidade = $this->input->post('quantidade') ? (int) $this->input->post('quantidade') : 1;
        $data['mode'] = $this->input->post('mode');
        $id_grupo = $this->input->post('id_ativo_externo_grupo');
        $grupo = $this->ativo_externo_model->get_ativo_externo_grupo($id_grupo);

        if ($grupo) {
            foreach($grupo as $k => $item) {
                $grupo[$k] = (array) $grupo[$k];
            }
        } else {
            $grupo = [];
        }
            
        if (($data['mode'] == 'insert_grupo')) {
            for($i=0; $i < $quantidade; $i++) {
                $items[$i]['id_ativo_externo_grupo'] = $id_grupo;
                $items[$i]['id_ativo_externo_categoria']     = $this->input->post('id_ativo_externo_categoria');
                $items[$i]['tipo']                           = $this->input->post('tipo');
                $items[$i]['id_obra']                        = $this->input->post('id_obra');
                $items[$i]['nome']                           = $this->input->post('nome');
                $items[$i]['observacao']                     = $this->input->post('observacao');
                $items[$i]['codigo']                         = $this->input->post('codigo');

                $valor = str_replace("R$ ", "", $this->input->post('valor'));
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor); 
                $items[$i]['valor'] = $valor;
            }
        }

        if (($data['mode'] == 'update_grupo')) {
            foreach($grupo as $k => $item) {
                if ($this->input->post('nome')) {
                    $grupo[$k]['nome'] = $this->input->post('nome');
                }
                if ($this->input->post('observacao')) {
                    $grupo[$k]['observacao'] = $this->input->post('observacao');
                }
                if ($this->input->post('valor')) {
                    $valor = str_replace("R$ ", "", $this->input->post('valor'));
                    $valor = str_replace(".", "", $valor);
                    $valor = str_replace(",", ".", $valor);
                    $grupo[$k]['valor'] = $valor;
                }
            }
        }

        $data['url'] = base_url("ativo_externo/gravar_items_grupo");
        $data['item'] = array_merge($grupo, $items);
        $this->get_template('index_form_item', $data);
    }

   
    function gravar_items($mode = null)
    {
        $dados = array();
        $mode = $this->input->post('mode') ? $this->input->post('mode') : 'insert';

        foreach($_POST['codigo'] as $k => $item){
            if(isset($_POST['id_ativo_externo'])) {              
                $dados[$k] = (array) $this->ativo_externo_model->get_ativo_externo($_POST['id_ativo_externo'][$k]);
            }

            if($_POST['codigo']){
                $dados[$k]['id_ativo_externo_grupo']        = $this->input->post('id_ativo_externo_grupo');                
                $dados[$k]['codigo']                        = $_POST['codigo'][$k];
                $dados[$k]['nome']                          = $_POST['item'][$k];
                $dados[$k]['valor']                         = $_POST['valor'][$k];
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
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            echo redirect(base_url("ativo_externo"));
            return;
        }   

        if( $mode == 'insert' && $this->db->insert_batch("ativo_externo", $dados))
        {
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            echo redirect(base_url("ativo_externo"));
        }
        echo redirect(base_url("ativo_externo"));
    }

    

    function gravar_items_grupo($mode = null)
    {
        $dados = array();
        $mode = $this->input->post('mode') ? $this->input->post('mode') : 'insert_grupo';

        foreach($_POST['codigo'] as $k => $item){
            if(isset($_POST['id_ativo_externo'][$k])) {              
                $dados[$k] = (array) $this->ativo_externo_model->get_ativo_externo($_POST['id_ativo_externo'][$k]);
            }

            if($_POST['codigo']){               
                $dados[$k]['codigo']                        = $_POST['codigo'][$k];
                $dados[$k]['nome']                          = $_POST['item'][$k];
                $dados[$k]['valor']                         = $_POST['valor'][$k];
                $dados[$k]['id_ativo_externo_categoria']    = $this->input->post('id_ativo_externo_categoria');
                $dados[$k]['id_obra']                       = $this->input->post('id_obra');
                $dados[$k]['observacao']                    = $this->input->post('observacao');

                if($mode == 'insert_grupo') {
                    $dados[$k]['situacao'] = 12; // Estoque
                    $dados[$k]['data_liberacao'] = "0000-00-00 00:00:00";
                    $dados[$k]['tipo'] = $this->input->post('tipo');
                    $dados[$k]['id_ativo_externo_grupo'] = $this->input->post('id_ativo_externo_grupo'); 
                }
            }
        }
    

        if($mode == 'insert_grupo') {
            foreach($dados as $k => $value){
                if (isset($value['id_ativo_externo'])) {
                    unset($dados[$k]);
                }
            }
        }

        if( $mode == 'update_grupo' && $this->db->update_batch("ativo_externo", $dados, 'id_ativo_externo'))
        {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
        }   

        if( $mode == 'insert_grupo' && $this->db->insert_batch("ativo_externo", $dados))
        {
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        }
        echo redirect(base_url("ativo_externo#lista2"));
    }

    function deletar($id){
        //foradeoperacao - 10
        $this->db
        ->where('id_ativo_externo', $id)
        ->delete('ativo_externo');
    }

    function deletar_grupo($id_ativo_externo_grupo){
        //foradeoperacao - 10
        $this->db
        ->where('id_ativo_externo_grupo', $id_ativo_externo_grupo)
        ->delete('ativo_externo');
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */