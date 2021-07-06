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
        if($data['id_ativo_veiculo']==''){
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_retorno', "Registro atualizado com sucesso!");            
        }
        echo redirect(base_url("ativo_veiculo"));

    }


    function depreciacao_salvar(){

        $data['id_ativo_veiculo'] = !is_null($this->input->post('id_ativo_veiculo')) ? $this->input->post('id_ativo_veiculo') : '';

        $valor_fipe = str_replace("R$ ", "", $this->input->post('valor_fipe'));
        $valor_fipe = str_replace(".", "", $valor_fipe);
        $valor_fipe = str_replace(",", ".", $valor_fipe);

        $data['valor_fipe'] = $valor_fipe;
        $data['fipe_mes_referencia'] = $this->input->post('fipe_mes_referencia');
        $data['veiculo_km'] = $this->input->post('veiculo_km');
        $data['veiculo_observacoes'] = $this->input->post('veiculo_observacoes');

        $this->db->insert('ativo_veiculo_depreciacao', $data);

        if($data['id_ativo_veiculo']==''){
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
        } else {
            $this->session->set_flashdata('msg_retorno', "Registro atualizado com sucesso!");            
        }

        echo redirect(base_url("ativo_veiculo/gerenciar/depreciacao/".$this->input->post('id_ativo_veiculo')));

    }    

    public function gerenciar($entrada=null, $tipo=null, $id_ativo_veiculo=null){

        switch ($entrada) {
            case 'quilometragem':
                if($tipo=='adicionar'){
                    $template = "_form";
                    $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
                    $data['id_ativo_veiculo'] = $id_ativo_veiculo;                    
                } elseif($tipo=='editar') {
                    $template = "_form";
                    $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
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
                    $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
                    $data['tipo_servico'] = $this->ativo_veiculo_model->get_tipo_servico(10);
                    $data['fornecedores'] = $this->ativo_veiculo_model->get_fornecedor();
                    $data['id_ativo_veiculo'] = $id_ativo_veiculo;
                } elseif($tipo=='editar') {
                    $template = "_form";
                    $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
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
                    $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
                    $data['id_ativo_veiculo'] = $id_ativo_veiculo;
                } elseif($tipo=='editar') {
                    $template = "_form";
                    $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
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
                    $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
                    $data['lista'] = $this->ativo_veiculo_model->get_ativo_veiculo_depreciacao_lista($id_ativo_veiculo);  
                    $data['id_ativo_veiculo'] = $id_ativo_veiculo;
                } elseif($tipo=='editar') {
                    $template = "_form";
                    $data['dados_veiculo'] = $this->ativo_veiculo_model->get_ativo_veiculo_detalhes($id_ativo_veiculo);
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

    # Salvar KM
    public function quilometragem_salvar(){

        $dados['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $dados['veiculo_km_inicial'] = $this->input->post('veiculo_km_inicial');
        $dados['veiculo_km_final'] = $this->input->post('veiculo_km_final');
        $dados['veiculo_litros'] = self::remocao_pontuacao($this->input->post('veiculo_litros'));
        $dados['veiculo_custo'] = self::remocao_pontuacao($this->input->post('veiculo_custo'));
        $dados['veiculo_km_data'] = $this->input->post('veiculo_km_data');
        $dados['comprovante_fiscal'] = ($_FILES['comprovante_fiscal'] ? self::upload_arquivo('comprovante_fiscal') : '');

        $this->db->insert('ativo_veiculo_quilometragem', $dados);

        if($data['id_ativo_veiculo']==''){
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
        }
        echo redirect(base_url("ativo_veiculo/gerenciar/quilometragem/".$this->input->post('id_ativo_veiculo')));

    }

    public function manutencao_salvar(){       

        $dados['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $dados['id_fornecedor'] = $this->input->post('id_fornecedor');
        $dados['id_ativo_configuracao'] = $this->input->post('id_ativo_configuracao');
        $dados['veiculo_km_atual'] = $this->input->post('veiculo_km_atual');
        $dados['veiculo_custo'] = self::remocao_pontuacao($this->input->post('veiculo_custo'));
        $dados['descricao'] = $this->input->post('descricao');
        $dados['veiculo_km_data'] = $this->input->post('veiculo_km_data');
        $dados['ordem_de_servico'] = ($_FILES['ordem_de_servico'] ? self::upload_arquivo('ordem_de_servico') : '');
     
        $this->db->insert('ativo_veiculo_manutencao', $dados);

        if($data['id_ativo_veiculo']==''){
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
        }
        echo redirect(base_url("ativo_veiculo/gerenciar/manutencao/".$this->input->post('id_ativo_veiculo')));
 
    }

    public function ipva_salvar(){

        $dados['id_ativo_veiculo'] = $this->input->post('id_ativo_veiculo');
        $dados['ipva_ano'] = $this->input->post('ipva_ano');
        $dados['ipva_custo'] = self::remocao_pontuacao($this->input->post('ipva_custo'));
        $dados['ipva_data_vencimento'] = $this->input->post('ipva_data_vencimento');
        $dados['comprovante_ipva'] = ($_FILES['comprovante_ipva'] ? self::upload_arquivo('comprovante_ipva') : '');

        $this->db->insert('ativo_veiculo_ipva', $dados);

        if($data['id_ativo_veiculo']==''){
            $this->session->set_flashdata('msg_retorno', "Novo registro inserido com sucesso!");
        }
        echo redirect(base_url("ativo_veiculo/gerenciar/ipva/".$this->input->post('id_ativo_veiculo')));
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

    public function upload_arquivo($pasta=null){

        $upload_path = "assets/uploads/".$pasta;
        if(!file_exists($upload_path)){
           mkdir($upload_path, 0777, true);
        }

        $name_file = $_FILES[$pasta]['name'];
        $ext = pathinfo($name_file, PATHINFO_EXTENSION);

        $new_name = self::remocao_acentos($_FILES[$pasta]['name']).".".$ext; 

        $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => TRUE,
            'encrypt_name' => true,
            'max_size' => "2048000"
        );


        $this->load->library('upload');

        $image = '';
        $this->upload->initialize($config);

        if(!$this->upload->do_upload($pasta)){ 
            $data['imageError'] =  $this->upload->display_errors();
        } else{
            $imageDetailArray = $this->upload->data();
            $image =  $imageDetailArray['file_name'];
        }

        return $image;
    }

    function deletar($id=null){
        $this->db->where('id_ativo_veiculo', $id);
        return $this->db->delete('ativo_veiculo');
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */