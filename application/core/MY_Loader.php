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
      $class = "info";
        
      switch ($nivel) {
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
        $texto = "Em Manutenção";
        $class = "warning";
        
        switch ((int) $status) {
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

    public function formata_moeda($valor = 0){
      return "R$ ". number_format($valor, 2, ',', '.');
    }
}
