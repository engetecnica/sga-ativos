<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ativo_externo
 *
 * @author https://www.roytuts.com
 */
class Ativo_externo extends MY_Controller {

    public $codigo_prefixo = "ENG";

    function __construct() {
        parent::__construct();
        $this->load->model('ativo_externo_model');
        $this->load->model('ferramental_requisicao/ferramental_requisicao_model');
        $this->load->model('anexo/anexo_model');
        $this->load->model('ativo_veiculo/ativo_veiculo_model'); 
        $this->load->model('obra_model');
    }

    function index($subitem = null) {
        if ($subitem === 'ativos') return $this->index_paginate();
        if ($subitem === 'grupos') return $this->grupos_paginate();
        $this->get_template('index', $this->index_data());
    }

    private function index_data() {
        /* Get filters URL */
        $filter = $this->input->get('filter_items');

        if($filter){
            $filter = explode("/", $filter);
            if(is_array($filter)){
                $filter = array(
                    'item' => $filter[1],
                    'calibracao' => $filter[3]
                );
            } else {
                $filter = array(
                    'item' => "null",
                    'calibracao' => 'sem-filtro'
                );
            }
        }

        return [
            'calibracao' => (isset($filter['calibracao'])) ? $filter['calibracao'] : null,
            'obras' => $this->obra_model->get_obras(),
            'lista' => $this->ativo_externo_model->get_ativos($this->user->id_obra, "", $filter), //@todo remove
            'grupos' => $this->ativo_externo_model->get_grupos($this->user->id_obra),  //@todo remove
            'status_lista' => $this->ferramental_requisicao_model->get_requisicao_status(),
        ];
    }

    protected function index_paginate(){     
        return $this->paginate_json([
            "query" => $this->ativo_externo_model->query(false),
            "templates" => [
                [
                    "name" => "codigo_link",
                    "view" => "index/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->codigo,
                            'link' => base_url("ativo_externo/editar/{$row->id_ativo_externo}"), 
                        ]);
                    }
                ],
                [
                    "name" => "nome_link",
                    "view" => "index/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->nome,
                            'link' => base_url("ativo_externo/editar/{$row->id_ativo_externo}"), 
                        ]);
                    }
                ],
                [
                    "name" => "tipo_html",
                    "view" => "index/tipo",
                ],
                [
                    "name" => "kit_html",
                    "view" => "index/kit",
                ],
                [
                    "name" => "calibracao_html",
                    "view" => "index/calibracao",
                ],
                [
                    "name" => "situacao_html",
                    "view" => "index/situacao"   
                ],
                [                       
                    "name" => "actions",
                    "view" => "index/actions"
                ]
            ],
            "after" => function(object &$row) {
                $row->data_inclusao = $this->formata_data_hora($row->data_inclusao);;
                $row->data_descarte = $row->data_descarte ? $this->formata_data_hora($row->data_descarte) : null;
                $row->valor = $this->formata_moeda_model($row->valor);
                return $row;
            }
        ]);
    }

    function grupos_paginate() {
        return $this->paginate_json([
            "query" => $this->ativo_externo_model->grupos_query($this->user->id_obra),
            "templates" => [
                [
                    "name" => "gid_link",
                    "view" => "grupos/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->id_ativo_externo_grupo,
                            'link' => base_url("ativo_externo/editar_grupo/{$row->id_ativo_externo_grupo}"), 
                        ]);
                    }
                ],
                [
                    "name" => "nome_link",
                    "view" => "grupos/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->nome,
                            'link' => base_url("ativo_externo/editar_grupo/{$row->id_ativo_externo_grupo}"), 
                        ]);
                    }
                ],
                [
                    "name" => "tipo_html",
                    "view" => "grupos/tipo",
                ],
                [                       
                    "name" => "actions",
                    "view" => "grupos/actions"
                ]
            ],
        ]);
    }

    function adicionar($id_ativo_externo_grupo=null) {
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 11, 'adicionar'));

        $data['mode'] = "insert";
        $data['form_url'] = base_url("ativo_externo/salvar");
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
        
        if ($id_ativo_externo_grupo) {
            $data['form_url'] = base_url("ativo_externo/salvar_grupo");
            $grupo = $this->ativo_externo_model->get_grupo($id_ativo_externo_grupo);
            $data['detalhes'] = (object) [
                'tipo' => $grupo->tipo,
                'id_ativo_externo_categoria' => $grupo->id_ativo_externo_categoria,
                'id_ativo_externo_grupo' => $grupo->id_ativo_externo_grupo,
                'necessita_calibracao' => $grupo->necessita_calibracao,
                'nome' => $grupo->nome,
                'valor' => $grupo->valor
            ];
            $data['mode'] = "insert_grupo";
        }

        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
    	$this->get_template('index_form', $data);
    }

    function editar($id_ativo_externo=null){

        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 11, 'editar'));

        $data = array_merge($this->anexo_model->getData('ativo_externo', $id_ativo_externo), [
            "back_url" => "ativo_externo/editar/{$id_ativo_externo}",
            'mode' => "update",
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'form_url' => base_url("ativo_externo/salvar"),
            'detalhes' => $this->ativo_externo_model->get_ativo($id_ativo_externo),
            'estados' => $this->get_estados(),
            'obra' => $this->ativo_externo_model->get_obra(),
            'categoria' => $this->ativo_externo_model->get_categoria(),
            'status_lista' => $this->status_lista(),
        ]);
        $this->get_template('index_form', $data);
    }

    function certificado_de_calibracao($id_ativo_externo, $id_certificado = null){
        $template = "index_certificado_de_calibracao";
        $data = array_merge($this->anexo_model->getData('ativo_externo', $id_ativo_externo, 'certificado_de_calibracao'), [
            'back_url' => "ativo_externo/certificado_de_calibracao/{$id_ativo_externo}",
            'ativo' => $this->ativo_externo_model->get_ativo($id_ativo_externo),
            'lista' => $this->ativo_externo_model->get_lista_certificado($id_ativo_externo)
        ]);

        if($id_certificado) $template = "index_form_certificado_de_calibracao"; 
        if ($id_certificado && $id_certificado != 'adicionar') $data['certificado'] = $this->ativo_externo_model->get_certificado_de_calibracao($id_ativo_externo, $id_certificado);
    	$this->get_template($template, $data);
    }

    function certificado_de_calibracao_salvar($id_ativo_externo){
        $ativo = $this->ativo_externo_model->get_ativo($id_ativo_externo);
       
        if ($ativo) {
            $validade = date("Y-m-d",  strtotime($this->input->post('data_vencimento') ?: '1 year'));
            $certificado = [
                'id_certificado' => $this->input->post('id_certificado') ?: null,
                'id_ativo_externo' => $id_ativo_externo,
                'data_inclusao' => date("Y-m-d", strtotime('now')),
                'data_vencimento' => $validade,
                'observacao' => $this->input->post('observacao')
            ];

            $certificado_de_calibracao = (isset($_FILES['certificado_de_calibracao']) ? $this->upload_arquivo('certificado_de_calibracao') : '');
            if (isset($_FILES['certificado_de_calibracao']) && 
                (!$certificado_de_calibracao || ($certificado_de_calibracao == '' && $certificado['id_certificado']))) {
                $this->session->set_flashdata('msg_erro', "O tamanho do certificado deve ser menor ou igual a ".ini_get('upload_max_filesize'));
                return redirect(base_url("ativo_externo/certificado_de_calibracao/{$id_ativo_externo}"));
            }

            if ($certificado_de_calibracao != "" && !$certificado['id_certificado']) {
                $validade_br_format = $this->formata_data($validade);
                $certificado['id_anexo'] = $this->salvar_anexo(
                    [
                       "titulo" => "Certificado de Calibração",
                       "descricao" => "Certificado de Calibração com validade até {$validade_br_format}",
                       "anexo" => "certificado_de_calibracao/{$certificado_de_calibracao}",
                    ],
                    'ativo_externo',
                    $id_ativo_externo,
                    'certificado_de_calibracao'
                );

                if (!$certificado['id_anexo']) {
                    $this->session->set_flashdata('msg_erro', "Erro ao salvar anexo!");
                    echo redirect(base_url("ativo_externo/certificado_de_calibracao/{$id_ativo_externo}"));
                    return;
                }
            }

            if (strtotime($validade) > strtotime(date("Y-m-d", strtotime('now')))) {
                if ($certificado['id_certificado']) {
                    $this->db->where('id_certificado', $certificado['id_certificado'])
                            ->update('ativo_externo_certificado_de_calibracao', $certificado);
                } else {
                    $this->db->insert('ativo_externo_certificado_de_calibracao', $certificado);  
                }

                $this->session->set_flashdata('msg_success', "Dados salvos com sucesso!");
                $last_id = $certificado['id_certificado'] ?: $this->db->insert_id();
                echo redirect(base_url("ativo_externo/certificado_de_calibracao/{$id_ativo_externo}/{$last_id}"));
                return;
            }

            if (!$certificado['id_certificado'] && $certificado['id_anexo']) $this->db->where('id_anexo', $certificado['id_anexo'])->delete('anexo');
            
            $this->session->set_flashdata('msg_erro', "Data de validade para o certificado inválida!");
            if (!$certificado['id_certificado']) {
                echo redirect(base_url("ativo_externo/certificado_de_calibracao/{$id_ativo_externo}/adicionar"));
                return;
            }

            echo redirect(base_url("ativo_externo/certificado_de_calibracao/{$id_ativo_externo}/{$certificado['id_certificado']}"));
            return;
        }

        $this->session->set_flashdata('msg_erro', "Nenhum ativo encontrado!");
        return redirect(base_url("ativo_externo"));
    }

    function certificado_de_calibracao_deletar($id_ativo_externo, $id_certificado){
        $certificado =  $this->ativo_externo_model->get_certificado_de_calibracao($id_ativo_externo, $id_certificado);
        if ($certificado && $certificado->vigencia) {
            $this->db
                ->where("id_anexo = {$certificado->id_anexo}")
                ->delete('anexo');
            $this->db
                ->where("id_certificado = {$certificado->id_certificado} and id_ativo_externo = {$id_ativo_externo}")
                ->delete('ativo_externo_certificado_de_calibracao');
        }
        return;
    }

    function editar_grupo($id_ativo_externo_grupo){
        $data['estados'] = $this->get_estados();
        $data['obra'] = $this->ativo_externo_model->get_obra();
        $data['categoria'] = $this->ativo_externo_model->get_categoria();
        $data['form_url'] = base_url("ativo_externo/salvar_grupo");
        $grupo = $this->ativo_externo_model->get_grupo($id_ativo_externo_grupo);
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');

        if ($grupo) {
            $data['detalhes'] = (object) [
                'total' => $grupo->total,
                'tipo' => $grupo->tipo,
                'id_ativo_externo_categoria' => $grupo->id_ativo_externo_categoria,
                'id_ativo_externo_grupo' => $grupo->id_ativo_externo_grupo,
                'necessita_calibracao' => $grupo->necessita_calibracao,
                'nome' => $grupo->nome,
                'valor' => $grupo->valor,
                'id_obra' => $grupo->id_obra,
                'observacao' => $grupo->observacao,
            ];
            $data['mode'] = "update_grupo";
            $this->get_template('index_form', $data);
            return;
        }
        echo redirect(base_url("ativo_externo")); 
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
        $mode = $this->input->post('mode');
        $id_obra = $this->input->post('id_obra');
        $quantidade = $this->input->post('quantidade') ? (int) $this->input->post('quantidade') : 1;
        $id_grupo = $this->input->post('id_ativo_externo_grupo') ? $this->input->post('id_ativo_externo_grupo') : $this->ativo_externo_model->get_proximo_grupo();
       
        if (!$id_obra) $id_obra =  $this->user->nivel == 1 && $this->user->id_obra_gerencia ? $this->user->id_obra_gerencia : $this->user->id_obra;

        $codigo_ativo = str_replace("ENG", "", $this->ativo_externo_model->get_ativo_ultimo()->codigo) +1;

        

        if ($quantidade > 0) {
            $items = [];

            for($i=0; $i < $quantidade; $i++) {
                if (isset($this->input->post('id_ativo_externo')[$i])) {
                    $items[$i]['id_ativo_externo'] = $this->input->post('id_ativo_externo')[$i];
                    if ($this->ativo_externo_model->permit_edit_situacao($items[$i]['id_ativo_externo'])) {
                        $items[$i]['situacao'] = $this->input->post('situacao')[$i];
                    }
                }

                if($mode == 'insert'){
                    $items[$i]['id_ativo_externo_grupo'] = $id_grupo;
                    $items[$i]['codigo'] = ($this->input->post('codigo')) ? strtoupper($this->input->post('codigo')) : $this->codigo_prefixo.$codigo_ativo;
                    $items[$i]['situacao'] = 12;
                }

                if($mode == 'update'){
                    $items[$i]['situacao'] = $this->input->post('situacao')[$i];
                }

                $items[$i]['id_ativo_externo_categoria']     = $this->input->post('id_ativo_externo_categoria');
                $items[$i]['tipo']                           = $this->input->post('tipo');
                $items[$i]['id_obra']                        = $id_obra;
                $items[$i]['nome']                           = ucwords($this->input->post('nome'));
                $items[$i]['observacao']                     = $this->input->post('observacao');
                $items[$i]['necessita_calibracao']           = $this->input->post('necessita_calibracao');

                $valor = str_replace("R$ ", "", $this->input->post('valor'));
                $valor = str_replace(".", "", $valor);
                $valor = str_replace(",", ".", $valor); 
                $items[$i]['valor'] = $valor;

                $codigo_ativo++;
            }

            if($mode == 'update'){
                $this->db->update_batch("ativo_externo", $items, 'id_ativo_externo');
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
                echo redirect(base_url("ativo_externo"));
                return;
            }   
    
            if($mode == 'insert'){
  
                $this->db->insert_batch("ativo_externo", $items);
    
                $this->notificacoes_model->enviar_push(
                    "Novas Ferramentas", 
                    "{$quantidade} Novas Ferramentas '{$items[0]['nome']}' adicionadas ao estoque da obra '{$this->user->obra->codigo_obra}'", 
                    [
                        "filters" => [
                            ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => '2'],
                        ],
                    ]
                );
                
                $s = count($items) > 1 ? "s" : "";
                $this->session->set_flashdata('msg_success', "Novo{$s} registro{$s} inserido{$s} com sucesso!");
                echo redirect(base_url("ativo_externo"));
                return;
            }
            $this->session->set_flashdata('msg_warning', "Nenhum registro modificado!");
        }
        
        echo redirect(base_url("ativo_externo"));
    }

    function salvar_grupo(){
        $items = [];
        $mode = $this->input->post('mode');
        $id_grupo = $this->input->post('id_ativo_externo_grupo');
        $grupo = $this->ativo_externo_model->get_grupo($id_grupo);
        $quantidade = $this->input->post('quantidade') ? (int) $this->input->post('quantidade') : 1;

        if ($grupo) {     
            if (($mode == 'insert_grupo')) {

                $codigo_ativo = str_replace("ENG", "", $this->ativo_externo_model->get_ativo_ultimo()->codigo) +1;

                for($i=0; $i < $quantidade; $i++) {
                    $items[$i]['id_ativo_externo_grupo'] = $id_grupo;
                    $items[$i]['id_ativo_externo_categoria']     = $grupo->id_ativo_externo_categoria;
                    $items[$i]['tipo']                           = $this->input->post('tipo');
                    $items[$i]['id_obra']                        = $this->user->id_obra;
                    $items[$i]['nome']                           = $grupo->nome;
                    $items[$i]['observacao']                     = $grupo->observacao;
                    $items[$i]['situacao']                       = 12;
                    $items[$i]['necessita_calibracao']           = $grupo->necessita_calibracao;
                    $items[$i]['codigo'] = ($this->input->post('codigo')) ? strtoupper($this->input->post('codigo')) : $this->codigo_prefixo.$codigo_ativo;

                    $valor = str_replace("R$ ", "", $this->input->post('valor'));
                    $valor = str_replace(".", "", $valor);
                    $valor = str_replace(",", ".", $valor); 
                    $items[$i]['valor'] = $valor;
                    $codigo_ativo++;
                }
               
            }

            if (($mode == 'update_grupo')) {
                foreach($grupo->ativos as $i => $ativo) {
                    $items[$i]['id_ativo_externo'] = $ativo->id_ativo_externo;
                    
                    if ($this->input->post('nome')) {
                       $items[$i]['nome'] = $this->input->post('nome');
                    }

                    if ($this->input->post('observacao')) {
                       $items[$i]['observacao'] = $this->input->post('observacao');
                    }

                    if ($this->input->post('necessita_calibracao') !== null) {
                        $items[$i]['necessita_calibracao'] = $this->input->post('necessita_calibracao');
                    }
              }
            }
            
            if($mode == 'insert_grupo') {
                foreach($items as $k => $value){
                    if (isset($value['id_ativo_externo'])) {
                        unset($items[$k]);
                    }
                }
            }


            $s = count($items) > 1 ? "s" : "";
            if( $mode == 'update_grupo' && $this->db->update_batch("ativo_externo", $items, 'id_ativo_externo')) {
                $this->session->set_flashdata('msg_success', "Registro{$s} atualizado{$s} com sucesso!");
            }   
    
            if( $mode == 'insert_grupo' && $this->db->insert_batch("ativo_externo", $items)) {
                $this->notificacoes_model->enviar_push(
                    "Novas Ferramentas", 
                    "{$quantidade} Novas Ferramentas adicionadas ao grupo '{$grupo->nome}', no estoque da obra '{$this->user->obra->codigo_obra}'", 
                    [
                        "filters" => [
                            ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => '2'],
                        ],
                    ]
                );

                $this->session->set_flashdata('msg_success', "Novo{$s} registro{$s} inserido{$s} com sucesso!");
            }
            redirect(base_url("ativo_externo"));
        }

        $this->session->set_flashdata('msg_erro', "Grupo não encontrado!");
        redirect(base_url("ativo_externo"));
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

    function manutencao($id_ativo_externo) {
        $data['ativo'] = $this->ativo_externo_model->get_ativo($id_ativo_externo);
        if ($data['ativo']) {
            $data['lista'] = $this->ativo_externo_model->get_lista_manutencao($id_ativo_externo);
            $this->get_template('index_manutencao', $data);
            return;
        }
        echo redirect(base_url("ativo_externo"));
    }

    function manutencao_adicionar($id_ativo_externo){
        $data['ativo'] = $this->ativo_externo_model->get_ativo($id_ativo_externo);
        if ($data['ativo']) {
            $this->get_template('index_form_manutencao', $data);
            return;
        }
        echo redirect(base_url("ativo_externo"));
    }

    function manutencao_editar($id_ativo_externo, $id_manutencao){
        $data = array_merge($this->anexo_model->getData('ativo_externo', $id_ativo_externo,'manutencao', $id_manutencao),[
            "manutencao" => $this->ativo_externo_model->get_manutencao($id_ativo_externo, $id_manutencao),
            "ativo" => $this->ativo_externo_model->get_ativo($id_ativo_externo),
            "back_url" => "ativo_externo/manutencao_editar/{$id_ativo_externo}/{$id_manutencao}"
        ]);
    
        if ($data['ativo'] && $data['manutencao']) {
            $data['obs'] = $this->ativo_externo_model->get_lista_manutencao_obs($id_manutencao);
            $this->get_template('index_form_manutencao', $data);
            return;
        }

        if ($data['ativo']) {
            echo redirect(base_url("ativo_externo/manutencao/{$id_ativo_externo}")); 
            return;
        }
        echo redirect(base_url("ativo_externo"));
    }

    function manutencao_salvar(){
        $data['id_ativo_externo'] = !is_null($this->input->post('id_ativo_externo')) ? $this->input->post('id_ativo_externo') : '';
        $data['id_manutencao'] = $this->input->post('id_manutencao');

        if ($data['id_manutencao'] == null  && $this->ativo_externo_model->permit_create_manutencao($data['id_ativo_externo'])) {
            $data['situacao'] = 0;
            $data['data_saida'] = $this->input->post('data_saida');
            $this->db->insert('ativo_externo_manutencao', $data);
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            echo redirect(base_url("ativo_externo/manutencao/{$data['id_ativo_externo']}"));
            return;
        } 
        
        if ($data['id_manutencao'] != null) {
            $data['situacao'] = $this->input->post('situacao') != null ? $this->input->post('situacao') : 0;
            $data['data_retorno'] = $this->input->post('data_retorno');
            $valor = str_replace("R$ ", "", $this->input->post('valor'));
            $valor = str_replace(".", "", $valor);
            $data['valor'] = str_replace(",", ".", $valor); 
        
            $this->db->where('id_manutencao', $data['id_manutencao'])
                ->where('id_manutencao', $data['id_manutencao'])
                ->update('ativo_externo_manutencao', $data);
            $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
        }
        echo redirect(base_url("ativo_externo/manutencao/{$data['id_ativo_externo']}"));
    }

    function manutencao_remover($id_manutencao){
        return $this->db->where('id_manutencao', $id_manutencao)
                ->delete('ativo_externo_manutencao');
    }

    function manutencao_obs_adicionar($id_ativo_externo, $id_manutencao){
        $data['ativo'] = $this->ativo_externo_model->get_ativo($id_ativo_externo);
        $data['manutencao'] = $this->ativo_externo_model->get_manutencao($id_ativo_externo, $id_manutencao);
        
        if ($data['ativo'] && $data['manutencao']) {
            $this->get_template('index_form_obs', $data);
            return;
        }
        echo redirect(base_url("ativo_externo/manutencao/{$id_ativo_externo}#obs"));
    }

    function manutencao_obs_editar($id_ativo_externo, $id_manutencao, $id_obs){
        $data['obs'] = $this->ativo_externo_model->get_obs($id_manutencao, $id_obs);
        $data['manutencao'] = $this->ativo_externo_model->get_manutencao($id_ativo_externo, $id_manutencao); 
        $data['ativo'] = $this->ativo_externo_model->get_ativo($id_ativo_externo);

        if (($data['obs'] && $data['manutencao']) && $data['ativo']) {
            $this->get_template('index_form_obs', $data);
            return;
        }
        echo redirect(base_url("ativo_externo/manutencao/{$id_ativo_externo}#obs"));
    }

    function manutencao_obs_salvar($id_ativo_externo, $id_manutencao) {
        $data['id_manutencao'] = $id_manutencao;
        $data['id_obs'] = $this->input->post('id_obs');
        $data['id_usuario'] = $this->user->id_usuario;
        $data['texto'] = trim($this->input->post('texto'));

        if (!$data['id_obs'] && $data['texto']) {
            $data['data_inclusao'] = date('Y-m-d H:i:s', strtotime('now'));
            $this->db->insert('ativo_externo_manutencao_obs', $data);
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } 
        
        if ($data['id_obs'] && $data['texto'] ) {
            $data['data_edicao'] = date('Y-m-d H:i:s', strtotime('now'));
            $this->db
                ->where('id_manutencao', $id_manutencao)
                ->where('id_obs', $data['id_obs'])
                ->where('id_usuario', $data['id_usuario'])
                ->update('ativo_externo_manutencao_obs', $data);
            $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
        }
        echo redirect(base_url("ativo_externo/manutencao_editar/{$id_ativo_externo}/{$id_manutencao}#obs"));
    }

    function manutencao_obs_remover($id_manutencao, $id_obs){
        $data['obs'] = $this->ativo_externo_model->get_obs($id_manutencao, $id_obs);
        if ($this->usera->id_usuario == $data['obs']->id_usuario ) {
            return $this->db
                ->where('id_obs', $id_obs)
                ->delete('ativo_externo_manutencao_obs');
        }
        return false;
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */