<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of ferramental_estoque
 *
 * @author https://github.com/srandrebaill
 */
class Insumo extends MY_Controller {

    function __construct() {
        parent::__construct();
        if ($this->is_auth()) {
            $this->load->model('insumo_model');
            $this->load->model('insumo_configuracao/insumo_configuracao_model');
        }
    }

    function index()
    {

        $this->get_template('index');
    }



    /*
        Adicionar Insumo
    */
    public function adicionar()
    {
        $data['tipo_insumo'] = $this->insumo_configuracao_model->get_lista();
        $this->get_template('index_form', $data);
    }




}