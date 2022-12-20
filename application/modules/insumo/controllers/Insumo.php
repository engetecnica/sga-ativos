<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of insumo
 *
 * @author https://github.com/srandrebaill
 */
class Insumo extends MY_Controller {

    function __construct() {
        parent::__construct();
        if ($this->is_auth()) {
            $this->load->model('insumo_model');
            $this->load->model('insumo_configuracao/insumo_configuracao_model');
            $this->load->model('fornecedor/fornecedor_model');
            $this->load->model('funcionario/funcionario_model');
        }
    }

    function index()
    {
        $data['insumos'] = $this->listar_insumos();    
        $this->get_template('index', $data);
    }



    /*
        Adicionar Insumo
    */
    public function adicionar()
    {
        $data['tipo_insumo'] = $this->insumo_configuracao_model->get_insumo_lista_completa();
        $data['fornecedor'] = $this->fornecedor_model->get_lista();
        $this->get_template('index_form', $data);
    }

    public function salvar(){

        $data['id_insumo'] = !is_null($this->input->post('id_insumo')) ? $this->input->post('id_insumo') : '';
        $data['id_insumo_configuracao'] = $this->input->post('tipo_insumo');
        $data['id_fornecedor'] = $this->input->post('fornecedor');
        $data['id_obra'] = ($this->user->id_obra) ?? null;
        $data['titulo'] = $this->input->post('titulo');
        $data['descricao'] = $this->input->post('descricao_insumo');
        $data['situacao'] = $this->input->post('situacao');

        $id_insumo = $this->insumo_model->salvar_formulario($data);

        if($id_insumo)
        {
            $insumo_estoque['id_insumo']    = $id_insumo;
            $insumo_estoque['id_usuario']    = $this->user->id_usuario;
            $insumo_estoque['quantidade']   = $this->input->post('quantidade');
            $insumo_estoque['valor']        = $this->formata_moeda_float($this->input->post('valor'));
            $insumo_estoque['tipo']         = 'entrada';

            $this->insumo_model->salvar_insumo_estoque($insumo_estoque);
        }

        if ($data['id_insumo'] == '') {
            $this->session->set_flashdata('msg_success', "Novo registro inserido com sucesso");
        }else {
            $this->session->set_flashdata('msg_success', "Registro atualizado com sucesso");
        }
        echo redirect(base_url("insumo"));
    }

    public function listar_insumos($id_obra=null)
    {
        return $this->insumo_model->get_insumos_by_obra($id_obra); 
    }

    function editar($id_insumo=null){

        $id_insumo = ($id_insumo) ?? 0;    

        if(!$this->insumo_model->get_insumo($id_insumo)){
            $this->session->set_flashdata('msg_erro', "Insumo não encontrado.");
            echo redirect(base_url("insumo"));
        }

        $data['tipo_insumo'] = $this->insumo_configuracao_model->get_insumo_lista_completa();
        $data['fornecedor'] = $this->fornecedor_model->get_lista();
        $data['detalhes'] = $this->insumo_model->get_insumo($id_insumo);
        $this->get_template('index_form', $data);
    }

    function deletar($id=null){

        // Salvar LOG
        $data['id_insumo'] = $id;
		$this->salvar_log(23, $id, 'deletar', $data);

        $this->db->where('id_insumo', $id);
        return $this->db->delete('insumo');

    }


    public function pesquisar_insumo_by_codigo(){
        echo $this->db->where('codigo_insumo', $this->input->post('cod_insumo'))->get('insumo')->num_rows();
    }

    public function salvar_estoque()
    {
        if($this->insumo_model->get_insumo($this->input->post('id_insumo'))){

            /* Salvar Estoque */
            $estoque['id_insumo'] = $this->input->post('id_insumo');
            $estoque['quantidade'] = $this->input->post('item-quantidade');
            $estoque['valor'] = $this->formata_moeda_float($this->input->post('item-valor-unitario'));
            $estoque['created_at'] = $this->input->post('item-data');
            $estoque['tipo'] = 'entrada';
            if($this->db->insert('insumo_estoque', $estoque)){

                /* LOG */
                $this->salvar_log(23, $this->input->post('id_insumo'), 'adicionar_estoque', $this->input->post());

                $this->session->set_flashdata('msg_success', "Estoque Atualizado com sucesso!");
                echo redirect(base_url('insumo'));
            }


            /* LOG */
            $this->salvar_log(23, $this->input->post('id_insumo'), 'erro_estoque', $this->input->post());

            $this->session->set_flashdata('msg_success', "Erro ao atualizar estoque.");
            echo redirect(base_url('insumo'));

        }
    }






    // Retiradas de Insumos
    public function retirada()
    {
        $data['retirada'] = $this->insumo_model->get_retirada_lista(); 
        $this->get_template('index_retirada', $data);   
    }


    public function retirada_adicionar(){
        $data['funcionario'] = $this->funcionario_model->get_lista(null, $this->user->id_obra);
        $data['insumo'] = $this->insumo_model->get_insumos_by_obra($this->user->id_obra);
        $this->get_template('index_form_retirada', $data);   
    }

    public function retirada_salvar(){

        if($this->input->post('quantidade')){

            $existe = 0;
            $insumo = [];
            $i = 0;
            foreach($this->input->post('quantidade') as $id_insumo=>$qtde)
            {

                if($qtde > 0){
                    $existe = 1;

                    $insumo[$i]['id_insumo'] = $id_insumo;
                    $insumo[$i]['id_usuario'] = $this->user->id_usuario;
                    $insumo[$i]['quantidade'] = $qtde;
                    $insumo[$i]['valor'] = "0.00";
                    $insumo[$i]['tipo'] = 'saida';

                    $i++;
                }

            }

            if($insumo){

                // registrar retirada
                $retirada['id_usuario'] = $this->user->id_usuario;
                $retirada['id_funcionario'] = $this->input->post('id_funcionario');
                $retirada['status'] = 0;

                $id_insumo_retirada = $this->insumo_model->salvar_insumo_retirada($retirada);

                $a = 0;
                foreach($insumo as $ins){
                    $insumo_adicionar[$a]['id_insumo'] = $ins['id_insumo'];
                    $insumo_adicionar[$a]['id_insumo_retirada '] = $id_insumo_retirada;
                    $insumo_adicionar[$a]['id_usuario'] = $this->user->id_usuario;
                    $insumo_adicionar[$a]['quantidade'] = $ins['quantidade'];
                    $insumo_adicionar[$a]['valor'] = "0.00";
                    $insumo_adicionar[$a]['tipo'] = 'saida';

                    $a++;
                }

                $this->insumo_model->salvar_insumo_estoque_batch($insumo_adicionar);
            }

          
            /* Verifica se um dos itens foi adicionado para salvar */
            if($existe == 1){
                $this->session->set_flashdata('msg_success', "Nova retirada registrada com sucesso!");
                echo redirect(base_url('insumo/retirada'));
            } else {
                $this->session->set_flashdata('msg_erro', "Erro ao salvar nova retirada. Você precisa incluir ao menos um item.");
                echo redirect(base_url('insumo/retirada/adicionar'));
            }

        } 

    }


    public function retirada_cancelar($id=null){
        
        $pesquisa_retirada = $this->insumo_model->get_retirada($id);

        if(!$pesquisa_retirada){
            $this->session->set_flashdata('msg_erro', "Retirada não localizada.");
            echo redirect(base_url('insumo/retirada'));
        }

        if($id==null){
            $this->session->set_flashdata('msg_erro', "Erro ao atualizar retirada.");
            echo redirect(base_url('insumo/retirada'));
        }  
        
        $this->salvar_log(23, $id, 'insumo_cancelar_retirada', $this->input->post());
        
        if($this->insumo_model->cancelar_retirada($id, ['status' => 5])){
            
            $this->insumo_model->set_estoque_entregue($id, ['status' => 5]); // Cancelado

            $this->session->set_flashdata('msg_success', "Retirada cancelada!");
            echo redirect(base_url('insumo/retirada'));
        }

    }


    public function retirada_entregar($id=null){

        $pesquisa_retirada = $this->insumo_model->get_retirada($id);

        

        if(!$pesquisa_retirada){
            $this->session->set_flashdata('msg_erro', "Retirada não localizada.");
            echo redirect(base_url('insumo/retirada'));
        }

        if($id==null){
            $this->session->set_flashdata('msg_erro', "Erro ao atualizar retirada.");
            echo redirect(base_url('insumo/retirada'));
        }

        // Baixar Estoque
        $set_estoque_entregue = $this->insumo_model->set_estoque_entregue($id, ['status' => 1]); // Entregue
        $this->salvar_log(23, $id, 'insumo_marcar_entregue_estoque', $this->input->post());

        // Baixar Retirada
        $set_estoque_retirada = $this->insumo_model->set_estoque_retirada($id, ['status' => 1]); // Entregue
        $this->salvar_log(23, $id, 'insumo_marcar_entregue_retirada', $this->input->post());

        if($set_estoque_entregue && $set_estoque_retirada)
        {
            $this->session->set_flashdata('msg_success', "Retirada marcada como Entregue!");
            echo redirect(base_url('insumo/retirada'));
        }

    }


    // Retirada Devolver
    public function devolver_itens($id_insumo_retirada=null)
    {

        $pesquisa_retirada = $this->insumo_model->get_retirada($id_insumo_retirada);

        if(!$pesquisa_retirada){
            $this->session->set_flashdata('msg_erro', "Retirada não localizada.");
            echo redirect(base_url('insumo/retirada'));
        }

        if($id_insumo_retirada==null){
            $this->session->set_flashdata('msg_erro', "Erro ao atualizar retirada.");
            echo redirect(base_url('insumo/retirada'));
        }    
        
        return $this->get_template('detalhes_retirada', ['detalhes'=>$pesquisa_retirada]);

    }

    public function salvar_devolucao()
    {
        $pesquisa_retirada = $this->insumo_model->get_retirada($this->input->post('id_insumo_retirada'));

        if(!$pesquisa_retirada){
            $this->session->set_flashdata('msg_erro', "Retirada não localizada.");
            echo redirect(base_url('insumo/retirada'));
        }

        $devolucao = [];
        if(null !== ($this->input->post('item_devolvido')))
        {

            $item_para_devolucao = 0;
            foreach($this->input->post('item_devolvido') as $key=>$valor)
            {
                $devolucao[$item_para_devolucao]['id_insumo'] = $key;
                $devolucao[$item_para_devolucao]['id_usuario'] = $this->user->id_usuario;
                $devolucao[$item_para_devolucao]['quantidade'] = $valor;
                $devolucao[$item_para_devolucao]['id_insumo_retirada'] = $this->input->post('id_insumo_retirada');
                $devolucao[$item_para_devolucao]['valor'] = '0.00';
                $devolucao[$item_para_devolucao]['tipo'] = 'entrada'; // entrada porque é devolução
                $devolucao[$item_para_devolucao]['status'] = '3'; // devolvido parcialmente

                if($valor > 0){
                    $item_para_devolucao++;
                }
            }

            if(count($devolucao) >0)
            {

                // Baixar Retirada
                $set_estoque_retirada = $this->insumo_model->set_estoque_retirada($this->input->post('id_insumo_retirada'), ['status' => 3]); // Devolvido Parcialmente
                $this->salvar_log(23, $this->input->post('id_insumo_retirada'), 'insumo_devolvido_parcialmente', $this->input->post());

                $this->insumo_model->salvar_insumo_estoque_batch($devolucao);

                $this->session->set_flashdata('msg_success', "Devolução registrada com sucesso.");
                echo redirect(base_url('insumo/retirada/devolver/'.$this->input->post('id_insumo_retirada')));
            }


            if($item_para_devolucao==0){
                $this->session->set_flashdata('msg_erro', "Nenhum insumo foi selecionado para devolução.");
                echo redirect(base_url('insumo/retirada'));
            }
        }

        $this->dd($devolucao, $this->input->post(), $pesquisa_retirada);
    }

    // Retirada Detalhes
    public function retirada_detalhes($id_insumo_retirada)
    {
        $pesquisa_retirada = $this->insumo_model->get_retirada($id_insumo_retirada);

        if(!$pesquisa_retirada){
            $this->session->set_flashdata('msg_erro', "Retirada não localizada.");
            echo redirect(base_url('insumo/retirada'));
        }

        if($id_insumo_retirada==null){
            $this->session->set_flashdata('msg_erro', "Erro ao atualizar retirada.");
            echo redirect(base_url('insumo/retirada'));
        }    
        
        return $this->get_template('detalhes_retirada', ['detalhes'=>$pesquisa_retirada]);
    }


    // Retirada Gerar Termo
    public function gerar_termo($id_insumo_retirada, $redirect = true)
    {

        $pesquisa_retirada = $this->insumo_model->get_retirada($id_insumo_retirada);

        $css = file_get_contents( __DIR__ ."/../../../../assets/css/relatorios.css", true, null);
        $data = [
            'css' =>  $css, 
            'logo' => $this->base64(__DIR__ ."/../../../../assets/images/icon/logo.png"),
            'header' => $this->base64(__DIR__ ."/../../../../assets/images/docs/termo_header.png"),
            'footer' => $this->base64(__DIR__ ."/../../../../assets/images/docs/termo_footer.png"),
            'data_hora' => date('d/m/Y H:i:s', strtotime('now')),
            'detalhes' => $pesquisa_retirada,
            'razaosocial' => $this->user->obra->obra_razaosocial
        ];

        $filename = "termo_retirada_insumo_" . date('YmdHis', strtotime('now')).".pdf";
        $html = $this->load->view("detalhes_retirada_termo", $data, true);

        $upload_path = "assets/uploads/anexo";
        $path = __DIR__."/../../../../{$upload_path}";
        $file = "{$path}/{$filename}";

        if (!file_exists($file)) {
            $this->gerar_pdf($file, $html);                

            $anexo = $this->anexo_model->query_anexos()
                        ->where("id_modulo_item = {$id_insumo_retirada} and tipo = 'retirada_insumo'")
                        ->limit(1)->get()->row();

            if ($anexo) {
                $id_anexo = $anexo->id_anexo;
                $this->db->where("id_anexo = {$id_anexo}")->update("anexo", ['anexo' => "anexo/{$filename}"]);
            } else {
                $id_anexo = $this->salvar_anexo(
                    [
                        "titulo" => "Insumo Retirada",
                        "descricao" => "Retirada do Insumo IDRETIRADA {$id_insumo_retirada}",
                        "anexo" => "anexo/{$filename}",
                    ],
                    'insumo',
                    $id_insumo_retirada,
                    "retirada_insumo"
                );
            } 

            if(!$redirect)  return $id_anexo != null;
        }

        if(!$redirect) return false;
        echo redirect($this->getRef());


        //$this->dd($pesquisa_retirada, $this->user);
    }
}