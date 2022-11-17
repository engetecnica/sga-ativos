<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author André Baill | https://github.com/srandrebaill
 */
class usuario  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('usuario_model');
        $this->load->model('obra/obra_model');
        $this->load->model('relatorio/relatorio_model');   
        $this->model = $this->usuario_model;  
    }

    function index() {
        if ($this->input->method() === 'post') {
            return $this->paginate_json(
                [
                    "templates" => [
                        [
                            "name" => "id_link",
                            "view" => "index_datatable/link",
                            "data" => function($row, $data) {
                                return  array_merge($data, [
                                    'text' => $row->id_usuario,
                                    'link' => base_url('usuario')."/editar/{$row->id_usuario}", 
                                ]);
                            }
                        ],
                        [
                            "name" => "nome_link",
                            "view" => "index_datatable/link",
                            "data" => function($row, $data) {
                                return  array_merge($data, [
                                    'text' => $row->nome,
                                    'link' => base_url('usuario')."/editar/{$row->id_usuario}", 
                                ]);
                            }
                        ],
                        [
                            "name" => "nivel_html",
                            "view" => "index_datatable/nivel"
                        ],
                        [
                            "name" => "situacao_html",
                            "view" => "index_datatable/situacao"
                        ], 
                        [
                            "name" => "email_confirmado_em_html",
                            "view" => "index_datatable/confirm_email"
                        ],
                        [
                            "name" => "permit_notification_email_html",
                            "view" => "index_datatable/notfications_email"
                        ],
                        [
                            "name" => "permit_notification_push_html",
                            "view" => "index_datatable/notfications_push"
                        ],
                        [
                            "name" => "avatar",
                            "view" => "index_datatable/avatar"   
                        ],
                        [                       
                            "name" => "actions",
                            "view" => "index_datatable/actions"
                        ]
                    ]
                ]
            );
        }

        $this->get_template('index');
    }

    protected function paginate_after(object &$row)
    {
        $row->data_criacao = date("d/m/Y H:i:s", strtotime($row->data_criacao));
    }

    function adicionar(){
        if ($this->user->nivel != 1) {
            echo redirect(base_url(""));
            return;
        }

        $data['relatorios'] = $this->relatorio_model->relatorios;        

        $data['form_type'] = "adicionar";
        $data['is_self'] = false;
        $data['detalhes'] =  (object) [
            'nivel' => null,
            'empresas' => $this->get_empresas(),
            'obras' => $this->obra_model->get_obras(),
            'niveis' => $this->get_niveis()
        ];
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
    	$this->get_template('index_form', $data);
    }

    function editar($id_usuario=null){
        $usuario = $this->usuario_model->get_usuario($id_usuario);
        $data = null;
        if ($usuario) {
            $data['form_type'] = "editar";
            $data['is_self'] = $usuario->id_usuario == $this->user->id_usuario;
            $data['detalhes'] = $usuario;
            $data['detalhes']->empresas = $this->get_empresas();
            $data['detalhes']->obras = $this->obra_model->get_obras();
            $data['detalhes']->niveis = $this->get_niveis();
            $data['relatorios'] = $this->relatorio_model->relatorios;  
        }
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');
        $this->get_template('index_form', $data);
    }

    function salvar(){

        $data['id_usuario'] = $this->input->post('id_usuario');
        $data['permissoes'] = json_encode($this->input->post('permissoes'));
        $usuario = $this->usuario_model->get_usuario($data['id_usuario']);
    
        $data['usuario'] = $this->input->post('usuario');
        $data['nome'] = $this->input->post('nome');
        $data['email'] = $this->input->post('email');
        if($this->input->post('nivel') == 1) $data['permit_notification_email'] = $this->input->post('permit_notification_email') ?: '1';
        $data['permit_notification_push'] = $this->input->post('permit_notification_push') ?: '0';
     
        if ($usuario && $this->user->id_usuario != $usuario->id_usuario || !$usuario && $this->user->nivel == 1) {
            $data['situacao'] = $this->input->post('situacao');
            $data['nivel'] = $this->input->post('nivel');
            $data['id_empresa'] = $this->input->post('id_empresa');
            $data['id_obra'] = $this->input->post('id_obra');
        }

        $senha = strlen($this->input->post('senha')) > 0 ? $this->input->post('senha') : null;
        $confirmar_senha = strlen($this->input->post('confirmar_senha')) > 0 ? $this->input->post('confirmar_senha') : null;
        $data['senha'] = $this->usuario_model->verificaSenha($senha, $confirmar_senha);


        if (($senha && $confirmar_senha) && $data['senha'] == null) {
            $this->session->set_flashdata('msg_erro', "As senhas fornecidas não conferem!");

            if($data['id_usuario'] == null){
                echo redirect(base_url("usuario/adicionar"));
            } else {
                echo redirect(base_url("usuario/editar/{$data['id_usuario']}"));          
            }
            return;
        } 
        
        if ($data['id_usuario'] == null && $data['senha'] == null) {
            $this->session->set_flashdata('msg_erro', "Deve fornecer uma senha e confirmar!");
            echo redirect(base_url("usuario/adicionar"));
            return;
        } 

        if ($data['id_usuario'] && !$data['senha']) {
            $usuario = $this->usuario_model->get_usuario($data['id_usuario'], true);
            if (isset($usuario->senha)) {
                $data['senha'] = $usuario->senha;
            }
        }

        if ($this->usuario_model->exists_usuario($data['usuario'], $data['id_usuario'])) {
            $this->session->set_flashdata('msg_erro', "Nome de usuário já existe na base de dados!");
            $this->redirect($data);
            return;
        }

        if ($this->usuario_model->exists_email($data['email'], $data['id_usuario']) && $data['email'] != null) {
            $this->session->set_flashdata('msg_erro', "Já existe um usuário cadastrado com o email especificado na base de dados!");
            $this->redirect($data);
            return;
        }

        if ($data['email'] != null && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('msg_erro', "O Email especificado é inválido!");
            $this->redirect($data);
            return;
        }   

        if ($usuario && $usuario->email != $data['email']) {
            $data['email_confirmado_em'] = null;
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
            $data['avatar'] = ($_FILES['avatar'] ? $this->upload_arquivo('avatar') : '');
            if (!$data['avatar'] || $data['avatar'] == '') {
                $this->session->set_flashdata('msg_erro', "O tamanho da imagem deve ser menor ou igual a ".ini_get('upload_max_filesize'));
                return $this->redirect($data);
            }

            if (isset($usuario->avatar)) {
                $path = __DIR__."/../../../../assets/uploads/avatar";
                $file = "$path/{$usuario->avatar}";
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }

        if ($data['id_usuario'] == null && $data['id_obra'] == null) {
            $data['id_obra'] == $this->get_obra_base()->id_obra;
        }
        if ($data['permit_notification_push'] == null) unset($data['permit_notification_push']); 
        
        $this->usuario_model->salvar_formulario($data);
        if($data['id_usuario'] == null){
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
        }

        if ($data['id_usuario'] != null && $this->user->id_usuario == $data['id_usuario']) {
            echo redirect(base_url("usuario"));
            return;
        }
        echo redirect(base_url("usuario"));
    }

    function deletar($id=null){
        if ($this->user->nivel != 1) {
            echo redirect(base_url(""));
            return;
        }

        $this->db->where('id_usuario', $id);
        return $this->db->delete('usuario');
    }

    function solicitar_confirmacao_email($id_usuario){
        if ($this->user->nivel != 1) {
            echo redirect(base_url(""));
            return;
        }

        return $this->json(['success' => $this->usuario_model->solicitar_confirmacao_email($id_usuario)]);
    }

    function permit_notification_email($id_usuario){
        $success = false;
        if ($this->user->nivel == 1 || $this->user->id_usuario == $id_usuario) {
            $usuario = $this->usuario_model->get_usuario($id_usuario);
            $permit = $usuario->permit_notification_email == 1 ? '0' : '1';
            if($usuario) {
                $this->db->where("id_usuario", $id_usuario)->update("usuario", ["permit_notification_email" => $permit]);
                $success = true;
            }
        }
        return $this->json(['success' => $success]);
    }

    function permit_notification_push($id_usuario, $permit = 2){
        $success = false;
        $usuario = $this->usuario_model->get_usuario($id_usuario);
        if ($usuario && $this->user->id_usuario === $id_usuario) {
            $this->db->where("id_usuario", $id_usuario)->update("usuario", ["permit_notification_push" => $permit == 2 ? '0' : '1']);
            $success = true;   
        }
        return $this->json(['success' => $success]);
    }

    private function redirect($data) {
        if ($data['id_usuario'] != null && $this->user->id_usuario == $data['id_usuario']) {
            echo redirect(base_url("usuario/editar/{$data['id_usuario']}")); 
            return;
        }

        if($data['id_usuario'] == null){
            echo redirect(base_url("usuario/adicionar"));
        } else {
            echo redirect(base_url("usuario/editar/{$data['id_usuario']}"));          
        }
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */