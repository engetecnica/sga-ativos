<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author André Baill | https://www.github.com/srandrebaill
 */
class Obra  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('obra_model');
        $this->model = $this->obra_model;
        $this->base = $this->get_obra_base();
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
                                'text' => $row->id_obra,
                                'link' => base_url('obra')."/editar/{$row->id_obra}", 
                            ]);
                        }
                    ],
                    [
                        "name" => "codigo_obra_link",
                        "view" => "index_datatable/link",
                        "data" => function($row, $data) {
                            return  array_merge($data, [
                                'text' => $row->codigo_obra,
                                'link' => base_url('obra')."/editar/{$row->id_obra}", 
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

    protected function paginate_after(object &$row)
    {
        $row->obra_base_html = $row->obra_base == 1 ? 'SIM' : 'NÃO';
    }

    function adicionar(){
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 6, 'adicionar'));
        $data['empresas'] = $this->obra_model->get_empresas();
        $data['estados'] = $this->get_estados();
    	$this->get_template('index_form', $data);
    }

    function editar($id_obra=null){
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 6, 'editar'));
        $data['empresas'] = $this->obra_model->get_empresas();
        $data['detalhes'] = $this->obra_model->get_obra($id_obra);
        $data['estados'] = $this->get_estados();
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_obra'] = !is_null($this->input->post('id_obra')) ? $this->input->post('id_obra') : '';
        $data['id_empresa'] = !is_null($this->input->post('id_empresa')) ? $this->input->post('id_empresa') : null;
        $data['codigo_obra'] = $this->input->post('codigo_obra');
        $data['obra_razaosocial'] = $this->input->post('obra_razaosocial');
        $data['obra_cnpj'] = $this->input->post('obra_cnpj');
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
        $data['obra_base'] = $this->input->post('obra_base');

        $obra = null; 
        $codigo_obra_exists = $this->obra_model->obra_exists($data['codigo_obra']);
        if ($data['id_obra'] != '') {
            $obra = $this->obra_model->get_obra($data['id_obra']);
        }

        if (($obra == null && $codigo_obra_exists) || 
            $obra != null && ((strtolower($obra->codigo_obra) != strtolower($data['codigo_obra'])) && $codigo_obra_exists) ) {
            $this->session->set_flashdata('msg_erro', "Já existe uma obra com o código informado!");
            
            if ($obra == null) {
                echo redirect(base_url("obra/adicionar"));
                return;
            }
            echo redirect(base_url("obra/editar/{$obra->id_obra}"));
            return;
        }

        $this->obra_model->salvar_formulario($data);
        if($data['id_obra']==''){
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("obra"));
    }

    function deletar($id){
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 6, 'excluir'));
        return $this->db->where('id_obra', $id)->delete('obra');
    }
}
/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */