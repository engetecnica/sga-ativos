<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author Messias Dias | https://github.com/messiasdias
 */
class Anexo_externo  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('anexo_model');

        # Login
        if($this->session->userdata('logado')==null){
          echo redirect(base_url('login')); 
        } 
        # Fecha Login 
       // $this->load->model('ferramental_requisicao/ferramental_requisicao_model');       
    }


    function index($id_modulo = null, $page = null, $limit = null){
      if ($page) {

      }

      $data['anexos'] = $this->anexo_model->get_anexos($id_modulo, $limit, $page);
      $this->get_template();
    }
}