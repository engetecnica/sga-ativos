<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Writer\Xls;

/**
 * Description of site
 *
 * @author Messias Dias | https://github.com/messiasdias
 */
class Relatorio extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('relatorio_model');

        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login
        $this->load->model('empresa/empresa_model');
        $this->load->model('obra/obra_model');
        $this->load->model('anexo/anexo_model');
        $this->load->model('notificacoes_model');
    }

    function index() {
      $data['relatorios'] = $this->relatorio_model->relatorios;
      $data['periodos'] = $this->relatorio_model->periodos;
      $data['tipos_veiculos'] = $this->relatorio_model->tipos_veiculos;
      $data['empresas'] = $this->empresa_model->get_empresas();
      $data['obras'] = $this->obra_model->get_obras();
      $this->get_template('relatorio_gerar', $data);
    }

    private function get_relatorio_pdf($relatorio_nome, $relatorio_data){
      $css = file_get_contents( __DIR__ ."/../../../../assets/css/relatorios.css", true, null);
      $data = [
          'css' =>  $css, 
          'logo' => $this->base64(__DIR__ ."/../../../../assets/images/icon/logo.png"),
          'header' => $this->base64(__DIR__ ."/../../../../assets/images/docs/termo_header.png"),
          'footer' => $this->base64(__DIR__ ."/../../../../assets/images/docs/termo_footer.png"),
          'data_hora' => date('d/m/Y H:i:s', strtotime('now')),
          'relatorio' => $relatorio_data
      ];

      $filename = "relatorio_{$relatorio_nome}_" . date('YmdHis', strtotime('now')).".pdf";
      $html = $this->load->view("/../views/relatorio_{$relatorio_nome}", $data, true);
      $upload_path = "assets/uploads/relatorio";
      $path = __DIR__."/../../../../{$upload_path}";

      if(!is_dir($path)){
        mkdir($path, 0775, true);
      }

      $file = "{$path}/{$filename}";
      if (!file_exists($file)) {
        $this->gerar_pdf($file, $html);
        return base_url("{$upload_path}/{$filename}");
      }
      return null;
    }

    private function get_relatorio_excel($relatorio, $data, $tipo = 'xls'){
      $store_path = "assets/uploads/relatorio";
      $path = APPPATH."../{$store_path}";
      $filename = "relatorio_{$relatorio}_" . date('YmdHis', strtotime('now')).".{$tipo}";
      $file = "{$path}/{$filename}";
      $return_file = null;
      
      //gerar arquivo
      $relatorio_file = __DIR__."/../views/relatorio_{$relatorio}_excel.php";
      if (file_exists($relatorio_file)) {
        //Cria arquivo
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
        ->setCreator("Engetecnica APP")
        ->setLastModifiedBy("Engetecnica APP")
        ->setTitle("Office 2007 {$tipo}")
        ->setSubject("Office 2007 {$tipo}")
        ->setDescription("Document for Office 2007 {$tipo}, generated using PHP classes in Engetecnica APP.")
        ->setKeywords("Office 2007 openxml php Excel spreadsheet")
        ->setCategory($relatorio);
        $spreadsheet->removeSheetByIndex(0);

        //Usada dentro do arquivo do relatório
        $sheet = new Worksheet($spreadsheet, 'Planilha Padrão');

        if (require $relatorio_file) {
          $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
          (new Xlsx($spreadsheet))->save($file);
          $return_file = base_url("{$store_path}/{$filename}");
        }
      }
      return $return_file;
    }

    function gerar_grafico($relatorio) {
      if ($this->input->method() == 'post') {
        return $this->json($this->relatorio_model->$relatorio($this->input->post(), 'grafico'));
      }
      return  $this->json(null);
    }

    function gerar_arquivo($relatorio) {
      if ($this->input->method() == 'post') {
        $data = $this->relatorio_model->$relatorio($this->input->post(), 'arquivo');
        switch($this->input->post('tipo_arquivo')) {
            default:
            case 'pdf':
              return $this->json([
                'relatorio' =>  $this->get_relatorio_pdf($relatorio, $data),
                'validade' => 120
              ]); 
            break;
            case 'xlsx':
            case 'xls':
              return $this->json([
                'relatorio' =>  $this->get_relatorio_excel($relatorio, $data, $this->input->post('tipo_arquivo')),
                'validade' => 120
              ]); 
            break;
        }
      }
      return  $this->json(['relatorio' => null]);
    }

    public function crescimento_empresa(){
      return $this->json($this->relatorio_model->crescimento_empresa());
    }

    public function crescimento_empresa_custos(){
      return $this->json($this->relatorio_model->crescimento_empresa_custos());
    }

    public function informe_vencimentos(){
      $relatorio_data = $this->relatorio_model->informe_vencimentos();

      if (count($relatorio_data) > 0) {
        $data = [
            'css' =>  file_get_contents( __DIR__ ."/../../../../assets/css/relatorios.css", true, null), 
            'logo' => $this->base64(__DIR__ ."/../../../../assets/images/icon/logo.png"),
            'header' => $this->base64(__DIR__ ."/../../../../assets/images/docs/termo_header.png"),
            'footer' => $this->base64(__DIR__ ."/../../../../assets/images/docs/termo_footer.png"),
            'data_hora' => date('d/m/Y H:i:s', strtotime('now')),
            'relatorio' => $relatorio_data
        ];

        $message_html = $this->load->view("/../views/relatorio_informe_vencimentos", $data, true);
        return $this->notificacoes_model->enviar_email("Informe de Vencimentos", $message_html, $this->config->item("notifications_address"));
      }
      return true;
    }

    public function automacoes() {
      $status = [
        'limpar_tmp' => $this->relatorio_model->limpar_tmp(),
        'informe_vencimentos' => $this->informe_vencimentos(),
        'remove_orphans_anexos' => $this->anexo_model->removeOrphans()
      ];
      $this->json($status);
    }

    public function test_email(){
      $return = $this->notificacoes_model->enviar_email("Test Email", "<h1> Test email ok!</h1>", $this->config->item("notifications_address"));
      $this->json(['success' => $return]);
    }

    public function test_push(){
      $return = $this->notificacoes_model->enviar_push("Test Push", "Test Push Notications ok!", [
        "filters" => [
            ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "1"],
            ["operator" => "AND"],
            ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "2"],
        ],
        "url" => "/"
      ]);
      $this->json($return);
    }
  }