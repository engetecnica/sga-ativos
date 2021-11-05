<?php
(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author Messias Dias | https://github.com/messiasdias
 */
class App extends MY_Controller {

    protected $path;

    function __construct() {
        parent::__construct();
        $this->load->model('relatorio/relatorio_model');
        $this->load->model('relatorio/notificacoes_model');
        $this->load->helper('download');
        $this->path = __DIR__."/../../../../assets/exports";
    }

    public function automacoes() {
        $status = [
          'limpar_uploads' => $this->relatorio_model->limpar_uploads(),
          'limpar_exports' => $this->db_export_clear(),
          'informe_vencimentos' => $this->relatorio_model->enviar_informe_vencimentos(),
          'informe_retiradas_pendentes' => $this->relatorio_model->enviar_informe_retiradas_pendentes(),
        ];

        $this->json($status);
    }
  
    public function test_email(){
      $top = $this->load->view('relatorio/email_top', ['ilustration' => "welcome", "assunto" => "Test email"], true);
      $email = "<h1> Test email ok!</h1> <p> Test email ok!</p>";
      $footer = $this->load->view('relatorio/email_footer', null, true);
      $html = $top.$email.$footer;
      $return = $this->notificacoes_model->enviar_email("Test Email", $html, $this->config->item("notifications_address"));
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
  
    public function export(){
      if ($this->user && $this->user->nivel == 1) {
        $filename = "{$this->path}/". date("Ymdhis") .".json";
    
        $tables = array_map(function($table) {
          return array_values((array) $table)[0];
        }, $this->db->query('show tables')->result());

        $data = [];
        foreach($tables as $table){
          $data[$table] = $this->db->get($table)->result();
        }

        file_put_contents($filename, json_encode($data));
        return force_download($filename, null);
      }
      echo "Ocorreu um erro ao gerar arquivo!";
    }
  
    private function db_export_clear() {
        foreach(glob("{$this->path}/*.json") as $filename) {
          unlink($filename);
        }
        return true;
    }
}