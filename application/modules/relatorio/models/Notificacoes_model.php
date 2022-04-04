<?php 
use \GuzzleHttp\Client;
use \PHPMailer\PHPMailer\PHPMailer;
use \PHPMailer\PHPMailer\Exception as PHPMailerException;

class Notificacoes_model extends MY_model {

  private $client, $push_headers, $configuracao;

  public function __construct() {
      parent::__construct();
      $this->load->model("configuracao/configuracao_model");
      $this->setApi();
  }

  private function setApi(){
    $this->configuracao = $this->configuracao_model->get_configuracao(1);

    if ($this->configuracao && $this->configuracao->permit_notificacoes) {
      $this->client = new Client([
          'base_uri' => $this->configuracao->one_signal_apiurl
      ]);

      $this->push_headers = [
          "Content-Type" => "application/json; charset=utf-8",
          "Authorization" => "Basic {$this->configuracao->one_signal_apikey}"
      ];
    }
  }

  public function enviar_push($titulo, $texto, ...$opcoes){
    if ($this->configuracao && $this->configuracao->permit_notificacoes_push) {
      $body = [
          "app_id" => $this->configuracao->one_signal_appid,
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
      try {
        $response = $this->client->post("/api/v1/notifications", [
            'body' => json_encode($body),
            'headers' => $this->push_headers
        ]);

        return (object) [
            "status" => $response->getStatusCode(),
            "body" => json_decode($response->getBody()->getContents())
        ];
      }
      catch (\Exception $e) {
        return (object) [
          "status" => $e->getCode(),
          "body" => ['errors' => [$e->getMessage()]]
        ];
      }
    }

    return (object) [
      "status" => 400,
      "body" => ['errors' => ["Enable notifications permitions on geral settings to send it."]]
    ];
  }

  public function enviar_email($assunto, $mensagem, $destinos = [], $embeddedImages = [], $anexos = []){
    $assets_path = APPPATH."../assets/";
    $smtp = $this->config->item("smtp");
    $mail = new PHPMailer(true);

    try {
      $mail->isSMTP();
      $mail->CharSet = "UTF-8";
      $mail->Host       = $smtp->host;
      $mail->SMTPAuth   = $smtp->auth;
      $mail->Username   = $smtp->user;
      $mail->Password   = $smtp->pass;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
      $mail->Port       = $smtp->port; 
      $mail->setFrom($smtp->user, $this->configuracao->app_descricao ?: "Engetecnica");

      foreach ($destinos as $nome => $email){
        if ($email != null) $mail->addAddress($email, $nome);
      }

      $layoutImages = [
        "header" => "images/docs/termo_header.png",
        "footer" => "images/docs/termo_footer.png",
      ];

      $embeddedImages = array_merge($layoutImages, is_array($embeddedImages) ? $embeddedImages : []);
      foreach ($embeddedImages as $nome => $path) {
        $mail->addEmbeddedImage("{$assets_path}/$path", $nome);
      }
  
      foreach ($anexos as $nome => $path) {
        $filepath = "{$assets_path}/$path";
        if(is_string($nome)) $mail->addAttachment($filepath, $nome);
        else $mail->addAttachment($filepath);
      }

      $mail->isHTML($mensagem != strip_tags($mensagem));
      $mail->Subject = $assunto;
      $mail->Body = $mail->msgHTML($mensagem, $assets_path);
      $mail->AltBody = strip_tags($mensagem);

      return $mail->send();;
    } catch (PHPMailerException $e) {
      echo "Message could not be sent. Mailer Error: {$e->getMessage()}";
    }
    return false;
  }
  
  public function getEmailStyles(){
    try {
      return include "./application/config/email_style.php";
    } catch (\Exception $e) {
      return [];
    }
  }
}