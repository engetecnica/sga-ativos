<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ferramental_requisicao
 *
 * @author https://www.roytuts.com
 */
class Ferramental_requisicao  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ferramental_requisicao_model');
    
        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        }
        $this->load->model('ativo_externo/ativo_externo_model');         
    }


    # Listagem de Itens
    function index($subitem=null) {
        $this->get_template('index', [
            'lista' => $this->ferramental_requisicao_model->get_lista_requisicao(),
            'status_lista' => $this->ferramental_requisicao_model->get_requisicao_status(),
            'user' => $this->user
        ]);
    }

    # Criar uma nova Requisição
    function adicionar() {
        $this->get_template('index_form',[
            'grupos' => $this->ativo_externo_model->get_lista_grupo()
        ]);
    }

    # Grava Requisição
    function salvar(){
        # Dados
        $data['id_obra'] = $this->user->id_obra;
        $data['id_usuario'] = $this->user->id_usuario;
        $data['status'] = 1; # Pendente
        $id_requisicao = $this->ferramental_requisicao_model->salvar_formulario($data);

        $dados = array();
        if ($_POST['quantidade'] > 0) {
            foreach($_POST['quantidade'] as $k=>$item){
                var_dump($k);
                $dados[$k] = array();
                $dados[$k]['quantidade'] = $_POST['quantidade'][$k];
                $dados[$k]['id_ativo_externo_grupo'] = $_POST['id_ativo_externo_grupo'][$k];
                $dados[$k]['id_requisicao'] = $id_requisicao;
                $dados[$k]['status'] = 1;
            }
        }

        $this->db->insert_batch("ativo_externo_requisicao_item", $dados);
        $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        echo redirect(base_url("ferramental_requisicao"));
    }

    public function acao($tipo=null)
    {
        $this->load->view('item_transferir');
    }

    function detalhes($id_requisicao=null)
    {
        $requisicao = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao);
        
        if(!$requisicao){
            $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
            echo redirect(base_url('ferramental_requisicao'));
            return; 
        }
        
        //$this->ferramental_requisicao_model->atualiza_status($id_requisicao);	
        
        $requisicao->status_lista = $this->ferramental_requisicao_model->get_requisicao_status();
        if ($this->user->nivel == 1) {
            $this->get_template('requisicao_detalhes_adm', ['requisicao' => $requisicao]);
        } else {
            $this->get_template('requisicao_detalhes_user', ['requisicao' => $requisicao]);
        }
    }


    public function detalhes_item($id_requisicao, $id_requisicao_item = null){
        $requisicao = (object)[];
        $requisicao->item = $this->ferramental_requisicao_model->get_requisicao_item($id_requisicao, $id_requisicao_item);
        $id_grupo = isset($requisicao->item) ? $requisicao->item->id_ativo_externo_grupo : null;
        $requisicao_all = $this->ferramental_requisicao_model->get_requisicao_com_items($id_requisicao, $id_grupo);
        $requisicao = (object) array_merge((array) $requisicao, (array) $requisicao_all);

        $ativos = [];
        if ($requisicao->item) {
            $ativos = $requisicao->item->ativos;
        } else {
            foreach($requisicao->items as $item) {
                $ativos = array_merge($ativos, $item->ativos);
            }
        }
        $requisicao->ativos = $ativos;
        $requisicao->status_lista = $this->ferramental_requisicao_model->get_requisicao_status();

        if ($requisicao) {
            $this->get_template('requisicao_manual', ['requisicao' => $requisicao, 'no_aceite' => true]);
            return;
        }
        $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
        echo redirect(base_url('ferramental_requisicao')); 
    }

    public function liberar_requisicao($id_requisicao=null, $id_requisicao_item=null)
    {
       $requisicao = $this->ferramental_requisicao_model->get_requisicao($this->input->post('id_requisicao'), $this->user);
       $obra_base = $this->get_obra_base(); 
       $items = $this->input->post('item');
       $quantidade = $this->input->post('quantidade');
       $quantidade_solicitada = $this->input->post('quantidade_solicitada');
      
        if ($requisicao) {
            $total_quantidade = 0;
            $total_quantidade_liberada = 0;
            $ativos_externos_requisicao_items = [];
            $ativos_externos = [];
          
            for ($i=0; $i < count($items); $i++) { 
                $item = $this->ferramental_requisicao_model
                        ->get_requisicao_item($requisicao->id_requisicao, $items[$i]);

                if ($item) {
                    $ativos = $this->ativo_externo_model
                                 ->get_estoque($obra_base->id_obra, $item->id_ativo_externo_grupo, true);
                    
                    $total_liberar = (count($ativos) > (int) $quantidade[$i]) ? $quantidade[$i] : count($ativos);
                    $total_quantidade += $item->quantidade;
       
                    for ($k=0; $k < $total_liberar; $k++) {
                        $ativos_externos[] = [
                            'id_ativo_externo' => $ativos[$k]->id_ativo_externo,
                            'id_requisicao_item' => $item->id_requisicao_item,
                            'data_liberacao' => date('Y-m-d H:i:s', strtotime('now')),
                            'situacao' => 2,
                        ];

                        if ($ativos[$k]->tipo == 1) {
                            $this->liberar_kit($ativos[$k], $ativos_externos);
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

                    $ativos_externos_requisicao_items[] = [
                        'id_requisicao_item' => $item->id_requisicao_item,
                        'id_requisicao' => $item->id_requisicao,
                        'status' => $item->status,
                        'data_liberado' => date('Y-m-d H:i:s', strtotime('now')),
                        'quantidade_liberada' => $item->quantidade_liberada
                    ];
                }
            }
    
            if (!empty($ativos_externos) && !empty($ativos_externos_requisicao_items)) {
                if ($total_quantidade == $total_quantidade_liberada) {
                    $requisicao->status = 2;
                    $this->session->set_flashdata('msg_success', "Requisição Liberada com Sucesso!");
                } else { 
                    $requisicao->status = 11;
                    $this->session->set_flashdata('msg_info', "Requisição Liberada com Pedência!");
                }

                $this->db->update_batch("ativo_externo", $ativos_externos, 'id_ativo_externo');
                $this->db->update_batch("ativo_externo_requisicao_item", $ativos_externos_requisicao_items, 'id_requisicao_item');
            
                $this->ferramental_requisicao_model->salvar_formulario([
                    'id_requisicao' => $requisicao->id_requisicao,
                    'id_obra' => $requisicao->id_obra,
                    'id_usuario' => $requisicao->id_usuario,
                    'status' => $requisicao->status,
                    'data_atualizacao' => date('Y-m-d H:i:s', strtotime('now'))
                ]);
                echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
                return;
            }

            $this->session->set_flashdata('msg_success', "Nenhum item liberado!");
            echo redirect(base_url("ferramental_requisicao/detalhes/{$requisicao->id_requisicao}"));
            return;
        } 

        $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
        echo redirect(base_url('ferramental_requisicao'));
        return;
    }


    function liberar_kit($ativo, array &$dados){
        $items = $this->ativo_externo_model->get_kit_items($ativo->id_ativo_externo);

        foreach($items as $item) {
            $dados[] = [
                'situacao' => $ativo->situacao,
                'id_ativo_externo' => $item->id_ativo_externo,
                'id_requisicao_item' => $ativo->id_requisicao_item,
                'data_liberacao' => date('Y-m-d H:i:s', strtotime('now')),
            ];

            if ($item->tipo == 1) {
                $this->liberar_kit($item, $dados);
            }
        }
    }

    function deletar($id_requisicao=null) {
        $msg = "Requisição não localizada.";
        $requisicao =  $this->ferramental_requisicao_model->get_requisicao($id_requisicao);
        
        if ($requisicao && $this->input->method() == 'post') {
            $msg = "Requisição não excluida. 
                A requisição aser excluida deve ter o status como: 'Pendente', 
                'Liberada' ou 'Liberada parcialmente'";

            if ($requisicao->status == 1) {
                $msg = null;
                $items = $this->db->from('ativo_externo_requisicao_item')
                        ->where("id_requisicao = {$id_requisicao}")
                        ->get()
                        ->result();

                $items_ids = array_map(
                    function ($item) {
                        return $item->id_requisicao_item;
                    },
                    $items
                );

                $ativos = $this->db->from('ativo_externo')
                        ->where("id_requisicao_item IN (".implode(',', $items_ids).")")
                        ->get()
                        ->result();

                $ativos_update = array_map(
                    function ($ativo) {
                        return [
                            'id_ativo_externo' => $ativo->id_ativo_externo,
                            'id_requisicao_item' => null,
                            'situacao' => 12
                        ];
                    },
                    $ativos
                );        

        
                if (!empty($ativos_update)) {
                    $this->db->update_batch('ativo_externo', $ativos_update, 'id_ativo_externo');       
                }
                $this->db->where('id_requisicao', $id_requisicao)->delete('ativo_externo_requisicao_item');
                $this->db->where('id_requisicao', $id_requisicao)->delete('ativo_externo_requisicao');
            }
        }

        if ($msg) {
            $this->session->set_flashdata('msg_erro', $msg);
        }
        echo redirect(base_url('ferramental_requisicao'));
        return;
    }

    public function manual($id_requisicao, $id_requisicao_item)
    {
        $requisicao = $this->ferramental_requisicao_model->get_requisicao($id_requisicao, $this->user);
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

    public function aceite_manual($id_requisicao, $id_requisicao_item) {
        $retorno = $this->ferramental_requisicao_model->aceite_items_requisicao($id_requisicao, $id_requisicao_item, $_POST['status'], $_POST['id_ativo_externo']);
        if ($retorno) {
            $this->session->set_flashdata('msg_success', "Você confirmou o recebimento dos items relacionados.");
            echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));  
            return;
        }
        $this->aceite_erro($this->input->post('id_requisicao'));
    }

    public function aceitar_tudo($id_requisicao, $id_requisicao_item){
        $requisicao_item = $this->ferramental_requisicao_model->get_requisicao_item($id_requisicao, $id_requisicao_item);
        
        if ($requisicao_item) {
            $items = array_map(
                function ($item) {
                    return $item->id_ativo_externo;
                },
                $requisicao_item->ativos
            );
            
            $retorno = $this->ferramental_requisicao_model->aceite_items_requisicao($id_requisicao, $id_requisicao_item, 4, $items);
            if ($retorno) {
                $this->db->where("id_requisicao_item", $id_requisicao_item)
                        ->update("ativo_externo_requisicao_item", ['status' => 4]);
                $this->session->set_flashdata('msg_success', "Você confirmou o recebimento dos items relacionados.");       
                return;
            }
            return $this->session->set_flashdata('msg_erro', "Ocorreu um erro ao tentar receber os items relacionados.");
        }
        return $this->aceite_erro($id_requisicao);
    }

    public function devolver_tudo($id_requisicao, $id_requisicao_item){
        $requisicao_item = $this->ferramental_requisicao_model->get_requisicao_item($id_requisicao, $id_requisicao_item);
        
        if ($requisicao_item) {
            $items = array_map(
                function ($item) {
                    return $item->id_ativo_externo;
                },
                $requisicao_item->ativos
            );
            
            $retorno = $this->ferramental_requisicao_model->aceite_items_requisicao($id_requisicao, $id_requisicao_item, 9, $items);
            if ($retorno) {
                $this->db->where("id_requisicao_item", $id_requisicao_item)
                        ->update("ativo_externo_requisicao_item", ['status' => 9]);
                $this->session->set_flashdata('msg_success', "Você confirmou a devolução dos items relacionados.");       
                return;
            }
            return $this->session->set_flashdata('msg_erro', "Ocorreu um erro ao tentar devolver os items relacionados.");
        }
        return $this->aceite_erro($id_requisicao);
    }

    public function aceite_erro($id_requisicao){
        $this->session->set_flashdata('msg_erro', "Item não existe na requisição!");
        echo redirect(base_url("ferramental_requisicao/detalhes/{$id_requisicao}"));         
    }
}

/* End of file ferramental_requisicao.php */
/* Location: ./application/modules/ferramental_requisicao/controllers/ferramental_requisicao.php */