<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');
    
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
class Ativo_veiculo  extends MY_Controller {

    private $ultimo_erro_upload_arquivo;

    function __construct() {
        parent::__construct();
        $this->load->model('ativo_veiculo_model');
        $this->load->helper('download');

        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login        
    }

    # Testando tipos de veiculos pela FIPE
    function fipe_get_marca(){
        $tipo = $this->input->post('tipo_veiculo');
        
        switch($tipo){
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

        foreach($marcas as $marca){
            echo "<option value=".$marca['codigo'].">".$marca['nome']."</option>";
        }

    }

    # Modelos - Tabela FIPE
    public function fipe_get_modelos(){
        $tipo = $this->input->post('tipo_veiculo');
        $codMarca = $this->input->post('id_marca');
        
        switch($tipo){
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

        foreach($modelos['modelos'] as $modelo){
            echo "<option value=".$modelo['codigo'].">".$modelo['nome']."</option>";
        }
    }


    # Anos - Tabela FIPE
    public function fipe_get_anos(){
        $tipo = $this->input->post('tipo_veiculo');
        $marca = $this->input->post('id_marca');
        $modelo = $this->input->post('id_modelo');
      
        switch($tipo){
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

        foreach($anos as $modelo){
            echo "<option value=".$modelo['codigo'].">".$modelo['nome']."</option>";
        }
    }

    # Anos - Tabela FIPE
    public function fipe_get_veiculos(){
        $tipo = $this->input->post('tipo_veiculo');
        $marca = $this->input->post('id_marca');
        $modelo = $this->input->post('id_modelo');
        $ano = $this->input->post('ano');
      
        switch($tipo){
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
    public function fipe_veiculo($tipo, $id_marca, $id_modelo){
        $marca = null;
        $marcas = [];
        switch($tipo){
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
            foreach($marcas as $mar){
                if ($mar['codigo'] == $id_marca) {
                    $marca = $mar['nome'];
                    break;
                }
            }
        }

        switch($tipo){
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
            foreach($modelos['modelos'] as $modelo){
                if ($modelo['codigo'] == $id_modelo) {
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

    function index($subitem=null) {
        $data['lista'] = $this->ativo_veiculo_model->get_lista();
    	$subitem = ($subitem==null ? 'index' : $subitem);
        $this->get_template($subitem, $data);
    }

    function adicionar(){
    	$this->get_template('index_form');
    }

    function salvar(){
        $data['id_ativo_veiculo'] = !is_null($this->input->post('id_ativo_veiculo')) ? $this->input->post('id_ativo_veiculo') : '';
        $data['tipo_veiculo'] = $this->input->post('tipo_veiculo');
        $data['id_marca'] = $this->input->post('id_marca');
        $data['id_modelo'] = $this->input->post('id_modelo');
        $data['ano'] = $this->input->post('ano');
        $data['veiculo'] = $this->input->post('veiculo');
        $data['veiculo_km'] = $this->input->post('veiculo_km');

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

        $tratamento = $this->ativo_veiculo_model->salvar_formulario($data);
        if($data['id_ativo_veiculo']=='' || !$data['id_ativo_veiculo']){
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("ativo_veiculo"));

    }

    public function gerenciar($entrada=null, $tipo=null, $id_ativo_veiculo=null){
        $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
        if ($data['dados_veiculo']){
            $data['dados_veiculo']->fabricante = $this->fipe_veiculo($data['dados_veiculo']->tipo_veiculo, $data['dados_veiculo']->id_marca, $data['dados_veiculo']->id_modelo);
        }
        $data['upload_max_filesize'] = ini_get('upload_max_filesize');

        switch ($entrada) {
            default:
                echo redirect(base_url("ativo_veiculo"));
                return;  
            break;

            case 'quilometragem':
                if($tipo=='adicionar'){
                    $template = "_form";
                    $data['id_ativo_veiculo'] = $id_ativo_veiculo;                    
                } elseif($tipo=='editar') {
                    $template = "_form";
                    
                } elseif($tipo=='comprovante'){
                    $pth = file_get_contents("assets/uploads/comprovante_fiscal/".$id_ativo_veiculo);
                    $nme = date("dmyis").$id_ativo_veiculo;
                    force_download($nme, $pth);                    
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_km_lista($tipo);     
                    $data['id_ativo_veiculo'] = $tipo;                 
                }
            break;
            
            case 'manutencao':
                if($tipo=='adicionar'){
                    $template = "_form";
                    $data['tipo_servico'] = $this->ativo_veiculo_model->get_tipo_servico(10);
                    $data['fornecedores'] = $this->ativo_veiculo_model->get_fornecedor();
                    $data['id_ativo_veiculo'] = $id_ativo_veiculo;
                } elseif($tipo=='editar') {
                    $template = "_form";
                } elseif($tipo=='comprovante') {
                    $pth = file_get_contents("assets/uploads/ordem_de_servico/".$id_ativo_veiculo);
                    $nme = date("dmyis").$id_ativo_veiculo;
                    force_download($nme, $pth);  
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_manutencao_lista($tipo);  
                    $data['id_ativo_veiculo'] = $tipo;                    
                }
            break;

            case 'ipva':
                if($tipo=='adicionar'){
                    $template = "_form";
                    $data['id_ativo_veiculo'] = $id_ativo_veiculo;
                } elseif($tipo=='editar') {
                    $template = "_form";
                } elseif($tipo=='comprovante') {
                    $pth = file_get_contents("assets/uploads/comprovante_ipva/".$id_ativo_veiculo);
                    $nme = date("dmyis").$id_ativo_veiculo;
                    force_download($nme, $pth);  
                } else {
                    $template = "";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_ipva_lista($tipo);  
                    $data['id_ativo_veiculo'] = $tipo;                    
                }
            break;

            case 'depreciacao':
                if($tipo=='adicionar'){
                    $template = "_form";
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_depreciacao_lista($id_ativo_veiculo);  
                    $data['id_ativo_veiculo'] = $id_ativo_veiculo;
                } elseif($tipo=='editar') {
                    $template = "_form";
                } else {
                    $template = "";
                    $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($tipo);
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_depreciacao_lista($tipo);  
                    $data['id_ativo_veiculo'] = $tipo;                    
                }
            break;
        }

        $this->get_template("gerenciar_".$entrada.$template, $data);
    }

    private function redirect($veiculo, $tipo){
        $url = "ativo_veiculo";
        if (!$dados["id_ativo_veiculo_{$tipo}"]) {
            $url .= "/gerenciar/{$tipo}/adicionar/{$veiculo->id_ativo_veiculo}";
        } else {
            $url .= "/gerenciar/{$tipo}/{$veiculo->id_ativo_veiculo}";
        }
        echo redirect(base_url($url));
        return;
    } 

    # Salvar KM
    public function quilometragem_salvar(){
        $dados['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $dados['id_ativo_veiculo_quilometragem'] = $this->input->post('id_ativo_veiculo_quilometragem');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($dados['id_ativo_veiculo']);
        
        if ($veiculo) {
            $veiculo_km = (int) $veiculo->veiculo_km;
            $veiculo_km_inicial = (int) $this->input->post('veiculo_km_inicial');
            $veiculo_km_final = (int) $this->input->post('veiculo_km_final');

            if ($veiculo_km_inicial < $veiculo_km) {
                $this->session->set_flashdata('msg_erro', "KM inicial deve ser maior que a quilometragem atual do veículo!");
                return $this->redirect($veiculo, 'quilometragem');
            }

            if ($veiculo_km_final <= $veiculo_km || $veiculo_km_final <= $veiculo_km_inicial) {
                $this->session->set_flashdata('msg_erro', "KM final deve ser maior que a quilometragem inicial e atual do veículo!");
                return $this->redirect($veiculo, 'quilometragem');
            }

            $dados['veiculo_km_inicial'] = $veiculo_km_inicial;
            $dados['veiculo_km_final'] = $veiculo_km_final;
            $dados['veiculo_litros'] = self::remocao_pontuacao($this->input->post('veiculo_litros'));
            $dados['veiculo_custo'] = self::remocao_pontuacao($this->input->post('veiculo_custo'));
            $dados['veiculo_km_data'] = $this->input->post('veiculo_km_data');
            $dados['comprovante_fiscal'] = ($_FILES['comprovante_fiscal'] ? self::upload_arquivo('comprovante_fiscal') : '');
            
            if (!$dados['comprovante_fiscal'] || $dados['comprovante_fiscal'] == '') {
                $this->session->set_flashdata('msg_erro', "O tamanho do comprovante deve ser menor ou igual a ".ini_get('upload_max_filesize'));
                return $this->redirect($veiculo, 'quilometragem');
            }

            if($data['id_ativo_veiculo_quilometragem']=='' || !$data['id_ativo_veiculo_quilometragem']){
                $this->db->insert('ativo_veiculo_quilometragem', $dados);
                $this->db->where('id_ativo_veiculo', $veiculo->id_ativo_veiculo)
                    ->update('ativo_veiculo', ['veiculo_km' => $veiculo_km + ($veiculo_km_final - $veiculo_km)]);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_quilometragem', $dados['id_ativo_veiculo_quilometragem'])
                    ->update('ativo_veiculo_quilometragem', $dados);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }
            echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/".$this->input->post('id_ativo_veiculo')));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function manutencao_salvar(){       
        $dados['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($dados['id_ativo_veiculo']);

        if ($veiculo) {
            $veiculo_km = (int) $veiculo->veiculo_km;
            $dados['id_fornecedor'] = $this->input->post('id_fornecedor');
            $dados['id_ativo_configuracao'] = $this->input->post('id_ativo_configuracao');
            $dados['veiculo_km_atual'] = (int) $this->input->post('veiculo_km_atual');
            
            if ($dados['veiculo_km_atual'] < $veiculo_km) {
                $this->session->set_flashdata('msg_erro', "KM atual deve ser maior ou igual a quilometragem atual do veículo!");
                return $this->redirect($veiculo, 'manutencao');
            }
            
            $dados['veiculo_custo'] = self::remocao_pontuacao($this->input->post('veiculo_custo'));
            $dados['descricao'] = $this->input->post('descricao');
            $dados['veiculo_km_data'] = $this->input->post('veiculo_km_data');
            $dados['ordem_de_servico'] = ($_FILES['ordem_de_servico'] ? self::upload_arquivo('ordem_de_servico') : '');
            
            if (!$dados['ordem_de_servico'] || $dados['ordem_de_servico'] == '') {
                $this->session->set_flashdata('msg_erro', "O tamanho do comprovante deve ser menor ou igual a ".ini_get('upload_max_filesize'));
                return $this->redirect($veiculo, 'manutencao');
            }

            if($data['id_ativo_veiculo_manutencao']=='' || !$data['id_ativo_veiculo_manutencao']){
                $this->db->insert('ativo_veiculo_manutencao', $dados);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_manutencao', $dados['id_ativo_veiculo_manutencao'])
                    ->update('ativo_veiculo_manutencao', $dados);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }
            echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/".$this->input->post('id_ativo_veiculo')));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function ipva_salvar(){
        $dados['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($dados['id_ativo_veiculo']);

        if ($veiculo) {
            $dados['ipva_ano'] = $this->input->post('ipva_ano');
            $dados['ipva_custo'] = self::remocao_pontuacao($this->input->post('ipva_custo'));
            $dados['ipva_data_vencimento'] = $this->input->post('ipva_data_vencimento');
            $dados['ipva_data_pagamento'] = $this->input->post('ipva_data_pagamento');
            $dados['comprovante_ipva'] = ($_FILES['comprovante_ipva'] ? self::upload_arquivo('comprovante_ipva') : '');
            if (!$dados['comprovante_ipva'] || $dados['comprovante_ipva'] == '') {
                $this->session->set_flashdata('msg_erro', "O tamanho do comprovante deve ser menor ou igual a ".ini_get('upload_max_filesize'));
                return $this->redirect($veiculo, 'ipva');
            }

            if($data['id_ativo_veiculo_ipva']=='' || !$data['id_ativo_veiculo_ipva']){
                $this->db->insert('ativo_veiculo_ipva', $dados);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_ipva', $dados['id_ativo_veiculo_ipva'])
                    ->update('ativo_veiculo_ipva', $dados);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
            }
            echo redirect(base_url("ativo_veiculo/gerenciar/ipva/".$this->input->post('id_ativo_veiculo')));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    function depreciacao_salvar(){
        $data['id_ativo_veiculo'] = !is_null($this->input->post('id_ativo_veiculo')) ? $this->input->post('id_ativo_veiculo') : '';
        $veiculo = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($data['id_ativo_veiculo']);
        
        if ($veiculo) {
            $valor_fipe = str_replace("R$ ", "", $this->input->post('valor_fipe'));
            $valor_fipe = str_replace(".", "", $valor_fipe);
            $valor_fipe = str_replace(",", ".", $valor_fipe);

            $data['valor_fipe'] = $valor_fipe;
            $data['fipe_mes_referencia'] = $this->input->post('fipe_mes_referencia');
            $data['veiculo_km'] = $this->input->post('veiculo_km');
            $data['veiculo_observacoes'] = $this->input->post('veiculo_observacoes');

            if($data['id_ativo_veiculo_depreciacao']=='' || !$data['id_ativo_veiculo_depreciacao']){
                $this->db->insert('ativo_veiculo_depreciacao', $data);
                $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso!");
            } else {
                $this->db->where('id_ativo_veiculo_depreciacao', $dados['id_ativo_veiculo_depreciacao'])
                ->update('ativo_veiculo_depreciacao', $dados);
                $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");            
            }
            echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/".$this->input->post('id_ativo_veiculo')));
            return;
        }
        $this->session->set_flashdata('msg_erro', "Veiculo não encontrado!");
        echo redirect(base_url("ativo_veiculo"));
    }

    public function remocao_pontuacao($string=null){
        return str_replace(",", ".", str_replace(".", "", $string));
    }

    public function remocao_acentos($string=null, $slug=false){
        
        // Caracteres a serem mantidos so que decodificados
        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'Ž'=>'Z', '.'=>' ',
            'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
            'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
            'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
            'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
            'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
            'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r',
        );

        // Traduz os caracteres em $string, baseado no vetor $table
        $string = strtr($string, $table);

        // Converte para minúsculo
        $string = strtolower($string);

        // Remove caracteres indesejáveis (que não estão no padrão)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);

        // Remove múltiplas ocorrências de hífens ou espaços
        $string = preg_replace("/[\s-]+/", " ", $string);

        // Faz a retirada de espaços multiplos no texto para evitar que a url fique com mais de uma hifen entre os espaçamentos
        $string = trim($string);

        // Transforma espaços e underscores em $slug
        $string = preg_replace("/[\s_]/", $slug, $string);

        // retorna a string
        return $string;     
    }

    public function upload_arquivo($pasta=null) {
        if (isset($_FILES[$pasta]) && $_FILES[$pasta]['error'] == 1) {
            return '';
        }

        $upload_path = "assets/uploads/".$pasta;
        if(!file_exists($upload_path)){
           mkdir($upload_path, 0777, true);
        }

        $image = '';
        $name_file = $_FILES[$pasta]['name'];
        $ext = pathinfo($name_file, PATHINFO_EXTENSION);
        $new_name = self::remocao_acentos($_FILES[$pasta]['name']).".".$ext; 
        $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => TRUE,
            'encrypt_name' => true,
            'max_size' => "100048000"
        );

        $this->load->library('upload');
        $this->upload->initialize($config);

        if(!$this->upload->do_upload($pasta)){ 
            $this->dd($_FILES[$pasta]['error'], $this->upload->display_errors());
            $this->ultimo_erro_upload_arquivo = $this->upload->display_errors();
            return '';
        } else{
            $this->ultimo_erro_upload_arquivo = null;
            $imageDetailArray = $this->upload->data();
            $image = $imageDetailArray['file_name'];
        }
        return $image;
    }

    function deletar($id_ativo_veiculo){
        return $this->db->where('id_ativo_veiculo', $id_ativo_veiculo)->delete('ativo_veiculo');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */