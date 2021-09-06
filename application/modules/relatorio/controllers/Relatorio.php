<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    private function get_relatorio_xls($relatorio_nome, $relatorio_data){
      //to-do
      //$this->dd($relatorio_nome, $relatorio_data);
      $filename = "/tmp/relatorio_{$relatorio_nome}_" . date('YmdHis', strtotime('now')).".xls";
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setCellValue('A1', 'Hello World !');
      $writer = new Xlsx($spreadsheet);
      $writer->save($filename);

      //$this->dd(file_get_contents($filename));
      return $filename;
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

            case 'xls':
              return $this->json([
                'relatorio' =>  $this->get_relatorio_xls($relatorio, $data),
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

    function limpar_tmp(){
      $path = __DIR__."/../../../../assets/uploads/relatorio";
      foreach(glob("{$path}/relatorio_*.pdf") as $file){
        $filetime = strtotime(explode(".", substr(strrchr($file, "_"), 1))[0]);
        if ($filetime <= strtotime('-2 minutes')) {
          unlink($file);
        }
      }
    }
  }