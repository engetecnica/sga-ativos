<?php
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Request as HttpClientRequest;
use GuzzleHttp\Psr7\Response as HttpClientResponse;

class MY_model extends CI_Model {
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * @var HttpClient
     */
    protected $httpClientObject;

     /**
     * @var HttpClientRequest
     */
    protected $httpClientRequest;

    protected $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = $this->buscar_dados_logado($this->session->userdata('logado'));
    }

    public function formatArrayReplied($items = [], $id_item = null){
        $lista = [];
        if ((count($items) > 0) && $id_item) {
            foreach($items as $item) {
                if (!isset($lista[$item->{$id_item}])) {
                    $lista[$item->{$id_item}] = (object) $item;
                }
            } 
        }
		return $lista;
    }

    public function buscar_dados_logado($logado=null){
        if($logado) {
            $user = $this->db
            ->select('usuario.*')
            ->select("empresa.razao_social, empresa.nome_fantasia, empresa.cnpj")
            ->where("usuario.id_usuario = {$logado->id_usuario}")
            ->join('empresa', "empresa.id_empresa = {$logado->id_empresa}")
            ->get('usuario')
            ->row();
        
            if ($user) {
                if($user->nivel == 1 && $user->id_obra_gerencia) $user->id_obra = $user->id_obra_gerencia;
                unset($user->senha);
                return $user;
            }
            unset($logado->senha);
            return $logado;
        }
        return null;
    }

    public function get_obra_base(){
        return $this->db
            ->select('ob.*')
            ->from('obra ob')
            ->where('obra_base = 1')
            ->group_by('ob.id_obra')
            ->get()
            ->row();
    }

    public function formata_moeda($valor = 0, $num_format = false){
        if($num_format){
            return  number_format($valor, 2, '.', '');
        }
        return "R$ ". number_format($valor, 2, ',', '.');
    }

    public function formata_moeda_float(string $valor){
        $formatado = str_replace("R$ ", "", $valor);
        $formatado = str_replace(".", "", $formatado);
        return (float) str_replace(",", ".", $formatado);
    }

    public function dd(...$data){
        foreach($data as $dt) {
            echo "<pre>";
            echo print_r($dt, true);
            echo "</pre>";
        }
        exit;
    }

    public function createHttpClient($base_uri = ""){
        $this->httpClientObject = new HttpClient(["base_uri" => $base_uri]);
    }

    public function httpClient(string $url, string $method = "GET", array $body = null, $headers = []) : HttpClientResponse
    {
        if(!$this->httpClientObject) $this->createHttpClient();
        $headers = array_merge(["Content-type" => "application/json"], $headers);
        $request = new HttpClientRequest($method, $url, $headers, $body ? json_encode($body) : "");
        $response = $this->httpClientObject->sendAsync($request);

        if($response instanceof Promise) {
          return Utils::unwrap([$url => $response])[$url];
        }
        return $response;
    }
}