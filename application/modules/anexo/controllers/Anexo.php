<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author Messias Dias | https://github.com/messiasdias
 */
class Anexo  extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('anexo_model');

        # Login
        if($this->session->userdata('logado')==null){
          echo redirect(base_url('login')); 
        }
        $this->load->model('ativo_externo/ativo_externo_model');
        $this->load->model('ativo_interno/ativo_interno_model');  
        $this->load->model('ativo_veiculo/ativo_veiculo_model');    
    }

    function index(
      $id_modulo = null, 
      $id_modulo_item = null, 
      $id_modulo_subitem = null, 
      $pagina = null, 
      $limite = null
    ){

      $data = [
        "id_modulo" => $id_modulo,
        "id_modulo_item" => $id_modulo_item,
        "id_modulo_subitem" => $id_modulo_subitem,  
        "pagina" => $pagina,
        "limite" => $limite,
        "refer" => getenv("HTTP_REFERER"),
        "modulo" => $this->db
                      ->where('id_modulo', $id_modulo)
                      ->get('modulo')->row()
      ];

      $data['anexos'] = $this->anexo_model->get_anexos(
        $id_modulo,
        $id_modulo_item,
        $id_modulo_subitem,  
        $pagina,
        $limite 
      );

      $data['anexo_modulos'] = $this->anexo_model->modulos;
      $data['anexo_tipos'] = $this->anexo_model->tipos;

      $this->get_template("index", $data);
    }

    function adicionar(
      $id_modulo = null, 
      $id_modulo_item = null, 
      $id_modulo_subitem = null, 
      $pagina = null, 
      $limite = null
    ){

      $data = [
        "id_modulo" => $id_modulo,
        "id_modulo_item" => $id_modulo_item,
        "id_modulo_subitem" => $id_modulo_subitem,  
        "pagina" => $pagina,
        "limite" => $limite,
        "modulo" => $this->db->where('rota', $id_modulo)->get('modulo')->row()
      ];

      $data['anexos'] = $this->anexo_model->get_anexos(
        $id_modulo,
        $id_modulo_item,
        $id_modulo_subitem,  
        $pagina,
        $limite 
      );

      $data['anexo_modulos'] = $this->anexo_model->modulos;
      $data['anexo_tipos'] = $this->anexo_model->tipos;
      $data['veiculos'] = $this->ativo_veiculo_model->get_tipo_servico(10, 'Serviços Mecânicos');
      $data['veiculo_manutencao_servicos'] = $this->ativo_veiculo_model->get_tipo_servico(10, 'Serviços Mecânicos');

      $this->get_template("index_form", $data);
    }


    function salvar()
    {
      $modulo_name = str_replace("_", " ", ucfirst($this->input->post('modulo')));
      $titulo = $this->input->post('titulo') ? $this->input->post('titulo') : $modulo_name . " - ". ucfirst($this->input->post('tipo')) . " - ".date("d/m/Y H:i:s", strtotime('now'));
      $descricao = $this->input->post('descricao') ? $this->input->post('descricao') : $this->anexo_model->get_anexo_tipo($this->input->post('tipo'))['nome'];
      $modulo = $this->db->where('rota', $this->input->post('modulo'))->get('modulo')->row();
    
      //upload file
      $anexo = 'anexo/';
      if (!is_readable(APPPATH.'assets/uploads/anexo')) {
        $this->session->set_flashdata('msg_erro',"A pasta de destino do upload não parece ser gravável.");
        echo redirect(base_url("anexo/adicionar"));
        return;
      }

      if($_FILES['anexo']['error'] == 0 && $_FILES['anexo']['size'] > 0){
        $anexo .= $this->upload_arquivo('anexo');
        if (!$anexo || $anexo == '') {
            $this->session->set_flashdata('msg_erro', "O tamanho do comprovante deve ser menor ou igual a ".ini_get('upload_max_filesize'));
            echo redirect(base_url("anexo/adicionar"));
            return;
        }
      }

      $data = [
        "id_usuario" => $this->user->id_usuario,
        "id_modulo" => $modulo->id_modulo,
        "id_modulo_item" => $this->input->post('item'),
        "id_modulo_subitem" => $this->input->post('suditem'),
        "id_configuracao" => $this->input->post('servico'),
        "tipo" => $this->input->post('tipo'),
        "titulo" => $titulo,
        "descricao" => $descricao,
        "anexo" => $anexo,
      ];

      $this->anexo_model->salvar_formulario($data);
      echo redirect(base_url("anexo"));
      return true;
    }

    function deletar($id_anexo){
      $success = false;
      if ($this->input->method() == "post") {
        $success = $this->anexo_model->deletar($id_anexo);
      }
      return $this->json(['success' => $success],  $success ? 200 : 404);
    }

    function items($modulo, $search = null) {
      $data = [];

      switch ($modulo) {
        case 'ativo_externo':
          $data = array_map(function($ativo) {
            return (object) [
              "id" => $ativo->id_ativo_externo,
              "descricao" => $ativo->codigo . " - ".$ativo->nome,
              "data" => date('d/m/Y H:i:s', strtotime($ativo->data_inclusao)),
              "valor" => $ativo->valor,
            ];
          },$this->ativo_externo_model->search_ativos($search));
        break;

        case 'ativo_interno':
          $data = array_map(function($ativo) {
            return (object) [
              "id" => $ativo->id_ativo_interno,
              "descricao" => $ativo->id_ativo_interno . " - ".$ativo->nome,
              "data" => date('d/m/Y H:i:s', strtotime($ativo->data_inclusao)),
              "valor" => $ativo->valor,
            ];
          },$this->ativo_interno_model->search_ativos($search));
        break;

        case 'ativo_veiculo':
          $data = array_map(function($ativo) {
            return (object) [
              "id" => $ativo->id_ativo_veiculo,
              "descricao" => $ativo->id_ativo_veiculo. " - ".$ativo->veiculo_placa . " - ".$ativo->veiculo,
              "data" => date('d/m/Y H:i:s', strtotime($ativo->data)),
              "valor" => $ativo->valor_fipe,
            ];
          },$this->ativo_veiculo_model->search_ativos($search));
        break;
      }

      $this->json($data, 200);
    } 


    function subitems($modulo, $tipo, $id_modulo_item) {
      $data = [];

      switch ($modulo) {
        case 'ativo_externo':
          $data = [];
        break;

        case 'ativo_interno':
          if ($tipo == "manutencao") {
            $data = array_map(function($manutencao) {
              return (object) [
                "id" => $manutencao->id_manutencao,
                "descricao" => $manutencao->id_manutencao ." - ".$manutencao->servico . " - ". date("d/m/Y H:i:s", strtotime($manutencao->data_saida)) ,
                "data" => $manutencao->data_saida,
                "valor" => $manutencao->valor,
              ];
            }, $this->ativo_interno_model->get_lista_manutencao($id_modulo_item));
          }
        break;

        case 'ativo_veiculo':
          if ($tipo == "manutencao") {
            $data = array_map(function($manutencao) {
              return (object) [
                "id" => $manutencao->id_ativo_veiculo_manutencao,
                "descricao" => $manutencao->id_ativo_veiculo_manutencao ." - ".$manutencao->servico . " - ". date("d/m/Y H:i:s", strtotime($manutencao->data_entrada)) ,
                "data" => $manutencao->data_entrada,
                "valor" => $manutencao->veiculo_custo,
              ];
            }, $this->ativo_veiculo_model->get_ativo_veiculo_manutencao_lista($id_modulo_item));
          }

          if ($tipo == "ipva") {
            $data = array_map(function($ipva) {
              return (object) [
                "id" => $ipva->id_ativo_veiculo_ipva,
                "descricao" => $ipva->id_ativo_veiculo_ipva ." - " . $ipva->ipva_ano. " - ".$ipva->veiculo . " - ". date("d/m/Y H:i:s", strtotime($ipva->ipva_data_pagamento)) ,
                "data" => $ipva->ipva_data_pagamento,
                "valor" => $ipva->ipva_custo,
              ];
            }, $this->ativo_veiculo_model->get_ativo_veiculo_ipva_lista($id_modulo_item));
          }

          if ($tipo == "kilometragem") {
            $data = array_map(function($km) {
              return (object) [
                "id" => $km->id_ativo_veiculo_quilometragem,
                "descricao" => $km->id_ativo_veiculo_quilometragem ." - " .$km->veiculo_km_inicial . "Km à ". $km->veiculo_km_final . "Km - ".  date("d/m/Y H:i:s", strtotime($km->data)) ,
                "data" => $km->data,
                "valor" => $km->veiculo_custo,
              ];
            }, $this->ativo_veiculo_model->get_ativo_veiculo_km_lista($id_modulo_item));
          }

          if ($tipo == "seguro") {
            $data = array_map(function($seguro) {
              return (object) [
                "id" => $seguro->id_ativo_veiculo_seguro,
                "descricao" => $seguro->id_ativo_veiculo_seguro ." - " .$seguro->veiculo_km_final . " - ". $seguro->veiculo_km_final . "KM - ".  date("d/m/Y H:i:s", strtotime($seguro->seguro_carencia_inicio)) ,
                "data" => $seguro->data,
                "valor" => $seguro->veiculo_custo,
              ];
            }, $this->ativo_veiculo_model->get_ativo_veiculo_seguro_lista($id_modulo_item));
          }
        break;
      }

      $this->json($data, 200);
    } 
    
}