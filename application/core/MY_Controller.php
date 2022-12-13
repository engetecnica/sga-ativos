<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
    /* load the MX_Router class */
    require APPPATH . "third_party/MX/Controller.php";


/**
 * Description of my_controller
 *
 * @author André Baill | https://www.github.com/srandrebaill
 */

use \Mpdf\Mpdf;
use Mpdf\Tag\Table;

class MY_Controller extends MX_Controller {
 
    protected $user, $logado, $permissoes, $model;

    use MY_Trait;

    function __construct($auth_user_required = true) {
        parent::__construct();

        if(isset($_POST[0])) $_POST = json_decode(array_keys($_POST)[0], true);

        if (version_compare(CI_VERSION, '2.1.0', '<')) {
            $this->load->library('security');
        }

        $this->load->model('obra/obra_model');
        $this->user = $this->buscar_dados_logado($this->session->userdata('logado'));

        $this->load->model('anexo/anexo_model');
        $this->load->model('relatorio/notificacoes_model');
        $this->load->model("configuracao/configuracao_model");
        
        if ($this->user) {
            $this->logado = true;
            $this->meses_ano = $this->config->item('meses_ano');
            $this->permissoes = $this->get_modulo_permission();
        }

        if($auth_user_required && !$this->user){
            if (isset($_SERVER['REQUEST_URI'])) { 
                $redirect_to = $_SERVER['REQUEST_URI'];
                $this->session->set_userdata('redirect_to', $redirect_to);
                echo redirect(base_url("login?redirect_to={$redirect_to}"));
                $this->logado = false;
            }
        }
    }

    public function is_auth(){
        return $this->logado;
    }

    public function json($data = null, int $status_code = 200){
        return @$this->output
        ->set_content_type('application/json')
        ->set_status_header($status_code)
        ->set_output(json_encode($data));
    }

    public function get_template($template=null, $data=null){
        $data['url'] =  current_url();
        $data['uri'] = uri_string();
        $data['user'] = $this->user;
        $data['modulos'] = $this->get_modulos($this->user->nivel);
        $data['obras'] = $this->obra_model->get_obras();
        $data['app_config'] = $this->configuracao_model->get_configuracao();
        $data['app_env'] = ($_ENV['CI_ENV']) ?? '';

        // aqui eu preciso trazer a função
        $data['modulos_permitidos'] = $this->modulos_permitidos();

        $data['permissoes'] = [];
        if(null!== $this->uri->segment(1)){
            $data['permissoes'] = $this->permissoes;
        }

        if($data['app_config']) {
            unset(
                $data['app_config']->sendgrid_apikey, 
                $data['app_config']->one_signal_apikey, 
                $data['app_config']->one_signal_apiurl
            );
        }

        $this->load->view("../views/template_top", $data);
        $this->load->view($template, $data);
        $this->load->view("../views/template_footer", $data);
    }  


    public function get_subtemplate($template=null){    
        $this->load->view("../views/template_subtop");
        $this->load->view($template);
        $this->load->view("../views/template_footer");
    }

    public function buscar_dados_logado($logado=null){
        if($logado) {
            $this->load->model('usuario/usuario_model');
            $user = $this->usuario_model->get_usuario($logado->id_usuario);

            if ($user) {
                unset($user->senha);
                return $this->set_obra_gerencia($user);
            }
            unset($logado->senha);
            return $this->set_obra_gerencia($logado);
        }
        return null;
    }

    private function set_obra_gerencia($user){
        if ($user->nivel == 1) {
            if (isset($user->id_obra_gerencia)) {
                $user->id_obra = $user->id_obra_gerencia;
            } else {
                try {
                    $user->id_obra = $this->get_obra_base()->id_obra;
                } catch(\Exception $e){}
            }
        }

        $user->obra = $this->obra_model->get_obra($user->id_obra);
        return $user;
    }


    # Módulos
    public function get_modulos($nivel=null)
    {
        $nivelObj = new stdClass();
        $this->db->where('modulo.id_vinculo', 0)
            ->where("usuario_modulo.id_usuario_nivel", $nivel)
            ->join("modulo", "modulo.id_modulo=usuario_modulo.id_modulo")
            ->group_by("usuario_modulo.id_modulo");

        $nivelObj->modulo = $this->db->get('usuario_modulo')->result();

        foreach($nivelObj->modulo as &$Obj)
        {
            $this->db
            ->where('modulo.id_vinculo !=', '0')
            ->where("usuario_modulo.id_usuario_nivel", $nivel)
            ->where("modulo.id_vinculo", ($Obj->id_modulo) ?? null)
            ->order_by("modulo.titulo", 'desc')
            ->join("modulo", "modulo.id_modulo=usuario_modulo.id_modulo")
            ->group_by("usuario_modulo.id_modulo");

            $Obj->submodulo = $this->db->get('usuario_modulo')->result();
        }
        
        return $nivelObj;
    }    

    # Usado em várias views
    public function get_estados(){
        $this->db->order_by('estado', 'ASC');
        return $this->db->get('estado')->result();
    }

    # Configuracoes
    public function get_configuracoes($id_categoria){
        $this->db->order_by('categoria', 'ASC');
        $this->db->where('id_categoria', $id_categoria);
        return $this->db->get('configuracao')->result();
    }

    public function get_empresas($id=null){
        $this->db->order_by('id_empresa', 'ASC');
        if ($id) {
            $this->db->where('id_empresa', $id);
        }
        return $this->db->group_by('id_empresa')->get('empresa')->result();
    }

    public function get_obras($id_empresa=null){
        $this->db->order_by('id_obra', 'ASC');
        if ($id_empresa) {
            $this->db->where('id_empresa', $id_empresa);
        }
        return $this->db->group_by('ob.id_obra')->get('obra')->result();
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

    public function get_niveis(){
        $this->db->order_by('id_usuario_nivel', 'ASC');
        return $this->db->get('usuario_nivel')->result();
    }

    public function gerar_pdf($filename, $html, $mode = null) {
        $mpdf = new Mpdf(['tempDir' => sys_get_temp_dir()."/mpdf"]);
        //desconmenta com php gd instalado
        //$mpdf->showImageErrors = true;
        $mpdf->WriteHTML($html);
        return $mpdf->Output($filename, $mode);
    }

    public function base64($filename){
        $contents = file_get_contents($filename, true, null);
        return 'data:image/' . pathinfo($filename, PATHINFO_EXTENSION) . ';base64,' . base64_encode($contents);
    }

    public function remocao_pontuacao($string=null){
        return str_replace([',','R$'], ['.', ''], str_replace('.', "", $string));
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
        if (!isset($_FILES[$pasta]) | (isset($_FILES[$pasta]) && $_FILES[$pasta]['error'] == 1)) {
            return '';
        }

        $upload_path = __DIR__."/../../assets/uploads/{$pasta}/";
        if(!is_dir($upload_path)){
            $this->ultimo_erro_upload_arquivo = "Path no is a directory.";
            return "";
        }

        $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => "gif|jpg|png|jpeg|pdf|xls",
            'overwrite' => TRUE,
            'encrypt_name' => true,
            'max_size' => "100048000"
        );
    
        $this->load->library('upload');
        $this->upload->initialize($config);

        if(!$this->upload->do_upload($pasta)){ 
            // - The upload destination folder does not appear to be writable.
            // - A problem was encountered while attempting to move the uploaded file to the final destination.
            $this->ultimo_erro_upload_arquivo = $this->upload->display_errors();
            log_message('error', $this->ultimo_erro_upload_arquivo);
            return '';
        } else{
            $this->ultimo_erro_upload_arquivo = null;
            $imageDetailArray = $this->upload->data();
            $image = $imageDetailArray['file_name'];
        }
        return $image;
    }
    
    public function salvar_anexo(
        array $config = [
            "titulo" => null, 
            "descricao" => null, 
            "anexo" => null,
            //outros
            //Ex: "id_configuracao" => $id_configuracao,
        ],
        $id_modulo = null, 
        $id_modulo_item = null, 
        $tipo = null, 
        $id_modulo_subitem = null
    ) : int {  



        
        $modulo = null;
        if(isset($config['anexo'])) {
            if (is_int($id_modulo)) $modulo = $this->db->where('id_modulo', $id_modulo)->get('modulo')->row();
            else $modulo = $this->db->where('rota', $id_modulo)->get('modulo')->row();

            if ($modulo) {
                $anexo = $this->anexo_model->get_anexo_by_name($config['anexo']);
                $anexo_data = [
                    "id_usuario" => $this->user->id_usuario,
                    "id_modulo" => $modulo->id_modulo,
                    "id_modulo_item" => $id_modulo_item,
                    "id_modulo_subitem" => $id_modulo_subitem,
                    "tipo" =>  $tipo,
                    "anexo" => isset($config['anexo']) ? $config['anexo'] : null,
                    "titulo" => isset($config['titulo']) ? $config['titulo'] : null,
                    "descricao" => isset($config['descricao']) ? $config['descricao'] : null,
                ];
                unset($config['path'], $config['anexo'], $config['titulo'], $config['descricao']);

                if ($anexo) $anexo_data['id_anexo'] = $anexo->id_anexo;
                return $this->anexo_model->salvar_formulario(array_merge($config, $anexo_data));
            }
        }
        return false;
    }

    public function deletar_anexos(
        $id_modulo, 
        int $id_modulo_item, 
        string $tipo = null, 
        int $id_modulo_subitem = null,
        int $id_anexo = null
    ) : int {  
        $modulo = null;
        if (is_int($id_modulo)) $modulo = $this->db->where('id_modulo', $id_modulo)->get('modulo')->row();
        else $modulo = $this->db->where('rota', $id_modulo)->get('modulo')->row();
            
        if ($modulo) {
            $query = $this->db;
            if ($id_modulo_item) $query->where("id_modulo_item = $id_modulo_item");
            if ($id_modulo_subitem) $query->where("id_modulo_subitem = $id_modulo_subitem");
            if ($tipo) $query->where("tipo = '{$tipo}'");
            if ($id_anexo) $query->where("id_anexo = $id_anexo");
            return $query->delete('anexo');
        }
        return false;
    }

    private function paginate_options(array $options = []): array 
    {
        return array_merge([
            "query" => null,
            "query_args" => [],
            "actions_template" => null,
            "actions_template_data" => [],
            "templates" => [],
            "before" => null,
            "after" => null,
            "table" => null,
            "start" => 0,
            "length" => 10,
            "search" => null,
            "columns" => [],
            "filters" => [],
            "order" => ['column' => 0, 'dir' => 'desc']
        ], $options);
    }

    protected function paginate_before(\CI_DB_mysqli_driver &$query)
    {
    }

    private function paginate_before_run(\CI_DB_mysqli_driver &$query, array $options = [])
    {
        $using_custom_before = false;
        $before = ($options['before'] ?? null);

        if (
            $before instanceof \Closure && 
            ($before_query = $before($query)) instanceof \CI_DB_mysqli_driver
        ) {
            $query = clone $before_query;
            $using_custom_before = true;
        }

        if (
            !$using_custom_before &&
            $this->model instanceof \CI_Model && 
            method_exists(get_called_class(), 'paginate_before')
        ) {
            $this->paginate_before($query);
        }
    }

    protected function paginate_after(object &$row)
    {
    }

    private function paginate_after_run(array &$result = [], array $options = [])
    {
        foreach ($result as $r => $row) {
            $using_custom_after = false;
            $after = ($options['after'] ?? null);
            $options['row_index'] = $r;

            if (
                $after instanceof \Closure && 
                is_object($after_row = $after($row, $result, $options))
            ) {
                $row = $after_row; 
                $using_custom_after = true;
            }

            if (
                !$using_custom_after &&
                $this->model instanceof \CI_Model && 
                method_exists(get_called_class(), 'paginate_after')
            ) {
                $this->paginate_after($row, $result, $options);
            }

            $result[$r] = $row;
        }
    }

    private function paginate_query(array $options = []) {
        $query = $this->db;

        if (
            isset($options['query']) && 
            $options['query'] instanceof \CI_DB_mysqli_driver
        ) {
            return $query = $options['query'];
        } 
        
        if (
            !isset($options['query']) && 
            (isset($this->model) && method_exists($this->model, 'query'))
        ) {
            $args = $options['query_args'] ?? [];
            return $query = $this->model->query(...$args ?? $args);
        }
        
        return $query;
    }

    private function paginate_search(
        \CI_DB_mysqli_driver &$query,
        array $columns = [], 
        string $search = null, 
        string $table = ''
    )  {
        if($columns) {
            $likes = 0;
            $count_searchable = array_sum(array_map(function($col){return $col['searchable'] ? 1 : 0;}, $columns));
            $count_searchabled = 0;
            foreach ($columns as  $c => $col) {
                if($count_searchabled == 0 && $search)  $query->group_start();
                if($col && $col['searchable'] && $search) {
                    $count_searchabled++;
                    $datas = [];
                    if(isset($col['data']) && strtoupper($col['data']) != $col['data']) {
                        $datas = array_merge($datas, [$col['data']]);
                    }

                    if(isset($col['name']) && strtoupper($col['name']) != $col['name']) {
                        $datas = array_merge($datas, [$col['name']]);
                    }

                    foreach ($datas as $data) {
                        $search_value = $search;
                        if (strpos($data, 'data_') !== false || strpos($data, '_data') !== false) {
                            $search_value = date('Y-m-d', strtotime(str_replace('/', '-', $search)));
                        }
                        if ($likes == 0) $query->or_like("{$table}{$data}", $search_value);
                        else $query->or_like("{$table}{$data}", $search_value);
                        $likes++;
                    }
				}
                if($count_searchable == $count_searchabled && $search) $query->group_end();
            }
		}
    }

    private function paginate_order(
        \CI_DB_mysqli_driver &$query,
        array $columns = [], 
        array $orders = [], 
        string $table = ''
    ) {
        if($orders) {
            foreach ($orders as $order) {  
                if(isset($order['column']) && isset($columns[$order['column']]) && $columns[$order['column']]['orderable']) {
                    $datas = [];
                    if(isset($columns[$order['column']]['data'])) {
                        $datas = array_merge($datas, [$columns[$order['column']]['data']]);
                    }

                    if(isset($columns[$order['column']]['name'])) {
                        $datas = array_merge($datas, [$columns[$order['column']]['name']]);
                    }

                    foreach ($datas as $data) {
                        if (strtoupper($data) !== $data) {
                            $query->order_by("{$table}{$data}", $order['dir']);
                        }
                    }
                }
            }
        }
    }

    private function paginate_filter(
        \CI_DB_mysqli_driver &$query,
        array $filters = [], 
        string $table = ''
    ) {
        if(count($filters) > 0) {
            foreach ($filters as $filter) {
                if(
                    isset($filter['column']) &&
                    isset($filter['value']) &&
                    $filter['value'] !== '*' &&
                    $filter['value'] !== []
                ) {
                    if(is_array($filter['value'])) $query->where_in("{$table}{$filter['column']}", $filter['value']);
                    else $query->where("{$table}{$filter['column']}", $filter['value']);
                }
            }
        }
    }

    private function paginate_templates(array &$result = [], array $options = []) : array
    {
        $actions_template = isset($options['actions_template']) ? "" : null;
        if(!$actions_template) $actions_template = isset($options['templates']) ? $options['templates'] : [];
        $templates = [];

        if ($actions_template) {
            if (is_array($actions_template)) {
                $templates = $actions_template;
            } else {
                $templates = [[
                    'view' => $actions_template,
                    'name' => 'actions',
                    'data' => $options['actions_template_data'] ?? [],
                ]];
            }
        }
    
        foreach($result as $r => $row) {
            $data = [
                "permissoes" => $this->permissoes, 
                "user"=> $this->user,
                "rows" => $result, 
                "row" => $row, 
                "index" => $r
            ];

            foreach ($templates as $template) {
                if (isset($template['data']) && $template['data'] instanceof \Closure) {
                    $template['data'] = $template['data']($row, $result, $template, $options);
                }

                if (isset($template['data']) && is_array($template['data'])) {
                    $data = array_merge($data, $template['data']);
                }

                if (isset($template['data']) && !is_array($template['data'])) {
                    $data = array_merge($data, ['data' => $template['data']]);
                }

                if (isset($template['view']) && isset($template['name'])) {
                    $name = $template['name'];
                    $result[$r]->$name  = $this->load->view("../views/{$template['view']}", $data, true);
                }
            }
        }

        return $result;
    }

    protected function paginate(array $options = []) : object {
        $options = $this->paginate_options($options);
        $start = $this->input->get('start') ?? $this->input->post('start') ?? $options['start'];
        $length = $this->input->get('length') ?? $this->input->post('length') ?? $options['length'];
        $search = $this->input->get('search') ?? $this->input->post('search') ?? $options['search'];
        $orders = $this->input->get('order') ?? $this->input->post('order') ?? $options['order'];
		$columns = $this->input->get('columns') ?? $this->input->post('columns') ?? $options['columns'];
        $filters = $this->input->get('filters') ?? $this->input->post('filters') ?? $options['filters'];
        $table = isset($options['table']) ? "{$options['table']}." : '';

        $query = $this->paginate_query($options);
        $this->paginate_search($query, $columns, $search, $table);
        $this->paginate_order($query, $columns, $orders, $table);
        $this->paginate_filter($query, $filters, $table);

        $totalQuery = clone $query;
        $query->limit($length, $start);
        $this->paginate_before_run($query, $options);

        $totalPageQuery = clone $query;
        $totalPage = $totalPageQuery->get()->num_rows();
        $result = $query->get()->result() ?? [];
        $total = $totalQuery->get()->num_rows();

        $this->paginate_after_run($result, $options);
        $this->paginate_templates($result, $options);

        return (object) [
            "total" => (int) $total,
            "total_page" => (int) $totalPage,
            "start" => (int) $start,
            "length" => (int) $length,
            "data" => (array) $result
        ] ;
    }

    protected function paginate_json(array $options = []) : \CI_Output {
        return $this->json($this->paginate($options));
    }
}
 
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */