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

    public function formata_moeda_model($valor = 0, $num_format = false){
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

    private function pagination_search(
        \CI_DB_mysqli_driver &$query,
         array $columns = [], 
         string $search = null, 
         string $table = ''
    ) : ?\CI_DB_mysqli_driver {
        if($columns) {
            $likes = 0;
            foreach ($columns as  $col) {
                if($col && $col['searchable'] && $search) {
                    $data = strtoupper($col['data']) != $col['data'] ? $col['data'] : ($col['name']?? null);
                    if ($data){
                        $search_value = $search;
                        if (strpos($data, 'data_') !== false) {
                            $search_value = date('Y-m-d', strtotime(str_replace('/', '-', $search)));
                        }

                        if ($likes == 0) $query->like("{$table}{$data}", $search_value);
                        else $query->or_like("{$table}{$data}", $search_value);
                        $likes++;
                    }
				}
            }
		}
        return $query;
    }

    private function pagination_order(
        \CI_DB_mysqli_driver &$query,
        array $columns = [], 
        array $orders = [], 
        string $table = ''
    ) : ?\CI_DB_mysqli_driver {
        if($orders) {
            foreach ($orders as $order) {
                if(isset($columns[$order['column']]) && $columns[$order['column']]['orderable']) {
                    $data = $columns[$order['column']]['data'] ?? $columns[$order['column']]['name'] ?? null;          
                    $query->order_by("{$table}{$data}", $order['dir']);
                }
            }
        }
        return $query;
    }

    private function pagination_actions_template(array &$result = [], array $options = []) : array
    {
        $template = isset($options['actions_template']) ? "../{$options['actions_template']}" : null;
        if($template) {
            foreach($result as $r => $row) {
                $data = array_merge(
                    ["rows" => $result, "row" => $row, "index" => $r],  
                    $options['actions_template_data'] ?? []
                );
                $result[$r]->actions = $this->load->view($template, $data, true);
            }
        }
        return $result;
    }

    public function paginate(
        \CI_DB_mysqli_driver $query, 
        array $options = [
            "table" => '',
            "actions_template" => null,
            "actions_template_data" => [],
            "before" => null,
            "after" => null,
        ]
    ) : object {
        $order_col = [['column' => 0, 'dir' => 'desc']];
        $start = $this->input->get('start', $this->input->post('start')) ?? 0;
        $length = $this->input->get('length', $this->input->post('length')) ?? 10;
        $search = $this->input->get('search', $this->input->post('search')) ?? null;
        $orders = $this->input->get('order', $this->input->post('order')) ?? $order_col;
		$columns = $this->input->get('columns', $this->input->post('columns')) ?? [];
        $table = isset($options['table']) ? "{$options['table']}." : '';

        $this->pagination_search($query, $columns, $search, $table);
        $this->pagination_order($query, $columns, $orders, $table);

        $totalQuery = clone $query;
        $query->limit($length, $start);

        $before =  $options['before'] ?? null;
        if ($before instanceof \Closure) {
            $before_query = $before($query);
            if($before_query instanceof \CI_DB_mysqli_driver) $query = clone $before_query;
        }

        $totalPageQuery = clone $query;
        $totalPage = $totalPageQuery->get()->num_rows();
        $result = $query->get()->result() ?? [];
        $total = $totalQuery->get()->num_rows();

        $after = $options['after'] ?? null;
        if ($after instanceof \Closure) {
            $after_result = $after($result);
            if(is_array($after_result)) $result = $after_result; 
        }

        $this->pagination_actions_template($result, $options);

        return (object) [
            "total" => $total,
            "total_page" => $totalPage,
            "start" => $start,
            "length" => $length,
            "data" => $result
        ] ;
    }

    public function join_status(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        return $query
            ->select('st.slug as status_slug, st.texto as status_texto, st.classe as status_classe')
            ->join('ativo_externo_requisicao_status st', "{$col} = st.id_requisicao_status", $join_type);
    }

    public function join_funcionario(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        return $query
        ->select('fn.cpf as funcionario_cpf, fn.rg as funcionario_rg, fn.celular as funcionario_celular, fn.nome as funcionario')
        ->join('funcionario fn', "{$col} = fn.id_funcionario", $join_type);
    }

    public function join_obra(\CI_DB_mysqli_driver &$query, string $col, string $join_type = 'left') : \CI_DB_mysqli_driver
    {
        return $query
        ->select('ob.id_obra, ob.responsavel, ob.responsavel_celular, ob.codigo_obra as obra')
        ->join('obra ob', "{$col} = ob.id_obra", $join_type);
    }
}