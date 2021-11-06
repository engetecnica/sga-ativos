<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ativo_interno
 *
 * @author https://www.roytuts.com
 */
class Ativo_interno  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ativo_interno_model');
        $this->load->model('obra/obra_model');    
    }

    function index() {
        $data['lista'] = $this->ativo_interno_model->get_lista();
        $this->get_template('index', $data);
    }

    function adicionar(){
        $data['obras'] = $this->obra_model->get_obras();
    	$this->get_template('index_form', $data);
    }

    function editar($id_ativo_interno=null){
        $data['obras'] = $this->obra_model->get_obras();
        $data['ativo'] = $this->ativo_interno_model->get_ativo_interno($id_ativo_interno);
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_ativo_interno'] = !is_null($this->input->post('id_ativo_interno')) ? $this->input->post('id_ativo_interno') : '';
        $data['nome'] = $this->input->post('nome');
        $data['marca'] = $this->input->post('marca');

        $valor = str_replace("R$ ", "", $this->input->post('valor'));
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor); 

        $data['valor'] = $valor;
        $data['quantidade'] = $this->input->post('quantidade');
        $data['observacao'] = $this->input->post('observacao');
        $data['situacao'] = $this->input->post('situacao');
        $data['id_obra'] = $this->input->post('id_obra');

        if($data['id_ativo_interno']=='' || !$data['id_ativo_interno']){
            unset($data['id_ativo_interno']);
            $data_insert = [];
            for($i = 0; $i < (int) $data['quantidade']; $i++){
                $data_insert[$i] = $data;
                $data_insert[$i]['quantidade'] = 1;
                $data_insert[$i]['situacao'] = 0;
            }
            $this->db->insert_batch("ativo_interno", $data_insert);
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->ativo_interno_model->salvar_formulario($data);
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
        }

        echo redirect(base_url("ativo_interno"));
    }

    function descartar($id_ativo_interno){
        $ativo = $this->ativo_interno_model->get_ativo_interno($id_ativo_interno);
        if (($ativo != null) & ($this->input->method() == 'post')) {

            $status = $this->db->where('id_ativo_interno', $ativo->id_ativo_interno)
                            ->update('ativo_interno', [
                                'situacao' => 2,
                                'data_descarte' => date('Y-m-d H:i:s', strtotime('now'))
                            ]);

            return $this->json([
                'success' => $status
            ]);
        }
        return $this->json(['success' => false]);
    }

    function deletar($id_ativo_interno){
        $this->db
        ->where('id_ativo_interno', $id_ativo_interno)
        ->delete('ativo_interno');
    }

    function manutencao($id_ativo_interno) {
        $data['ativo'] = $this->ativo_interno_model->get_ativo_interno($id_ativo_interno);
        if ($data['ativo']) {
            $data['lista'] = $this->ativo_interno_model->get_lista_manutencao($id_ativo_interno);
            $this->get_template('index_manutencao', $data);
            return;
        }
        echo redirect(base_url("ativo_interno"));
    }

    function manutencao_adicionar($id_ativo_interno){
        $data['ativo'] = $this->ativo_interno_model->get_ativo_interno($id_ativo_interno);
        if ($data['ativo']) {
            $this->get_template('index_form_manutencao', $data);
            return;
        }
        echo redirect(base_url("ativo_interno"));
    }

    function manutencao_editar($id_ativo_interno, $id_manutencao){
        $data['manutencao'] = $this->ativo_interno_model->get_manutencao($id_ativo_interno, $id_manutencao);
        $data['ativo'] = $this->ativo_interno_model->get_ativo_interno($id_ativo_interno);

        if ($data['ativo'] && $data['manutencao']) {
            $data['obs'] = $this->ativo_interno_model->get_lista_manutencao_obs($id_manutencao);
            $this->get_template('index_form_manutencao', $data);
            return;
        }

        if ($data['ativo']) {
            echo redirect(base_url("ativo_interno/manutencao/{$id_ativo_interno}")); 
            return;
        }
        echo redirect(base_url("ativo_interno"));
    }

    function manutencao_salvar(){
        $data['id_ativo_interno'] = !is_null($this->input->post('id_ativo_interno')) ? $this->input->post('id_ativo_interno') : '';
        $data['id_manutencao'] = $this->input->post('id_manutencao');

        if ($data['id_manutencao'] == null) {
            $data['situacao'] = 0;
            $data['data_saida'] = $this->input->post('data_saida');
            $this->db->insert('ativo_interno_manutencao', $data);
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            echo redirect(base_url("ativo_interno/manutencao/{$data['id_ativo_interno']}"));
            return;
        } else {
            $data['situacao'] = $this->input->post('situacao') != null ? $this->input->post('situacao') : 0;
            $data['data_retorno'] = $this->input->post('data_retorno');
            $valor = str_replace("R$ ", "", $this->input->post('valor'));
            $valor = str_replace(".", "", $valor);
            $data['valor'] = str_replace(",", ".", $valor); 
        
            $this->db->where('id_manutencao', $data['id_manutencao'])
                ->where('id_manutencao', $data['id_manutencao'])
                ->update('ativo_interno_manutencao', $data);
            $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
        }
        echo redirect(base_url("ativo_interno/manutencao/{$data['id_ativo_interno']}"));
    }

    function manutencao_remover($id_ativo_interno, $id_manutencao){
        return $this->db->where('id_manutencao', $id_manutencao)
                ->delete('ativo_interno_manutencao');
    }

    function manutencao_obs_adicionar($id_ativo_interno, $id_manutencao){
        $data['ativo'] = $this->ativo_interno_model->get_ativo_interno($id_ativo_interno);
        $data['manutencao'] = $this->ativo_interno_model->get_manutencao($id_ativo_interno, $id_manutencao);
        
        if ($data['ativo'] && $data['manutencao']) {
            $this->get_template('index_form_obs', $data);
            return;
        }
        echo redirect(base_url("ativo_interno/manutencao/{$id_ativo_interno}#obs"));
    }

    function manutencao_obs_editar($id_ativo_interno, $id_manutencao, $id_obs){
        $data['obs'] = $this->ativo_interno_model->get_obs($id_manutencao, $id_obs);
        $data['manutencao'] = $this->ativo_interno_model->get_manutencao($id_ativo_interno, $id_manutencao); 
        $data['ativo'] = $this->ativo_interno_model->get_ativo_interno($id_ativo_interno);

        if (($data['obs'] && $data['manutencao']) && $data['ativo']) {
            $this->get_template('index_form_obs', $data);
            return;
        }
        echo redirect(base_url("ativo_interno/manutencao/{$id_ativo_interno}#obs"));
    }

    function manutencao_obs_salvar($id_ativo_interno, $id_manutencao) {
        $data['id_manutencao'] = $id_manutencao;
        $data['id_obs'] = $this->input->post('id_obs');
        $data['id_usuario'] = $this->user->id_usuario;
        $data['texto'] = trim($this->input->post('texto'));

        if (!$data['id_obs'] && $data['texto']) {
            $data['data_inclusao'] = date('Y-m-d H:i:s', strtotime('now'));
            $this->db->insert('ativo_interno_manutencao_obs', $data);
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } 
        
        if ($data['id_obs'] && $data['texto'] ) {
            $data['data_edicao'] = date('Y-m-d H:i:s', strtotime('now'));
            $this->db
                ->where('id_manutencao', $id_manutencao)
                ->where('id_obs', $data['id_obs'])
                ->where('id_usuario', $data['id_usuario'])
                ->update('ativo_interno_manutencao_obs', $data);
            $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
        }
        echo redirect(base_url("ativo_interno/manutencao_editar/{$id_ativo_interno}/{$id_manutencao}#obs"));
    }

    function manutencao_obs_remover($id_obs){
        return $this->db
            ->where('id_obs', $id_obs)
            ->delete('ativo_interno_manutencao_obs');
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */