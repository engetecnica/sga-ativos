<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author André Baill | https://www.github.com/srandrebaill
 */
class Configuracao  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('configuracao_model');
    }

    function index() {
        $this->get_template('index', ['configuracao' => $this->configuracao_model->get_configuracao(1)]);
    }

    function salvar(){
        $permit_notificacoes = $this->input->post('permit_notificacoes') ?: 0;
        $operacao_alerta = $this->input->post('operacao_alerta') ?: 1000;
        $km_alerta = $this->input->post('km_alerta') ?: 1000;

        $data  = [
            'id_configuracao' => 1, //config default
            'app_descricao' => $this->input->post('app_descricao') ?: "Engetecnica App",
            'origem_email' => $this->input->post('origem_email'),
            'km_alerta' => (int) trim(str_replace('KM', '', $km_alerta)),
            'operacao_alerta' => (int) trim(str_replace('Horas', '', $operacao_alerta)),
            'permit_notificacoes' => $permit_notificacoes,
            'one_signal_apiurl' => $this->input->post('one_signal_apiurl'),
            'one_signal_appid' => $this->input->post('one_signal_appid'),
            'one_signal_apikey' => $this->input->post('one_signal_apikey'),
            'one_signal_safari_web_id' => $this->input->post('one_signal_safari_web_id'),
            'valor_medio_diesel' => $this->formata_moeda_float($this->input->post('valor_medio_diesel') ?: 0),
            'valor_medio_gasolina' => $this->formata_moeda_float($this->input->post('valor_medio_gasolina') ?: 0),
            'valor_medio_etanol' => $this->formata_moeda_float($this->input->post('valor_medio_etanol') ?: 0),
            'valor_medio_gnv' => $this->formata_moeda_float($this->input->post('valor_medio_gnv') ?: 0),
        ]; 

        $this->configuracao_model->salvar_formulario($data);
        $this->session->set_flashdata('msg_success', "Registro salvo com sucesso!");
        echo redirect(base_url("configuracao"));
    }

}

/* End of file Site.php */
/* Location: ./application/modules/site/controllers/site.php */