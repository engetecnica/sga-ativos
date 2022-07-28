<?php

(defined('BASEPATH')) or exit('No direct script access allowed');
require_once __DIR__ ."/Ativo_veiculo_trait.php";

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class Ativo_veiculo  extends MY_Controller
{
    use Ativo_veiculo_trait;

    protected $ultimo_erro_upload_arquivo, $tipos, $tipos_vetor, $tipos_pt;

    function __construct()
    {
        parent::__construct();
        $this->load->model('ativo_veiculo_model');
        $this->load->helper('download');

        # Login
        if ($this->session->userdata('logado') == null) {
            echo redirect(base_url('login'));
        }
        # Fecha Login 

        $this->tipos =  $this->ativo_veiculo_model->tipos;
        $this->tipos_vetor =  $this->ativo_veiculo_model->tipos_vetor;
        $this->tipos_pt =  $this->ativo_veiculo_model->tipos_pt;

        $this->get_modulo_permission();
    }

    function index()
    {
        $this->get_template('index', ["lista" => $this->ativo_veiculo_model->get_lista()]);
    }

    function adicionar()
    {
        $this->permitido_redirect($this->permitido($this->get_modulo_permission(), 9, 'adicionar'));

        $data = ["permit_edit" => true];
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
                "permit_edit" => $permit_edit
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

    public function gerenciar($entrada = null, $tipo = null, $id_ativo_veiculo = null, $id_gerenciar_item = null)
    {
        if (in_array($tipo, ['adicionar', 'editar'])) {
            $data['id_ativo_veiculo'] = $id_ativo_veiculo;
        } else {
            $data['id_ativo_veiculo'] = $tipo;
            $id_ativo_veiculo = $tipo;
        }

        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
        $tipo_anexo = $entrada;

        switch ($entrada) {
            default:
                echo redirect(base_url("ativo_veiculo"));
                return;
                break;

            case 'quilometragem':
                if ($tipo == 'adicionar') {
                    $template = "_form";
                } elseif ($tipo == 'editar') {
                    $data['quilometragem'] = $this->db->where('id_ativo_veiculo_quilometragem', $id_gerenciar_item)
                        ->where('id_ativo_veiculo', $id_ativo_veiculo)
                        ->get('ativo_veiculo_quilometragem')->row();
                    $template = "_form";
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_km_lista($tipo);
                }
            break;
            case 'abastecimento':
                if ($tipo == 'adicionar') {
                    $data['fornecedores'] = $this->ativo_veiculo_model->get_fornecedores();
                    $data['combustiveis'] = $this->ativo_veiculo_model->get_combustiveis();
                    $template = "_form";
                } elseif ($tipo == 'editar') {
                    $data['abastecimento'] = $this->db->where('id_ativo_veiculo_abastecimento', $id_gerenciar_item)
                        ->where('id_ativo_veiculo', $id_ativo_veiculo)
                        ->get('ativo_veiculo_abastecimento')->row();
                    $data['fornecedores'] = $this->ativo_veiculo_model->get_fornecedores();
                    $data['combustiveis'] = $this->ativo_veiculo_model->get_combustiveis();
                    $template = "_form";
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_abastecimento_lista($tipo);
                }
            break;
    
            case 'operacao':
                if ($tipo == 'adicionar') {
                    $template = "_form";

                } elseif ($tipo == 'editar') {
                    $data['operacao'] = $this->db->where('id_ativo_veiculo_operacao', $id_gerenciar_item)
                                        ->where('id_ativo_veiculo', $id_ativo_veiculo)
                                        ->get('ativo_veiculo_operacao')->row();
                    $template = "_form";
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_operacao_lista($tipo);
                }
            break;

            case 'manutencao':
                $tipo_anexo = "ordem_de_servico";
                if ($tipo == 'adicionar') {
                    $template = "_form";
                    $data['tipo_servico'] = $this->ativo_veiculo_model->get_tipo_servico(10, 'Serviços Mecânicos');
                    $data['fornecedores'] = $this->ativo_veiculo_model->get_fornecedores();
                } elseif ($tipo == 'editar') {
                    $data['tipo_servico'] = $this->ativo_veiculo_model->get_tipo_servico(10, 'Serviços Mecânicos');
                    $data['fornecedores'] = $this->ativo_veiculo_model->get_fornecedores();
                    $data['manutencao'] = $this->db->where('id_ativo_veiculo_manutencao', $id_gerenciar_item)
                        ->where('id_ativo_veiculo', $id_ativo_veiculo)
                        ->get('ativo_veiculo_manutencao')->row();
                    $template = "_form";
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_manutencao_lista($tipo);
                }
                break;

            case 'ipva':
                if ($tipo == 'adicionar') {
                    $template = "_form";
                } elseif ($tipo == 'editar') {
                    $data['ipva'] = $this->db->where('id_ativo_veiculo_ipva', $id_gerenciar_item)
                        ->where('id_ativo_veiculo', $id_ativo_veiculo)
                        ->get('ativo_veiculo_ipva')->row();
                    $template = "_form";
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_ipva_lista($tipo);
                }
            break;

            case 'seguro':
                if ($tipo == 'adicionar') {
                    $template = "_form";
                } elseif ($tipo == 'editar') {
                    $data['seguro'] = $this->db->where('id_ativo_veiculo_seguro', $id_gerenciar_item)
                    ->where('id_ativo_veiculo', $id_ativo_veiculo)
                    ->get('ativo_veiculo_seguro')->row();
                    $template = "_form";
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_seguro_lista($tipo);
                }
            break;

            case 'depreciacao':
                $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_depreciacao_lista($id_ativo_veiculo);
                $data['meses_ano'] = $this->meses_ano;

                if ($tipo == 'adicionar') {
                    $template = "_form";
                } elseif ($tipo == 'editar') {
                    $template = "_form";
                    $data['depreciacao'] = $this->db->where('id_ativo_veiculo_depreciacao', $id_gerenciar_item)
                    ->where('id_ativo_veiculo', $id_ativo_veiculo)
                    ->get('ativo_veiculo_depreciacao')->row();
                } else {
                    $template = "";
                    $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($tipo);
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_depreciacao_lista($tipo);
                }
            break;
        }

        $back_url = "ativo_veiculo/gerenciar/{$entrada}/{$tipo}/{$id_ativo_veiculo}";
        if($id_gerenciar_item) $back_url .= "/{$id_gerenciar_item}";

        $this->get_template("gerenciar_" . $entrada . $template, array_merge($this->anexo_model->getData('ativo_veiculo', $id_ativo_veiculo, $tipo_anexo, $id_gerenciar_item), $data, [
            "back_url" =>  $back_url,
            "veiculo" => $veiculo
        ]));
    }

    private function redirect($veiculo, $tipo, $data, $acao = "adicionar")
    {
        $url = "ativo_veiculo";
        if (!$data["id_ativo_veiculo_{$tipo}"]) {
            $url .= "/gerenciar/{$tipo}/{$acao}/{$veiculo->id_ativo_veiculo}";
        } else {
            $url .= "/gerenciar/{$tipo}/{$veiculo->id_ativo_veiculo}";
        }
        echo redirect(base_url($url));
        return;
    }

    # Salvar Abastecimento
    public function abastecimento_salvar($returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }
        
        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $data['id_ativo_veiculo_abastecimento'] = $this->input->post('id_ativo_veiculo_abastecimento');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $veiculo_km = (int) $this->input->post('veiculo_km');
            $ultimo_km = $this->db->where("id_ativo_veiculo = {$data['id_ativo_veiculo']}")
                        ->order_by('data', 'desc')
                        ->limit(1)
                        ->get('ativo_veiculo_quilometragem')
                        ->row();

            if ($ultimo_km && $veiculo_km < $ultimo_km->veiculo_km) {
                $msg =  "KM atual deve ser maior que a KM inicial do veículo e última lançada!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
                $this->session->set_flashdata('msg_erro', $msg);
                return $this->redirect($veiculo, 'abastecimento', $data, $data['id_ativo_veiculo_abastecimento'] ? "editar" : "adicionar");
            }
            
            $data['veiculo_km'] = $veiculo_km;
            $data['combustivel'] = $this->input->post('combustivel'); 
            $data['id_fornecedor'] = $this->input->post('id_fornecedor');   
            $data['combustivel_unidade_tipo'] = $this->input->post('combustivel_unidade_tipo') == 'litro' ? '0' : '1';   
            $data['combustivel_unidade_valor'] = $this->formata_moeda_float($this->input->post('combustivel_unidade_valor'));
            $data['abastecimento_custo'] = $this->formata_moeda_float($this->input->post('abastecimento_custo'));
            $data['abastecimento_data'] = $this->input->post('abastecimento_data') ?: date("Y-m-d");
            $data['combustivel_unidade_total'] = number_format(($data['abastecimento_custo'] / $data['combustivel_unidade_valor']), 2);

            if (!$data['id_ativo_veiculo_abastecimento']) {
                $this->db->insert('ativo_veiculo_abastecimento', $data);
                if ($ultimo_km && $veiculo_km > $ultimo_km->veiculo_km) {
                    $this->db->insert('ativo_veiculo_quilometragem', ["data" =>  $data['abastecimento_data'], "veiculo_km" => $veiculo_km]);
                }
                $msg = "Novo registro inserido com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            } else {
                $this->db->where('id_ativo_veiculo_abastecimento', $data['id_ativo_veiculo_abastecimento'])
                    ->update('ativo_veiculo_abastecimento', $data);
                $msg = "Registro atualizado com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            }


            $last_id = $data['id_ativo_veiculo_abastecimento'] ? $data['id_ativo_veiculo_abastecimento'] : $this->db->insert_id() ;
            echo redirect(base_url("ativo_veiculo/gerenciar/abastecimento/editar/{$data['id_ativo_veiculo']}/{$last_id}"));
            return;
        }

        $msg = "Veiculo não encontrado!";
        if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
        $this->session->set_flashdata('msg_erro', $msg);
        echo redirect(base_url("ativo_veiculo"));
    }


    public function abastecimento_deletar($id_ativo_veiculo, $id_ativo_veiculo_abastecimento, $returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }

        $abastecimento = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_abastecimento = {$id_ativo_veiculo_abastecimento}")
            ->get('ativo_veiculo_abastecimento')->num_rows() == 1;

        if (!$abastecimento) {
            $msg = "Lançamento de Abastecimento não encontrado!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/gerenciar/abastecimento/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_abastecimento($id_ativo_veiculo, $id_ativo_veiculo_abastecimento)) {
            $msg = "Lançamento Quilometragem não pode ser excluído!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/gerenciar/abastecimento/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'abastecimento', $id_ativo_veiculo_abastecimento);
        $this->db->where("id_ativo_veiculo_abastecimento = {$id_ativo_veiculo_abastecimento}")->delete('ativo_veiculo_abastecimento');

        if ($returnJson) return $this->json(['success' => true]);
        echo redirect(base_url("ativo_veiculo/gerenciar/abastecimento/{$id_ativo_veiculo}"));
        return true;
    }

    # Salvar KM
    public function quilometragem_salvar($returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $data['id_ativo_veiculo_quilometragem'] = $this->input->post('id_ativo_veiculo_quilometragem');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);
        $ultimo_km = $this->db->where("id_ativo_veiculo = {$data['id_ativo_veiculo']}")
                        ->order_by('data', 'desc')
                        ->limit(1)
                        ->get('ativo_veiculo_quilometragem')
                        ->row();

        if ($veiculo) {
            $veiculo_km = (int) $this->input->post('veiculo_km');
            if ($ultimo_km && $veiculo_km < $ultimo_km->veiculo_km) {
                $msg =  "KM atual deve ser maior que a quilometragem inicial do veículo e anterior lançada!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
                $this->session->set_flashdata('msg_erro', $msg);
                return $this->redirect($veiculo, 'quilometragem', $data);
            }
            
            $data['veiculo_km'] = $veiculo_km;
            $data['data'] = $this->input->post('data');

            if (!$data['id_ativo_veiculo_quilometragem']) {
                $this->db->insert('ativo_veiculo_quilometragem', $data);
                $msg = "Novo registro inserido com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            } else {
                $this->db->where('id_ativo_veiculo_quilometragem', $data['id_ativo_veiculo_quilometragem'])
                    ->update('ativo_veiculo_quilometragem', $data);
                $msg = "Registro atualizado com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            }

            $last_id = $data['id_ativo_veiculo_quilometragem'] ? $data['id_ativo_veiculo_quilometragem'] : $this->db->insert_id() ;
            echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/editar/{$data['id_ativo_veiculo']}/{$last_id}"));
            return;
        }

        $msg = "Veiculo não encontrado!";
        if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
        $this->session->set_flashdata('msg_erro', $msg);
        echo redirect(base_url("ativo_veiculo"));
    }

    public function quilometragem_deletar($id_ativo_veiculo, $id_ativo_veiculo_quilometragem, $returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }

        $quilometragem = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_quilometragem = {$id_ativo_veiculo_quilometragem}")
            ->get('ativo_veiculo_quilometragem')->num_rows() == 1;

        if (!$quilometragem) {
            $msg = "Lançamento de Quilometragem não encontrado!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_quilometragem($id_ativo_veiculo, $id_ativo_veiculo_quilometragem)) {
            $msg = "Lançamento Quilometragem não pode ser excluído!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'quilometragem', $id_ativo_veiculo_quilometragem);
        $this->db->where("id_ativo_veiculo_quilometragem = {$id_ativo_veiculo_quilometragem}")->delete('ativo_veiculo_quilometragem');

        if ($returnJson) return $this->json(['success' => true]);
        echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}"));
        return true;
    }


    public function count_operacao_horas($inicio, $fim)
    {
        return ((strtotime($fim) - strtotime($inicio)) / 60) / 60;
    }

    # Salvar Tempo de Operação para maquinas - Horimetro
    public function operacao_salvar($returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $data['id_ativo_veiculo_operacao'] = $this->input->post('id_ativo_veiculo_operacao');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $veiculo_horimetro = (int) $this->input->post('veiculo_horimetro');

            if ($veiculo->veiculo_horimetro_atual && $veiculo_horimetro < $veiculo->veiculo_horimetro_atual) {
                $msg =  "O valor atual deve ser maior que o valor do horimetro inicial do veículo e anterior lançada!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
                $this->session->set_flashdata('msg_erro', $msg);
                return $this->redirect($veiculo, 'operacao', $data);
            }
            
            $data['veiculo_horimetro'] = $veiculo_horimetro;
            $data['data'] = $this->input->post('data');

            if (!$data['id_ativo_veiculo_operacao']) {
                $this->db->insert('ativo_veiculo_operacao', $data);
                $msg = "Novo registro inserido com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            } else {
                $this->db->where('id_ativo_veiculo_operacao', $data['id_ativo_veiculo_operacao'])
                    ->update('ativo_veiculo_operacao', $data);
                $msg = "Registro atualizado com sucesso!";
                if ($returnJson) return $this->json(['message' => $msg, 'success' => true]);
                $this->session->set_flashdata('msg_success', $msg);
            }

            $last_id = $data['id_ativo_veiculo_operacao'] ? $data['id_ativo_veiculo_operacao'] : $this->db->insert_id() ;
            echo redirect(base_url("ativo_veiculo/gerenciar/operacao/editar/{$data['id_ativo_veiculo']}/{$last_id}"));
            return;
        }

        $msg = "Veiculo não encontrado!";
        if ($returnJson) return $this->json(['message' => $msg, 'success' => false]);
        $this->session->set_flashdata('msg_erro', $msg);
        echo redirect(base_url("ativo_veiculo"));
    }

    public function operacao_deletar($id_ativo_veiculo, $id_ativo_veiculo_operacao, $returnJson = false)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $msg = "Usuário sem permissão!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);
            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("/"));
            return false;
        }

        $operacao = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_operacao = {$id_ativo_veiculo_operacao}")
            ->get('ativo_veiculo_operacao')->num_rows() == 1;

        if (!$operacao) {
            $msg = "Operação não encontrada!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);

            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/gerenciar/operacao/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_operacao($id_ativo_veiculo, $id_ativo_veiculo_operacao)) {
            $msg = "Lançamento Operação não pode ser excluído!";
            if ($returnJson) return $this->json(['success' => false, 'message' => $msg]);

            $this->session->set_flashdata('msg_erro', $msg);
            echo redirect(base_url("ativo_veiculo/gerenciar/operacao/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'operacao', $id_ativo_veiculo_operacao);
        $this->db->where("id_ativo_veiculo_operacao = {$id_ativo_veiculo_operacao}")->delete('ativo_veiculo_operacao');

        if ($returnJson) return $this->json(['success' => true]);
        echo redirect(base_url("ativo_veiculo/gerenciar/operacao/{$id_ativo_veiculo}"));
        return true;
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

    public function manutencao_salvar()
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = (int) $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $data['id_fornecedor'] = (int) $this->input->post('id_fornecedor');
            $data['id_ativo_configuracao'] = (int) $this->input->post('id_ativo_configuracao');
            $data['id_ativo_veiculo_manutencao'] = (int) $this->input->post('id_ativo_veiculo_manutencao');
            $data['veiculo_km_atual'] = (int) $this->input->post('veiculo_km_atual');
            $data['veiculo_horimetro_atual'] = (int) $this->input->post('veiculo_horimetro_atual');
            $data['veiculo_km_proxima_revisao'] = (int) $this->input->post('veiculo_km_proxima_revisao');
            $data['veiculo_horimetro_proxima_revisao'] = (int) $this->input->post('veiculo_horimetro_proxima_revisao');
    
            if ($data['veiculo_km_atual'] < $veiculo->veiculo_km_atual) {
                $this->session->set_flashdata('msg_erro', "KM atual deve ser maior ou igual a quilometragem atual do veículo!");
                return $this->redirect($veiculo, 'manutencao', $data);
            }

            if ($data['veiculo_km_proxima_revisao'] > 0  && 
                ($data['veiculo_km_proxima_revisao'] < $veiculo->veiculo_km_atual || ((int) $data['veiculo_km_proxima_revisao'] < (int) $data['veiculo_km_atual']))) {
                $this->session->set_flashdata('msg_erro', "KM para a próxima revisão deve ser maior ou igual a quilometragem atual do veículo!");
                return $this->redirect($veiculo, 'manutencao', $data);
            }

            if ($data['veiculo_horimetro_atual'] < $veiculo->veiculo_horimetro_atual) {
                $this->session->set_flashdata('msg_erro', "Horimetro atual deve ser maior ou igual ao valor atual do veículo!");
                return $this->redirect($veiculo, 'manutencao', $data);
            }

            if ($data['veiculo_horimetro_proxima_revisao'] > 0  && 
                ($data['veiculo_horimetro_proxima_revisao'] < $veiculo->veiculo_horimetro_atual || ((int) $data['veiculo_horimetro_proxima_revisao'] < (int) $data['veiculo_horimetro_atual']))) {
                $this->session->set_flashdata('msg_erro', "Horimetro para a próxima revisão deve ser maior ou igual ao valor atual do veículo!");
                return $this->redirect($veiculo, 'manutencao', $data);
            }

            $data['veiculo_custo'] = $this->remocao_pontuacao($this->input->post('veiculo_custo'));
            $data['descricao'] = $this->input->post('descricao');
            $data['data_entrada'] = $this->input->post('data_entrada');
            $data['data_vencimento'] = $this->input->post('data_vencimento');
            
            if ($data['id_ativo_veiculo_manutencao'] == '' || !$data['id_ativo_veiculo_manutencao']) {
                if (!$data['data_vencimento']) {
                    unset($data['data_vencimento']);
                }
                
                $this->db->insert('ativo_veiculo_manutencao', $data);
                $this->db->insert('ativo_veiculo_quilometragem', ["data" =>  $data['data_entrada'], "veiculo_km" => $data['veiculo_km_atual']]);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_manutencao', $data['id_ativo_veiculo_manutencao'])
                    ->update('ativo_veiculo_manutencao', $data);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }

            $last_id = $data['id_ativo_veiculo_manutencao'] ? $data['id_ativo_veiculo_manutencao'] : $this->db->insert_id() ;
            if ($last_id) {
                $this->insert_km_and_operacao(
                    $veiculo, 
                    $data['veiculo_km_atual'],
                    $data['veiculo_horimetro_atual']
                );
                echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/editar/{$data['id_ativo_veiculo']}/{$last_id}"));
                return;
            }

            echo redirect(base_url("ativo_veiculo"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }


    public function manutencao_saida($id_ativo_veiculo, $id_ativo_veiculo_manutencao)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
        $manutencao = $this->db->where('id_ativo_veiculo_manutencao', $id_ativo_veiculo_manutencao)
            ->where('id_ativo_veiculo', $id_ativo_veiculo)
            ->get('ativo_veiculo_manutencao')->row();

        if ($this->input->method() == 'post' && ($veiculo && $manutencao)) {
            if (!isset($manutencao->ordem_de_servico) && strlen($manutencao->ordem_de_servico) > 0) {
                $this->session->set_flashdata('msg_info', "Deve anexar a Ordem de Serviço!");
                echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/$id_ativo_veiculo"));
                return;
            }

            $manutencao->data_saida = date('Y-m-d H:i:s', strtotime('now'));
            $this->db->where('id_ativo_veiculo_manutencao', $id_ativo_veiculo_manutencao)
                ->update('ativo_veiculo_manutencao', (array) $manutencao);
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/$id_ativo_veiculo"));
            return;
        }

        $this->session->set_flashdata('msg_erro', "Nenhuma manutenção encontrada!");
        echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/$id_ativo_veiculo"));
        return;
    }

    public function manutencao_deletar($id_ativo_veiculo, $id_ativo_veiculo_manutencao)
    {
        if (!in_array($this->user->nivel, [1, 2])) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $manutencao = $this->db
                ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
                ->where("id_ativo_veiculo_manutencao = {$id_ativo_veiculo_manutencao}")
                ->get('ativo_veiculo_manutencao')->num_rows() == 1;

        if (!$manutencao) {
            $this->session->set_flashdata('msg_erro', "Manutenção não encontrada!");
            echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/{$id_ativo_veiculo}"));
            return false;
        }

        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'ordem_de_servico', $id_ativo_veiculo_manutencao);
        $this->db->where("id_ativo_veiculo_manutencao = {$id_ativo_veiculo_manutencao}")->delete('ativo_veiculo_manutencao');
        echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/{$id_ativo_veiculo}"));
        return true;
    }

    public function ipva_salvar()
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $data['id_ativo_veiculo_ipva'] = $this->input->post('id_ativo_veiculo_ipva');
            $data['ipva_ano'] = $this->input->post('ipva_ano');
            $data['ipva_custo'] = $this->remocao_pontuacao($this->input->post('ipva_custo'));
            $data['ipva_data_vencimento'] = $this->input->post('ipva_data_vencimento');
            $data['ipva_data_pagamento'] = $this->input->post('ipva_data_pagamento');
            $data['ipva_data_vencimento'] = $this->input->post('ipva_data_vencimento');

            if ($data['id_ativo_veiculo_ipva'] == '' || !$data['id_ativo_veiculo_ipva']) {
                if ($this->ativo_veiculo_model->permit_add_ipva($data['id_ativo_veiculo'], $data['ipva_ano'])) {
                    $this->db->insert('ativo_veiculo_ipva', $data);
                    $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
                } else {
                    $this->session->set_flashdata('msg_erro', "Já existe um lançamento de IPVA pra o mesmo ano!");
                    echo redirect(base_url("ativo_veiculo/gerenciar/ipva/adicionar/" . $this->input->post('id_ativo_veiculo')));
                    return;
                }
            } else {
                $this->db->where('id_ativo_veiculo_ipva', $data['id_ativo_veiculo_ipva'])
                    ->update('ativo_veiculo_ipva', $data);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }

            $last_id = $data['id_ativo_veiculo_ipva'] ? $data['id_ativo_veiculo_ipva'] : $this->db->insert_id() ;
            echo redirect(base_url("ativo_veiculo/gerenciar/ipva/editar/{$data['id_ativo_veiculo']}/{$last_id}"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function ipva_deletar($id_ativo_veiculo, $id_ativo_veiculo_ipva)
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/gerenciar/ipva/{$id_ativo_veiculo}"));
            return false;
        }

        $ipva = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_ipva = {$id_ativo_veiculo_ipva}")
            ->get('ativo_veiculo_ipva')->num_rows() == 1;

        if (!$ipva) {
            $this->session->set_flashdata('msg_erro', "Lançamento IPVA não encontrado!");
            echo redirect(base_url("ativo_veiculo/gerenciar/ipva/{$id_ativo_veiculo}"));
            return false;
        }

        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/gerenciar/ipva/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_ipva($id_ativo_veiculo, $id_ativo_veiculo_ipva)) {
            $this->session->set_flashdata('msg_erro', "Lançamento IPVA não pode ser excluído!");
            echo redirect(base_url("ativo_veiculo/gerenciar/ipva/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'ipva', $id_ativo_veiculo_ipva);
        $this->db->where("id_ativo_veiculo_ipva = {$id_ativo_veiculo_ipva}")->delete('ativo_veiculo_ipva');
        echo redirect(base_url("ativo_veiculo/gerenciar/ipva/{$id_ativo_veiculo}"));
        return true;
    }


    public function seguro_salvar()
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $data['id_ativo_veiculo_seguro'] = $this->input->post('id_ativo_veiculo_seguro');
            $data['seguro_custo'] = $this->remocao_pontuacao($this->input->post('seguro_custo'));
            $data['carencia_inicio'] = $this->input->post('carencia_inicio');
            $data['carencia_fim'] = $this->input->post('carencia_fim');

            if ($data['id_ativo_veiculo_seguro'] == '' || !$data['id_ativo_veiculo_seguro']) {
                $this->db->insert('ativo_veiculo_seguro', $data);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_seguro', $data['id_ativo_veiculo_seguro'])
                    ->update('ativo_veiculo_seguro', $data);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }

            $last_id = $data['id_ativo_veiculo_seguro'] ? $data['id_ativo_veiculo_seguro'] : $this->db->insert_id() ;
            echo redirect(base_url("ativo_veiculo/gerenciar/seguro/editar/{$data['id_ativo_veiculo']}/{$last_id}"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function seguro_deletar($id_ativo_veiculo, $id_ativo_veiculo_seguro)
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $seguro = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_seguro = {$id_ativo_veiculo_seguro}")
            ->get('ativo_veiculo_seguro')->num_rows() == 1;

        if (!$seguro) {
            $this->session->set_flashdata('msg_erro', "Lançamento seguro não encontrado!");
            echo redirect(base_url("ativo_veiculo/gerenciar/seguro/{$id_ativo_veiculo}"));
            return false;
        }

        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/gerenciar/seguro/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_seguro($id_ativo_veiculo, $id_ativo_veiculo_seguro)) {
            $this->session->set_flashdata('msg_erro', "Lançamento seguro não pode ser excluído!");
            echo redirect(base_url("ativo_veiculo/gerenciar/seguro/{$id_ativo_veiculo}"));
            return false;
        }

        $this->deletar_anexos('ativo_veiculo', $id_ativo_veiculo, 'seguro', $id_ativo_veiculo_seguro);
        $this->db->where("id_ativo_veiculo_seguro = {$id_ativo_veiculo_seguro}")->delete('ativo_veiculo_seguro');
        echo redirect(base_url("ativo_veiculo/gerenciar/seguro/{$id_ativo_veiculo}"));
        return true;
    }

    function depreciacao_salvar()
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = !is_null($this->input->post('id_ativo_veiculo')) ? $this->input->post('id_ativo_veiculo') : '';
        $data['id_ativo_veiculo_depreciacao'] = $this->input->post('id_ativo_veiculo_depreciacao');

        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);
        $depreciacao = $this->ativo_veiculo_model->get_ativo_veiculo_depreciacao($data['id_ativo_veiculo'], $data['id_ativo_veiculo_depreciacao']);

        if (
            (!$data['id_ativo_veiculo_depreciacao'] && $veiculo) || 
            ($data['id_ativo_veiculo_depreciacao'] && ($veiculo && $depreciacao))
        ) {
            $referencia = $this->get_mes_referecia( $this->formata_mes_referecia(
                $this->input->post('fipe_mes_referencia'), 
                $this->input->post('fipe_ano_referencia'), 
            ));

            if(!$referencia || $referencia->ano > (int) date("Y")) {
                $this->session->set_flashdata('msg_erro', "Dados de referência inválidos ou futuro não permitido!");
                echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/{$data['id_ativo_veiculo']}"));
                return;
            }

            if(
                ($this->ativo_veiculo_model->permit_update_depreciacao($data['id_ativo_veiculo'], $referencia->id, $referencia->ano) && 
                !$data['id_ativo_veiculo_depreciacao']) ||
                $depreciacao 
            ) {
                $data['fipe_mes_referencia'] = $referencia->id;
                $data['fipe_ano_referencia'] = $referencia->ano;
                $data['fipe_valor'] = $this->formata_moeda_float($this->input->post('fipe_valor'));

                if ($data['id_ativo_veiculo_depreciacao'] == '' || !$data['id_ativo_veiculo_depreciacao']) {
                    $this->db->insert('ativo_veiculo_depreciacao', $data);
                    $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
                } else {
                    $this->db->where('id_ativo_veiculo_depreciacao', $data['id_ativo_veiculo_depreciacao'])
                        ->update('ativo_veiculo_depreciacao', $data);
                    $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
                }
                echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/{$data['id_ativo_veiculo']}"));
                return;
            }

            $this->session->set_flashdata('msg_erro', "Já existe um registro para o mês referência!");
            echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/{$data['id_ativo_veiculo']}"));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo ou registro de depreciação não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function fipe_get_veiculo_from_model($veiculo) {
        return $this->fipe_get_veiculo($this->tipos[$veiculo->tipo_veiculo], $veiculo->codigo_fipe, $veiculo->ano);
    }

    function depreciacao_atualizar($id_ativo_veiculo = null, $returnJson = false, $automation = false)
    {
        if ($this->user->nivel != 1 || ($this->user->nivel != 1 && !$automation)) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $data['id_ativo_veiculo'] = 
        !is_null($this->input->post('id_ativo_veiculo')) ? 
        $this->input->post('id_ativo_veiculo') : 
        $id_ativo_veiculo;

        $message = "Veiculo não encontrado!";
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);

        if ($veiculo) {
            $message = null;
            if($this->ativo_veiculo_model->permit_update_depreciacao($id_ativo_veiculo)) {
                $fipe = $this->fipe_get_veiculo_from_model($veiculo);
                if ($fipe->success) {
                    $data = [
                        'id_ativo_veiculo' => $id_ativo_veiculo,
                        'fipe_valor' => $fipe->data->fipe_valor,
                        'fipe_mes_referencia' =>  $fipe->data->fipe_mes_referencia,
                        'fipe_ano_referencia' =>  $fipe->data->fipe_ano_referencia,
                    ];
                    $this->db->insert('ativo_veiculo_depreciacao',  $data);
                }

                $message = $fipe->message;

                if ($returnJson) return $this->json(['success' => $fipe->success , 'message' => $message]);
                $this->session->set_flashdata($fipe->success ?'msg_success' : 'msg_erro', $message);
                echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/{$id_ativo_veiculo}"));
                return;
            }

            $message =  "Já existe um registro para o mês atual!";
            if ($returnJson) return $this->json(['success' => true, 'message' => $message]);
            $this->session->set_flashdata('msg_erro', $message);
            echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/{$id_ativo_veiculo}"));
            return;
        }

        if ($returnJson) return $this->json(['success' => false, 'message' => $message]);
        $this->session->set_flashdata('msg_erro', $message);
        echo redirect(base_url("ativo_veiculo"));
        return;
    }

    public function depreciacao_deletar($id_ativo_veiculo, $id_ativo_veiculo_depreciacao)
    {
        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("/"));
            return false;
        }

        $depreciacao = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_depreciacao = {$id_ativo_veiculo_depreciacao}")
            ->get('ativo_veiculo_depreciacao')->num_rows() == 1;

        if (!$depreciacao) {
            $this->session->set_flashdata('msg_erro', "Lançamento depreciacao não encontrado!");
            echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/{$id_ativo_veiculo}"));
            return false;
        }

        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao)) {
            $this->session->set_flashdata('msg_erro', "Lançamento depreciacao não pode ser excluído!");
            echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/{$id_ativo_veiculo}"));
            return false;
        }

        $this->db->where("id_ativo_veiculo_depreciacao = {$id_ativo_veiculo_depreciacao}")->delete('ativo_veiculo_depreciacao');
        echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/{$id_ativo_veiculo}"));
        return true;
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