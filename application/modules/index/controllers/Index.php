<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of Index
 *
 * @author https://www.roytuts.com
 */
class Index extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('index_model');
        
        # Login
        if($this->session->userdata('logado')==null){
            echo redirect(base_url('login')); 
        } 
        # Fecha Login
        $this->load->model('ferramental_requisicao/ferramental_requisicao_model');
    }

    function index() {
        $data['requisicoes_pendentes'] = $this->ferramental_requisicao_model->get_lista_requisicao([1, 11], 0, 5);
        $data['requisicoes_total'] = $this->ferramental_requisicao_model->lista_requisicao_count([1, 11]);
        $data['status_lista'] = $this->ferramental_requisicao_model->get_requisicao_status();
        $this->get_template('index', $data);
    }

    function sem_acesso(){
    	$this->session->set_flashdata('msg_erro', "Você não possui acesso a este módulo.");
    	echo redirect(base_url());
    }

    # Manipular novos registros através do CSV
    public function set_registros(){

        $handle = fopen("assets/motoristas.csv", "r");

        $row = 0;
        while ($line = fgetcsv($handle, 1000, ";")) {
            
            if($row>1){

                # Definição de campos para importar
                $dados['nome'] = $line[1];
                $dados['rg'] = $line[3];
                $dados['cpf'] = $line[2];
                $dados['endereco'] = $line[9];
                $dados['endereco_numero'] = $line[10];
                $dados['endereco_bairro'] = $line[11];
                $dados['endereco_cep'] = $line[8];
                $dados['endereco_cidade'] = $line[6];
                $dados['endereco_estado'] = ($line[7]) ? $this->index_model->get_estados_by($line[7]) : '0'; // manipular estado com ID
                $dados['telefone'] = $line[4];
                $dados['situacao'] = 0;

                $this->db->insert('motorista', $dados);

                echo "<pre>";
                print_r($dados);
            }

            $row++;

        }
        
        fclose($handle);

    }

}

/* End of file Site.php */
/* Location: ./application/modules/index/controllers/Index.php */