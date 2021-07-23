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



class MY_Controller extends MX_Controller {
 
    protected $user;

    function __construct() {
        parent::__construct();
        if (version_compare(CI_VERSION, '2.1.0', '<')) {
            $this->load->library('security');
        }
        $this->user = self::buscar_dados_logado($this->session->userdata('logado'));
    }

    public function json(array $data = null, int $status_code = 200){
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header($status_code)
        ->set_output(json_encode($data));
    }

    public function get_template($template=null, $data=null){
        $data['user'] = $this->user;
        $data['modulos'] = self::get_modulos($this->user->nivel);
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
            $user = $this->db
            ->select('usuario.*, empresa.*')
            ->where("usuario.id_usuario = {$logado->id_usuario}")
            ->join('empresa', "empresa.id_empresa = {$logado->id_empresa}")
            ->get('usuario')
            ->row();

            if ($user) {
                unset($user->senha);
                return $user;
            }
            unset($logado->senha);
            return $logado;
        }
        return null;
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

    public function get_empresas($id=null){
        $this->db->order_by('id_empresa', 'ASC');
        if ($id) {
            $this->db->where('id_empresa', $id);
        }
        return $this->formatArrayReplied($this->db->get('empresa')->result(), 'id_empresa');
    }

    public function get_obras($id_empresa=null){
        $this->db->order_by('id_obra', 'ASC');
        if ($id_empresa) {
            $this->db->where('id_empresa', $id_empresa);
        }
        return $this->formatArrayReplied($this->db->get('obra')->result(), 'id_empresa');
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
        return $this->formatArrayReplied($this->db->get('usuario_nivel')->result(), 'id_usuario_nivel');
    }

    public function dd($data, $exit = false){
        echo "<pre>";
        echo print_r($data);
        echo "</pre>";
        if ($exit) {
            exit;
        }
    }
}
 
/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */