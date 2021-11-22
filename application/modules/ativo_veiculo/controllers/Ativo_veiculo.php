<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

# Require AutoLoad
require APPPATH . "../vendor/autoload.php";

# Iniciando a FIPE - Carros, motos e caminhões
require APPPATH . "../vendor/deividfortuna/fipe/src/IFipe.php";
require APPPATH . "../vendor/deividfortuna/fipe/src/FipeCarros.php";
require APPPATH . "../vendor/deividfortuna/fipe/src/FipeMotos.php";
require APPPATH . "../vendor/deividfortuna/fipe/src/FipeCaminhoes.php";

use DeividFortuna\Fipe\IFipe;
use DeividFortuna\Fipe\FipeCarros;
use DeividFortuna\Fipe\FipeMotos;
use DeividFortuna\Fipe\FipeCaminhoes;

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class Ativo_veiculo  extends MY_Controller
{

    protected $ultimo_erro_upload_arquivo;

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
    }

    # Testando tipos de veiculos pela FIPE
    function fipe_get_marca()
    {
        $tipo = $this->input->post('tipo_veiculo');

        switch ($tipo) {
            case 'carro':
                $marcas = FipeCarros::getMarcas();
                break;

            case 'moto':
                $marcas = FipeMotos::getMarcas();
                break;

            case 'caminhao':
                $marcas = FipeCaminhoes::getMarcas();
                break;
        }

        foreach ($marcas as $marca) {
            echo "<option value=" . $marca['codigo'] . ">" . $marca['nome'] . "</option>";
        }
    }

    # Modelos - Tabela FIPE
    public function fipe_get_modelos()
    {
        $tipo = $this->input->post('tipo_veiculo');
        $codMarca = $this->input->post('id_marca');

        switch ($tipo) {
            case 'carro':
                $modelos = FipeCarros::getModelos($codMarca);
                break;

            case 'moto':
                $modelos = FipeMotos::getModelos($codMarca);
                break;

            case 'caminhao':
                $modelos = FipeCaminhoes::getModelos($codMarca);
                break;
        }

        foreach ($modelos['modelos'] as $modelo) {
            echo "<option value=" . $modelo['codigo'] . ">" . $modelo['nome'] . "</option>";
        }
    }


    # Anos - Tabela FIPE
    public function fipe_get_anos()
    {
        $tipo = $this->input->post('tipo_veiculo');
        $marca = $this->input->post('id_marca');
        $modelo = $this->input->post('id_modelo');

        switch ($tipo) {
            case 'carro':
                $anos = FipeCarros::getAnos($marca, $modelo);
                break;

            case 'moto':
                $anos = FipeMotos::getAnos($marca, $modelo);
                break;

            case 'caminhao':
                $anos = FipeCaminhoes::getAnos($marca, $modelo);
                break;
        }

        foreach ($anos as $modelo) {
            echo "<option value=" . $modelo['codigo'] . ">" . $modelo['nome'] . "</option>";
        }
    }

    # Anos - Tabela FIPE
    public function fipe_get_veiculos()
    {
        $tipo = $this->input->post('tipo_veiculo');
        $marca = $this->input->post('id_marca');
        $modelo = $this->input->post('id_modelo');
        $ano = $this->input->post('ano');

        switch ($tipo) {
            case 'carro':
                $veiculos = FipeCarros::getVeiculo($marca, $modelo, $ano);
                break;

            case 'moto':
                $veiculos = FipeMotos::getVeiculo($marca, $modelo, $ano);
                break;

            case 'caminhao':
                $veiculos = FipeCaminhoes::getVeiculo($marca, $modelo, $ano);
                break;
        }
        echo json_encode($veiculos);
    }

    # Modelos - Tabela FIPE
    public function fipe_veiculo($tipo, $id_marca, $id_modelo)
    {
        $marca = null;
        $marcas = [];
        switch ($tipo) {
            case 'carro':
                $marcas = FipeCarros::getMarcas();
                break;

            case 'moto':
                $marcas = FipeMotos::getMarcas();
                break;

            case 'caminhao':
                $marcas = FipeCaminhoes::getMarcas();
                break;
        }

        if (is_array($marcas)) {
            foreach ($marcas as $mar) {
                if ($mar['codigo'] == $id_marca) {
                    $marca = $mar['nome'];
                    break;
                }
            }
        }

        switch ($tipo) {
            case 'carro':
                $modelos = FipeCarros::getModelos($id_marca);
                break;

            case 'moto':
                $modelos = FipeMotos::getModelos($id_marca);
                break;

            case 'caminhao':
                $modelos = FipeCaminhoes::getModelos($id_marca);
                break;
        }

        if (is_array($modelos['modelos'])) {
            foreach ($modelos['modelos'] as $modelo) {
                if (!is_bool($modelo) && $modelo['codigo'] == $id_modelo) {
                    return (object) [
                        'marca' => $marca,
                        'modelo' => $modelo['nome'],
                    ];
                }
            }
        }

        return (object) [
            'marca' => '-',
            'modelo' => '-',
        ];
    }

    function index($subitem = null)
    {
        $data['lista'] = $this->ativo_veiculo_model->get_lista();
        $subitem = ($subitem == null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar()
    {
        $this->get_template('index_form');
    }

    function editar($id_ativo_veiculo)
    {
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($id_ativo_veiculo);
        if ($veiculo) {
            $this->get_template('index_form', ["detalhes" => $veiculo]);
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veículo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    function salvar()
    {
        $data['id_ativo_veiculo'] = !is_null($this->input->post('id_ativo_veiculo')) ? $this->input->post('id_ativo_veiculo') : '';
        $data['tipo_veiculo'] = $this->input->post('tipo_veiculo');
        $data['id_marca'] = $this->input->post('id_marca');
        $data['id_modelo'] = $this->input->post('id_modelo');
        $data['ano'] = $this->input->post('ano');
        $data['veiculo'] = $this->input->post('veiculo');
        $data['veiculo_km'] = $this->input->post('veiculo_km');
        $data['veiculo_km_data'] = $this->input->post('veiculo_km_data');

        if (isset($data['id_ativo_veiculo'])) {
            $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo($data['id_ativo_veiculo']);
            foreach (['tipo_veiculo', 'id_marca', 'id_modelo', 'ano', 'veiculo'] as $item) {
                if ($this->input->post($item)) {
                    $data[$item] = $this->input->post($item);
                } else {
                    $data[$item] = $veiculo->$item;
                }
            }
        }

        $valor_fipe = str_replace("R$ ", "", $this->input->post('valor_fipe'));
        $valor_fipe = str_replace(".", "", $valor_fipe);
        $valor_fipe = str_replace(",", ".", $valor_fipe);

        $valor_funcionario = str_replace("R$ ", "", $this->input->post('valor_funcionario'));
        $valor_funcionario = str_replace(".", "", $valor_funcionario);
        $valor_funcionario = str_replace(",", ".", $valor_funcionario);

        $valor_adicional = str_replace("R$ ", "", $this->input->post('valor_adicional'));
        $valor_adicional = str_replace(".", "", $valor_adicional);
        $valor_adicional = str_replace(",", ".", $valor_adicional);

        $data['valor_fipe'] = $valor_fipe;
        $data['valor_funcionario'] = $valor_funcionario;
        $data['valor_adicional'] = $valor_adicional;
        $data['codigo_fipe'] = $this->input->post('codigo_fipe');
        $data['fipe_mes_referencia'] = $this->input->post('fipe_mes_referencia');
        $data['veiculo_placa'] = $this->input->post('veiculo_placa');
        $data['veiculo_renavam'] = $this->input->post('veiculo_renavam');
        $data['veiculo_observacoes'] = $this->input->post('veiculo_observacoes');
        $data['situacao'] = $this->input->post('situacao');

        $this->ativo_veiculo_model->salvar_formulario($data);
        if ($data['id_ativo_veiculo'] == '' || !$data['id_ativo_veiculo']) {
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
        }
        echo redirect(base_url("ativo_veiculo"));
    }

    public function gerenciar($entrada = null, $tipo = null, $id_ativo_veiculo = null, $id_gerenciar_item = null)
    {
        if (in_array($tipo, ['adicionar', 'editar'])) {
            $data['id_ativo_veiculo'] = $id_ativo_veiculo;
        } else {
            $data['id_ativo_veiculo'] = $tipo;
            $id_ativo_veiculo = $tipo;
        }

        $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
        if ($data['dados_veiculo']) {
            $data['dados_veiculo']->fabricante = $this->fipe_veiculo($data['dados_veiculo']->tipo_veiculo, $data['dados_veiculo']->id_marca, $data['dados_veiculo']->id_modelo);
        }
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');

        switch ($entrada) {
            default:
                echo redirect(base_url("ativo_veiculo"));
                return;
                break;

            case 'quilometragem':
                if ($tipo == 'adicionar') {
                    $template = "_form";
                    $data['ultimo_km'] = $this->db
                        ->where("id_ativo_veiculo = {$data['id_ativo_veiculo']}")
                        ->order_by('data', 'desc')
                        ->limit(1)
                        ->get('ativo_veiculo_quilometragem')
                        ->row();

                } elseif ($tipo == 'editar') {
                    $data['quilometragem'] = $this->db->where('id_ativo_veiculo_quilometragem', $id_gerenciar_item)
                        ->where('id_ativo_veiculo', $id_ativo_veiculo)
                        ->get('ativo_veiculo_quilometragem')->row();

                    $data['ultimo_km'] = $this->db
                        ->where("id_ativo_veiculo = {$data['id_ativo_veiculo']}")
                        ->order_by('data', 'desc')
                        ->limit(1)
                        ->get('ativo_veiculo_quilometragem')
                        ->row();

                    if ($data['ultimo_km']->id_ativo_veiculo_quilometragem == $data['quilometragem']->id_ativo_veiculo_quilometragem) {
                        $data['ultimo_km'] = null;
                    }    
                        
                    $template = "_form";
                } elseif ($tipo == 'comprovante') {
                    $pth = file_get_contents("assets/uploads/comprovante_fiscal/" . $id_ativo_veiculo);
                    $nme = date("dmyis") . $id_ativo_veiculo;
                    force_download($nme, $pth);
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_km_lista($tipo);
                }
                break;

            case 'manutencao':
                if ($tipo == 'adicionar') {
                    $template = "_form";
                    $data['tipo_servico'] = $this->ativo_veiculo_model->get_tipo_servico(10, 'Serviços Mecânicos');
                    $data['fornecedores'] = $this->ativo_veiculo_model->get_fornecedor();
                } elseif ($tipo == 'editar') {
                    $data['tipo_servico'] = $this->ativo_veiculo_model->get_tipo_servico(10, 'Serviços Mecânicos');
                    $data['fornecedores'] = $this->ativo_veiculo_model->get_fornecedor();
                    $data['manutencao'] = $this->db->where('id_ativo_veiculo_manutencao', $id_gerenciar_item)
                        ->where('id_ativo_veiculo', $id_ativo_veiculo)
                        ->get('ativo_veiculo_manutencao')->row();
                    $template = "_form";
                } elseif ($tipo == 'comprovante') {
                    $pth = file_get_contents("assets/uploads/ordem_de_servico/" . $id_ativo_veiculo);
                    $nme = date("dmyis") . $id_ativo_veiculo;
                    force_download($nme, $pth);
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
                } elseif ($tipo == 'comprovante') {
                    $pth = file_get_contents("assets/uploads/comprovante_ipva/" . $id_ativo_veiculo);
                    $nme = date("dmyis") . $id_ativo_veiculo;
                    force_download($nme, $pth);
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
                } elseif ($tipo == 'comprovante') {
                    $pth = file_get_contents("assets/uploads/contrato_seguro/" . $id_ativo_veiculo);
                    $nme = date("dmyis") . $id_ativo_veiculo;
                    force_download($nme, $pth);
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_seguro_lista($tipo);
                }
                break;

            case 'depreciacao':
                $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_depreciacao_lista($id_ativo_veiculo);
                if ($tipo == 'adicionar') {
                    $template = "_form";
                } elseif ($tipo == 'editar') {
                    $template = "_form";
                    $data['depreciacao'] = $this->db->where('id_ativo_veiculo_depreciacao', $id_gerenciar_item)
                    ->where('id_ativo_veiculo', $id_ativo_veiculo)
                    ->get('ativo_veiculo_depreciacao')->row();
                } else {
                    $template = "";
                    $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($tipo);
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_depreciacao_lista($tipo);
                }
                break;
        }

        $this->get_template("gerenciar_" . $entrada . $template, $data);
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

    # Salvar KM
    public function quilometragem_salvar()
    {
        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $data['id_ativo_veiculo_quilometragem'] = $this->input->post('id_ativo_veiculo_quilometragem');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($data['id_ativo_veiculo']);
        $ultimo_km = $this->db->where("id_ativo_veiculo = {$data['id_ativo_veiculo']}")
                        ->order_by('data', 'desc')
                        ->limit(1)
                        ->get('ativo_veiculo_quilometragem')
                        ->row();

        if ($veiculo) {
            $veiculo_km = (int) $this->input->post('veiculo_km');
            if ($ultimo_km && $veiculo_km < $ultimo_km->veiculo_km) {
                $this->session->set_flashdata('msg_erro', "KM atual deve ser maior que a quilometragem inicial do veículo e anterior lançada!");
                return $this->redirect($veiculo, 'quilometragem', $data);
            }
            
            $data['veiculo_km'] = $veiculo_km;
            $data['veiculo_litros'] = (float) $this->remocao_pontuacao($this->input->post('veiculo_litros'));
            $data['veiculo_custo'] = (float) $this->remocao_pontuacao($this->input->post('veiculo_custo'));
            $data['data'] = $this->input->post('veiculo_km_data');

            if (isset($_FILES['comprovante_fiscal']) && ($_FILES['comprovante_fiscal']['error'] == 0 && $_FILES['comprovante_fiscal']['size'] > 0)) {
                $data['comprovante_fiscal'] = (isset($_FILES['comprovante_fiscal']) ? $this->upload_arquivo('comprovante_fiscal') : '');
                if (!$data['comprovante_fiscal'] || $data['comprovante_fiscal'] == '') {
                    $this->session->set_flashdata('msg_erro', "O tamanho do comprovante deve ser menor ou igual a " . ini_get('upload_max_filesize'));
                    return $this->redirect($veiculo, 'quilometragem', $data);
                }
            }

            if (!$data['id_ativo_veiculo_quilometragem']) {
                $this->db->insert('ativo_veiculo_quilometragem', $data);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_quilometragem', $data['id_ativo_veiculo_quilometragem'])
                    ->update('ativo_veiculo_quilometragem', $data);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }

            if (isset($_FILES['comprovante_fiscal']) && ($_FILES['comprovante_fiscal']['error'] == 0 && $_FILES['comprovante_fiscal']['size'] > 0)) {
                $this->salvar_anexo(
                    9,
                    $data,
                    $data['id_ativo_veiculo'],
                    'id_ativo_veiculo_quilometragem',
                    'comprovante_fiscal',
                    "quilometragem"
                );
            }

            echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/" . $this->input->post('id_ativo_veiculo')));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function quilometragem_deletar($id_ativo_veiculo, $id_ativo_veiculo_quilometragem){
        $quilometragem = $this->db
            ->where("id_ativo_veiculo = {$id_ativo_veiculo}")
            ->where("id_ativo_veiculo_quilometragem = {$id_ativo_veiculo_quilometragem}")
            ->get('ativo_veiculo_quilometragem')->num_rows() == 1;

        if (!$quilometragem) {
            $this->session->set_flashdata('msg_erro', "Manutenção não encontrada!");
            echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}"));
            return false;
        }

        if ($this->user->nivel != 1) {
            $this->session->set_flashdata('msg_erro', "Usuário sem permissão!");
            echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}"));
            return false;
        }

        if (!$this->ativo_veiculo_model->permit_delete_quilometragem($id_ativo_veiculo, $id_ativo_veiculo_quilometragem)) {
            $this->session->set_flashdata('msg_erro', "Lançamento Quilometragem não pode ser excluído!");
            echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}"));
            return false;
        }

        $this->db->where("id_ativo_veiculo_quilometragem = {$id_ativo_veiculo_quilometragem}")->delete('ativo_veiculo_quilometragem');
        echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/{$id_ativo_veiculo}"));
        return true;
    }

    public function manutencao_salvar()
    {
        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($data['id_ativo_veiculo']);
        if ($veiculo) {
            $veiculo_km = (int) $veiculo->veiculo_km;
            $data['id_fornecedor'] = $this->input->post('id_fornecedor');
            $data['id_ativo_configuracao'] = $this->input->post('id_ativo_configuracao');
            $data['id_ativo_veiculo_manutencao'] = $this->input->post('id_ativo_veiculo_manutencao');
            $data['veiculo_km_atual'] = (int) $this->input->post('veiculo_km_atual');
            $data['ordem_de_servico'] = $this->input->post('ordem_de_servico');

            if ($data['veiculo_km_atual'] < $veiculo_km) {
                $this->session->set_flashdata('msg_erro', "KM atual deve ser maior ou igual a quilometragem atual do veículo!");
                return $this->redirect($veiculo, 'manutencao', $data);
            }

            $data['veiculo_custo'] = $this->remocao_pontuacao($this->input->post('veiculo_custo'));
            $data['descricao'] = $this->input->post('descricao');
            $data['data_entrada'] = $this->input->post('data_entrada');
            $data['data_vencimento'] = $this->input->post('data_vencimento');

            if (isset($_FILES['ordem_de_servico']) && ($_FILES['ordem_de_servico']['error'] == 0 && $_FILES['ordem_de_servico']['size'] > 0)) {
                $data['ordem_de_servico'] = $this->upload_arquivo('ordem_de_servico');
                if (!$data['ordem_de_servico'] || $data['ordem_de_servico'] == '') {
                    $this->session->set_flashdata('msg_erro', "O tamanho do comprovante deve ser menor ou igual a " . ini_get('upload_max_filesize'));
                    return $this->redirect($veiculo, 'manutencao', $data, $data['id_ativo_veiculo_manutencao'] ? 'editar' : 'adicionar');
                }
            }

            if ($data['id_ativo_veiculo_manutencao'] == '' || !$data['id_ativo_veiculo_manutencao']) {
                if (!$data['data_vencimento']) {
                    unset($data['data_vencimento']);
                }
                
                $this->db->insert('ativo_veiculo_manutencao', $data);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_manutencao', $data['id_ativo_veiculo_manutencao'])
                    ->update('ativo_veiculo_manutencao', $data);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }

            if (isset($_FILES['ordem_de_servico']) && ($_FILES['ordem_de_servico']['error'] == 0 && $_FILES['ordem_de_servico']['size'] > 0)) {
                $this->salvar_anexo(
                    9,
                    $data,
                    $data['id_ativo_veiculo'],
                    'id_ativo_veiculo_manutencao',
                    'ordem_de_servico',
                    "manutencao",
                    $data['id_ativo_configuracao']
                );
            }

            echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/" . $this->input->post('id_ativo_veiculo')));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }


    public function manutencao_saida($id_ativo_veiculo, $id_ativo_veiculo_manutencao)
    {
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
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

        $this->db->where("id_ativo_veiculo_manutencao = {$id_ativo_veiculo_manutencao}")->delete('ativo_veiculo_manutencao');
        echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/{$id_ativo_veiculo}"));
        return true;
    }

    public function ipva_salvar()
    {
        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($data['id_ativo_veiculo']);

        if ($veiculo) {
            $data['id_ativo_veiculo_ipva'] = $this->input->post('id_ativo_veiculo_ipva');
            $data['ipva_ano'] = $this->input->post('ipva_ano');
            $data['ipva_custo'] = $this->remocao_pontuacao($this->input->post('ipva_custo'));
            $data['ipva_data_vencimento'] = $this->input->post('ipva_data_vencimento');
            $data['ipva_data_pagamento'] = $this->input->post('ipva_data_pagamento');
            $data['ipva_data_vencimento'] = $this->input->post('ipva_data_vencimento');

            if (isset($_FILES['comprovante_ipva']) && ($_FILES['comprovante_ipva']['error'] == 0 && $_FILES['comprovante_ipva']['size'] > 0)) {
                $data['comprovante_ipva'] = ($_FILES['comprovante_ipva'] ? $this->upload_arquivo('comprovante_ipva') : '');
                if (!$data['comprovante_ipva'] || $data['comprovante_ipva'] == '') {
                    $this->session->set_flashdata('msg_erro', "O tamanho do comprovante deve ser menor ou igual a " . ini_get('upload_max_filesize'));
                    return $this->redirect($veiculo, 'ipva', $data);
                }
            }

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

            if (isset($_FILES['comprovante_ipva']) && ($_FILES['comprovante_ipva']['error'] == 0 && $_FILES['comprovante_ipva']['size'] > 0)) {
                $this->salvar_anexo(
                    9,
                    $data,
                    $data['id_ativo_veiculo'],
                    'id_ativo_veiculo_ipva',
                    'comprovante_ipva',
                    "ipva"
                );
            }

            echo redirect(base_url("ativo_veiculo/gerenciar/ipva/" . $this->input->post('id_ativo_veiculo')));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function ipva_deletar($id_ativo_veiculo, $id_ativo_veiculo_ipva)
    {
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

        $this->db->where("id_ativo_veiculo_ipva = {$id_ativo_veiculo_ipva}")->delete('ativo_veiculo_ipva');
        echo redirect(base_url("ativo_veiculo/gerenciar/ipva/{$id_ativo_veiculo}"));
        return true;
    }


    public function seguro_salvar()
    {
        $data['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($data['id_ativo_veiculo']);

        if ($veiculo) {
            $data['id_ativo_veiculo_seguro'] = $this->input->post('id_ativo_veiculo_seguro');
            $data['seguro_custo'] = $this->remocao_pontuacao($this->input->post('seguro_custo'));
            $data['carencia_inicio'] = $this->input->post('carencia_inicio');
            $data['carencia_fim'] = $this->input->post('carencia_fim');

            if (isset($_FILES['contrato_seguro']) && ($_FILES['contrato_seguro']['error'] == 0 && $_FILES['contrato_seguro']['size'] > 0)) {
                $data['contrato_seguro'] = ($_FILES['contrato_seguro'] ? $this->upload_arquivo('contrato_seguro') : '');
                if (!$data['contrato_seguro'] || $data['contrato_seguro'] == '') {
                    $this->session->set_flashdata('msg_erro', "O tamanho do comprovante deve ser menor ou igual a " . ini_get('upload_max_filesize'));
                    return $this->redirect($veiculo, 'seguro', $data);
                }
            }

            if ($data['id_ativo_veiculo_seguro'] == '' || !$data['id_ativo_veiculo_seguro']) {
                $this->db->insert('ativo_veiculo_seguro', $data);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_seguro', $data['id_ativo_veiculo_seguro'])
                    ->update('ativo_veiculo_seguro', $data);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }

            if (isset($_FILES['contrato_seguro']) && ($_FILES['contrato_seguro']['error'] == 0 && $_FILES['contrato_seguro']['size'] > 0)) {
                $this->salvar_anexo(
                    9,
                    $data,
                    $data['id_ativo_veiculo'],
                    'id_ativo_veiculo_seguro',
                    'contrato_seguro',
                    "seguro"
                );
            }

            echo redirect(base_url("ativo_veiculo/gerenciar/seguro/" . $this->input->post('id_ativo_veiculo')));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function seguro_deletar($id_ativo_veiculo, $id_ativo_veiculo_seguro)
    {
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

        $this->db->where("id_ativo_veiculo_seguro = {$id_ativo_veiculo_seguro}")->delete('ativo_veiculo_seguro');
        echo redirect(base_url("ativo_veiculo/gerenciar/seguro/{$id_ativo_veiculo}"));
        return true;
    }

    function depreciacao_salvar()
    {
        $data['id_ativo_veiculo'] = !is_null($this->input->post('id_ativo_veiculo')) ? $this->input->post('id_ativo_veiculo') : '';
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($data['id_ativo_veiculo']);

        if ($veiculo) {
            $valor_fipe = str_replace("R$", "", $this->input->post('valor_fipe'));
            $valor_fipe = str_replace(".", "", $valor_fipe);
            $valor_fipe = str_replace(",", ".", $valor_fipe);
            $data['veiculo_valor_fipe'] = trim($valor_fipe);

            $valor_depreciacao = str_replace("R$", "", $this->input->post('veiculo_valor_depreciacao'));
            $valor_depreciacao = str_replace(".", "", $valor_depreciacao);
            $valor_depreciacao = str_replace(",", ".", $valor_depreciacao);
            $data['veiculo_valor_depreciacao'] = trim($valor_depreciacao);

            $data['fipe_mes_referencia'] = $this->input->post('fipe_mes_referencia');
            $data['veiculo_km'] = $this->input->post('veiculo_km');
            $data['veiculo_observacoes'] = $this->input->post('veiculo_observacoes');
            $data['id_ativo_veiculo_depreciacao'] = $this->input->post('id_ativo_veiculo_depreciacao');

            if ($data['id_ativo_veiculo_depreciacao'] == '' || !$data['id_ativo_veiculo_depreciacao']) {
                $this->db->insert('ativo_veiculo_depreciacao', $data);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_depreciacao', $data['id_ativo_veiculo_depreciacao'])
                    ->update('ativo_veiculo_depreciacao', $data);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }
            echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/" . $this->input->post('id_ativo_veiculo')));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function depreciacao_deletar($id_ativo_veiculo, $id_ativo_veiculo_depreciacao)
    {
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
        if ($this->ativo_veiculo_model->permit_delete($id_ativo_veiculo) && $this->user->nivel == 1) {
            return $this->db->where('id_ativo_veiculo', $id_ativo_veiculo)->delete('ativo_veiculo');
        }
        $this->session->set_flashdata('msg_erro', "Veículo contém itens lançados, não pode ser excluído!");
        echo redirect(base_url("ativo_veiculo"));
    }
}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */