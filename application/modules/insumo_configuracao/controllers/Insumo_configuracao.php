<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author André Baill | https://www.github.com/srandrebaill
 */
class Insumo_configuracao  extends MY_Controller {

    public $tipo_medicao;

    function __construct() {
        parent::__construct();
        $this->load->model('insumo_configuracao_model');
    }

    function index($subitem=null) {

        echo "Vamos parar aqui.";




        return false;
        $data['lista'] = $this->insumo_configuracao_model->get_lista();
        $data['lista_principal'] = $this->insumo_configuracao_model->get_lista_principal();
        $subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar(){
        $data['lista_categoria'] = $this->insumo_configuracao_model->get_categoria_lista(0);
        $data['tipo_medicao'] = $this->insumo_configuracao_model->get_tipo_medicao(null);
        $this->get_template('index_form', $data);
    }

    function editar($id_insumo_configuracao=null){
        $data['detalhes'] = $this->insumo_configuracao_model->get_insumo_configuracao($id_insumo_configuracao);

        $data['tipo_medicao'] = $this->insumo_configuracao_model->get_tipo_medicao(null);

        if(!$data['detalhes']->permit_edit || !$data['detalhes']->permit_delete) {
            $this->session->set_flashdata('msg_erro', "Item não pode ser editado ou excluido!");
            echo redirect(base_url("insumo_configuracao"));
            return;
        }

        $data['lista_categoria'] = $this->insumo_configuracao_model->get_categoria_lista(0);
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_insumo_configuracao'] = !is_null($this->input->post('id_insumo_configuracao')) ? $this->input->post('id_insumo_configuracao') : '';
        $data['id_insumo_configuracao_vinculo'] = $this->input->post('id_insumo_configuracao_vinculo');
        $data['titulo'] = ucwords($this->input->post('titulo'));
        $data['medicao'] = $this->input->post('medicao');
        $data['situacao'] = $this->input->post('situacao');
        $data['slug'] = strtolower($this->input->post('slug'));
        $data['codigo_insumo'] = $this->input->post('cod_insumo');

        if($data['id_insumo_configuracao'] == ''){
            $data['permit_edit'] = $data['permit_delete'] = "1";
        }

        // $configuracao = $this->insumo_configuracao_model->get_insumo_configuracao($data['id_insumo_configuracao']);
        // $vinculo = $this->insumo_configuracao_model->get_insumo_configuracao($data['id_insumo_configuracao_vinculo']);
     
        // if ($vinculo) {
        //     if ($configuracao && $vinculo->slug == "categoria-ferramenta") {
        //         $this->db->where("nome", $configuracao->titulo)
        //                 ->update('insumo_externo_categoria', ["nome" =>  ucwords($data['titulo'])]);
        //     }

        //     if (!$configuracao && $vinculo->slug == "categoria-ferramenta") {
        //         if ($this->db->where('nome', ucwords($data['titulo']))->get('insumo_externo_categoria')->num_rows() > 0) {
        //             $this->session->set_flashdata('msg_erro', "Já existe um item com o mesmo nome!");
        //             echo redirect(base_url("insumo_configuracao/adicionar"));
        //             return;
        //         }
        //         $this->db->insert('insumo_externo_categoria', ["nome" =>  ucwords($data['titulo'])]);
        //     }
        // }

        $this->insumo_configuracao_model->salvar_formulario($data);
        if($data['id_insumo_configuracao'] == ''){
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("insumo_configuracao"));
    }

    function deletar($id=null){
        $configuracao = $this->insumo_configuracao_model->get_insumo_configuracao($id);
        if ($this->db->where('nome', $configuracao->titulo)->get('insumo_externo_categoria')->num_rows() > 0) {
            $this->db->where("nome", $configuracao->titulo)->delete('insumo_externo_categoria');
        }        

        return $this->db->where('id_insumo_configuracao', $id)->delete('insumo_configuracao');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */