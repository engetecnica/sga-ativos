<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
use \Mpdf\Mpdf;
/**
 * Description of ferramental_estoque
 *
 * @author https://github.com/messiasdias
 */
class Ferramental_estoque  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ferramental_estoque_model');
    
        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        }
        $this->load->model('empresa/empresa_model'); 
        $this->load->model('ativo_externo/ativo_externo_model');
        $this->load->model('ferramental_requisicao/ferramental_requisicao_model');
        $this->load->model('funcionario/funcionario_model');
        //$this->load->helper('download');             
    }

    function index() {
        $obra_base = $this->get_obra_base();
        $id_obra = (isset($this->user->id_obra) && $this->user->id_obra > 0) ? $this->user->id_obra : $obra_base->id_obra;

        $data['retiradas'] = $this->ferramental_estoque_model->get_lista_retiradas($id_obra);
        $data['estoque'] = $this->ativo_externo_model->get_estoque($id_obra, null, true);
        $data['grupos'] = $this->ativo_externo_model->get_lista_grupo();
        $data['status_lista'] = $this->ferramental_requisicao_model->get_requisicao_status();
        $this->get_template('index', $data);
    }

    function detalhes($id_retirada) {
        $obra_base = $this->get_obra_base();
        $id_obra = (isset($this->user->id_obra) && $this->user->id_obra > 0) ? $this->user->id_obra : $obra_base->id_obra;
        $data['retirada'] = $this->ferramental_estoque_model->get_retirada($id_retirada, $id_obra);
        $data['status_lista'] = $this->ferramental_requisicao_model->get_requisicao_status();

        if ($data['retirada']) {
            if ($this->user->nivel == 1) {
                $this->get_template('retirada_detalhes_adm', $data);
            } else {
                $this->get_template('retirada_detalhes_user', $data);
            }
            return;
        }
        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }

    function detalhes_item($id_retirada, $id_retirada_item = null){
        $ativos = [];
        $item = null;
        $obra_base = $this->get_obra_base();
        $id_obra = (isset($this->user->id_obra) && $this->user->id_obra > 0) ? $this->user->id_obra : $obra_base->id_obra;
        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada, $id_obra);

        if ($retirada) {
            $items = $retirada->items;
            foreach($retirada->items as $item) {
                $ativos = array_merge($ativos, $item->ativos);
            }

            if ($id_retirada_item) {
                $ativos = [];
                $items = [];
                foreach($retirada->items as $it) {
                    if ($it->id_retirada_item == $id_retirada_item) {
                        $item = $it;
                        $ativos = $it->ativos;
                        $items[] = $item;
                    }
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
        $this->get_template('index_form', [
            'funcionarios' => $this->funcionario_model->get_lista($this->user->id_empresa, $this->user->id_obra),
            'grupos' => $this->ativo_externo_model->get_lista_grupo($this->user->id_empresa, $this->user->id_obra, true),
            'id_obra' => $this->user->id_obra,
        ]);
    }


    function editar($id_retirada){
        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada);
        if($retirada) { 
            if ($retirada->status != 1) {
                $this->session->set_flashdata('msg_erro', "Retirada não pode ser modificada!");
                echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
                return;
            }

            $this->get_template('index_form', [
                'retirada' => $retirada,
                'funcionarios' => $this->funcionario_model->get_lista($this->user->id_empresa, $this->user->id_obra),
                'grupos' => $this->ativo_externo_model->get_lista_grupo($this->user->id_empresa, $this->user->id_obra, true),
                'id_obra' => $this->user->id_obra,
            ]);
            return;
        }
        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }


    function lista_ativos_grupos_json(){
        echo json_encode($this->ativo_externo_model->get_lista_grupo($this->user->id_empresa, $this->user->id_obra, true));
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
        # Dados
        $data['id_obra'] = $this->input->post('id_obra');
        $data['id_funcionario'] = $this->input->post('id_funcionario');
        $data['status'] = 1; # Pendente
        $items = array();

        if (count($this->input->post('quantidade')) > 0) {
            # Dados
            $id_retirada = $this->input->post('id_retirada');
            $retirada['id_obra'] = $this->input->post('id_obra');
            $retirada['id_funcionario'] = $this->input->post('id_funcionario');
            $retirada['status'] = 1; # Pendente
            $retirada['observacoes'] = $this->input->post('observacoes');

            $mode = 'update';
            if (!$id_retirada) {
                $mode = 'insert';
                $id_retirada = $this->ferramental_estoque_model->salvar_formulario($retirada);
            }

            foreach($this->input->post('quantidade') as $k=>$item){
                if ((int) $this->input->post('quantidade')[$k] > 0) {
                    $items[$k] = array();
                    if(isset($this->input->post('id_retirada_item')[$k])) {
                        $items[$k]['id_retirada_item'] = $this->input->post('id_retirada_item')[$k]; 
                    }

                    $items[$k]['id_retirada'] = $id_retirada;
                    $items[$k]['quantidade'] = (int) $this->input->post('quantidade')[$k];
                    $items[$k]['id_ativo_externo_grupo'] = $this->input->post('id_ativo_externo_grupo')[$k];
                    $items[$k]['status'] = $retirada['status'];
                }
            }

            if ($mode == 'update') {
                $items_update = $items_insert = array();
                foreach($items as $i => $item) {
                    if (isset($item['id_retirada_item'])) {
                        $items_update[] = $item;
                    } 
                    else {
                        $items_insert[] = $item; 
                    }
                }

                if (count($items_update) > 0) {
                    $this->db->update_batch("ativo_externo_retirada_item", $items_update, 'id_retirada_item');
                }
                
                if (count($items_insert) > 0) {
                    $this->db->insert_batch("ativo_externo_retirada_item", $items_insert);
                }
            } else{ 
                $this->db->insert_batch("ativo_externo_retirada_item", $items);
            }

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
            $retiradas = $this->ferramental_estoque_model->get_lista_retiradas($retirada->id_obra, $retirada->id_funcionario, [2, 4, 14]);
            $aguardar_autorizacao = (count($retiradas) > 0) && ($this->user->nivel == 2);
            $retirada->status = $aguardar_autorizacao ? 14 : 2;

            foreach($retirada->items as $k => $item) {
                $ativos_estoque = $item->ativos;
                if (!$ativos_estoque || count($ativos_estoque) == 0) {
                    $ativos_estoque = $this->ativo_externo_model->get_estoque($retirada->id_obra, $item->id_ativo_externo_grupo);
                }

                for($i=0; $i < $item->quantidade; $i++) {
                    if (isset($ativos_estoque[$i])) {
                        $item->status = $retirada->status;
                        $ativos[] = [
                            'id_retirada' => $id_retirada,
                            'id_ativo_externo' => $ativos_estoque[$i]->id_ativo_externo,
                            'id_retirada_item' => $item->id_retirada_item,
                            'status' => $item->status,
                        ];

                        $ativos_externos[] = [
                            'id_ativo_externo' => $ativos_estoque[$i]->id_ativo_externo,
                            'situacao' => $item->status,
                        ];
                    } else {
                        $item->status = 6;
                    }
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
                            'status' => $item->status,
                            'data_retirada' => date('Y-m-d H:i:s', strtotime('now'))
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
        $ativos = [];
        $item = null;
        $obra_base = $this->get_obra_base();
        $id_obra = (isset($this->user->id_obra) && $this->user->id_obra > 0) ? $this->user->id_obra : $obra_base->id_obra;
        $retirada = $this->ferramental_estoque_model->get_retirada($id_retirada, $id_obra);
      

        if ($retirada) {
            $items = $retirada->items;
            foreach($retirada->items as $item) {
                $ativos = array_merge($ativos, $item->ativos);
            }

            if ($id_retirada_item) {
                $ativos = [];
                $items = [];
                foreach($retirada->items as $it) {
                    if ($it->id_retirada_item == $id_retirada_item) {
                        $item = $it;
                        $ativos = $it->ativos;
                        $items[] = $item;
                    }
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
            if($retirada->status == 4) {
                $items_devolvidos = $this->db->select('item.*')
                        ->from('ativo_externo_retirada_item item')
                        ->where("item.id_retirada = {$id_retirada}")
                        ->where("item.status = 9")
                        ->group_by('item.id_retirada_item')
                        ->get()
                        ->num_rows();

                $ativos = $ativos_externos = $items = $post_items = array();
                foreach ($this->input->post('id_retirada_item') as $item) {
                    $post_items[$item]['ativos'] = $this->input->post("id_retirada_ativo_{$item}");
                    $post_items[$item]['status'] = $this->input->post("status_{$item}");
                }
            
                foreach ($post_items as $id => $pi){
                    $ativos_devolvidos = $this->db->select('ativo.*')
                        ->from('ativo_externo_retirada_ativo ativo')
                        ->where("ativo.id_retirada_item = {$id}")
                        ->where("ativo.id_retirada = {$id_retirada}")
                        ->where("ativo.status = 9")
                        ->group_by('ativo.id_retirada_ativo')
                        ->get()
                        ->num_rows();

                    $ativos_item = $this->db->select('ativo.*')
                        ->from('ativo_externo_retirada_ativo ativo')
                        ->where("ativo.id_retirada_item = {$id}")
                        ->where("ativo.id_retirada = {$id_retirada}")
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
                            'id_ativo_externo' => $ativo,
                            'situacao' => $pi['status'][$a] == 9 ? 12 : 8,
                            'data_liberacao' => date('Y-m-d H:i:s', strtotime('now')),
                        ];
                        $ativos_devolvidos++;
                    }

                    $items[] = [
                        'id_retirada_item' => $id,
                        'status' => ($ativos_devolvidos == $ativos_item) ? 9 : 4,
                        'data_devolucao' => date('Y-m-d H:i:s', strtotime('now'))
                    ];
                    $items_devolvidos++;
                }

                if (!in_array(0, [count($ativos), count($items), count($ativos_externos)])) {
                    $this->db->update_batch("ativo_externo_retirada_ativo", $ativos, 'id_retirada_ativo');
                    $this->db->update_batch("ativo_externo", $ativos_externos, 'id_ativo_externo');
                    $this->db->update_batch("ativo_externo_retirada_item", $items, 'id_retirada_item');
                    $this->ferramental_estoque_model->salvar_formulario([
                        'id_retirada' => $id_retirada,
                        'status' => ($items_devolvidos == count($retirada->items)) ? 9 : 4,
                    ]);
                    $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
                    echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
                    return;
                }

                $this->session->set_flashdata('msg_erro', "Nenhum Registro salvo!");
                echo redirect(base_url("ferramental_estoque"));
                return;
            }

            $this->session->set_flashdata('msg_erro', "Retirada não liberada para devolução!");
            echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
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
                    $item->ativos[$a]->count = count($item->ativos); 
                }
                $ativos = array_merge($ativos, $item->ativos);
            }

            if(in_array($retirada->status, [2, 4, 9])) {
                $logo_path = __DIR__ ."/../../../../assets/images/icon/logo.png";
                $type = pathinfo($logo_path, PATHINFO_EXTENSION);
                $logo_data = file_get_contents($logo_path, null, null);
                $logo_base64 = 'data:image/' . $type . ';base64,' . base64_encode($logo_data);

                $data = [
                    'retirada' => $retirada,
                    'ativos' => $ativos,
                    'logo' => $logo_base64,
                    'data_hora' => date('d/m/Y H:i:s', strtotime('now')),
                ];
                $filename = "termo_de_reponsabilidade_{$retirada->id_retirada}" . date('YmdHis', strtotime('now')).".pdf";
               //var_dump($logo_base64); exit;
                $html = $this->load->view("/../views/termo_de_reponsabilidade", $data, true);

                $mpdf = new \Mpdf\Mpdf();
                $mpdf->WriteHTML($html);
                $mpdf->Output($filename, 'D'); //D
                return;
            }
            $this->session->set_flashdata('msg_erro', "Retirada não liberada para impressão!");
            echo redirect(base_url("ferramental_estoque/detalhes/{$id_retirada}"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Retirada não encontrada!");
        echo redirect(base_url("ferramental_estoque"));
    }

}