<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
    /* load the MX_Router class */
    require APPPATH . "third_party/MX/Controller.php";


/**
 * Description of my_controller
 *
 * @author https://roytuts.com
 */

use \Mpdf\Mpdf;

class MY_Controller extends MX_Controller {
 
    protected $user, $logado;


    function __construct($auth_user_required = true) {
        parent::__construct();

        if(isset($_POST[0])) $_POST = json_decode(array_keys($_POST)[0], true);

        if (version_compare(CI_VERSION, '2.1.0', '<')) {
            $this->load->library('security');
        }
        $this->user = $this->buscar_dados_logado($this->session->userdata('logado'));
        $this->load->model('anexo/anexo_model');
        $this->load->model('obra/obra_model');
        $this->load->model('relatorio/notificacoes_model');
        
        if ($this->user) {
            $this->logado = true;
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

    use MY_Trait;

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
            ->where("modulo.id_vinculo", $Obj->id_modulo)
            ->order_by("modulo.titulo", 'ASC')
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

        $upload_path = "assets/uploads/".$pasta;
        if(!file_exists($upload_path)){
           mkdir($upload_path, 0777, true);
        }

        $image = '';

        $name_file = $_FILES[$pasta]['name'];
        $ext = pathinfo($name_file, PATHINFO_EXTENSION);
       
        $new_name = $this->remocao_acentos($_FILES[$pasta]['name']).".".$ext; 
        $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => "gif|jpg|png|jpeg|pdf",
            'overwrite' => TRUE,
            'encrypt_name' => true,
            'max_size' => "100048000"
        );
        
        $this->load->library('upload');
        $this->upload->initialize($config);

        if(!$this->upload->do_upload($pasta)){ 
            $this->ultimo_erro_upload_arquivo = $this->upload->display_errors();
            return '';
        } else{
            $this->ultimo_erro_upload_arquivo = null;
            $imageDetailArray = $this->upload->data();
            $image = $imageDetailArray['file_name'];
        }
        return $image;
    }

    public function salvar_anexo($id_modulo, $data, $id_item, $subitem_column = null, $path, $tipo, $id_configuracao = null){
        if($data[$path]) {
            $anexo_name = "{$path}/{$data[$path]}";
            $anexo = $this->anexo_model->get_anexo_by_name($anexo_name);

            $anexo_data = [
                "id_usuario" => $this->user->id_usuario,
                "id_modulo" => $id_modulo,
                "id_modulo_item" => $id_item,
                "id_modulo_subitem" =>  $subitem_column && $data[$subitem_column] ? $data[$subitem_column] : $this->db->insert_id(),
                "id_configuracao" => $id_configuracao,
                "tipo" =>  $tipo,
                "anexo" => $anexo_name,
                "titulo" => $anexo_name,
                "descricao" => null,
            ];

            if ($anexo) {
                $anexo_data['id_anexo'] = $anexo->id_anexo;
            }
            $this->anexo_model->salvar_formulario($anexo_data);
        }
    }
}
 
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */