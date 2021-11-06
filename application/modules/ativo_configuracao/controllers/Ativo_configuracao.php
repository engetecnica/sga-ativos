<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class Ativo_configuracao  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ativo_configuracao_model');
    }

    function index($subitem=null) {
        $data['lista'] = $this->ativo_configuracao_model->get_lista();
    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar(){
        $data['lista_categoria'] = $this->ativo_configuracao_model->get_categoria_lista(0);
    	$this->get_template('index_form', $data);
    }

    function editar($id_ativo_configuracao=null){
        $data['detalhes'] = $this->ativo_configuracao_model->get_ativo_configuracao($id_ativo_configuracao);
        $data['lista_categoria'] = $this->ativo_configuracao_model->get_categoria_lista(0);
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_ativo_configuracao'] = !is_null($this->input->post('id_ativo_configuracao')) ? $this->input->post('id_ativo_configuracao') : '';
        $data['id_ativo_configuracao_vinculo'] = $this->input->post('id_ativo_configuracao_vinculo');
        $data['titulo'] = $this->input->post('titulo');
        $data['situacao'] = $this->input->post('situacao');

        $configuracao = $this->ativo_configuracao_model->get_ativo_configuracao($data['id_ativo_configuracao']);
        $vinculo = $this->ativo_configuracao_model->get_ativo_configuracao($data['id_ativo_configuracao_vinculo']);
     
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

        $data['titulo'] = ucwords($data['titulo']);
        $this->ativo_configuracao_model->salvar_formulario($data);

        if($data['id_ativo_configuracao']==''){
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("ativo_configuracao"));
    }

    function deletar($id=null){
        $configuracao = $this->ativo_configuracao_model->get_ativo_configuracao($id);
        if ($this->db->where('nome', $configuracao->titulo)->get('ativo_externo_categoria')->num_rows() > 0) {
            $this->db->where("nome", $configuracao->titulo)->delete('ativo_externo_categoria');
        }        

        return $this->db->where('id_ativo_configuracao', $id)->delete('ativo_configuracao');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */