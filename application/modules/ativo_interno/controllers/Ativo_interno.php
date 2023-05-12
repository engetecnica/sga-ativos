<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ativo_interno
 *
 * @author André Baill | https://www.github.com/srandrebaill
 */

use chillerlan\QRCode\QRCode;
use \Mpdf\Mpdf;
use Mpdf\Tag\Table;
class Ativo_interno  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ativo_interno_model');
        $this->load->model('obra/obra_model');
        $this->load->model('ativo_externo/ativo_externo_model');
        $this->model = $this->ativo_interno_model;
    }

    function index() {
        if ($this->input->method() === 'post')  {
            return $this->paginate_json([
                "templates" => [
                    [
                        "name" => "serie_link",
                        "view" => "index/link",
                        "data" => function($row, $data) {
                            return  array_merge($data, [
                                'text' => $row->serie,
                                'link' => base_url('ativo_interno')."/editar/{$row->id_ativo_interno}", 
                            ]);
                        }
                    ],
                    [
                        "name" => "nome_link",
                        "view" => "index/link",
                        "data" => function($row, $data) {
                            return  array_merge($data, [
                                'text' => $row->nome,
                                'link' => base_url('ativo_interno')."/editar/{$row->id_ativo_interno}", 
                            ]);
                        }
                    ],
                    [
                        "name" => "situacao_html",
                        "view" => "index/situacao"   
                    ],
                    [                       
                        "name" => "actions",
                        "view" => "index/actions"
                    ]
                ]
            ]);
        }

        $this->get_template('index');
    }

    protected function paginate_after(object &$row)
    {
        $row->data_inclusao = date('d/m/Y H:i:s', strtotime($row->data_inclusao));
        $row->data_descarte = (isset($row->data_descarte)) ? date('d/m/Y H:i:s', strtotime($row->data_descarte)) : '-';
        $row->valor = "R$ " . number_format($row->valor, 2, ',', '.');
    }

    function adicionar($data = []){
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 10, 'adicionar'));
        $data['obras'] = $this->obra_model->get_obras();
        $data['marcas'] = $this->ativo_interno_model->get_marcas();
        $data['patrimonio'] = $this->ativo_externo_model->get_codigo_patrimonio_increment();
    	$this->get_template('index_form', $data);
    }

    function editar($id_ativo_interno=null){
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 10, 'editar'));
        $data = array_merge($this->anexo_model->getData('ativo_interno', $id_ativo_interno), [
            "back_url" => "ativo_interno/editar/{$id_ativo_interno}",
            'obras' => $this->obra_model->get_obras(),
            'ativo' => $this->ativo_interno_model->get_ativo($id_ativo_interno),
            'marcas' => $this->ativo_interno_model->get_marcas()
        ]);
        $this->get_template('index_form', $data);
    }

    function salvar(){
        $data['id_ativo_interno'] = !is_null($this->input->post('id_ativo_interno')) ? $this->input->post('id_ativo_interno') : '';
        $valor = str_replace("R$ ", "", $this->input->post('valor'));
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor); 
        $data['valor'] = $valor;
        $data['nome'] = $this->input->post('nome');
        $data['marca'] = $this->input->post('marca');
        $data['patrimonio'] = $this->input->post('patrimonio');
        $data['serie'] = $this->input->post('serie');
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

            if ($this->ativo_interno_model->permit_create($data)) {
                $this->db->insert_batch("ativo_interno", $data_insert);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $data['ativo'] = (object) $data;
                $this->session->set_flashdata('msg_erro', "Um ativo com os mesmos dados já existe!");
                return $this->adicionar($data);
            }
        } else {
            if($this->ativo_interno_model->permit_update($data)) {
                $this->ativo_interno_model->salvar_formulario($data);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!"); 
            } else {
                $this->session->set_flashdata('msg_erro', "Um ativo com os mesmos dados já existe!");
                return $this->editar($data['id_ativo_interno']);
            }          
        }

        echo redirect(base_url("ativo_interno"));
    }

    function descartar($id_ativo_interno){
        $ativo = $this->ativo_interno_model->get_ativo($id_ativo_interno);
        if (($ativo != null) & ($this->input->method() == 'post')) {

            $status = $this->db->where('id_ativo_interno', $ativo->id_ativo_interno)
                            ->update('ativo_interno', [
                                'situacao' => 2,
                                'data_descarte' => date('Y-m-d H:i:s', strtotime('now'))
                            ]);

            // Salvar LOG
			$this->salvar_log(10, $id_ativo_interno, 'descartar', $this->ativo_interno_model->get_ativo($id_ativo_interno), null, null);                

            return $this->json([
                'success' => $status
            ]);
        }
        return $this->json(['success' => false]);
    }

    function desfazer_descarte($id_ativo_interno){
        $ativo = $this->ativo_interno_model->get_ativo($id_ativo_interno);
        if ($ativo && $this->user->nivel == 1) {

            $status = $this->db->where('id_ativo_interno', $ativo->id_ativo_interno)
                            ->update('ativo_interno', [
                                'id_ativo_interno' => $id_ativo_interno,
                                'situacao' => '1',
                                'data_descarte' => null
                            ]);
            // Salvar LOG
			$this->salvar_log(10, $id_ativo_interno, 'desfazer', $this->ativo_interno_model->get_ativo($id_ativo_interno), null, null);                

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
        $data['ativo'] = $this->ativo_interno_model->get_ativo($id_ativo_interno);
        if ($data['ativo']) {
            $data['lista'] = $this->ativo_interno_model->get_lista_manutencao($id_ativo_interno);
            $this->get_template('index_manutencao', $data);
            return;
        }
        echo redirect(base_url("ativo_interno"));
    }

    function manutencao_adicionar($id_ativo_interno){
        $data['ativo'] = $this->ativo_interno_model->get_ativo($id_ativo_interno);
        if ($data['ativo']) {
            $this->get_template('index_form_manutencao', $data);
            return;
        }
        echo redirect(base_url("ativo_interno"));
    }

    function manutencao_editar($id_ativo_interno, $id_manutencao){
        $data = array_merge($this->anexo_model->getData('ativo_interno', $id_ativo_interno, 'manutencao', $id_manutencao), [
            "manutencao" => $this->ativo_interno_model->get_manutencao($id_ativo_interno, $id_manutencao),
            "ativo" => $this->ativo_interno_model->get_ativo($id_ativo_interno),
            "back_url" => "ativo_interno/manutencao_editar/{$id_ativo_interno}/{$id_manutencao}"
        ]);

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

        if ($data['id_manutencao'] == null && $this->ativo_interno_model->permit_create_manutencao($data['id_ativo_interno'])) {
            $data['situacao'] = 0;
            $data['data_saida'] = $this->input->post('data_saida');

            //Salvar LOG
            $this->salvar_log(10, null, 'adicionar', $data);

            $this->db->insert('ativo_interno_manutencao', $data);
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            echo redirect(base_url("ativo_interno/manutencao/{$data['id_ativo_interno']}"));
            return;
        }
        
        if ($data['id_manutencao'] != null) {
            $data['situacao'] = $this->input->post('situacao') != null ? $this->input->post('situacao') : 0;
            $data['data_retorno'] = $this->input->post('data_retorno');
            $valor = str_replace("R$ ", "", $this->input->post('valor'));
            $valor = str_replace(".", "", $valor);
            $data['valor'] = str_replace(",", ".", $valor); 
        
            $this->db->where('id_manutencao', $data['id_manutencao'])
                ->where('id_manutencao', $data['id_manutencao'])
                ->update('ativo_interno_manutencao', $data);

            //Salvar LOG
            $this->salvar_log(10, $data['id_manutencao'], 'editar', $data);

            $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
        }
        echo redirect(base_url("ativo_interno/manutencao/{$data['id_ativo_interno']}"));
    }

    function manutencao_remover($id_manutencao){
        
        //Salvar LOG
        $data['id_manutencao'] = $id_manutencao;
        $this->salvar_log(10, $id_manutencao, 'excluir', $data);

        return $this->db->where('id_manutencao', $id_manutencao)
                ->delete('ativo_interno_manutencao');
    }

    function manutencao_obs_adicionar($id_ativo_interno, $id_manutencao){
        $data['ativo'] = $this->ativo_interno_model->get_ativo($id_ativo_interno);
        $data['manutencao'] = $this->ativo_interno_model->get_manutencao($id_ativo_interno, $id_manutencao);
        
        if ($data['ativo'] && $data['manutencao']) {
            $this->get_template('index_form_obs', $data);
            return;
        }
        echo redirect(base_url("ativo_interno/manutencao/{$id_ativo_interno}#obs"));
    }

    function manutencao_obs_editar($id_ativo_interno, $id_manutencao, $id_obs){
        $data['obs'] = $this->ativo_interno_model->get_obs($id_manutencao, $id_obs);
        if ($this->usera->id_usuario == $data['obs']->id_usuario ) {
            $data['manutencao'] = $this->ativo_interno_model->get_manutencao($id_ativo_interno, $id_manutencao); 
            $data['ativo'] = $this->ativo_interno_model->get_ativo($id_ativo_interno);

            if (($data['obs'] && $data['manutencao']) && $data['ativo']) {
                $this->get_template('index_form_obs', $data);
                return;
            }
        }
        echo redirect(base_url("ativo_interno/manutencao/{$id_ativo_interno}#obs"));
    }

    function manutencao_obs_salvar($id_ativo_interno, $id_manutencao) {
        $data['id_manutencao'] = $id_manutencao;
        $data['id_obs'] = $this->input->post('id_obs');
        $data['id_usuario'] = $this->user->id_usuario;
        $data['texto'] = ucfirst(trim($this->input->post('texto')));

        if (!$data['id_obs'] && $data['texto']) {
            $data['data_inclusao'] = date('Y-m-d H:i:s', strtotime('now'));

            //Salvar LOG
            $this->salvar_log(10, $data['id_obs'], 'adicionar', $data);

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

            //Salvar LOG
            $this->salvar_log(10, $data['id_obs'], 'editar', $data);

            $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
        }
        echo redirect(base_url("ativo_interno/manutencao_editar/{$id_ativo_interno}/{$id_manutencao}#obs"));
    }

    function manutencao_obs_remover($id_manutencao, $id_obs){
        $data['obs'] = $this->ativo_interno_model->get_obs($id_manutencao, $id_obs);
        if ($this->usera->id_usuario == $data['obs']->id_usuario ) {
            return $this->db
                ->where('id_obs', $id_obs)
                ->delete('ativo_interno_manutencao_obs');
        }
        return false;
    }

    /** Listar Marcas */
    public function marca()
    {
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 10, 'adicionar'));
        $data['lista'] = $this->ativo_interno_model->get_marcas();
        $this->get_template('marca', $data);
    }

    /** Adicionar Marca */
    public function marca_adicionar()
    {
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 10, 'adicionar'));
        $this->get_template('marca_form');
    }

    /** Editar Marca */
    public function marca_editar($id)
    {
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 10, 'editar'));
        $data['marca_detalhe'] = $this->ativo_interno_model->get_marca($id);
        $this->get_template('marca_form', $data);
    }

    /** Salvar Marca */
    public function marca_salvar()
    {
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 10, 'adicionar'));

        if (!$this->input->post('id_ativo_interno_marca')) {
            $marca['titulo'] = $this->input->post('titulo');
            $this->db->insert('ativo_interno_marca', $marca);
        } else {
            $marca['titulo'] = $this->input->post('titulo');
            $this->db->where('id_ativo_interno_marca', $this->input->post('id_ativo_interno_marca'));
            $this->db->update('ativo_interno_marca', $marca);
        }

        $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
        echo redirect(base_url("ativo_interno/marca"));
    }

    /** Excluir Marca */
    public function marca_deletar($id_ativo_interno_marca)
    {
        return $this->db
            ->where('id_ativo_interno_marca', $id_ativo_interno_marca)
            ->delete('ativo_interno_marca');
    }


    /** QRCODE - Gerar */
    public function qrcode($id_ativo_interno)
    {
        $data['dados'] = $this->ativo_interno_model->get_ativo($id_ativo_interno);
        $data['url'] = base_url('ativo_interno/qrcode/' . $id_ativo_interno);
        $data['qrcode'] = (new QRCode)->render($data['url']);
        $filename = 'QrCode-Ativo-Interno-' . $data['dados']->patrimonio . '.pdf';

        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view('ativo_qrcode', $data, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, 'D');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */