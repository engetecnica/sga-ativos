<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH . "third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
    public function get_situacao($status=null, $case2 = 'DESCARTADO', $case2_class = 'info'){
      $texto = "ATIVO";
      $class = "success";
        
      switch ((int) $status) {
        case 1:
          $texto = "INATIVO";
          $class = "danger";
        break;
        case 2:
          $texto = $case2;
          $class = $case2_class;
        break;
      }

      return [
        'texto' => $texto,
        'class' => $class
      ];
    }

    public function get_usuario_nivel($nivel=null){
      $texto = $nivel;
      $class = "";
        
      switch ($nivel) {
        default:
        case 0:
          $texto = $nivel;
          $class = "info";
        break;
        case "Administrador":
          $class = "info";
        break;
        case "Almoxarifado":
          $class = "warning";
        break;
      }

      return [
        'texto' => $texto,
        'class' => $class
      ];
    }

    public function get_obra_base($status=null){
    	switch($status){
    		case 0:
    		  $status = "NÃO";
    		break;
    		case 1:
    		  $status = "SIM";
    		break;
    	}
    	return $status;
    }

    public function get_situacao_transporte($status=null){
        switch($status){
            case 1:
              $status = "Pendente";
            break;
            case 2:
              $status = "Em Andamento";
            break;
            case 3:
              $status = "Entregue";
            break;
            case 4:
              $status = "Concluído";
            break;
        }
        return $status;
    } 
    
    public function get_situacao_manutencao($status=null){
        $texto = $class = "";
        switch ((int) $status) {
          default:
          case 0:
            $texto = "Em Manutenção";
            $class = "warning";
          break;
          case 1:
            $texto = "Manutenção OK";
            $class = "success";
          break;
          case 2:
            $texto =  "Manutenção Com Pedência";
            $class = "danger";
          break;
        }

        return [
            'texto' => $texto,
            'class' => $class
        ];
    }

    public function get_requisicao_status($status=null){
      $texto = $class = "";
      switch ((int) $status) {
        case 1:
          $texto = "Pendente";
          $class = "danger";
        break;
        case 2:
          $texto = "Liberado";
          $class = "info";
        break;
      }
      return [
          'texto' => $texto,
          'class' => $class
      ];
    }

    public function formata_moeda($valor = 0){
      return "R$ ". number_format($valor, 2, ',', '.');
    }

    public function get_ativo_externo_on_lista($lista, $id){
      foreach($lista as $item) {
        if ($item->id_ativo_externo == $id) {
          return $item;
        }
      }
      return (object)['codigo' => ''];
    }
}
