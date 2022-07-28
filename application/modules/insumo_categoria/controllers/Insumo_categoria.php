<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class Insumo_categoria  extends MY_Controller {

    public $tipo_medicao;

    function __construct() {
        parent::__construct();
        $this->load->model('insumo_categoria_model');

        $this->tipo_medicao = $this->insumo_categoria_model->tipo_medicao;
    }

    function index($subitem=null) {
        $data['lista'] = $this->insumo_categoria_model->get_lista();
    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar(){
        $data['lista_categoria'] = $this->insumo_categoria_model->get_categoria_lista(0);

        $data['tipo_medicao'] = $this->tipo_medicao;


        
        
    	$this->get_template('index_form', $data);
    }

    function editar($id_ativo_configuracao=null){
        $data['detalhes'] = $this->insumo_categoria_model->get_ativo_configuracao($id_ativo_configuracao);
        if(!$data['detalhes']->permit_edit || !$data['detalhes']->permit_delete) {
            $this->session->set_flashdata('msg_erro', "Item não pode ser editado ou excluido!");
            echo redirect(base_url("ativo_configuracao"));
            return;
        }

        $data['lista_categoria'] = $this->insumo_categoria_model->get_categoria_lista(0);
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_ativo_configuracao'] = !is_null($this->input->post('id_ativo_configuracao')) ? $this->input->post('id_ativo_configuracao') : '';
        $data['id_ativo_configuracao_vinculo'] = $this->input->post('id_ativo_configuracao_vinculo');
        $data['titulo'] = ucwords($this->input->post('titulo'));
        $data['situacao'] = $this->input->post('situacao');
        $data['slug'] = strtolower($this->input->post('slug'));

        if($data['id_ativo_configuracao'] == ''){
            $data['permit_edit'] = $data['permit_delete'] = "1";
        }

        $configuracao = $this->insumo_categoria_model->get_ativo_configuracao($data['id_ativo_configuracao']);
        $vinculo = $this->insumo_categoria_model->get_ativo_configuracao($data['id_ativo_configuracao_vinculo']);
     
        if ($vinculo) {
            if ($configuracao && $vinculo->slug == "categoria-ferramenta") {
                $this->db->where("nome", $configuracao->titulo)
                        ->update('ativo_externo_categoria', ["nome" =>  ucwords($data['titulo'])]);
            }

            if (!$configuracao && $vinculo->slug == "categoria-ferramenta") {
                if ($this->db->where('nome', ucwords($data['titulo']))->get('ativo_externo_categoria')->num_rows() > 0) {
                    $this->session->set_flashdata('msg_erro', "Já existe um item com o mesmo nome!");
                    echo redirect(base_url("ativo_configuracao/adicionar"));
                    return;
                }
                $this->db->insert('ativo_externo_categoria', ["nome" =>  ucwords($data['titulo'])]);
            }
        }

        $this->insumo_categoria_model->salvar_formulario($data);
        if($data['id_ativo_configuracao'] == ''){
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("ativo_configuracao"));
    }

    function deletar($id=null){
        $configuracao = $this->insumo_categoria_model->get_ativo_configuracao($id);
        if ($this->db->where('nome', $configuracao->titulo)->get('ativo_externo_categoria')->num_rows() > 0) {
            $this->db->where("nome", $configuracao->titulo)->delete('ativo_externo_categoria');
        }        

        return $this->db->where('id_ativo_configuracao', $id)->delete('ativo_configuracao');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */