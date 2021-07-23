<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ferramental_estoque
 *
 * @author https://github.com/messiasdias
 */
class Ferramental_estoque  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ferramental_estoque_model');
    
        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        }
        $this->load->model('ativo_externo/ativo_externo_model');         
    }


    # Listagem de Itens
    function index($subitem=null) {
        // $this->get_template('index', [
        //     'lista' => $this->ferramental_estoque_model->get_lista_estoque($this->user),
        //     'status_lista' => $this->ferramental_estoque_model->get_estoque_status(),
        //     'user' => $this->user
        // ]);
        $obra_base = $this->get_obra_base(); 
        echo "<pre>";
        echo print_r($this->ativo_externo_model
        ->get_estoque(2, null, true));
    }

}