<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Apisms_model extends MY_Model {
                        
    protected $api_curl         = "http://api.facilitamovel.com.br/api/simpleSend.ft";
    protected $api_user         = "srandrebaill";
    protected $api_password     = "thelorde";
    protected $api_curl_disparo = null;
    protected $api_msg          = null;
    protected $api_number       = null;
    protected $api_link         = null;

    public function __construct(){
        $this->load->model('usuario/usuario_model');
    }


    public function enviar_sms($nivel, $tipo, $opcoes)
    {

        $usuarios = $this->usuario_model->get_usuario_by_nivel($nivel);

        $disparo = [];
        if(!null==$usuarios && count($usuarios) >0)
        {
            foreach($usuarios as $i=>$users){
                $disparo[$i]['nome'] = $users->nome;
                $disparo[$i]['celular'] = ($users->celular) ? preg_replace('/[^0-9]/', '', $users->celular) : '41998036863';
            }
        }

        if (!null == $usuarios && count($disparo) > 0){

            foreach($disparo as $submit){

                /* Construção da Mensagem */
                $this->api_link         = base_url($opcoes['url']);
                $this->api_msg          = $opcoes['data']['mensagem']." {$this->api_link} | SGA Engetecnica";
                $this->api_number       = $submit['celular'];
                $this->api_curl_disparo = $this->api_curl .
                    "?user=" . $this->api_user .
                    "&password=" . $this->api_password .
                    "&destinatario=" . $this->api_number .
                    "&msg=" . urlencode($this->api_msg);

                $this->cURL_disparar($this->api_curl_disparo, $this->api_number);

            }   
            
        }    
        
    }  
    
    // Disparar SMS Efetivo
    public function cURL_disparar($api_curl_disparo, $celular){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_curl_disparo,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        // Fechar URL
        curl_close($curl);

        // Verificação de Erros
        if ($err) {

            // Erro ao Enviar SMS
            $this->salvar_log(14, null, 'automacao_sms_error', $celular, $err);
        
        } else {

            // Requisição
            $this->salvar_log(14, null, 'automacao_sms', $celular);

        }
    }                  
                        
}
                        
/* End of file Apisms.php */
    
                        