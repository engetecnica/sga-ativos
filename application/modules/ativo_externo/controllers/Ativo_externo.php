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
        $this->load->model('anexo/anexo_model');   
    }

    function index($subitem=null) {
        $data['lista'] = $this->ativo_externo_model->get_ativos();
        $data['grupos'] = $this->ativo_externo_model->get_grupos($this->user->id_obra);
        $data['status_lista'] = $this->ferramental_requisicao_model->get_requisicao_status();
    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar($id_ativo_externo_grupo=null){
        $data['mode'] = "insert";
        $data['url'] = base_url("ativo_externo/salvar");
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
        
        if ($id_ativo_externo_grupo) {
            $data['url'] = base_url("ativo_externo/salvar_grupo");
            $grupo = $this->ativo_externo_model->get_grupo($id_ativo_externo_grupo);
            $data['detalhes'] = (object) [
                'tipo' => $grupo->tipo,
                'id_ativo_externo_categoria' => $grupo->id_ativo_externo_categoria,
                'id_ativo_externo_grupo' => $grupo->id_ativo_externo_grupo,
                'nome' => $grupo->nome,
                'valor' => $grupo->valor,
            ];
            $data['mode'] = "insert_grupo";
        }

        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
    	$this->get_template('index_form', $data);
    }

    function editar($id_ativo_externo=null){
        $data['mode'] = "update";
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
        $data['url'] = base_url("ativo_externo/salvar");
        $data['detalhes'] = $this->ativo_externo_model->get_ativo($id_ativo_externo);
        $data['estados'] = $this->get_estados();
        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
        $this->get_template('index_form', $data);
    }


    function certificado_de_calibracao($id_ativo_externo){
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
        $data['detalhes'] = $this->ativo_externo_model->get_ativo($id_ativo_externo);
    	$this->get_template('index_certificado_de_calibracao', $data);
    }


    function salvar_certificado_de_calibracao($id_ativo_externo){
        $ativo =  $this->ativo_externo_model->get_ativo($id_ativo_externo);
        if ($ativo) {
            $data['certificado_de_calibracao'] = ($_FILES['certificado_de_calibracao'] ? $this->upload_arquivo('certificado_de_calibracao') : '');
            if (!$data['certificado_de_calibracao'] || $data['certificado_de_calibracao'] == '') {
                $this->session->set_flashdata('msg_erro', "O tamanho do certificado deve ser menor ou igual a ".ini_get('upload_max_filesize'));
                return redirect(base_url("ativo_externo/certificado_de_calibracao/{$id_ativo_externo}"));
            }

            $certificado = [
                'certificado_de_calibracao' => $data['certificado_de_calibracao'],
                'inclusao_certificado' => date("Y-m-d", strtotime('now')),
                'validade_certificado' => $this->input->post('validade_certificado')
            ];
            $this->db->where('id_ativo_externo', $id_ativo_externo)->update('ativo_externo', $certificado);

            $this->salvar_anexo(
                12, 
                $data, 
                $id_ativo_externo, 
                null, 
                'certificado_de_calibracao', 
                'certificado_de_calibracao'
            );

            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            echo redirect(base_url("ativo_externo/certificado_de_calibracao/{$id_ativo_externo}"));
            return;
        }

        $this->session->set_flashdata('msg_error', "Nenhum ativo encontrado!");
        return redirect(base_url("ativo_externo"));
    }

    function deletar_certificado_de_calibracao($id_ativo_externo){
        $ativo =  $this->ativo_externo_model->get_ativo($id_ativo_externo);
        if ($ativo) {
       
            $certificado = [
                'certificado_de_calibracao' => null,
                'inclusao_certificado' => null,
                'validade_certificado' => null
            ];
            $this->db->where('id_ativo_externo', $id_ativo_externo)->update('ativo_externo', $certificado);

            $this->db->where('anexo', "certificado_de_calibracao/{$ativo->certificado_de_calibracao}")
                    ->delete('anexo');

            $path = __DIR__."/../../../../assets/uploads";
            $file = "{$path}/certificado_de_calibracao/{$ativo->certificado_de_calibracao}";
            if (file_exists($file)) {
                unlink($file);
            }
            
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            echo redirect(base_url("ativo_externo/certificado_de_calibracao/{$id_ativo_externo}"));
            return;
        }

        $this->session->set_flashdata('msg_error', "Nenhum ativo encontrado!");
        return redirect(base_url("ativo_externo"));
    }


    function editar_grupo($id_ativo_externo_grupo){
        $data['estados'] = $this->get_estados();
        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
        $data['url'] = base_url("ativo_externo/salvar_grupo");
        $grupo = $this->ativo_externo_model->get_grupo($id_ativo_externo_grupo);
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');

        if ($grupo) {
            $data['detalhes'] = (object) [
                'total' => $grupo->total,
                'tipo' => $grupo->tipo,
                'id_ativo_externo_categoria' => $grupo->id_ativo_externo_categoria,
                'id_ativo_externo_grupo' => $grupo->id_ativo_externo_grupo,
                'nome' => $grupo->nome,
                'valor' => $grupo->valor,
                'id_obra' => $grupo->id_obra,
                'observacao' => $grupo->observacao,
            ];
            $data['mode'] = "update_grupo";
            $this->get_template('index_form', $data);
            return;
        }
        echo redirect(base_url("ativo_externo#lista2")); 
    }

    function editar_items($id_ativo_externo_kit){
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
        $data['detalhes'] = $this->ativo_externo_model->get_ativo($id_ativo_externo_kit);
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
        $id_obra = $this->input->post('id_obra');
        if (!$id_obra) {
            $id_obra = $this->user->id_obra;
        }

        if ($quantidade > 0) {
            $items = [];
            for($i=0; $i < $quantidade; $i++) {
                if (isset($this->input->post('id_ativo_externo')[$i])) {
                     $items[$i]['id_ativo_externo'] = $this->input->post('id_ativo_externo')[$i];
                }

                $id_obra = $this->input->post('id_obra');
                if (!$id_obra) {
                    $id_obra = $this->user->id_obra;
                }

                 $items[$i]['id_ativo_externo_grupo']         =  $id_grupo;
                 $items[$i]['id_ativo_externo_categoria']     = $this->input->post('id_ativo_externo_categoria');
                 $items[$i]['tipo']                           = $this->input->post('tipo');
                 $items[$i]['id_obra']                        = $id_obra;
                 $items[$i]['nome']                           = ucwords($this->input->post('nome'));
                 $items[$i]['observacao']                     = $this->input->post('observacao');
                 $items[$i]['necessecita_calibracao']        = $this->input->post('necessecita_calibracao');
                 $items[$i]['codigo']                         = strtoupper($this->input->post('codigo'));

                $valor = str_replace("R$ ", "", $this->input->post('valor'));
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor); 
                $items[$i]['valor'] = $valor;
            }

            $data['url'] = base_url("ativo_externo/gravar_items");
            $data['item'] = (object) array_merge($data, [
                'nome' => $items[0]['nome'],
                'id_ativo_externo_grupo' => $items[0]['id_ativo_externo_grupo'],
                'id_ativo_externo_categoria' => $items[0]['id_ativo_externo_categoria'],
                'tipo' => $items[0]['tipo'],
                'valor' => $items[0]['valor'],
                'id_obra' => $id_obra,
                'observacao' => $items[0]['observacao'],
                'necessecita_calibracao' => $items[0]['necessecita_calibracao'],
                'ativos' => $items
            ]);

            $this->get_template('index_form_item', $data);
            return;
        }

        $this->session->set_flashdata('msg_success', "Nenhum registro modificado!");
        echo redirect(base_url("ativo_externo"));
    }

   
    function gravar_items($mode = null) {
        $dados = array();
        $mode = $this->input->post('mode') ? $this->input->post('mode') : 'insert';

        foreach($_POST['codigo'] as $k => $item){
            $ativo = null;
            if(isset($_POST['id_ativo_externo'])) {              
                $ativo = $this->ativo_externo_model->get_ativo($_POST['id_ativo_externo'][$k]);
            }

            if($this->input->post('codigo')){
                if ($ativo) {
                    $dados[$k]['id_ativo_externo'] = $ativo->id_ativo_externo;
                    if(($mode == 'update' && $this->input->post('id_obra')) && ($ativo->situacao == 12 || !$ativo->id_obra)) {
                        $dados[$k]['id_obra'] = $this->input->post('id_obra');
                    }
                }

                $dados[$k]['codigo']                        = $this->input->post('codigo')[$k];
                $dados[$k]['nome']                          = $this->input->post('item')[$k];
                $dados[$k]['valor']                         = $this->input->post('valor');
                $dados[$k]['observacao']                    = $this->input->post('observacao');
                $dados[$k]['necessecita_calibracao']       = $this->input->post('necessecita_calibracao');
                $dados[$k]['tipo'] = $this->input->post('tipo');
                $dados[$k]['id_ativo_externo_categoria']    = $this->input->post('id_ativo_externo_categoria');
                
                if($mode == 'insert') {
                    $dados[$k]['situacao'] = 12; // Estoque
                    $dados[$k]['id_ativo_externo_grupo'] = $this->input->post('id_ativo_externo_grupo');
                    $dados[$k]['id_obra'] = $this->input->post('id_obra')[$k];
                }
            }
        }
        
        if( $mode == 'update'){
            $this->db->update_batch("ativo_externo", $dados, 'id_ativo_externo');
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            echo redirect(base_url("ativo_externo"));
            return;
        }   

        if( $mode == 'insert'){
            $this->db->insert_batch("ativo_externo", $dados);

            $this->notificacoes_model->enviar_push(
                "Novas Ferramentas", 
                "Novas Ferramentas Adicionadas ao Sistema.", 
                [
                    "filters" => [
                        ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => '2'],
                    ],
                ]
            );

            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            echo redirect(base_url("ativo_externo"));
            return;
        }
        $this->session->set_flashdata('msg_warning', "Nenhum registro modificado!");
        echo redirect(base_url("ativo_externo"));
    }


    function salvar_grupo(){
        $items = $data = [];
        $quantidade = $this->input->post('quantidade') ? (int) $this->input->post('quantidade') : 1;
        $mode = $this->input->post('mode');
        $id_grupo = $this->input->post('id_ativo_externo_grupo');
        $grupo = $this->ativo_externo_model->get_grupo($id_grupo);

        if ($grupo) {     
            if (($mode == 'insert_grupo')) {
                for($i=0; $i < $quantidade; $i++) {
                    $items[$i]['id_ativo_externo_grupo'] = $id_grupo;
                    $items[$i]['id_ativo_externo_categoria']     = $this->input->post('id_ativo_externo_categoria');
                    $items[$i]['tipo']                           = $this->input->post('tipo');
                    $items[$i]['id_obra']                        = $this->input->post('id_obra');
                    $items[$i]['nome']                           = $this->input->post('nome');
                    $items[$i]['observacao']                     = $grupo->observacao;
                    $items[$i]['necessecita_calibracao']        = $grupo->necessecita_calibracao;
                    $items[$i]['codigo']                         = $this->input->post('codigo');

                    $valor = str_replace("R$ ", "", $this->input->post('valor'));
                    $valor = str_replace(".", "", $valor);
                    $valor = str_replace(",", ".", $valor); 
                    $items[$i]['valor'] = $valor;
                }
            }

            if (($mode == 'update_grupo')) {
                foreach($grupo->ativos as $i => $ativo) {
                    $items[$i]['id_ativo_externo'] = $ativo->id_ativo_externo;
                    $items[$i]['codigo'] = $ativo->codigo;
                    $items[$i]['id_obra'] = $this->input->post('id_obra');

                    if ($this->input->post('nome')) {
                       $items[$i]['nome'] = $this->input->post('nome');
                    }

                    if ($this->input->post('observacao')) {
                       $items[$i]['observacao'] = $this->input->post('observacao');
                    }

                    if ($this->input->post('necessecita_calibracao')) {
                        $items[$i]['necessecita_calibracao'] = $this->input->post('necessecita_calibracao');
                     }
                    
                    if ($this->input->post('valor')) {
                        $valor = str_replace("R$ ", "", $this->input->post('valor'));
                        $valor = str_replace(".", "", $valor);
                        $valor = str_replace(",", ".", $valor);
                        $items[$i]['valor'] = $valor;
                    }
              }
            }
            
            $data['item'] = (object) array_merge($data, [
                'nome' => $grupo->nome,
                'id_ativo_externo_grupo' => $grupo->id_ativo_externo_grupo,
                'id_ativo_externo_categoria' => $grupo->id_ativo_externo_categoria,
                'tipo' => $grupo->tipo,
                'valor' => $items[0]['valor'],
                'id_obra' => $grupo->id_obra,
                'observacao' => $grupo->observacao,
                'necessecita_calibracao' => $grupo->necessecita_calibracao,
                'ativos' => $items
            ]);
            $data['url'] = base_url("ativo_externo/gravar_items_grupo");
            $data['mode'] = $mode;
            $this->get_template('index_form_item', $data);
            return;
        }

        $this->session->set_flashdata('msg_erro', "Grupo não encontrado!");
        echo redirect(base_url("ativo_externo"));
    }


    function gravar_items_grupo($mode = null){
        $dados = array();
        if(!$mode) {
            $mode = $this->input->post('mode') ? $this->input->post('mode') : 'insert_grupo';
        }
        
        foreach($_POST['codigo'] as $k => $item){
            $ativo = null;
            if(isset($_POST['id_ativo_externo'][$k])) {              
                $ativo = $this->ativo_externo_model->get_ativo($_POST['id_ativo_externo'][$k]);
                $dados[$k]['id_ativo_externo'] = $ativo->id_ativo_externo; 
            }

            if($_POST['codigo']){
                $dados[$k]['codigo']                        = $_POST['codigo'][$k];
                $dados[$k]['nome']                          = $_POST['item'][$k];
                $dados[$k]['valor']                         = $_POST['valor'];

                if (is_array($this->input->post('observacao'))) {
                    $dados[$k]['observacao'] = $this->input->post('observacao')[$k];
                } else {
                    $dados[$k]['observacao'] = $this->input->post('observacao');
                }

                if (is_array($this->input->post('necessecita_calibracao'))) {
                    $dados[$k]['necessecita_calibracao'] = $this->input->post('necessecita_calibracao')[$k];
                } else {
                    $dados[$k]['necessecita_calibracao'] = $this->input->post('necessecita_calibracao');
                }

                if($mode == 'insert_grupo') {
                    $dados[$k]['situacao'] = 12; // Estoque
                    $dados[$k]['tipo'] = $this->input->post('tipo');
                    $dados[$k]['id_obra'] = $this->input->post('id_obra');
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
            $this->notificacoes_model->enviar_push(
                "Novas Grupo de Ferramentas", 
                "Novas Grupo de Ferramentas Adicionadas ao Sistema.", 
                [
                    "filters" => [
                        ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => '2'],
                    ],
                ]
            );
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        }
        echo redirect(base_url("ativo_externo#lista2"));
    }

    function descartar($id_ativo_externo){
        if($this->input->method() == 'post') {
            $this->db
                ->where('id_ativo_externo', $id_ativo_externo)
                ->update('ativo_externo', [
                    'situacao' => 10,
                    'data_descarte' => date('Y-m-d H:i:s', strtotime('now'))
                ]);
        }
        echo redirect(base_url("ativo_externo#lista"));
    }

    function descartar_grupo($id_ativo_externo_grupo){
        if($this->input->method() == 'post') {
            $this->db
                ->where('id_ativo_externo_grupo', $id_ativo_externo_grupo)
                ->update('ativo_externo', [
                    'situacao' => 10,
                    'data_descarte' => date('Y-m-d H:i:s', strtotime('now'))
                ]);
        }
        echo redirect(base_url("ativo_externo#lista2"));
    }

    function deletar($id_ativo_externo){
        $this->db
            ->where('id_ativo_externo', $id_ativo_externo)
            ->delete('ativo_externo');
        echo redirect(base_url("ativo_externo#lista"));
    }

    function deletar_grupo($id_ativo_externo_grupo){
        $this->db
            ->where('id_ativo_externo_grupo', $id_ativo_externo_grupo)
            ->delete('ativo_externo');
        echo redirect(base_url("ativo_externo#lista2"));
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */