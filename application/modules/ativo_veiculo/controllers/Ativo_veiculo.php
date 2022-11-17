<?php

(defined('BASEPATH')) or exit('No direct script access allowed');
require_once __DIR__ ."/Ativo_veiculo_trait.php";
require_once __DIR__ ."/Ativo_veiculo_abastecimento.php";
require_once __DIR__ ."/Ativo_veiculo_quilometragem.php";
require_once __DIR__ ."/Ativo_veiculo_operacao.php";
require_once __DIR__ ."/Ativo_veiculo_manutencao.php";
require_once __DIR__ ."/Ativo_veiculo_ipva.php";
require_once __DIR__ ."/Ativo_veiculo_seguro.php";
require_once __DIR__ ."/Ativo_veiculo_depreciacao.php";

/**
 * Description of site
 *
 * @author André Baill | https://www.github.com/srandrebaill
 */
class Ativo_veiculo  extends MY_Controller
{
    use 
    Ativo_veiculo_trait, 
    Ativo_veiculo_quilometragem, 
    Ativo_veiculo_operacao,
    Ativo_veiculo_abastecimento,
    Ativo_veiculo_manutencao,
    Ativo_veiculo_ipva,
    Ativo_veiculo_seguro,
    Ativo_veiculo_depreciacao;

    protected $ultimo_erro_upload_arquivo, $tipos, $tipos_vetor, $tipos_pt;

    function __construct()
    {
        parent::__construct();
        $this->load->model('ativo_veiculo_model');
        $this->load->helper('download');
        $this->tipos = $this->ativo_veiculo_model->tipos;
        $this->tipos_vetor =  $this->ativo_veiculo_model->tipos_vetor;
        $this->tipos_pt =  $this->ativo_veiculo_model->tipos_pt;
        $this->model = $this->ativo_veiculo_model;
    }

    function index() {
        if ($this->input->method() === 'post')  {
            return $this->index_paginate();
        }
        $this->get_template('index');
    }

    protected function paginate_after(object &$row)
    {
        $row->valor_fipe_html = "R$ " . number_format($row->valor_fipe ?? 0, 2, ',', '.');
        $row->fipe_mes_referencia = strtoupper($row->fipe_mes_referencia ?? '');
        $row->tipo_veiculo = strtoupper($row->tipo_veiculo ?? '');
    }

    private function index_paginate(){
        return $this->paginate_json([
            "templates" => [
                [
                    "name" => "id_link",
                    "view" => "index/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => $row->id_ativo_veiculo,
                            'link' => base_url('ativo_veiculo')."/editar/{$row->id_ativo_veiculo}", 
                        ]);
                    }
                ],
                [
                    "name" => "veiculo_identificacao_link",
                    "view" => "index/link",
                    "data" => function($row, $data) {
                        return  array_merge($data, [
                            'text' => strtoupper($row->veiculo_identificacao),
                            'link' => base_url('ativo_veiculo')."/editar/{$row->id_ativo_veiculo}", 
                        ]);
                    }
                ],
                [
                    "name" => "obra_html",
                    "view" => "index/obra",
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

    function adicionar()
    {
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 9, 'adicionar'));
        $this->get_template('index_form', ["permit_edit" => true]);
    }

    function editar($id_ativo_veiculo)
    {
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 9, 'editar'));
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);

        if ($veiculo) {
            $permit_edit = $this->ativo_veiculo_model->permit_delete($id_ativo_veiculo);
            $data = array_merge($this->anexo_model->getData('ativo_veiculo', $id_ativo_veiculo),[
                "back_url" => "ativo_veiculo/editar/{$id_ativo_veiculo}",
                "detalhes" => $veiculo,
                "permit_edit" => $permit_edit,
                "permissoes" => $this->permissoes
            ]);
            
            $this->get_template('index_form', $data);
            return;
        }
        
        $this->session->set_flashdata('msg_erro', "Veículo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    function salvar()
    {
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($this->input->post('id_ativo_veiculo'));
        $this->validar_periodo($this->input->post('periodo_inicial'), $this->input->post('periodo_final'), ($this->input->post('id_ativo_veiculo')) ?? null);

        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $data['tipo_veiculo'] = $this->input->post('tipo_veiculo');
        $data['id_obra'] = $this->input->post('id_obra');
        $data['periodo_inicial'] = $this->input->post('periodo_inicial');
        $data['periodo_final'] = $this->input->post('periodo_final');
        $data['id_marca'] = $this->input->post('id_marca');
        $data['id_modelo'] = $this->input->post('id_modelo');
        $data['ano'] = $this->input->post('ano');
        $data['veiculo'] = $this->input->post('veiculo');
        $data['veiculo_placa'] = $this->input->post('veiculo_placa');
        $data['veiculo_renavam'] = $this->input->post('veiculo_renavam');
        $data['veiculo_observacoes'] = $this->input->post('veiculo_observacoes');
        $data['situacao'] = $this->input->post('situacao');
        $data['id_interno_maquina'] = $this->input->post('id_interno_maquina');
        $data['valor_funcionario'] = $this->formata_moeda_float($this->input->post('valor_funcionario') ?: 0);
        $data['valor_adicional'] =  $this->formata_moeda_float($this->input->post('valor_adicional') ?: 0);
        $data['valor_fipe'] = $this->formata_moeda_float($this->input->post('valor_fipe') ?: 0);
        $data['fipe_mes_referencia'] = $this->input->post('fipe_mes_referencia');
        $data['codigo_fipe'] = $this->input->post('codigo_fipe');
        
        $permit_edit = $data['id_ativo_veiculo'] && $this->ativo_veiculo_model->permit_delete($data['id_ativo_veiculo']);
        if(
            !$veiculo ||
            ((int) $this->input->post('veiculo_km') <= (int) $veiculo->veiculo_km && $permit_edit) || 
            (int) $veiculo->veiculo_km == 0
        ) {
            $data['veiculo_km'] = $this->input->post('veiculo_km') ?: 0;
            $data['veiculo_km_data'] = date('Y-m-d H:i:s');
        }

        if(
            !$veiculo || 
            ((int) $this->input->post('veiculo_horimetro') <= (int) $veiculo->veiculo_horimetro && $permit_edit) || 
            (int) $veiculo->veiculo_horimetro == 0
        ) {
            $data['veiculo_horimetro'] = $this->input->post('veiculo_horimetro') ?: 0;
            $data['veiculo_horimetro_data'] = date('Y-m-d H:i:s');
        }


        if(!in_array($data['tipo_veiculo'], ['maquina', 'machine'])) {
            $fipe = $this->fipe_get_veiculo($this->tipos[$data['tipo_veiculo']], $data['codigo_fipe'], $data['ano']);

            if ($fipe->success) {
                $fipe = $fipe->data;
                $data['marca'] = $fipe->marca;
                $data['modelo'] = $fipe->modelo;
                $data['combustivel'] = $fipe->combustivel;
                $data['fipe_mes_referencia'] = $this->formata_mes_referecia(
                    $fipe->fipe_mes_referencia,
                    $fipe->fipe_ano_referencia
                );

                if((!$veiculo && (float) $data['valor_fipe'] === 0) || (float) $veiculo->valor_fipe === 0) {
                    $data['valor_fipe'] = $fipe->fipe_valor;
                }   
            }
        }

        if(in_array($data['tipo_veiculo'], ['maquina', 'machine'])) {
            $fipe = $this->fipe_veiculo($data['tipo_veiculo'], $data['id_marca'], $data['id_modelo']);
            $data['marca'] = $fipe->marca;
            $data['modelo'] = $fipe->modelo;
            $data['combustivel'] = "Diesel";
        }
        
        $last_id = $this->ativo_veiculo_model->salvar_formulario($data);


        /* Veículos na Obra - Histórico */
        if(
            $this->input->post('id_obra') != 
            $this->input->post('id_veiculo_obra_atual') or

            $this->input->post('periodo_inicial') != $this->input->post('periodo_inicial_atual') or
            $this->input->post('periodo_final') != $this->input->post('periodo_final_atual')
        ){ 
            $veiculo_obra['id_obra']            = $this->input->post('id_obra');
            $veiculo_obra['id_veiculo']         = (isset($last_id) ? $last_id : $this->input->post('id_ativo_veiculo'));
            $veiculo_obra['periodo_inicial']    = $this->input->post('periodo_inicial');
            $veiculo_obra['periodo_final']      = $this->input->post('periodo_final');
            $this->db->insert('ativo_veiculo_obra', $veiculo_obra);
        }

        if ($data['id_ativo_veiculo'] == '' || !$data['id_ativo_veiculo']) {
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
        }
        echo redirect(base_url("ativo_veiculo/editar/{$last_id}"));
    }

    private function redirect($veiculo, $tipo, $data, $acao = "adicionar")
    {
        $url = "ativo_veiculo";
        if (!$data["id_ativo_veiculo_{$tipo}"]) {
            $url .= "/{$tipo}/{$veiculo->id_ativo_veiculo}";
        } else {
            $url .= "/{$tipo}/{$veiculo->id_ativo_veiculo}";
        }
        echo redirect(base_url($url));
        return;
    }

    private function insert_km_and_operacao($veiculo, $km_atual = 0, $horimetro_atual = 0)
    {
        if ($veiculo) {
            if ($km_atual > 0 && $km_atual > $veiculo->veiculo_km_atual){
                $this->db->insert('ativo_veiculo_quilometragem', [
                    'id_ativo_veiculo' => $veiculo->id_ativo_veiculo,
                    'veiculo_km' =>  $km_atual,
                    'data' => date('Y-m-d H:i:s')
                ]);
            }

            if ($horimetro_atual > 0  && $horimetro_atual > $veiculo->veiculo_horimetro_atual){
                $this->db->insert('ativo_veiculo_operacao', [
                    'id_ativo_veiculo' => $veiculo->id_ativo_veiculo,
                    'veiculo_horimetro' =>  $horimetro_atual ,
                    'data' => date('Y-m-d H:i:s')
                ]);
            }
            return true;
        }
        return false;
    }

    private function merger_anexo_data(string $tipo_anexo, &$data = [], $id_ativo_veiculo = null, $id_ativo_veiculo_item = null){
        $back_url = "ativo_veiculo/{$tipo_anexo}/{$id_ativo_veiculo}";
        $data = ["id_ativo_veiculo" => $id_ativo_veiculo];
        $form_action = false;

        if(
            ($id_ativo_veiculo && $id_ativo_veiculo_item) &&
            $id_ativo_veiculo_item !== 'paginate' &&
            $id_ativo_veiculo_item !== 'adicionar'
        ) {
            $form_action = true;
            $back_url .= "/{$id_ativo_veiculo_item}";
        } 
        
        $data = array_merge(
            $data,
            $this->anexo_model->getData(
                'ativo_veiculo', 
                $id_ativo_veiculo, 
                $tipo_anexo,
                $form_action ? $id_ativo_veiculo_item : null
            ),
            [
                "id_ativo_veiculo" => $id_ativo_veiculo,
                'tipo_anexo' => $tipo_anexo,
                'back_url' => $back_url
            ]
        );
        return $data;
    }

    function deletar_anexo($tabela, $id_item, $anexo){
        $item  = $this->db->where("id_$tabela = $id_item")->get($tabela)->row();
        if ($item && $this->user->nivel == 1) {
            $file = $item->$anexo;
            $this->db->where("id_$tabela = $id_item")->update($tabela, [$anexo => ""]);
            $this->db->where("anexo = '$anexo/$file'")->delete("anexo");
            $file = file_exists(APPPATH."../assets/uploads/$anexo/$file");
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    function deletar($id_ativo_veiculo)
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        if ($this->ativo_veiculo_model->permit_delete($id_ativo_veiculo) && $this->user->nivel == 1) {
            return $this->db->where('id_ativo_veiculo', $id_ativo_veiculo)->delete('ativo_veiculo');
        }
        $this->session->set_flashdata('msg_erro', "Veículo contém itens lançados, não pode ser excluído!");
        echo redirect(base_url("ativo_veiculo"));
    }

    function buscar_veiculo($coluna, $valor){
        return $this->json($this->ativo_veiculo_model->get_ativo_veiculo($valor, $coluna));
    }

    function consultar_extrato($tipo, $id_ativo_veiculo){
        switch($tipo){
            case "km":
            case "quilometragem":
                $data = [
                    "historico" => [
                        "data" => $this->ativo_veiculo_model->get_ativo_veiculo_km_lista($id_ativo_veiculo, 5),
                        "title" => "Quilometragem",
                        "id_ativo_veiculo" => $id_ativo_veiculo
                    ],
                    "extrato" => $this->ativo_veiculo_model->get_extrato("km", $id_ativo_veiculo),
                ];
                return $this->json($data);
            break;

            case "hs":
            case "operacao":
                $data = [
                    "historico" => [
                        "data" => $this->ativo_veiculo_model->get_ativo_veiculo_operacao_lista($id_ativo_veiculo, 5),
                        "title" => "Tempo de Operação",
                        "id_ativo_veiculo" => $id_ativo_veiculo
                    ],
                    "extrato" => $this->ativo_veiculo_model->get_extrato("operacao", $id_ativo_veiculo),
                ];
                return $this->json($data);
            break;
        }
        return $this->json(null);
    }

    function lancar_operacao($tipo, $id_ativo_veiculo){
        $_POST['id_ativo_veiculo'] = $id_ativo_veiculo;
        switch($tipo){
            case "km":
            case "quilometragem":
                return $this->quilometragem_salvar(true);
            break;

            case "hs":
            case "operacao":
                return $this->operacao_salvar(true);
            break;
        }
        return $this->json(null, 400);
    }

    function deletar_operacao($tipo, $id_ativo_veiculo){
        switch($tipo){
            case "km":
            case "quilometragem":
                return $this->quilometragem_deletar($id_ativo_veiculo, $this->input->post('id_ativo_veiculo_quilometragem'), true);
            break;

            case "hs":
            case "operacao":
                return $this->operacao_deletar($id_ativo_veiculo, $this->input->post('id_ativo_veiculo_operacao'), true);
            break;
        }
        return $this->json(null, 400);
    }

    function historico($id_ativo_veiculo){
        $data['historico_veiculo'] = $this->ativo_veiculo_model->get_historico_veiculo($id_ativo_veiculo);
        $this->load->vars($data);
        $this->load->view("historico_veiculo");
    }

    function validar_periodo($data_inicial = null, $data_final = null, $id_ativo_veiculo = null){

        if($data_inicial == null || $data_final == null || $data_final <= $data_inicial){
            $this->session->set_flashdata('msg_erro', "O período selecionado é inválido!");

            if(isset($id_ativo_veiculo)){
                echo redirect(base_url("ativo_veiculo/editar/".$id_ativo_veiculo));
            } else { 
                echo redirect(base_url("ativo_veiculo/"));
            }
        }
    }

    public function excluir_historico($id_veiculo_obra)
    {
        if($this->ativo_veiculo_model->excluir_historico($id_veiculo_obra)){
            echo "ok";
        } else {
            echo "error";
        }
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */