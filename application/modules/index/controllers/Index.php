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
        $this->load->model('empresa/empresa_model');
        $this->load->model('funcionario/funcionario_model');
        $this->load->model('ferramental_requisicao/ferramental_requisicao_model');
        $this->load->model('ativo_veiculo/ativo_veiculo_model');
        $this->load->model('ativo_externo/ativo_externo_model');
        $this->load->model('ativo_interno/ativo_interno_model');
        $this->load->model('relatorio/relatorio_model');
    }

    function index() {
        if ($this->user->nivel == 1){
            $data['estoque'] = count($this->ativo_externo_model->get_estoque($this->user->id_obra, null, 12));
            $data['requisicoes_pendentes'] = $this->ferramental_requisicao_model->get_lista_requisicao([1, 3, 6, 11, 14], 0, 5, $this->user->id_obra);
            $data['requisicoes_pendentes_total'] = $this->ferramental_requisicao_model->lista_requisicao_count([1, 3, 6, 11, 14], $this->user->id_obra);
        }

        $data['clientes'] = count($this->empresa_model->get_empresas());
        $data['colaboradores'] = count($this->funcionario_model->get_lista($this->user->id_empresa, $this->user->id_obra));
        $data['veiculos_manutencao'] = $this->ativo_veiculo_model->count_ativo_veiculo_em_manutencao();

        $data['ativo_interno_manutencoes'] = $this->ativo_interno_model->get_lista_manutencao(null, ["", 0, 2], true);
        $data['ativo_externo_manutencoes'] = $this->ativo_externo_model->get_lista_manutencao(null, ["", 0, 2], true);

        if ($this->input->get('informe_vencimentos') > 0 && in_array((int) $this->input->get('informe_vencimentos'), [5, 15, 30])) {
            $data['informe_vencimentos']['relatorio'] = $this->relatorio_model->informe_vencimentos((int) $this->input->get('informe_vencimentos'), $this->user->id_obra);
            $data['informe_vencimentos']['dias'] = (int) $this->input->get('informe_vencimentos');
        } else {
            $data['informe_vencimentos']['relatorio'] = $this->relatorio_model->informe_vencimentos(30, $this->user->id_obra);
            $data['informe_vencimentos']['dias'] = 30;
        }

        $today =  date("Y-m-d 23:59:59", strtotime('now'));
        $data['informe_retiradas_pendentes']['relatorio'] = $this->relatorio_model->informe_retiradas_pendentes($today, $this->user->id_obra);
        $data['informe_retiradas_pendentes']['vencimento'] = $today;
        
        $data_patrimonio = [
            'id_obra' => $this->user->id_obra,
            'tipo_veiculo' => 'todos',
        ];
        
        $data['patrimonio'] = $this->relatorio_model->patrimonio_disponivel($data_patrimonio, 'arquivo');

        $data['maquina_manutencao_hora'] = $this->relatorio_model->maquina_manutencao_hora();
        $data['revisao_por_km'] = $this->relatorio_model->revisao_por_km();

        $this->get_template('index', $data);
    }

    public function selecionar_obra(){
        $success = false;
        if ($this->user->nivel == 1 && $this->input->post("id_obra_gerencia")) {
            $this->db
                ->where('id_usuario', $this->user->id_usuario)
                ->update('usuario', ["id_obra_gerencia" => $this->input->post("id_obra_gerencia")]);
            $success = true;
        }

        $user = $this->db->where('id_usuario', $this->user->id_usuario)->get('usuario')->row();
        $this->user = $this->buscar_dados_logado($user);

        $redirect_url = $this->input->post("redirect_url");
        if ($redirect_url) echo redirect($redirect_url);
        else return $this->json(["success" => $user && $success, 'id_obra_gerencia' => $user->id_obra_gerencia]);
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