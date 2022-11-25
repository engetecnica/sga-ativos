<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ferramental_requisicao
 *
 * @author André Baill | https://www.github.com/srandrebaill
 */
class Ferramental_requisicao  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ferramental_requisicao_model');
        $this->load->model('ativo_externo/ativo_externo_model');
        $this->load->model('obra/obra_model');
        $this->load->model('usuario/usuario_model'); 
        $this->model = $this->ferramental_requisicao_model;       
    }

    function index($subitem = null) {
        if ($subitem === 'paginate')  {
            $id_obra = null;
            $id_usuario = null;
            if ($this->user->nivel == 2) {
                $id_obra = $this->user->id_obra;
                $id_usuario = $this->user->id_usuario;
            }
            return $this->paginate_json([
                "query" => $this->model->query($id_obra, $id_usuario),
                "templates" => [
                    [
                        "name" => "id_link",
                        "view" => "index/link",
                        "data" => function($row, $data) {
                            return  array_merge($data, [
                                'text' => $row->id_requisicao,
                                'link' => base_url("ferramental_requisicao/detalhes/{$row->id_requisicao}"), 
                            ]);
                        }
                    ],
                    [
                        "name" => "tipo_html",
                        "view" => "index/tipo"   
                    ],
                    [
                        "name" => "complementar_html",
                        "view" => "index/complementar"   
                    ],
                    [
                        "name" => "complementa_html",
                        "view" => "index/complementa"   
                    ],
                    [
                        "name" => "status_html",
                        "view" => "index/status"   
                    ],
                    [                       
                        "name" => "actions",
                        "view" => "index/actions"
                    ]
                ]
            ]);
        }

        $this->get_template('index');
    }

    protected function paginate_after(object &$row)
    {
        $row->solicitante = ucwords($row->solicitante);
        $row->despachante = ucwords($row->despachante);
        $row->origem = ucwords($row->origem);
        $row->destino = ucwords($row->destino);
        $row->data_inclusao = $this->formata_data_hora($row->data_inclusao);
    }

    # Criar uma nova Requisição
    function adicionar() {
        //$grupos = [];
        $grupos = $this->ativo_externo_model->get_grupos(null);
        
        
        // if ($this->user->nivel == 1) {
        // }
        
        // if ($this->user->nivel == 2) {
        //    // $grupos = $this->ativo_externo_model->get_grupos($this->user->id_obra);
        // }
    
        $this->get_template('index_form',[
            'grupos' => $grupos,
            'obras' => $this->obra_model->get_obras(),
        ]);
    }

    # Grava Requisição
    function salvar(){
        # Dados
        $requisicao['id_solicitante'] = $this->user->id_usuario;
        $requisicao['status'] = 1; //Pendente

        if ($this->user->nivel == 1) {
            $requisicao['id_destino'] = $this->input->post('id_destino');
            if ($requisicao['id_destino'] != $this->user->id_obra) {
                $requisicao['id_origem'] = $this->user->id_obra;
                $requisicao['id_despachante'] = $this->user->id_usuario;
            }
        }

        if ($this->user->nivel == 2) {
            $requisicao['id_destino'] = $this->user->id_obra;
        }

        $requisicao_items = array();
        if ($this->input->post('id_ativo_externo_grupo')[0] != null) {
            $id_requisicao = $this->ferramental_requisicao_model->salvar_formulario($requisicao);

            foreach($this->input->post('id_ativo_externo_grupo') as $k => $id_ativo_externo_grupo){
                $requisicao_items[$k] = array();
                $requisicao_items[$k]['id_ativo_externo_grupo'] = $id_ativo_externo_grupo;
                $requisicao_items[$k]['quantidade'] = $this->input->post('quantidade')[$k];
                $requisicao_items[$k]['quantidade_liberada'] = 0;
                $requisicao_items[$k]['id_requisicao'] = $id_requisicao;
                $requisicao_items[$k]['status'] = 1;
            }

            $this->db->insert_batch("ativo_externo_requisicao_item", $requisicao_items);
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");

            $msg = "Nova Requisição de Ferramentas {$id_requisicao} criada e Pendênte de aprovação ";
            if (isset($requisicao['id_origem'])) {
                $obra = $this->obra_model->get_obra($requisicao['id_origem']);
                $msg .= " Da obra {$obra->codigo_obra}";
            }

            if (isset($requisicao['id_destino'])) {
                $obra = $this->obra_model->get_obra($requisicao['id_destino']);
                $msg .= " para a obra {$obra->codigo_obra}";
            }

            $this->notificacoes_model->enviar_push(
                "Requisição de Ferramentas Pendênte", 
                "{$msg}. Clique na Notificação para mais detalhes.",
                [
                    "filters" => [
                        ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "1"],
                        ["operator" => "AND"],
                        ["field" => "tag", "key" => "id_obra", "relation" => "!=", "value" => $this->user->id_obra],
                    ],
                    "include_external_user_ids" => [$this->user->id_usuario],
                    "url" => "ferramental_requisicao/detalhes/{$id_requisicao}"
                ]
            );

            echo redirect(base_url("ferramental_requisicao"));
            return;
        }
        $this->session->set_flashdata('msg_success', "Nenhum registro salvo!");
        echo redirect(base_url("ferramental_requisicao"));
    }

    function detalhes($id_requisicao=null) {

        /* Requisição não encontrada, sem ID ou indefinida */
        if(!$id_requisicao || $id_requisicao=='undefined'){
            $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
            echo redirect(base_url('ferramental_requisicao'));
            return; 
        }


        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);
        if(!$requisicao){
            $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
            echo redirect(base_url('ferramental_requisicao'));
            return; 
        }

        if ($this->user->nivel == 1) {
            $this->get_template('requisicao_detalhes_adm', ['requisicao' => $requisicao]);
        } else {
            $this->get_template('requisicao_detalhes_user', ['requisicao' => $requisicao]);
        }
    }

    public function detalhes_item($id_requisicao, $id_requisicao_item = null){
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);
        $ativos = [];
        foreach($requisicao->items as $item) {
            if (($item->id_requisicao_item == $id_requisicao_item) || !$id_requisicao_item) {
                $ativos = array_merge($ativos, $item->ativos);
            }
        }
        $requisicao->ativos = $ativos;
        $this->get_template('requisicao_manual', ['requisicao' => $requisicao, 'no_aceite' => true]);
    }

    public function liberar_requisicao() {
       $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($this->input->post('id_requisicao'), $this->user);
       $items = $this->input->post('item');
       $quantidade = $this->input->post('quantidade');
  
        if ($requisicao && $this->input->method() == 'post') {
            if ($this->user->nivel != 1 || !in_array($requisicao->status, [1, 11])) {
                $this->session->set_flashdata('msg_erro', "Requisição não pode ser liberada!");
                echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
                return;
            }

            if (!$quantidade) {
                $this->session->set_flashdata('msg_erro', "Deve especificar quantidade de itens a ser liberados!");
                echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
                return;
            }

            $total_quantidade = 0;
            $total_quantidade_liberada = 0;
            $total_sem_estoque = array_sum(array_map(function($it){return $it->status == 6 ? 1 : 0;}, $requisicao->items));
            $requisicao_items = $requisicao_ativos = $ativos_externos = $ativos = [];
        
            if (is_array($items)) {
                for ($i=0; $i < count($items); $i++) { 
                    $item = $this->ferramental_requisicao_model
                            ->get_requisicao_item($requisicao->id_requisicao, $items[$i]);
                          
                    if ($item) {
                        $ativos = $this->ativo_externo_model
                                    ->get_estoque($this->user->id_obra, $item->id_ativo_externo_grupo, 12);
    
                        $total_liberar = isset($quantidade[$i]) ? $quantidade[$i] : 0;
                        $total_quantidade += $item->quantidade;

                        if ($total_liberar > 0) {
                            for ($k=0; $k < $total_liberar; $k++) {
                                $requisicao_ativos[] = [
                                    'id_requisicao' => $item->id_requisicao,
                                    'id_ativo_externo' => $ativos[$k]->id_ativo_externo,
                                    'id_requisicao_item' => $item->id_requisicao_item,
                                    'data_liberado' => date('Y-m-d H:i:s', strtotime('now')),
                                    'status' => 2,
                                ];

                                $ativos_externos[] = [
                                    'id_ativo_externo' => $ativos[$k]->id_ativo_externo,
                                    'situacao' => 2,
                                ];

                                if ($ativos[$k]->tipo == 1) {
                                    $this->ferramental_requisicao_model->liberar_kit($ativos[$k], $ativos_externos, 2);
                                }
                                $item->quantidade_liberada++;
                            }

                            $total_quantidade_liberada += $item->quantidade_liberada;

                            if ($item->quantidade > $item->quantidade_liberada) {
                                $item->status = 11;
                            }
                            
                            if ($item->quantidade == $item->quantidade_liberada) {
                                $item->status = 2;
                            }

                            if ($item->quantidade_liberada == 0) {
                                $item->status = 6;
                                $total_sem_estoque++;
                            }

                            $requisicao_items[] = [
                                'id_requisicao_item' => $item->id_requisicao_item,
                                'id_requisicao' => $item->id_requisicao,
                                'status' => $item->status,
                                'data_liberado' => date('Y-m-d H:i:s', strtotime('now')),
                                'quantidade_liberada' => $item->quantidade_liberada,
                                'quantidade' => $item->quantidade,
                            ];
                        }
                    }
                }

                if (empty($requisicao_items) || empty($requisicao_ativos)) {
                    $this->session->set_flashdata('msg_info', "Nenhum item liberado! Digite a quantidade de cada item a ser liberado.");
                    echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
                    return;
                }

                $status = ""; 
                $msg = "";
                if (count($requisicao->items) == $total_sem_estoque) {
                    $requisicao->status = 6;
                    $status = "msg_warning"; 
                    $msg =   "Não Liberada por falta de estoque!";
                }


                if (!empty($requisicao_ativos) && !empty($requisicao_items)) {
                    if ($total_quantidade == $total_quantidade_liberada) {
                        $requisicao->status = 2;
                        $status = "msg_success"; 
                        $msg = "Liberada com Sucesso!";
                    } 

                    if ($total_quantidade > $total_quantidade_liberada) { 
                        $requisicao->status = 11;
                        $status = "msg_info"; 
                        $msg =  "Liberada com Pedência!";
                    }

                    $this->db->insert_batch("ativo_externo_requisicao_ativo", $requisicao_ativos);
                    $this->db->update_batch("ativo_externo_requisicao_item", $requisicao_items, 'id_requisicao_item');
                    $this->db->update_batch("ativo_externo", $ativos_externos, 'id_ativo_externo');
                }

                $this->ferramental_requisicao_model->salvar_formulario([
                    'id_requisicao' => $requisicao->id_requisicao,
                    'id_origem' => $this->user->id_obra,
                    'id_despachante' =>$this->user->id_usuario,
                    'status' => $requisicao->status,
                    'data_liberado' => date('Y-m-d H:i:s', strtotime('now'))
                ]);

                $this->notificacoes_model->enviar_push(
                    "Requisição $msg", 
                    "Requisição de Ferramentas {$requisicao->id_requisicao}, $msg. Clique na Notificação para mais detalhes.", 
                    [
                        "filters" => [
                            ["field" => "tag", "key" => "id_obra", "relation" => "=", "value" => $this->user->id_obra],
                            ["operator" => "AND"],
                            ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => $this->user->nivel],
                        ],
                        "include_external_user_ids" => [$requisicao->id_solicitante],
                        "url" => "ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"
                    ]
                );

                $this->session->set_flashdata($status, "Requisição $msg");
                echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
                return;
            }

            $this->session->set_flashdata('msg_info', "Nenhum item liberado!");
            echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
            return;
        } 

        $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
        echo redirect(base_url('ferramental_requisicao'));
        return;
    }

    public function transferir_requisicao($id_requisicao) {
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);

        if ($requisicao && $this->input->method() == 'post') {
            if (($requisicao->tipo == 1 &&  in_array($requisicao->status, [2, 11])) && (($this->user->id_usuario == $requisicao->id_despachante) || ($this->user->id_obra == $requisicao->id_origem))) {
                $requisicao_ativos = $requisicao_items = $ativos_externos = [];

                foreach ($requisicao->items as $item) {
                    foreach ($item->ativos as $ativo) {
                        $requisicao_ativos[] = [
                            'id_requisicao_ativo' => $ativo->id_requisicao_ativo,
                            'data_transferido' => date('Y-m-d H:i:s', strtotime('now')),
                            'status' => 3
                        ];
                        $ativos_externos[] = [
                            'id_ativo_externo' => $ativo->id_ativo_externo,
                            'situacao' => 3,
                        ];

                        $ativo_externo = $this->ativo_externo_model->get_ativo($ativo->id_ativo_externo);
                        if ($ativo_externo->tipo == 1) {
                            $this->ferramental_requisicao_model->liberar_kit($ativo_externo, $ativos_externos, 3);
                        }
                    }
                    $requisicao_items[] = [
                        'id_requisicao_item' => $item->id_requisicao_item,
                        'data_transferido' => date('Y-m-d H:i:s', strtotime('now')),
                        'status' => $item->quantidade_liberada > 0 ? 3 : $item->status 
                    ];
                }

                $this->db->update_batch("ativo_externo_requisicao_ativo", $requisicao_ativos, 'id_requisicao_ativo');
                $this->db->update_batch("ativo_externo_requisicao_item", $requisicao_items, 'id_requisicao_item');
                $this->db->update_batch("ativo_externo", $ativos_externos, 'id_ativo_externo');

                $this->ferramental_requisicao_model->salvar_formulario([
                    'id_requisicao' => $requisicao->id_requisicao,
                    'data_transferido' => date('Y-m-d H:i:s', strtotime('now')),
                    'status' => 3
                ]);

                $this->gerar_romaneio($requisicao->id_requisicao, false);

                $obra = $this->obra_model->get_obra($requisicao->id_destino);
                $this->notificacoes_model->enviar_push(
                    "Requisição Transferida", "Requisição de Ferramentas {$requisicao->id_requisicao} Transferida para a obra {$obra->codigo_obra}. 
                    Clique na Notificação para mais detalhes.", 
                    [
                        "filters" => [
                            ["field" => "tag", "key" => "id_obra", "relation" => "=", "value" => $this->user->id_obra],
                            ["operator" => "AND"],
                            ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => $this->user->nivel],
                        ],
                        "include_external_user_ids" => [$requisicao->id_solicitante],
                        "url" => "ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"
                    ]
                );

                $this->session->set_flashdata('msg_success', "Requisição Transferida com Sucesso!");
                echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
                return;
            }
            $this->session->set_flashdata('msg_erro', "Requisição não pode ser transferida pelo o usuário!");
            echo redirect(base_url('ferramental_requisicao'));
        }

        $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
        echo redirect(base_url('ferramental_requisicao'));
        return;
    }

    public function transferir_devolucao($id_requisicao) {
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);

        if ($requisicao && $this->input->method() == 'post') {
            if (($requisicao->tipo == 2 && $requisicao->status == 2) && (($this->user->id_usuario == $requisicao->id_despachante) || ($this->user->id_obra == $requisicao->id_origem))) {
                $requisicao_ativos = $requisicao_items = $ativos_externos = [];

                foreach ($requisicao->items as $item) {
                    foreach ($item->ativos as $ativo) {
                        $requisicao_ativos[] = [
                            'id_requisicao_ativo' => $ativo->id_requisicao_ativo,
                            'data_transferido' => date('Y-m-d H:i:s', strtotime('now')),
                        ];

                        $ativos_externos[] = [
                            'id_ativo_externo' => $ativo->id_ativo_externo,
                            'situacao' => $ativo->status,
                        ];

                        $ativo_externo = $this->ativo_externo_model->get_ativo($ativo->id_ativo_externo);
                        if ($ativo_externo->tipo == 1) {
                            $this->ferramental_requisicao_model->liberar_kit($ativo_externo, $ativos_externos, 3);
                        }
                    }
                    $requisicao_items[] = [
                        'id_requisicao_item' => $item->id_requisicao_item,
                        'data_transferido' => date('Y-m-d H:i:s', strtotime('now')),
                        'status' => 3
                    ];
                }

                $this->db->update_batch("ativo_externo_requisicao_ativo", $requisicao_ativos, 'id_requisicao_ativo');
                $this->db->update_batch("ativo_externo_requisicao_item", $requisicao_items, 'id_requisicao_item');
                $this->ferramental_requisicao_model->salvar_formulario([
                    'id_requisicao' => $requisicao->id_requisicao,
                    'data_transferido' => date('Y-m-d H:i:s', strtotime('now')),
                    'status' => 3
                ]);

                $this->gerar_romaneio($requisicao->id_requisicao, false);

                $obra = $this->obra_model->get_obra($requisicao->id_destino);
                $this->notificacoes_model->enviar_push(
                    "Requisição de devolução Transferida", 
                    "Devolução de Ferramentas {$requisicao->id_requisicao} Transferida para a obra {$obra->codigo_obra}. 
                    Clique na Notificação para mais detalhes.", 
                    [
                        "filters" => [
                        ["field" => "tag", "key" => "id_obra", "relation" => "=", "value" => $this->user->id_obra],
                        ["operator" => "AND"],
                        ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => '1'],
                    ],
                    "include_external_user_ids" => [$requisicao->id_solicitante],
                    "url" => "ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"
                ]);

                $this->session->set_flashdata('msg_success', "Requisição Transferida com Sucesso!");
                echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
                return;
            }
            $this->session->set_flashdata('msg_erro', "Requisição não pode ser transferida pelo o usuário!");
            echo redirect(base_url('ferramental_requisicao'));
        }

        $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
        echo redirect(base_url('ferramental_requisicao'));
        return;
    }

    public function recusar_requisicao($id_requisicao){
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);
        
        if ($id_requisicao && $this->input->method() ==  'post') {
            if ($this->user->nivel != 1 || !in_array($requisicao->status, [1, 11])) {
                $this->session->set_flashdata('msg_erro', "Requisição não pode ser recusada!");
                echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
                return;
            }

            $requisicao_items = $requisicao_ativos = $ativos_externos = [];
            foreach($requisicao->items as $item) {
                if (count($item->ativos) > 0) {
                    foreach ($item->ativos as $ativo) {
                        $requisicao_ativos[] = [
                            'id_requisicao_ativo' => $ativo->id_requisicao_ativo,
                            'data_transferido' => null,
                            'status' => 15,
                        ];

                        $ativos_externos[] = [
                            'id_ativo_externo' => $ativo->id_ativo_externo,
                            'situacao' => 12,
                            'id_obra' => $requisicao->id_origem,
                        ];

                        $ativo_externo = $this->ativo_externo_model->get_ativo($ativo->id_ativo_externo);
                        if ($ativo_externo->tipo == 1) {
                            $this->ferramental_requisicao_model->liberar_kit($ativo_externo, $ativos_externos, 3);
                        }
                    }
                }

                $requisicao_items[] = [
                    'id_requisicao_item' =>  $item->id_requisicao_item,
                    'data_liberado' => date('Y-m-d H:i:s', strtotime('now')),
                    'status' => 15
                ];
            }
            
            if (!empty($ativos_externos)) {
                $this->db->update_batch("ativo_externo", $ativos_externos, 'id_ativo_externo');
            }
            
            if (!empty($requisicao_ativos)) {
                $this->db->update_batch("ativo_externo_requisicao_ativo", $requisicao_ativos, 'id_requisicao_ativo');
            }

            if (!empty($requisicao_items)) {
                $this->db->update_batch("ativo_externo_requisicao_item", $requisicao_items, 'id_requisicao_item');
            }

            $this->ferramental_requisicao_model->salvar_formulario([
                'id_requisicao' => $requisicao->id_requisicao,
                'id_origem' => $requisicao->id_origem,
                'id_despachante' => $this->user->id_usuario,
                'status' => 15,
                'data_liberado' => date('Y-m-d H:i:s', strtotime('now'))
            ]);
            $this->session->set_flashdata('msg_success', "Requisição Recusada com Sucesso!");
            echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
            return;
        }

        $this->notificacoes_model->enviar_push("Requisição Recusada", "Requisição de Ferramentas {$requisicao->id_requisicao} Recusada pelo administrador da obra. Clique na Notificação para mais detalhes.", [
            "filters" => [
                ["field" => "tag", "key" => "id_obra", "relation" => "=", "value" => $this->user->id_obra],
                ["operator" => "AND"],
                ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => '2'],
            ],
            "include_external_user_ids" => [$requisicao->id_solicitante],
            "url" => "ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"
        ]);

        $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
        echo redirect(base_url('ferramental_requisicao'));
        return;
    }

    function deletar($id_requisicao) {
        $msg = "Requisição não localizada.";
        $requisicao =  $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);
        
        if ($requisicao && $this->input->method() == 'post') {
            $msg = "Requisição não excluida. A requisição a ser excluida deve ter o status como: 'Pendente'";

            if ((int) $requisicao->status == 1) {
                $msg = null;               
                $items_ids = array_map(
                    function ($item) {
                        return $item->id_requisicao_item;
                    },
                    $requisicao->items
                );

                $this->db
                    ->where('id_requisicao', $id_requisicao)
                    ->where("id_requisicao_item IN ('".implode(',',$items_ids)."')")
                    ->delete('ativo_externo_requisicao_item');
                $this->db->where('id_requisicao', $id_requisicao)->delete('ativo_externo_requisicao');

                if (isset($requisicao->id_requisicao_mae)) {
                    $this->ferramental_requisicao_model->salvar_formulario([
                        'id_requisicao' => $requisicao->id_requisicao_mae,
                        'id_requisicao_filha' => null,
                        'data_inclusao_filha' => null
                    ]);
                }
            }
        }

        if ($msg) {
            $this->session->set_flashdata('msg_erro', $msg);
        }
        echo redirect(base_url('ferramental_requisicao'));
        return;
    }

    public function manual($id_requisicao, $id_requisicao_item = null){
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);
        if($requisicao){
            $requisicao->item = $this->ferramental_requisicao_model->get_requisicao_item($id_requisicao, $id_requisicao_item);
            $ativos = [];
            if ($requisicao->item) {
                $ativos = $requisicao->item->ativos;
            } else {
                foreach($requisicao->items as $item) {
                    $ativos = array_merge($ativos, $item->ativos);
                }
            }
            $requisicao->ativos = $ativos;
            
            $this->get_template('requisicao_manual', ['requisicao' => $requisicao]);
            return;
        }
        $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
        echo redirect(base_url('ferramental_requisicao')); 
    }

    public function receber_item_manual($id_requisicao, $id_requisicao_item = null) {
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);
        if($requisicao && ($this->user->id_obra == $requisicao->id_destino)) {
            if ($this->user->nivel == 2 && $requisicao->tipo == 2) {
                $this->session->set_flashdata('msg_erro', "Usuário não pode aceitar essa Requisição!");
                echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));  
                return;
            }

            if(!$id_requisicao_item){
                $id_requisicao_item = array_map(function($item) {
                    return $item->id_requisicao_item;
                }, $requisicao->items);
            }

            if ($id_requisicao_item && $this->input->method() == 'post' && isset($_POST['id_requisicao_ativo'])) {
                $retorno = $this->ferramental_requisicao_model->aceite_items_requisicao($id_requisicao, $id_requisicao_item, $_POST['id_requisicao_ativo'], [4, $_POST['status']]);
                if ($retorno) {
                    $obra = $this->obra_model->get_obra($this->user->id_obra);
                    $this->notificacoes_model->enviar_push(
                        "Requisição Recebida", 
                        "Requisição de Ferramentas {$requisicao->id_requisicao} Recebida na obra {$obra->codigo_obra}. Clique na Notificação para mais detalhes.", 
                        [
                            "filters" => [
                                ["field" => "tag", "key" => "id_obra", "relation" => "=", "value" => $this->user->id_obra],
                                ["operator" => "AND"],
                                ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => '1'],
                            ],
                            "include_external_user_ids" => [$requisicao->id_despachante],
                            "url" => "ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"
                        ]
                    );

                    $this->session->set_flashdata('msg_success', "Você confirmou o recebimento dos items relacionados.");
                    echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}")); 
                    return;
                }
            }
            $this->aceite_erro($this->input->post('id_requisicao'));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
        echo redirect(base_url('ferramental_requisicao')); 

    }

    public function receber_item($id_requisicao, $id_requisicao_item, $status){
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);
        $requisicao_item = $this->ferramental_requisicao_model->get_requisicao_item($id_requisicao, $id_requisicao_item);
 
        if ($requisicao_item && ($this->user->id_obra == $requisicao->id_destino)) {
            $ativos = array_map(function ($ativo){return $ativo->id_requisicao_ativo;}, $requisicao_item->ativos);
            $acao = (int) $status == 4 ? 'receber' : 'devolver';
            $acao_sinonimo = (int) $status == 4 ? 'recepção' : 'devoluçãp';
            $retorno = $this->ferramental_requisicao_model->aceite_items_requisicao($id_requisicao, $id_requisicao_item, $ativos, [(int) $status, (int)$status]);
            if ($retorno) {
                $obra = $this->obra_model->get_obra($this->user->id_obra);

                $this->notificacoes_model->enviar_push(
                    "Requisição Recebida", 
                    "Requisição de Ferramentas {$requisicao->id_requisicao}, o Almoxarife confirmou $acao_sinonimo dos itens relacionados na obra {$obra->codigo_obra}.
                     Clique na Notificação para mais detalhes.", 
                    [
                        "filters" => [
                            ["field" => "tag", "key" => "id_obra", "relation" => "=", "value" => $this->user->id_obra],
                            ["operator" => "AND"],
                            ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => '1'],
                        ],
                        "include_external_user_ids" => [$requisicao->id_despachante],
                        "url" => "ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"
                    ]
                );

                $this->session->set_flashdata('msg_success', "Você confirmou a $acao_sinonimo dos itens relacionados.");
                return;
            }
            return $this->session->set_flashdata('msg_erro', "Ocorreu um erro ao tentar $acao os itens relacionados.");
        }
        return $this->aceite_erro($id_requisicao);
    }

    public function receber_devolucoes($id_requisicao){
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);
       
        if ($requisicao && $this->input->method() == 'post') {
            if ($this->user->id_obra != $requisicao->id_destino){
                $this->session->set_flashdata('msg_erro', "Usuário não pode receber a Devolução!");
                echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
                return;
            }

            $ativos = [];
            foreach ($requisicao->items as $item) {
                $item_ativos = array_map(
                    function ($ativo) {
                        return $ativo->id_requisicao_ativo;
                    },
                    $item->ativos
                );
                $ativos = array_merge($ativos, $item_ativos);
            }

            $retorno = $this->ferramental_requisicao_model->aceite_items_requisicao($id_requisicao, null, $ativos, [4, 4]);
            if ($retorno) {
                $obra = $this->obra_model->get_obra($requisicao->id_destino);
                $this->notificacoes_model->enviar_push(
                    "Requisição Devolvida", 
                    "Requisição de Ferramentas {$requisicao->id_requisicao}, o Administrador confirmou o recebimento da Devolução dos itens para a obra {$obra->codigo_obra}. Clique na Notificação para mais detalhes.", 
                    [
                        "filters" => [
                            ["field" => "tag", "key" => "id_obra", "relation" => "=", "value" => $this->user->id_obra],
                            ["operator" => "AND"],
                            ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => '2'],
                        ],
                        "include_external_user_ids" => [$requisicao->id_solicitante],
                        "url" => "ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"
                    ]
                );

                $this->session->set_flashdata('msg_success', "Você confirmou o recebimento da Devolução."); 
                return;
            }
            
            $this->session->set_flashdata('msg_erro', "Ocorreu um erro ao tentar receber a Devolução.");
            echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Requisição não localizada!");
        echo redirect(base_url("ferramental_requisicao"));         
    }

    public function solicitar_items_nao_inclusos($id_requisicao){
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);
        $permit_solicitar = $this->ferramental_requisicao_model->permit_solicitar_items_nao_inclusos($requisicao);
  
        if (($requisicao && $permit_solicitar) && ($requisicao->id_solicitante == $this->user->id_usuario || $requisicao->id_destino == $this->user->id_obra)) {
            $status =  $this->ferramental_requisicao_model->solicitar_items_nao_inclusos_requisicao($requisicao);
            if ($status) {
                $this->session->set_flashdata('msg_success', "Nova Requisição Complementar com itens não inclusos criada com sucesso!");
                echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));         
                return;
            }
            $this->session->set_flashdata('msg_erro', "Erro ao criar Requisição Complementar com itens não inclusos!");
            echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));      
            return;
        }
        $this->session->set_flashdata('msg_erro', "Sem premissão para essa soliciatação!");
        echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));   
    }

    public function devolver_items_requisicao($id_requisicao) {
        $permit_devolver = $this->ferramental_requisicao_model->permit_devolver_items_requisicao($id_requisicao);
        if ($permit_devolver) {
            $status =  $this->ferramental_requisicao_model->devolver_items_requisicao($id_requisicao);
            if ($status) {
                $this->session->set_flashdata('msg_success', "Nova Requisição de Devolução com itens não recebidos ou com defeito criada com sucesso!");    
                echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));     
                return;
            }
            $this->session->set_flashdata('msg_erro', "Erro ao criar Requisição de Devolução com itens não recebidos ou com defeito!");
            echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));      
            return;
        }
        $this->session->set_flashdata('msg_erro', "Sem premissão para essa soliciatação!");
        echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));   
    }

    public function aceite_erro($id_requisicao){
        $this->session->set_flashdata('msg_erro', "Item não existe na requisição!");
        echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));         
    }

    public function gerar_romaneio(int $id_requisicao, $redirect = true) {
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);

        if ($requisicao && $requisicao->status == 3) {
            $css = file_get_contents( __DIR__ ."/../../../../assets/css/relatorios.css", true, null);
            $data = [
                'css' =>  $css, 
                'logo' => $this->base64(__DIR__ ."/../../../../assets/images/icon/logo.png"),
                'header' => $this->base64(__DIR__ ."/../../../../assets/images/docs/termo_header.png"),
                'footer' => $this->base64(__DIR__ ."/../../../../assets/images/docs/termo_footer.png"),
                'data_hora' => date('d/m/Y H:i:s', strtotime('now')),
                'requisicao' => $requisicao
            ];

            $filename = "requisicao_romaneio_" . date('YmdHis', strtotime('now')).".pdf";
            $html = $this->load->view("requisicao_romaneio", $data, true);

            $upload_path = "assets/uploads/anexo";
            $path = __DIR__."/../../../../{$upload_path}";
            $file = "{$path}/{$filename}";

            if (!file_exists($file)) {
                $this->gerar_pdf($file, $html);                

                $anexo = $this->anexo_model->query_anexos()
                            ->where("id_modulo_item = {$id_requisicao} and tipo = 'romaneio'")
                            ->limit(1)->get()->row();

                if ($anexo) {
                    $id_anexo = $anexo->id_anexo;
                    $this->db->where("id_anexo = {$id_anexo}")->update("anexo", ['anexo' => "anexo/{$filename}"]);
                } else {
                    $id_anexo = $this->salvar_anexo(
                        [
                            "titulo" => "Romaneio",
                            "descricao" => "Romaneio da Requisição ID {$id_requisicao}",
                            "anexo" => "anexo/{$filename}",
                        ],
                        'ferramental_requisicao',
                        $id_requisicao,
                        "romaneio"
                    );
                } 

                if(!$redirect)  return $id_anexo != null;
            }
        }

        if(!$redirect) return false;
        echo redirect($this->getRef());
    }



    
    


}