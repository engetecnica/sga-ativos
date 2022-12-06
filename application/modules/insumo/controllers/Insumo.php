<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of insumo
 *
 * @author https://github.com/srandrebaill
 */
class Insumo extends MY_Controller {

    function __construct() {
        parent::__construct();
        if ($this->is_auth()) {
            $this->load->model('insumo_model');
            $this->load->model('insumo_configuracao/insumo_configuracao_model');
            $this->load->model('fornecedor/fornecedor_model');
            $this->load->model('funcionario/funcionario_model');
        }
    }

    function index()
    {
        $data['insumos'] = $this->listar_insumos();    
        $this->get_template('index', $data);
    }



    /*
        Adicionar Insumo
    */
    public function adicionar()
    {
        $data['tipo_insumo'] = $this->insumo_configuracao_model->get_insumo_lista_completa();
        $data['fornecedor'] = $this->fornecedor_model->get_lista();
        $this->get_template('index_form', $data);
    }

    public function salvar(){

        $data['id_insumo'] = !is_null($this->input->post('id_insumo')) ? $this->input->post('id_insumo') : '';
        $data['id_insumo_configuracao'] = $this->input->post('tipo_insumo');
        $data['id_fornecedor'] = $this->input->post('fornecedor');
        $data['id_obra'] = ($this->user->id_obra) ?? null;
        $data['titulo'] = $this->input->post('titulo');
        $data['codigo_insumo'] = $this->input->post('cod_insumo');
        $data['descricao'] = $this->input->post('descricao_insumo');
        $data['situacao'] = $this->input->post('situacao');

        $id_insumo = $this->insumo_model->salvar_formulario($data);

        if($id_insumo)
        {
            $insumo_estoque['id_insumo']    = $id_insumo;
            $insumo_estoque['id_usuario']    = $this->user->id_usuario;
            $insumo_estoque['quantidade']   = $this->input->post('quantidade');
            $insumo_estoque['valor']        = $this->formata_moeda_float($this->input->post('valor'));
            $insumo_estoque['tipo']         = 'entrada';

            $this->insumo_model->salvar_insumo_estoque($insumo_estoque);
        }

        if ($data['id_insumo'] == '') {
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso");
        }else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso");
        }
        echo redirect(base_url("insumo"));
    }

    public function listar_insumos($id_obra)
    {
        return $this->insumo_model->get_insumos_by_obra($id_obra); 
    }

    function editar($id_insumo=null){

        $id_insumo = ($id_insumo) ?? 0;    

        if(!$this->insumo_model->get_insumo($id_insumo)){
            $this->session->set_flashdata('msg_erro', "Insumo não encontrado.");
            echo redirect(base_url("insumo"));
        }

        $data['tipo_insumo'] = $this->insumo_configuracao_model->get_insumo_lista_completa();
        $data['fornecedor'] = $this->fornecedor_model->get_lista();
        $data['detalhes'] = $this->insumo_model->get_insumo($id_insumo);
        $this->get_template('index_form', $data);
    }

    function deletar($id=null){

        // Salvar LOG
        $data['id_insumo'] = $id;
		$this->salvar_log(23, $id, 'deletar', $data);

        $this->db->where('id_insumo', $id);
        return $this->db->delete('insumo');

    }


    public function pesquisar_insumo_by_codigo(){
        echo $this->db->where('codigo_insumo', $this->input->post('cod_insumo'))->get('insumo')->num_rows();
    }

    public function salvar_estoque()
    {
        if($this->insumo_model->get_insumo($this->input->post('id_insumo'))){

            /* Salvar Estoque */
            $estoque['id_insumo'] = $this->input->post('id_insumo');
            $estoque['quantidade'] = $this->input->post('item-quantidade');
            $estoque['valor'] = $this->formata_moeda_float($this->input->post('item-valor-unitario'));
            $estoque['created_at'] = $this->input->post('item-data');
            $estoque['tipo'] = 'entrada';
            if($this->db->insert('insumo_estoque', $estoque)){

                /* LOG */
                $this->salvar_log(23, $this->input->post('id_insumo'), 'adicionar_estoque', $this->input->post());

                $this->session->set_flashdata('msg_success', "Estoque Atualizado com sucesso!");
                echo redirect(base_url('insumo'));
            }


            /* LOG */
            $this->salvar_log(23, $this->input->post('id_insumo'), 'erro_estoque', $this->input->post());

            $this->session->set_flashdata('msg_success', "Erro ao atualizar estoque.");
            echo redirect(base_url('insumo'));

        }
    }

    // Retiradas
    public function retirada()
    {
        $data['retirada'] = $this->insumo_model->get_retirada_lista(); 
        $this->get_template('index_retirada', $data);   
    }


    public function retirada_adicionar(){
        $data['funcionario'] = $this->funcionario_model->get_lista(null, $this->user->id_obra);
        $data['insumo'] = $this->insumo_model->get_todos_insumos();
        $this->get_template('index_form_retirada', $data);   
    }

    public function retirada_salvar(){

        if($this->input->post('quantidade')){

            $existe = 0;
            $insumo = [];
            $i = 0;
            foreach($this->input->post('quantidade') as $id_insumo=>$qtde)
            {

               // echo $id_insumo;
                if($qtde > 0){
                    $existe = 1;

                    $insumo[$i]['id_insumo'] = $id_insumo;
                    $insumo[$i]['id_usuario'] = $this->user->id_usuario;
                    $insumo[$i]['quantidade'] = $qtde;
                    $insumo[$i]['valor'] = "0.00";
                    $insumo[$i]['tipo'] = 'saida';

                    $i++;
                }

            }

            if($insumo){

                // registrar retirada
                $retirada['id_usuario'] = $this->user->id_usuario;
                $retirada['id_funcionario'] = $this->input->post('id_funcionario');
                $retirada['status'] = 1;

                $id_insumo_retirada = $this->insumo_model->salvar_insumo_retirada($retirada);

                $a = 0;
                foreach($insumo as $ins){
                    $insumo_adicionar[$a]['id_insumo'] = $ins['id_insumo'];
                    $insumo_adicionar[$a]['id_insumo_retirada '] = $id_insumo_retirada;
                    $insumo_adicionar[$a]['id_usuario'] = $this->user->id_usuario;
                    $insumo_adicionar[$a]['quantidade'] = $ins['quantidade'];
                    $insumo_adicionar[$a]['valor'] = "0.00";
                    $insumo_adicionar[$a]['tipo'] = 'saida';

                    $a++;
                }

                $this->insumo_model->salvar_insumo_estoque_batch($insumo_adicionar);
            }

          
            /* Verifica se um dos itens foi adicionado para salvar */
            if($existe == 1){
                $this->session->set_flashdata('msg_success', "Nova retirada registrada com sucesso!");
                echo redirect(base_url('insumo/retirada'));
            } else {
                $this->session->set_flashdata('msg_erro', "Erro ao salvar nova retirada. Você precisa incluir ao menos um item.");
                echo redirect(base_url('insumo/retirada/adicionar'));
            }

        } 

        



        $this->dd($this->input->post());
    }


    public function retirada_cancelar($id){
        echo $id;
    }


    public function retirada_entregar($id=null){

        if(!$id){
            $this->session->set_flashdata('msg_success', "Erro ao atualizar retirada.");
            echo redirect(base_url('insumo/retirada'));
        }
        echo $id;
    }
}