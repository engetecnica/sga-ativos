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
        # Fecha Login        
    }


    # Listagem de Itens
    function index($subitem=null) {
    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem);
    }

    # Criar uma nova Requisição
    function adicionar(){
        $data['ativo_externo'] = $this->ferramental_requisicao_model->get_ativo_externo_lista();
    	$this->get_template('index_form', $data);
    }

    # Grava Requisição
    function salvar(){

        # Dados
        $data['id_obra'] = $this->session->userdata('logado')->id_obra;
        $data['id_usuario'] = $this->session->userdata('logado')->id_usuario;
        $data['status'] = '1'; # Pendente
        $id_requisicao = $this->ferramental_requisicao_model->salvar_formulario($data);

        $dados = array();
        foreach($_POST['quantidade'] as $k=>$item){
            if($_POST['quantidade']>0){
                $dados[$k] = array();
                $dados[$k]['quantidade'] = $_POST['quantidade'][$k];
                $dados[$k]['id_ativo_externo'] = $_POST['id_ativo_externo'][$k];
                $dados[$k]['id_requisicao'] = $id_requisicao;
                $dados[$k]['status'] = '1';
            }
        }

        $this->db->insert_batch("ativo_externo_requisicao_item", $dados);


        # Retorno
        $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
        echo redirect(base_url("ferramental_requisicao"));

    }

    # Lista de Itens
    public function getlistagem()
    {
        return $this->ferramental_requisicao_model->get_lista();
    }

    public function acao($tipo=null)
    {
        $this->load->view('item_transferir');
    }


    function detalhes($id_requisicao=null, $item=null, $id_requisicao_item=null)
    {

        if(!$id_requisicao)
        {
            $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
            echo redirect(base_url('ferramental_requisicao')); 
        } 
        else 
        {
            
            if($item==null && $id_requisicao_item==null)
            {


                # Detalhes da Requisição
                $data['requisicao_liberada'] = $this->ferramental_requisicao_model->get_requisicao_item($id_requisicao);
                $data['requisicao'] = $this->ferramental_requisicao_model->get_requisicao($id_requisicao);
                

                #echo "<pre>";
                #print_r($data);
                #die();


                if(!$data['requisicao'])
                    {
                        $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
                        echo redirect(base_url('ferramental_requisicao')); 
                    }

                if($this->session->userdata('logado')->nivel==1)
                {
                    $this->get_template('requisicao_detalhes_adm', $data);
                } 
                else 
                {
                    $this->get_template('requisicao_detalhes_user', $data);
                }


            }
            


        }

    }

    # Liberar Requisição
    public function liberar_requisicao()
    {

        $dados = array();
        $erro = 0;
        foreach($_POST['quantidade'] as $k=>$quantidade)
        {

            if($_POST['quantidade'][$k]>0)
            {
                $item[$k]['quantidade_solicitada']      = $_POST['quantidade_solicitada'][$k];
                $item[$k]['quantidade']                 = $_POST['quantidade'][$k];
                $item[$k]['item']                       = $_POST['item'][$k];
                $item[$k]['id_requisicao']              = $_POST['id_requisicao'];
                $item[$k]['id_obra']                    = $_POST['id_obra'];
            }

            # Retorno Itens por quantidade // liberados
            $items = $this->db
                        ->select('
                                c1.id_ativo_externo, 
                                c1.nome, 
                                c1.codigo, 
                                c1.situacao, 
                                c2.codigo_obra, 
                                c2.endereco
                        ')
                        ->join('obra as c2', 'c2.id_obra=c1.id_obra')
                        ->where('c1.situacao', '12')
                        ->limit($item[$k]['quantidade'])
                        ->get('ativo_externo as c1')
                        ->result();

            # Liberação dos itens
            foreach($items as $valor)
            {
                $dados['id_ativo_externo_requisicao_item']  = $item[$k]['id_requisicao'];
                $dados['id_obra']                           = $item[$k]['id_obra'];
                #$dados['condicao']                          = "Em Operação";
                $dados['situacao']                          = 2; # Liberado

               // $this->db
                       // ->where('id_ativo_externo', $valor->id_ativo_externo)
                       // ->update('ativo_externo', $dados);
            }

            
            # Verificação de quantidade para saber se foi liberado integralmente ou parcialmente
            if($item[$k]['quantidade_solicitada'] == $item[$k]['quantidade'])
            {
                $status_requisicao = '2'; // Liberado
                $mensagem = "Requisição Liberada";
            }
            elseif($item[$k]['quantidade'] < $item[$k]['quantidade_solicitada']) 
            {
                $status_requisicao = '11'; // parcialmente
                $mensagem = "Requisição Liberada Parcialmente";
            }
            else 
            {
                $erro = 1;
                $mensagem = "Erro. Não foi possível efetuar a liberação. REF ER761";
            }


            # Atualiza Item Requisição (onde pode pedir vários itens)
            $atex['quantidade_liberada']    = $item[$k]['quantidade'];
            $atex['status']                 = 2;
            $this->db->where('id_requisicao', $this->input->post('id_requisicao'));
            $this->db->update('ativo_externo_requisicao_item', $atex);

            
        }


        echo "<pre>";
        print_r($items);
        print_r($dados);
        print_r($_POST);
        print_r($item);
        die();

        if(!$item)
        {
            $erro = 1;
            $mensagem = "Houve um erro ao tentar salvar esssa informação.";
        }

        # Atualiza requisição completa / parcial
        $req_status['status'] = $status_requisicao;
        $this->db->where('id_requisicao', $this->input->post('id_requisicao'));
        $this->db->update('ativo_externo_requisicao', $req_status);


        if($erro==1) $tipo = "msg_erro"; else $tipo = "msg_retorno"; 
        $this->session->set_flashdata($tipo, $mensagem);
        echo redirect(base_url('ferramental_requisicao'));         
    }

    function deletar($id=null){
        $this->db->where('id_ferramental_requisicao', $id);
        return $this->db->delete('ferramental_requisicao');
    }

    public function manual($id_requisicao=null)
    {

        if(!$id_requisicao)
        {
            $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
            echo redirect(base_url('ferramental_requisicao')); 
        } 
        else 
        {
            $data['requisicao_manual'] = $this->ferramental_requisicao_model->get_requisicao_manual($id_requisicao);
            $this->get_template('requisicao_manual', $data);
        }

        #echo "<pre>";
        #print_r($data);
        #echo "</pre>";

        #echo $id_requisicao;
        #die();
    }

    public function aceite_manual(){

        //echo "<pre>";
        //print_r($this->input->post());

        //die();


        foreach($_POST['id_ativo_externo'] as $key=>$valor)
        {
            $item[$key]['id_ativo_externo']     = $_POST['id_ativo_externo'][$key];
            $item[$key]['observacoes']          = $_POST['observacoes'][$key];
            $item[$key]['status']               = $_POST['status'][$key];
            $item[$key]['id_usuario_aceite']    = $this->session->userdata('logado')->id_usuario;
            $item[$key]['id_obra']              = $this->session->userdata('logado')->id_obra;
        }


        /* @ atualiza a tabela ativo_externo [condicao, situacao]
         * @ cria um registro na tabela ativo_externo_obra 
         * @ [id_ativo_externo, id_usuario_aceite, id_obra, observacoes, status]
         */

        foreach($item as $valor)
        {
            $atualiza_item['condicao'] = "Recebido";
            $atualiza_item['situacao'] = 4;

            $this->db->where('id_ativo_externo', $valor->id_ativo_externo);
            $this->db->where('id_obra', $valor->id_obra);
            $this->db->update('ativo_externo', $atualiza_item);
        }


        $this->db->insert_batch('ativo_externo_obra', $item);

        $this->session->set_flashdata('msg_retorno', "Você confirmou o recebimento dos itens relacionados.");
        echo redirect(base_url('ferramental_requisicao/manual/'.$this->input->post('id_requisicao')));         

        print_r($item);
    }

}

/* End of file ferramental_requisicao.php */
/* Location: ./application/modules/ferramental_requisicao/controllers/ferramental_requisicao.php */