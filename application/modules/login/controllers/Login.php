<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author https://www.roytuts.com
 */
class Login  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('login_model');     
    }

    function index($subitem=null) {

        if($this->session->userdata('logado')==true){
            redirect(base_url());
        }  

    	//$subitem = ($subitem==null ? 'index' : $subitem);
        $this->load->view('index');
    }

    function acessar(){
    	$this->login_model->verificar_login();
    }

    function logout(){
    	$this->session->sess_destroy();
    	$this->session->unset_userdata('logado');
//    	unset($_SESSION['logado']);
    	echo redirect(base_url("login"));
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */