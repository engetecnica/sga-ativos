<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author André Baill | https://www.github.com/srandrebaill
 */
class funcionario  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('funcionario_model');
        $this->load->model('obra/obra_model');     
        $this->model = $this->funcionario_model;
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
                                'text' => $row->id_funcionario,
                                'link' => base_url('funcionario')."/editar/{$row->id_funcionario}", 
                            ]);
                        }
                    ],
                    [
                        "name" => "nome_link",
                        "view" => "index_datatable/link",
                        "data" => function($row, $data) {
                            return  array_merge($data, [
                                'text' => $row->nome,
                                'link' => base_url('funcionario')."/editar/{$row->id_funcionario}", 
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

    function adicionar($data = null){

        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 3, 'adicionar'));

        $data['empresas'] = $this->get_empresas();
        $data['obras'] = $this->obra_model->get_obras();
        $data['estados'] = $this->get_estados(); 
        $data['estados'] = $this->get_estados();
    	$this->get_template('index_form', $data);
    }

    function editar($id_funcionario=null){

        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 3, 'editar'));


        $data['detalhes'] = $this->funcionario_model->get_funcionario($id_funcionario);
        $data['empresas'] = $this->get_empresas();
        $data['obras'] = $this->obra_model->get_obras();
        $data['estados'] = $this->get_estados(); 
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_funcionario'] = !is_null($this->input->post('id_funcionario')) ? $this->input->post('id_funcionario') : '';
        $data['id_empresa'] = $this->input->post('id_empresa');
        $data['id_obra'] = $this->input->post('id_obra');
        $data['matricula'] = $this->input->post('matricula'); // única
        $data['nome'] = $this->input->post('nome');
        $data['rg'] = $this->input->post('rg');
        $data['cpf'] = $this->input->post('cpf');
        $data['data_nascimento'] = $this->input->post('data_nascimento');
        $data['endereco'] = $this->input->post('endereco');
        $data['endereco_numero'] = $this->input->post('endereco_numero');
        $data['endereco_complemento'] = $this->input->post('endereco_complemento');
        $data['endereco_bairro'] = $this->input->post('endereco_bairro');
        $data['endereco_cep'] = $this->input->post('endereco_cep');
        $data['endereco_cidade'] = $this->input->post('endereco_cidade');
        $data['endereco_estado'] = $this->input->post('endereco_estado');
        $data['telefone'] = $this->input->post('telefone');
        $data['celular'] = $this->input->post('celular');
        $data['email'] = $this->input->post('email');
        $data['observacao'] = $this->input->post('observacao');
        $data['situacao'] = $this->input->post('situacao');

        $funcionario = $this->funcionario_exists($data);
        if(!$funcionario){
            $this->funcionario_model->salvar_formulario($data);
            if($data['id_funcionario'] == ''){
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");     
            }
            echo redirect(base_url("funcionario")); 
            return;
        }

        if($funcionario){
            $this->session->set_flashdata('msg_erro', "Dados registrados já existem na base de dados!");
            if($data['id_funcionario'] == ''){
                return $this->adicionar(['detalhes' => (object) $data]);
            } else {
                echo redirect(base_url("funcionario/editar/{$data['id_funcionario']}"));     
            }
        }
    }

    function deletar($id=null){
        $this->db->where('id_funcionario', $id);
        return $this->db->delete('funcionario');
    }

    function funcionario_exists($data = []){
        // $funcionario = $this->db
        //     ->where("(cpf='{$data['cpf']}' OR rg='{$data['rg']}') AND id_funcionario != {$data['id_funcionario']}")
        //     ->get('funcionario')->result();

        // if ($funcionario) {
        //     return $funcionario;
        // }
        return false;
    }

    function lista_fucionarios_json() {
        echo json_encode($this->funcionario_model->get_lista($this->user->id_empresa, $this->user->id_obra));
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */