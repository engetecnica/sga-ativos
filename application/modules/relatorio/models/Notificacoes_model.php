<?php 
require_once(__DIR__."/Relatorio_model_base.php");
use GuzzleHttp\Client;

class Notificacoes_model extends MY_model {

  private $client, $headers;

  public function __construct() {
      parent::__construct();
      $this->setApi();
  }

  private function setApi(){
    $this->client = new Client([
        'base_uri' => $this->config->item('one_signal_apiurl')
    ]);

    $this->headers = [
        "Content-Type" => "application/json; charset=utf-8",
        "Authorization" => "Basic {$this->config->item('one_signal_apikey')}"
    ];
  }

  public function getSegmentos(){

    //$this->dd($this->user);
    // $response = $this->enviar_push("Test Api Notification 2", "Test message ok 2!", [
    //     "filters" => [
    //         ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "1"],
    //         ["operator" => "OR"],
    //         ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "2"]
    //     ],
    //     "url" => base_url("ativo_interno/editar/2")
    // ]);

    $response = $this->enviar_push("Nova Requisição de Ferramentas", "Nova Requisição de Ferramentas criada para a obra ", [
        "filters" => [
            ["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "1"],
            ["operator" => "AND"],
            ["field" => "tag", "key" => "id_obra", "relation" => "!=", "value" => $this->user->id_obra],
        ],
        "include_external_user_ids" => [$this->user->id_usuario],
        "url" => "ferramental_requisicao/detalhes/1000000073"
    ]);

    $this->dd($response);
    $this->enviar_email("Test Email", "Test email ok!", ["messiasdias.ti@gmail.com"]);
  }

  public function enviar_push($titulo, $texto, ...$opcoes){
    $body = [
        "app_id" => $this->config->item('one_signal_appid'),
        "headings" => ["en" => $titulo],
        "contents" => ["en" => $texto],
        "sound" => base_url("assets/media/mixkit-correct-answer-tone-2870.wav"),
        "ios_sound" => base_url("assets/media/mixkit-correct-answer-tone-2870.wav"),
        "android_sound" => base_url("assets/media/mixkit-correct-answer-tone-2870.wav"),
        "huawei_sound" => base_url("assets/media/mixkit-correct-answer-tone-2870.raw"),
        "adm_sound" => base_url("assets/media/mixkit-correct-answer-tone-2870.wav"),
        "wp_wns_sound" => base_url("assets/media/mixkit-correct-answer-tone-2870.wav"),
        "android_led_color" => "#fd7e14", 
        "huawei_led_color" => "#fd7e14",
        "android_accent_color" => "#ffffff",
        "huawei_accent_color" => "#ffffff",
        "icon" => base_url("assets/images/icon/logo2.png"),
        "chrome_web_icon" => base_url("assets/images/icon/logo2.png"),
        "chrome_icon" => base_url("assets/images/icon/logo2.png"),
        "firefox_icon" => base_url("assets/images/icon/logo2.png")
    ];

    if (isset($opcoes[0])) {
        $body = array_merge($body, $opcoes[0]);
        
        if (isset($body['url'])) {
            $body['url'] = base_url($body['url']);
        }
    }

    $response = $this->client->post("/api/v1/notifications", [
        'body' => json_encode($body),
        'headers' => $this->headers
    ]);

    return (object) [
        "status" => $response->getStatusCode(),
        "body" => json_decode($response->getBody()->getContents())
    ];
  }

  public function enviar_email($titulo, $texto, $destinos = []){
    $this->dd($titulo, $texto, $destinos);
  }

      
}