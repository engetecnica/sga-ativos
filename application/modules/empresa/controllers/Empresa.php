<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author André Baill | https://www.github.com/srandrebaill
 */
class Empresa  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('empresa_model');   
        $this->model = $this->empresa_model;  
    }

    function index() {
        if ($this->input->method() === 'post')  {
            return $this->paginate_json([
                "templates" => [
                    [
                        "name" => "id_link",
                        "view" => "index_datatable/link",
                        "data" => function($row, $data) {
                            return  array_merge($data, [
                                'text' => $row->id_empresa,
                                'link' => base_url('empresa')."/editar/{$row->id_empresa}", 
                            ]);
                        }
                    ],
                    [
                        "name" => "razao_social_link",
                        "view" => "index_datatable/link",
                        "data" => function($row, $data) {
                            return  array_merge($data, [
                                'text' => $row->razao_social,
                                'link' => base_url('empresa')."/editar/{$row->id_empresa}", 
                            ]);
                        }
                    ],
                    [
                        "name" => "situacao_html",
                        "view" => "index_datatable/situacao"   
                    ],
                    [                       
                        "name" => "actions",
                        "view" => "index_datatable/actions"
                    ]
                ]
            ]);
        }

        $this->get_template('index');
    }

    function adicionar(){
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 4, 'adicionar'));
        $data['estados'] = $this->get_estados();
    	$this->get_template('index_form', $data);
    }

    function editar($id_empresa=null){
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 4, 'editar'));
        $data['detalhes'] = $this->empresa_model->get_empresa($id_empresa);
        $data['estados'] = $this->get_estados();
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_empresa'] = !is_null($this->input->post('id_empresa')) ? $this->input->post('id_empresa') : '';
        $data['razao_social'] = $this->input->post('razao_social');
        $data['nome_fantasia'] = $this->input->post('nome_fantasia');
        $data['cnpj'] = $this->input->post('cnpj');
        $data['inscricao_estadual'] = $this->input->post('inscricao_estadual');
        $data['inscricao_municipal'] = $this->input->post('inscricao_municipal');
        $data['endereco'] = $this->input->post('endereco');
        $data['endereco_numero'] = $this->input->post('endereco_numero');
        $data['endereco_complemento'] = $this->input->post('endereco_complemento');
        $data['endereco_bairro'] = $this->input->post('endereco_bairro');
        $data['endereco_cep'] = $this->input->post('endereco_cep');
        $data['endereco_cidade'] = $this->input->post('endereco_cidade');
        $data['endereco_estado'] = $this->input->post('endereco_estado');
        $data['responsavel'] = $this->input->post('responsavel');
        $data['responsavel_telefone'] = $this->input->post('responsavel_telefone');
        $data['responsavel_celular'] = $this->input->post('responsavel_celular');
        $data['responsavel_email'] = $this->input->post('responsavel_email');
        $data['observacao'] = $this->input->post('observacao');
        $data['situacao'] = $this->input->post('situacao');

        $this->empresa_model->salvar_formulario($data);
        if($data['id_empresa']==''){
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("empresa"));
    }

    function deletar($id=null){

        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 4, 'excluir'));



        $this->db->where('id_empresa', $id);
        return $this->db->delete('empresa');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */