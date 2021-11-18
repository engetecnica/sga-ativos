<?php
trait MY_Trait {
    public function get_situacao($status=null, $case2 = 'Descartado', $case2_class = 'info'){
        $texto = "Ativo";
        $class = "success";
          
        switch ((int) $status) {
          case 1:
            $texto = "Inativo";
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
        $texto = "Desconhecido";
        $class = "danger";
          
        switch ($nivel) {
          default:
          case 0:
            $class = "info";
          break;
          case 1:
            $texto = "Administrador";
            $class = "info";
          break;
          case 2:
            $texto = "Almoxarifado";
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
  
      public function status_lista($type = 'object') {
        $lista = $this->session->status_lista;
        if (!$lista) {
          $lista = $this->ferramental_requisicao_model->get_requisicao_status();
          $this->session->status_lista = json_encode($lista);
        }
  
        return array_map(function($item) use ($type) {
          $new_item = [
            'texto' => $item->texto,
            'class' => $item->classe,
            'slug' => $item->slug,
            'id_status' => $item->id_requisicao_status
          ];

          if ($type === 'object') {
            return (object) $new_item;
          }
          return $new_item;
        }, is_string($lista) ? json_decode($lista) : $lista);
      }
  
      public function status($status=null) {
        $lista = $this->status_lista();
        $texto = "Desconhecido"; $class = "muted"; $slug = "desconhecido";
  
        foreach ($lista as $item){
          if ($item->id_status == $status) {
            $texto = $item->texto;
            $class = $item->class;
            $slug = $item->slug;
          }
        }
  
        return [
            'texto' => $texto,
            'class' => $class,
            'slug' => $slug,
        ];
      }

      public function status_by_name($status=null) {
        $lista = $this->status_lista();
        $texto = "Desconhecido"; $class = "muted"; $slug = "desconhecido";
  
        foreach ($lista as $item){
          if ($item->slug == $status) {
            $texto = $item->texto;
            $class = $item->class;
            $slug = $item->slug;
          }
        }
  
        return [
            'texto' => $texto,
            'class' => $class,
            'slug' => $slug,
        ];
      }

      public function status_is($id, $name) : bool {
        $lista = $this->status_lista();
        foreach ($lista as $item){
          if ($item->id_status == $id && $item->slug == $name) {
            return true;
          }
        }
        return false;
      }
  
      public function formata_moeda($valor = 0){
        return "R$ ". number_format($valor, 2, ',', '.');
      }

      public function formata_data_hora($data_hora = null){
        return $data_hora == "0000-00-00 00:00:00" || !$data_hora ? "-" : date("d/m/Y H:i:s", strtotime($data_hora));
      }

      public function formata_data($data = null){
        return $data == "0000-00-00" || !$data ? "-" : date("d/m/Y", strtotime($data));
      }
  
      public function get_ativo_externo_on_lista($lista, $id){
        foreach($lista as $item) {
          if ($item->id_ativo_externo == $id) {
            return $item;
          }
        }
        return null;
      }

      public function sem_acesso(){
        $this->session->set_flashdata('msg_erro', "Você não possui acesso a este módulo.");
        echo redirect(base_url());
        return;
      }

      public function dd(...$data){
        foreach($data as $dt) {
            echo "<pre>";
            echo print_r($dt, true);
            echo "</pre>";
        }
        exit;
    }
}