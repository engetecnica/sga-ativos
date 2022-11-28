<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ferramental_estoque
 *
 * @author https://github.com/messiasdias
 */
class Ferramental_estoque  extends MY_Controller {

    function __construct() {
        parent::__construct();
        if ($this->is_auth()) {
            $this->load->model('ferramental_estoque_model');
            $this->load->model('empresa/empresa_model'); 
            $this->load->model('ativo_externo/ativo_externo_model');
            $this->load->model('ferramental_requisicao/ferramental_requisicao_model');
            $this->load->model('funcionario/funcionario_model');
            $this->model = $this->ferramental_estoque_model; 
        }
    }

    function index() {
        if ($this->input->method() === 'post')  {
            return $this->paginate_json([
                "query_args" => [$this->user->id_obra],
                "templates" => [
                    [
                        "name" => "id_retirada_html",
                        "view" => "index/id_retirada",
                    ],
                    [
                        "name" => "status_html",
                        "view" => "index/status"   
                    ],
                    [                       
                        "name" => "actions",
                        "view" => "index/actions"
                    ]
                ],
                "after" => function(object &$row) {
                    $row->data_inclusao = date("d/m/Y H:i:s", strtotime($row->data_inclusao));
                    $row->devolucao_prevista = date("d/m/Y H:i:s", strtotime($row->devolucao_prevista));
                }
            ]);
        }
        $this->get_template('index');
    }

    function detalhes($id_retirada) {
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 13, 'visualizar'));

        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada, $this->user->id_obra);
        $data = array_merge($this->anexo_model->getData('ferramental_estoque', $id_retirada, 'termo_de_responsabilidade'), [
            "back_url" => "ferramental_estoque/detalhes/{$id_retirada}",
            'retirada' => $retirada,
        ]);

        if ($data['retirada']) {
            $this->get_template('retirada_detalhes', $data);
            return;
        }
        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }

    function detalhes_item($id_retirada, $id_retirada_item = null){

        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 13, 'visualizar'));

        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada);



        
        if ($retirada) {
            $ativos = $items = [];
            foreach($retirada->items as $item) {
                if (($item->id_retirada_item == $id_retirada_item) || !$id_retirada_item) {
                    $items[] = $item;
                    $ativos = array_merge($ativos, $item->ativos);
                }
            }

            $this->get_template('retirada_detalhes_item',[
                'retirada' => $retirada,
                'items' => $items,
                'ativos' => $ativos,
                'status_lista' => $this->ferramental_requisicao_model->get_requisicao_status()
            ]);
            return;
        }

        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }

    function adicionar(){        
        $id_obra = $this->user->nivel == 2 ? $this->user->id_obra : null;
        $data['lista_funcionario'] = $this->funcionario_model->get_lista($this->user->id_empresa, $id_obra, 0);
        $data['lista_ferramental'] = $this->ativo_externo_model->get_estoque($this->user->id_obra);

      //  $this->dd($data);






        $this->get_template('index_form', $data);
    }

    function editar($id_retirada){


             

        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 13, 'editar'));

        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada);
        if($retirada) { 
            if ($retirada->status != 1) {
                $this->session->set_flashdata('msg_erro', "Retirada não pode ser modificada!");
                echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
                return;
            }


            $id_obra = $this->user->nivel == 2 ? $this->user->id_obra : null;
            $data['lista_funcionario'] = $this->funcionario_model->get_lista($this->user->id_empresa, $id_obra, 0);
            $data['lista_ferramental'] = $this->ativo_externo_model->get_estoque($this->user->id_obra);   
            $data['id_retirada'] = $id_retirada;



            $this->get_template('index_form', $data);
            return;
        }
        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }

    function dados_retirada($id_retirada = null) {
        $this->json([
            'id_obra' => $this->user->id_obra,
            'retirada' => $id_retirada ? $this->ferramental_estoque_model->get_retirada($id_retirada) : null
        ]);
    }

    function buscar($items = null) {
        $data = [];
        $id_obra = $this->user->nivel == 2 ? $this->user->id_obra : null;
        if ($items == 'funcionarios') {
            $data = $this->funcionario_model->search_funcionarios($this->user->id_empresa, $id_obra);
        }

        if ($items == 'grupos') {
            $data = $this->ativo_externo_model->search_grupos($this->user->id_obra);
            $data['templates'] = [
                [
                    "name" => "actions",
                    "view" => "index_form/actions"
                ],
                [
                    "name" => "patrimonio",
                    "view" => "index_form/patrimonio"
                ]
            ];
            $data['table'] = 'atv';  
        }
        $this->paginate_json($data);
    }

    function lista_ativos_grupos_json(){
       $this->json($this->ativo_externo_model->get_grupos($this->user->id_obra, true));
    }
    
    function lista_retiradas($id_obra = null, $id_funcionario = null, $status = null) {
        if (!$status) {
            $status = $this->input->get('status');
        }
        echo json_encode($this->ferramental_estoque_model->get_lista_retiradas($id_obra, $id_funcionario, $status));
    }

    function remove_retirada($id_retirada) {
        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada);
        if($retirada && $this->input->method() == 'post') {
            if ($retirada->status == 1) {
                foreach($retirada->items as $it => $item) {
                    $this->remove_item($id_retirada, $item->id_retirada_item);
                }

                $deleted = $this->db->where("id_retirada", $id_retirada)
                                ->delete("ativo_externo_retirada");
                if ($deleted) {
                    $this->session->set_flashdata('msg_success', "Dados removidos com sucesso!");
                    return true;
                }
                $this->session->set_flashdata('msg_success', "Ocorreu um erro ao tentar remover os dados!");
                return false;
            }
            $this->session->set_flashdata('msg_erro', "Retirada não pode ser removida!");
            return false;
        }
        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }

    function remove_item($id_retirada_item){
        $item = $this->ferramental_estoque_model->get_retirada_item($id_retirada_item);
        if ($item) {
            $deleted = $this->db->where("id_retirada_item", $id_retirada_item)
                                ->delete("ativo_externo_retirada_item");
            if($deleted) {
                echo json_encode(true);
                return true;
            }
        }
        echo json_encode(true);
        return false;
    }


    # Grava Retirada
    function salvar(){ 
        
        
        
        




        
        if ($this->input->post('ativo') && count($this->input->post('ativo')) > 0) {
            # Dados
            $id_retirada = $this->input->post('id_retirada');
            $retirada['id_obra'] = ($this->user->id_obra) ?? null;
            $retirada['id_funcionario'] = $this->input->post('id_funcionario');
            $retirada['status'] = 1; # Pendente
            $retirada['observacoes'] = $this->input->post('observacoes');
            $retirada['devolucao_prevista'] = $this->input->post('devolucao_prevista');
            
            $mode = 'update';
            if (!$id_retirada) {
                $mode = 'insert';
                $id_retirada = $this->ferramental_estoque_model->salvar_formulario($retirada);                
            } else {
                $retirada['id_retirada'] =  $id_retirada;
            }
                        
            if (strtotime($retirada['devolucao_prevista']) < strtotime('now')) {
                $this->session->set_flashdata('msg_success', "Data de devolução prevista deve ser maior que atual!");
                if ($mode == 'insert') {
                    // echo redirect(base_url("ferramental_estoque/adicionar"));
                    // return;
                }
                // echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
                // return;
            }          
            
            $items = array();
            
            foreach($this->input->post('ativo') as $k=>$item){
                
                $patrimonio[$k] = $this->ferramental_estoque_model->get_patrimonio_by_code($item);
                if (isset($patrimonio[$k])) {
                    
                    //echo "aqui";
                    $items[$k] = array();
                    if(isset($patrimonio[$k]->id_retirada_item)) {
                        $items[$k]['id_retirada_item'] = $patrimonio[$k]->id_retirada_item; 
                    }
                    
                    $items[$k]['id_retirada'] = $id_retirada;
                    $items[$k]['quantidade'] = 1;
                    $items[$k]['id_ativo_externo'] = $patrimonio[$k]->id_ativo_externo;
                    $items[$k]['id_ativo_externo_grupo'] = $patrimonio[$k]->id_ativo_externo_grupo;
                    $items[$k]['status'] = $retirada['status'];
                }
            }
            

            
       //     $this->dd($items, $retirada, $id_retirada, $this->input->post());
            // if ($mode == 'update') {
            //     $items_update = $items_insert = array();
            //     foreach($items as $i => $item) {
            //         if (isset($item['id_retirada_item'])) {
            //             $items_update[] = $item;
            //         } 
            //         else {
            //             $items_insert[] = $item; 
            //         }
            //     }
                
            //     if (count($items_update) > 0) {
            //         $this->db->update_batch("ativo_externo_retirada_item", $items_update, 'id_retirada_item');
            //     }
                
            //     if (count($items_insert) > 0) {
            //         $this->db->insert_batch("ativo_externo_retirada_item", $items_insert);
            //     }
                
            //     $this->ferramental_estoque_model->salvar_formulario($retirada);
            // } else{ 
                
                
                
                
                if($this->db->insert_batch("ativo_externo_retirada_item", $items)){
                    
                }

                // $this->notificacoes_model->enviar_push(
                //     "Retirada de Ferramentas Pendente", 
                //     "Nova Retirada de Ferramentas Pendente de aprovação. Clique na Notificação para mais detalhes.", 
                //     [
                //         "filters" => [
                //             ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "1"],
                //             ["operator" => "AND"],
                //             ["field" => "tag", "key" => "id_obra", "relation" => "=", "value" => $this->user->id_obra],
                //                     ],
                //                     "url" => "ferramental_estoque/detalhes/{$id_retirada}"
                //                 ]
                //             );
           


            $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
            echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
            return;
        }

        $this->session->set_flashdata('msg_success', "Nenhum Registro salvo!");
        echo redirect(base_url("ferramental_estoque"));
    }

    function liberar_retirada($id_retirada) {
        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada);

        if($retirada && $this->input->method() == 'post') {
            $ativos = $ativos_externos = $items = array();
            //$retiradas = $this->ferramental_estoque_model->get_lista_retiradas($retirada->id_obra, $retirada->id_funcionario, [2, 4, 14]);

            /* Não é necessário aguardar autorização */
            $aguardar_autorizacao = 0;

            /*
            $aguardar_autorizacao = (count($retiradas) > 0) && ($this->user->nivel == 2);
            */

            $retirada->status = $aguardar_autorizacao ? 14 : 2;

            foreach($retirada->items as $k => $item) {
                $ativos_estoque = $item->ativos;
                if (!$ativos_estoque || count($ativos_estoque) == 0) {
                    $ativos_estoque = $this->ativo_externo_model->get_estoque($retirada->id_obra, $item->id_ativo_externo_grupo, 12);
                }

                if($item)
                {
                    $item->status = $retirada->status;
                    $ativos[] = [
                        'id_retirada' => $id_retirada,
                        'id_ativo_externo' => $item->id_ativo_externo,
                        'id_retirada_item' => $item->id_retirada_item,
                        'status' => $item->status,
                    ];

                    $ativos_externos[] = [
                            'id_ativo_externo' => $item->id_ativo_externo,
                            'situacao' => $item->status,
                        ];                    
                }

                $items[] = [
                    'id_retirada_item' => $item->id_retirada_item,
                    'status' => $item->status,
                ];
            }

            if (count($ativos) > 0 && count($items) > 0) {

                $this->db->insert_batch("ativo_externo_retirada_ativo", $ativos);
                $this->db->update_batch("ativo_externo", $ativos_externos, 'id_ativo_externo');
    
                $this->db->update_batch("ativo_externo_retirada_item", $items, 'id_retirada_item');
                $this->ferramental_estoque_model->salvar_formulario([
                    'id_retirada' => $retirada->id_retirada,
                    'status' => $retirada->status,
                ]);
                                
                $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
                echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
                return;
            }

            $this->session->set_flashdata('msg_erro', "Nenhum Registro salvo!");
            echo redirect(base_url("ferramental_estoque"));
            return;
       }

        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }


    function entregar_items_retirada($id_retirada) {
        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada);
        if($retirada && $this->input->method() == 'post') {
            if($retirada->status == 2) {
                $ativos = $ativos_externos = $items = array();
                $retirada->status = 4;

                foreach($retirada->items as $k => $item) {
                    $item->status = $retirada->status;

                    foreach($item->ativos as $ativo) {
                        $ativos[] = [
                            'id_retirada_ativo' => $ativo->id_retirada_ativo,
                            'data_retirada' => date('Y-m-d H:i:s', strtotime('now')),
                            'status' => $item->status,
                        ];

                        $ativos_externos[] = [
                            'id_ativo_externo' => $ativo->id_ativo_externo,
                            'situacao' => 5,
                        ];
                    }

                    $items[] = [
                        'id_retirada_item' => $item->id_retirada_item,
                        'status' => $item->status,
                        'data_retirada' => date('Y-m-d H:i:s', strtotime('now'))
                    ];
                }

                if (!in_array(0, [count($ativos), count($items), count($ativos_externos)])) {
                    $this->db->update_batch("ativo_externo_retirada_ativo", $ativos, 'id_retirada_ativo');
                    $this->db->update_batch("ativo_externo", $ativos_externos, 'id_ativo_externo');
                    $this->db->update_batch("ativo_externo_retirada_item", $items, 'id_retirada_item');
                    $this->ferramental_estoque_model->salvar_formulario([
                        'id_retirada' => $retirada->id_retirada,
                        'status' => $retirada->status,
                    ]);
                    $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
                    echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
                    return;
                }

                $this->session->set_flashdata('msg_erro', "Nenhum Registro salvo!");
                echo redirect(base_url("ferramental_estoque"));
                return;
            }

            $this->session->set_flashdata('msg_erro', "Retirada não liberada para entrega!");
            echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
            return;
        }

        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }

    function devolver_items_retirada($id_retirada, $id_retirada_item = null){
        $item = null;
        $obra_base = $this->get_obra_base();
        $id_obra = (isset($this->user->id_obra) && $this->user->id_obra > 0) ? $this->user->id_obra : $obra_base->id_obra;
        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada, $id_obra);
      

        if ($retirada) {
            $ativos = $items = [];
            foreach($retirada->items as $item) {
                if (($item->id_retirada_item == $id_retirada_item) || !$id_retirada_item) {
                    $items[] = $item;
                    $ativos = array_merge($ativos, $item->ativos);
                }
            }

            $this->get_template('retirada_devolver_items',[
                'retirada' => $retirada,
                'items' => $items,
                'ativos' => $ativos,
                'status_lista' => $this->ferramental_requisicao_model->get_requisicao_status()
            ]);
            return;
        }

        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }

    function devolver_items_retirada_salvar() {
        $id_retirada = $this->input->post('id_retirada');
        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada);

        if($retirada && $this->input->method() == 'post') {
            $items_devolvidos_anterior = $this->db->select('item.*')
                        ->from('ativo_externo_retirada_item item')
                        ->where("item.id_retirada = {$retirada->id_retirada}")
                        ->where("item.status = 9")
                        ->group_by('item.id_retirada_item')
                        ->get()
                        ->num_rows();
            $items_devolvidos = $items_devolvidos_anterior;

            if($retirada->status == 4) {
                $ativos = $ativos_externos = $items = $post_items = array();
                foreach ($this->input->post('id_retirada_item') as $item) {
                    $post_items[$item]['ativos_externos'] = $this->input->post("id_ativo_externo_{$item}");
                    $post_items[$item]['ativos'] = $this->input->post("id_retirada_ativo_{$item}");
                    $post_items[$item]['status'] = $this->input->post("status_{$item}");
                }
                
                foreach ($post_items as $id => $pi){
                    $ativos_devolvidos_anterior = $this->db->select('ativo.*')
                        ->from('ativo_externo_retirada_ativo ativo')
                        ->where("ativo.id_retirada_item = {$id}")
                        ->where("ativo.id_retirada = {$retirada->id_retirada}")
                        ->where("ativo.status IN (8, 9)")
                        ->group_by('ativo.id_retirada_ativo')
                        ->get()
                        ->num_rows();

                    $ativos_devolvidos = $ativos_devolvidos_anterior;

                    $ativos_item_total = $this->db->select('ativo.*')
                        ->from('ativo_externo_retirada_ativo ativo')
                        ->where("ativo.id_retirada_item = {$id}")
                        ->where("ativo.id_retirada = {$retirada->id_retirada}")
                        ->group_by('ativo.id_retirada_ativo')
                        ->get()
                        ->num_rows();

                    foreach ($pi['ativos'] as $a => $ativo){
                        $ativos[] = [
                            'id_retirada_ativo' => $ativo,
                            'status' => $pi['status'][$a],
                            'data_devolucao' => date('Y-m-d H:i:s', strtotime('now'))
                        ];

                        $ativos_externos[] = [
                            'id_ativo_externo' => $pi['ativos_externos'][$a],
                            'situacao' => $pi['status'][$a] == 9 ? 12 : 8,
                        ];
                        $ativos_devolvidos++;
                    }

                    $todos_ativos_devolvidos = ($ativos_devolvidos == $ativos_item_total);
                    $items[] = [
                        'id_retirada_item' => $id,
                        'status' => $todos_ativos_devolvidos ? 9 : 4,
                        'data_devolucao' => date('Y-m-d H:i:s', strtotime('now'))
                    ];
                    $items_devolvidos++;
                }

                if (!in_array(0, [count($ativos), count($items), count($ativos_externos)])) {
                    $this->db->update_batch("ativo_externo_retirada_ativo", $ativos, 'id_retirada_ativo');
                    $this->db->update_batch("ativo_externo", $ativos_externos, 'id_ativo_externo');
                    $this->db->update_batch("ativo_externo_retirada_item", $items, 'id_retirada_item');

                    $this->ferramental_estoque_model->salvar_formulario([
                        'id_retirada' => $retirada->id_retirada,
                        'status' => ($items_devolvidos == count($retirada->items)) ? 9 : 4,
                    ]);
                    
                    $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
                    echo redirect(base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}"));
                    return;
                }

                $this->session->set_flashdata('msg_erro', "Nenhum Registro salvo!");
                echo redirect(base_url("ferramental_estoque"));
                return;
            }

            $this->session->set_flashdata('msg_erro', "Retirada não liberada para devolução!");
            echo redirect(base_url("ferramental_estoque/detalhes/{$retirada->id_retirada}"));
            return;
        }

        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }


    function impimir_termo_resposabilidade($id_retirada){
        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada);
        if($retirada) {
            $ativos = [];
            foreach($retirada->items as $item) {
                foreach($item->ativos as $a => $ativo) {
                    $item->ativos[$a]->quantidade = count($item->ativos); 
                }
                $ativos = array_merge($ativos, $item->ativos);
            }

            if(in_array($retirada->status, [2, 4, 9])) {
                $css = file_get_contents( __DIR__ ."/../../../../assets/css/termo_de_resposabilidade_retirada.css", true, null);

                $header_path = __DIR__ ."/../../../../assets/images/docs/termo_header.png";
                $header_data = file_get_contents($header_path, true, null);
                $header_base64 = 'data:image/' . pathinfo($header_path, PATHINFO_EXTENSION) . ';base64,' . base64_encode($header_data);

                $footer_path = __DIR__ ."/../../../../assets/images/docs/termo_footer.png";
                $footer_data = file_get_contents($footer_path, true, null);
                $footer_base64 = 'data:image/' . pathinfo($footer_path, PATHINFO_EXTENSION) . ';base64,' . base64_encode($footer_data);

                $data = [
                    'css' =>  $css, 
                    'retirada' => $retirada,
                    'ativos' => $ativos,
                    'header' => $header_base64,
                    'footer' => $footer_base64,
                    'data_hora' => date('d/m/Y H:i:s', strtotime($retirada->data_inclusao)),
                ];
                $filename = "termo_de_responsabilidade_{$retirada->id_retirada}" . date('YmdHis', strtotime('now')).".pdf";
                $html = $this->load->view("termo_de_responsabilidade", $data, true);

                $this->gerar_pdf($filename, $html, 'D');
                return;
            }
            $this->session->set_flashdata('msg_erro', "Retirada não liberada para impressão!");
            echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }

    function anexar_termo_resposabilidade($id_retirada){
        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada);
        if($retirada) {
            $data['id_retirada'] = $id_retirada;
            $data['termo_de_responsabilidade'] = ($_FILES['termo_de_responsabilidade'] ? $this->upload_arquivo('termo_de_responsabilidade') : '');
            if (!$data['termo_de_responsabilidade'] || $data['termo_de_responsabilidade'] == '') {
                $this->session->set_flashdata('msg_erro', "O tamanho do comprovante deve ser menor ou igual a ".ini_get('upload_max_filesize'));
                echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
                return;
            }

            $this->db->where('id_retirada', $id_retirada)->update('ativo_externo_retirada', $data);
            $this->salvar_anexo(
                [
                    "titulo" => "Termo de Responsabilidade",
                    "descricao" => "Termo de Responsabilidade da Retirada ID {$data['id_retirada']}",
                    "anexo" => "termo_de_responsabilidade/{$data['termo_de_responsabilidade']}",
                ],
                'ferramental_estoque',
                $data['id_retirada'],
                "termo_de_responsabilidade"
            );

            $this->session->set_flashdata('msg_success', "Dados salvos com sucesso!");
            echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }



    public function renovar_retirada($id_retirada)
    {
        $retirada['retirada'] = $this->ferramental_estoque_model->get_retirada($id_retirada);

        if ($retirada['retirada']) {
            $this->get_template('retirada_detalhes_item_renovacao', $retirada);
            return;
        }
        
        

        if($retirada) {
            //
        } else {
            $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
            echo redirect(base_url("ferramental_estoque"));
        }

    }

    public function salvar_renovacao()
    {

        if($this->input->post('renovar')){


            $retirada_item_1 = $this->ferramental_estoque_model->get_retirada($this->input->post('id_retirada'));
            
            // Criar a Retirada
            $retirada['id_retirada_pai'] = $this->input->post('id_retirada');
            $retirada['id_obra'] = $this->input->post('id_obra');
            $retirada['id_funcionario'] = $retirada_item_1->id_funcionario;
            $retirada['data_inclusao'] = date('Y-m-d H:i:s', strtotime('now'));
            $retirada['devolucao_prevista'] = $this->input->post('data_entrega');
            $retirada['status'] = 1; # Pendente
            $retirada['observacoes'] = "Ref. ID Retirada: ".$this->input->post('id_retirada');
          

            $this->db->insert("ativo_externo_retirada", $retirada);
            $id_retirada = $this->db->insert_id();


            foreach($this->input->post('renovar') as $i=>$renovar){

                $item[$i] = $this->ferramental_estoque_model->get_item_renovar($renovar);

                $retirada_item['id_retirada'] = $id_retirada;
                $retirada_item['id_ativo_externo_grupo'] = $item[$i]->id_ativo_externo_grupo;
                $retirada_item['quantidade'] = 1;
                $retirada_item['data_retirada'] = date('Y-m-d H:i:s', strtotime('now'));
                $retirada_item['status'] = 1; # Pendente

                $this->db->insert("ativo_externo_retirada_item", $retirada_item);
                $id_retirada_item = $this->db->insert_id();



                $retirada_ativo['id_retirada'] = $id_retirada;
                $retirada_ativo['id_retirada_item'] = $id_retirada_item;
                $retirada_ativo['id_ativo_externo'] = $item[$i]->ativo->id_ativo_externo;;
                $retirada_ativo['data_retirada'] = date('Y-m-d H:i:s', strtotime('now'));

                $this->db->insert("ativo_externo_retirada_ativo", $retirada_ativo);


            }

           
        }

        $this->session->set_flashdata('msg_sucesso', "Renovação feita com sucesso!");
        echo redirect(base_url("ferramental_estoque"));

    }



}