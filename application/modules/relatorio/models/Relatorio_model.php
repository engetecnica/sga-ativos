<?php 
require_once(__DIR__."/Relatorio_model_base.php");
class Relatorio_model extends Relatorio_model_base {

  protected $uploads = [];

  public function __construct() {
      parent::__construct();
      $this->load->model("ativo_veiculo/ativo_veiculo_model");
      $this->load->model("anexo/anexo_model");
      $this->load->model("notificacoes_model");

      $this->uploads = [
        'avatar' => 'usuario', 
        'comprovante_fiscal' => 'ativo_veiculo_quilometragem', 
        'contrato_seguro' => 'ativo_veiculo_seguro',
        'ordem_de_servico' => 'ativo_veiculo_manutencao',
        'comprovante_ipva' => 'ativo_veiculo_ipva',
        'anexo' => 'anexo', 
        'certificado_de_calibracao' => 'ativo_externo', 
        'ferramental_estoque' => 'ativo_externo_retirada',
      ];
  }

  private function extract_data($tipo, $data){
    $extracted_data = [];
    foreach($this->relatorios[$tipo]['filtros'] as $filtro){
      if (isset($data[$filtro])) {
        $extracted_data[$filtro] = $data[$filtro];
      }
    }

    if (isset($extracted_data['periodo']) && ($extracted_data['periodo']['tipo'] == 'outro')) {
      $extracted_data['periodo']['inicio'] = "{$extracted_data['periodo']['inicio']} 00:00:00";
      $extracted_data['periodo']['fim'] = "{$extracted_data['periodo']['fim']} 23:59:59";
    }
    return $extracted_data;
  }

	public function funcionario($data=null, $tipo=null) {
    $data = $this->extract_data('funcionario', $data);
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    $relatorio = null;
    if ($tipo && $tipo == 'arquivo') {
      $relatorio = $this->db->from('funcionario fnc')->select('fnc.*');
    } else {

      $relatorio = $this->db
              ->from('funcionario fnc')
              ->select('COUNT(fnc.id_funcionario) as total');
      
      $select = "select COUNT(situacao) FROM funcionario WHERE (situacao = '0'";
      $select2 = "select COUNT(situacao) FROM funcionario WHERE (situacao = '1'";

      if ($data['id_empresa']) {
        $select .= " and id_empresa = fnc.id_empresa";
        $select2 .= " and id_empresa = fnc.id_empresa";
      }
    
      if ($data['id_obra']) {
        $select .= " and id_obra = fnc.id_obra";
        $select2 .= " and id_obra = fnc.id_obra";
      }

      if ($inicio && $fim) {
        $select .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
        $select2 .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
      }

      $select .= ")";
      $select2 .= ")";
      $relatorio
              ->select("($select) as ativos")
              ->select("($select2) as inativos");
    }

    $relatorio
    ->select('emp.id_empresa, emp.razao_social as empresa')
    ->join('empresa emp', 'fnc.id_empresa = emp.id_empresa','left');

    if ($data['id_empresa']) {
      $relatorio->where("fnc.id_empresa = {$data['id_empresa']}");
    }

    $relatorio
      ->select('ob.id_obra, ob.codigo_obra as obra, ob.endereco')
      ->join('obra ob', 'fnc.id_obra = ob.id_obra', 'left');

    if ($data['id_obra']) {
      $relatorio->where("fnc.id_obra = {$data['id_obra']}");
    }

    if ($inicio && $fim) {
      $relatorio->where("fnc.data_criacao >= '$inicio'")
                 ->where("fnc.data_criacao <= '$fim'");
    }
    
    if ($tipo && $tipo == 'arquivo') {
      return $relatorio->group_by('fnc.id_funcionario')->get()->result();
    }
    return $relatorio->get()->row();
  }

  public function empresa($data=null, $tipo=null) {
    $data = $this->extract_data('empresa', $data);
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    $relatorio = null;
    if ($tipo && $tipo == 'arquivo') {
      $relatorio = $this->db->from('empresa emp')->select('emp.*');
    } else {
      $relatorio = $this->db
              ->from('empresa emp')
              ->select('COUNT(emp.id_empresa) as total');

      $select = "select COUNT(situacao) FROM funcionario WHERE (situacao = '0'";
      $select2 = "select COUNT(situacao) FROM funcionario WHERE (situacao = '1'";

      $inicio = $data['periodo']['inicio'];
      $fim = $data['periodo']['fim'];
      if ($inicio && $fim) {
        $select .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
        $select2 .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
      }
      $select .= ")";
      $select2 .= ")";

      $relatorio
              ->select("($select) as ativos")
              ->select("($select2) as inativos");
    }

    if ($inicio && $fim) {
      $relatorio->where("emp.data_criacao >= '$inicio'")
                 ->where("emp.data_criacao <= '$fim'");
    }

    if ($tipo && $tipo == 'arquivo') { 
      return $relatorio->group_by('emp.id_empresa')->get()->result();
    }
    return $relatorio->get()->row();
  }

  public function obra($data=null, $tipo=null) {
    $data = $this->extract_data('obra', $data);
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    $relatorio = null;
    if ($tipo && $tipo == 'arquivo') {
      $relatorio = $this->db->from('obra ob')->select('ob.*');
    } else {
      $relatorio = $this->db
              ->from('obra ob')
              ->select('COUNT(ob.id_obra) as total');

      $select = "select COUNT(situacao) FROM obra WHERE (situacao = '0'";
      $select2 = "select COUNT(situacao) FROM obra WHERE (situacao = '1'";

      if ($data['id_empresa']) {
        $select .= " and id_empresa = ob.id_empresa";
        $select2 .= " and id_empresa = ob.id_empresa";
      }
    
      if ($inicio && $fim) {
        $select .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
        $select2 .= " and (data_criacao >= '$inicio' and data_criacao <= '$fim')";
      }
      $select .= ")";
      $select2 .= ")";
      $relatorio
          ->select("($select) as ativos")
          ->select("($select2) as inativos");
    }

    $relatorio
        ->select('emp.id_empresa, emp.razao_social as empresa')
        ->join('empresa emp', 'ob.id_empresa = emp.id_empresa');

    if ($data['id_empresa']) {
      $relatorio->where("ob.id_empresa = {$data['id_empresa']}");
    }

    if ($inicio && $fim) {
      $relatorio->where("ob.data_criacao >= '$inicio'")
                 ->where("ob.data_criacao <= '$fim'")
                 ->group_by('ob.id_obra');
    }
    
    if ($tipo && $tipo == 'arquivo') { 
      return $relatorio->get()->result();
    }
    return $relatorio->get()->row();
  }

  public function ferramentas_disponiveis_na_obra($data=null, $tipo=null) {
    $data = $this->extract_data('ferramentas_disponiveis_na_obra', $data);
    $relatorio = null;
    $obras_data = [
      'obras' => [],
      'show_valor_total' => isset($data['valor_total']) && $data['valor_total'] === "true"
    ];

    if ($tipo && $tipo == 'arquivo') {
      if ($data['id_obra']) {
        $obra = $this->obra_model->get_obra($data['id_obra']);
        $obra->grupos = [];
        $obra->grupos = $this->ativo_externo_model->get_grupos($obra->id_obra);
        $grupos = $this->ativo_externo_model->get_grupos($obra->id_obra);

        if ($obras_data['show_valor_total']) {
          $obra->total_obra = 0;
          foreach($grupos as $grupo){ 
            $grupo->total_grupo = 0;
            foreach($grupo->ativos as $ativo){ 
              $grupo->total_grupo += floatval($ativo->valor);
            } 
            $obra->total_obra += floatval($grupo->total_grupo);
          }
        }

        $obra->grupos = $grupos;
        $obras_data['obras'] = [$obra];
        return $obras_data;
      }

      $obras = $this->obra_model->get_obras();
      foreach($obras as $obra){
        $grupos = $this->ativo_externo_model->get_grupos($obra->id_obra);

        if ($obras_data['show_valor_total']) {
          $obra->total_obra = 0;
          foreach($grupos as $grupo){ 
            $grupo->total_grupo = 0;
            foreach($grupo->ativos as $ativo){ 
              $grupo->total_grupo += floatval($ativo->valor);
            } 
            $obra->total_obra += floatval($grupo->total_grupo);
          }
        }

        $obra->grupos = $grupos;
      }

      $obras_data['obras'] = $obras;
      return $obras_data;
    } else {
      $relatorio = $this->db
      ->from('ativo_externo atv')
      ->select('COUNT(atv.id_ativo_externo) as total');

      //'Em Estoque', 'Liberado' ,'Em Transito', 'Em Operação', 'Fora de Operação', 'Com Defeito', 'Total'
      $select = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 12";
      $select2 = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 2";
      $select3 = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 3";
      $select4 = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 5";
      $select5 = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 8";
      $select6 = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (situacao = 10";

      if ($data['id_obra']) {
        $select .= " and id_obra = atv.id_obra";
        $select2 .= " and id_obra = atv.id_obra";
        $select3 .= " and id_obra = atv.id_obra";
        $select4 .= " and id_obra = atv.id_obra";
        $select5 .= " and id_obra = atv.id_obra";
        $select6 .= " and id_obra = atv.id_obra";
      }

      $select .= ")";
      $select2 .= ")";
      $select3 .= ")";
      $select4 .= ")";
      $select5 .= ")";
      $select6 .= ")";

      $relatorio
          ->select("($select) as em_estoque")
          ->select("($select2) as liberado")
          ->select("($select3) as em_transito")
          ->select("($select4) as em_operacao")
          ->select("($select5) as fora_de_operacao")
          ->select("($select6) as com_defeito");
    }

    $relatorio
      ->select('ob.id_obra, ob.codigo_obra as obra, ob.endereco')
      ->join('obra ob', 'atv.id_obra = ob.id_obra', 'left');

    if ($data['id_obra']) {
        $relatorio->where("atv.id_obra = {$data['id_obra']}");
    }

    if ($tipo && $tipo == 'arquivo') { 
      return $relatorio->get()->result();
    }
    return $relatorio->get()->row();
  }

  public function ferramentas_em_estoque($data=null, $tipo=null){
    $data = $this->extract_data('ferramentas_em_estoque', $data);
    $relatorio = null;

    if ($tipo && $tipo == 'arquivo') {
      if ($data['id_obra']) {
        $obra = $this->obra_model->get_obra($data['id_obra']);
        $obra->grupos = $this->ativo_externo_model->get_grupos($obra->id_obra);
        return [$obra];
      }

      $obras = $this->obra_model->get_obras();
      foreach($obras as $obra){
        $obra->grupos = $this->ativo_externo_model->get_grupos($obra->id_obra, null, 12);
      }
      return $obras;
    } else {
      $relatorio = $this->db
      ->from('ativo_externo atv')
      ->select('COUNT(atv.id_ativo_externo) as total, atv.id_obra')
      ->where("atv.situacao = 12");

      if ($data['id_obra']) {
          $relatorio
          ->select('ob.id_obra, ob.codigo_obra as nome, ob.endereco as endereco')
          ->join('obra ob', 'atv.id_obra = ob.id_obra')
          ->where("atv.id_obra = {$data['id_obra']}");
      } else {
        $relatorio
          ->select("ob.id_obra, ob.codigo_obra as nome, ob.endereco as endereco")
          ->join('obra ob', 'atv.id_obra = ob.id_obra')
          ->where("atv.id_obra = ob.id_obra");
      }

      $obras = $relatorio->group_by('atv.id_obra')->get()->result();
      $relatorio = [
        'total' => 0,
      ];

      foreach ($obras as $key => $obra) {
        $relatorio[str_replace([' ', '-'], ['_', ''] ,strtolower($obra->nome))] = (int) $obra->total;
        $relatorio['total'] += (int) $obra->total;
      }
      return (object) $relatorio;
    }
  }

  public function equipamentos_em_estoque($data=null, $tipo=null){
    $data = $this->extract_data('equipamentos_em_estoque', $data);
    $relatorio = null;

    if ($tipo && $tipo == 'arquivo') {
      if ($data['id_obra']) {
        $obra = $this->obra_model->get_obra($data['id_obra']);
        $obra->equipamentos = $this->ativo_interno_model->get_lista($obra->id_obra, 0);
        return [$obra];
      }

      $obras = $this->obra_model->get_obras();
      foreach($obras as $obra){
        $obra->equipamentos = $this->ativo_interno_model->get_lista($obra->id_obra, 0);
      }
      return $obras;

    } else {
      $relatorio = $this->db
        ->from('ativo_interno atv')
        ->select('COUNT(atv.id_ativo_interno) as total, atv.id_obra')
        ->where("atv.situacao = 0")
        ->select('ob.id_obra, ob.codigo_obra as nome, ob.endereco as endereco')
        ->join('obra ob', 'atv.id_obra = ob.id_obra');

      if ($data['id_obra']) {
          $relatorio->where("atv.id_obra = {$data['id_obra']}");
      } else {
        $relatorio->where("atv.id_obra = ob.id_obra");
      }
      $relatorio->group_by('atv.id_obra');

      if ($tipo && $tipo == 'arquivo') {
        return $relatorio->get()->result();
      }

      $obras = $relatorio->get()->result();
      $relatorio = [
        'total' => 0,
      ];

      foreach ($obras as $key => $obra) {
        $relatorio[str_replace([' ', '-'], ['_', ''] ,strtolower($obra->nome))] = (int) $obra->total;
        $relatorio['total'] += (int) $obra->total;
      }

      return (object) $relatorio;
    }
  }

  public function veiculos_disponiveis($data=null, $tipo = null){
    $data = $this->extract_data('veiculos_disponiveis', $data);

    if ($tipo && $tipo == 'arquivo') {
      $relatorio = $this->db ->from('ativo_veiculo atv');
      if ($data['tipo_veiculo'] && $data['tipo_veiculo'] !== 'todos') {
          $relatorio->where("tipo_veiculo = {$data['tipo_veiculo']}");
      }
      return  $relatorio->where("situacao = '0'")->get()->result();
    }

    $relatorio = $this->db
    ->from('ativo_veiculo atv')
    ->select('COUNT(atv.id_ativo_veiculo) as total');

    if ($data['tipo_veiculo'] && $data['tipo_veiculo'] !== 'todos') {
        $select = "select COUNT(id_ativo_veiculo) FROM ativo_veiculo WHERE (tipo_veiculo = '{$data['tipo_veiculo']}' and situacao = '0')";
        $relatorio->select("($select) as '{$data['tipo_veiculo']}'");
    } else {
      $select = "select COUNT(id_ativo_veiculo) FROM ativo_veiculo WHERE (tipo_veiculo = 'carro' and situacao = '0')";
      $select2 = "select COUNT(id_ativo_veiculo) FROM ativo_veiculo WHERE (tipo_veiculo = 'moto' and situacao = '0')";
      $select3 = "select COUNT(id_ativo_veiculo) FROM ativo_veiculo WHERE (tipo_veiculo = 'caminhao' and situacao = '0')";
      $select4 = "select COUNT(id_ativo_veiculo) FROM ativo_veiculo WHERE (tipo_veiculo = 'maquina' and situacao = '0')";
      $relatorio->select("($select) as carro")
                ->select("($select2) as moto")
                ->select("($select3) as caminhao")
                ->select("($select4) as maquina");
    }
    return $relatorio->where("atv.situacao = '0'")->get()->row();
  }

  public function veiculos_depreciacao($data=null){
    $data = $this->extract_data('veiculos_depreciacao', $data);
    $relatorio = $this->db
        ->from('ativo_veiculo_depreciacao vdp')
        ->join('ativo_veiculo atv', 'vdp.id_ativo_veiculo = atv.id_ativo_veiculo');
    
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    if ($inicio && $fim) {
      $relatorio->where("vdp.veiculo_data >= '$inicio'")
                 ->where("vdp.veiculo_data <= '$fim'");
    }

    if (isset($data['veiculo_placa']) && !empty($data['veiculo_placa'])) {
      $relatorio->where("atv.veiculo_placa = '{$data['veiculo_placa']}'");
    }

    if (isset($data['id_interno_maquina']) && !empty($data['id_interno_maquina'])) {
      $relatorio->where("atv.id_interno_maquina = '{$data['id_interno_maquina']}'");
    }

    return $relatorio->get()->result();
  }


  public function veiculos_quilometragem($data=null){
    $data = $this->extract_data('veiculos_quilometragem', $data);
    $kms_credito_select = "(select veiculo_km_proxima_revisao from ativo_veiculo_manutencao where (id_ativo_veiculo = atv.id_ativo_veiculo AND (veiculo_km_proxima_revisao IS NOT NULL AND veiculo_km_proxima_revisao > 0)) order by id_ativo_veiculo_manutencao desc limit 1)";

    $relatorio = $this->db
        ->from('ativo_veiculo_quilometragem km')
        ->join('ativo_veiculo atv', 'km.id_ativo_veiculo = atv.id_ativo_veiculo','right')
        ->select('km.veiculo_km as km_atual, atv.veiculo_km as km_inicial, (km.veiculo_km  - atv.veiculo_km) as km_rodado')
        ->select("atv.id_ativo_veiculo, atv.veiculo_placa, atv.veiculo, atv.veiculo_placa, atv.id_interno_maquina, atv.tipo_veiculo, atv.situacao, atv.data, atv.id_marca, atv.id_modelo")
        ->select("(({$kms_credito_select} - km.veiculo_km) + atv.veiculo_km)  as km_ultima_revisao, {$kms_credito_select} as km_proxima_revisao");
    
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];
    if ($inicio && $fim) {
      $relatorio->where("km.data >= '$inicio'")
                 ->where("km.data <= '$fim'");
    }

    if (isset($data['tipo_veiculo']) && $data['tipo_veiculo'] != 'todos') {
      $relatorio->where("atv.tipo_veiculo = '{$data['tipo_veiculo']}'");
    }

    if (isset($data['veiculo_placa']) && !empty($data['veiculo_placa'])) {
      $relatorio->where("atv.veiculo_placa = '{$data['veiculo_placa']}'");
    }

    if (isset($data['id_interno_maquina']) && !empty($data['id_interno_maquina'])) {
      $relatorio->where("atv.id_interno_maquina = '{$data['id_interno_maquina']}'");
    }
 
    $veiculos = $relatorio->group_by('atv.id_ativo_veiculo')->get()->result();
    if(count($veiculos) > 0) {
      foreach($veiculos as $k => $veiculo){
        if ($veiculo->tipo_veiculo == 'maquina') $veiculos[$k] = $this->ativo_veiculo_model->set_outros_dados_veiculo($veiculo);
      }
    }
    return $veiculos;
  }

  public function veiculos_abastecimentos($data=null){
    $veiculos_abastecimentos = $this->custos_veiculos_abastecimentos($this->extract_data('veiculos_abastecimentos', $data), 'arquivo');

    return (object) [
        'abastecimentos' => $veiculos_abastecimentos->lista,
        'total' => $veiculos_abastecimentos->total
    ];
  }

  public function custos_ferramentas($data, $tipo=null){
    $ferramentas = null;
    $ferramentas_total = null;
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    if ($tipo && $tipo == 'arquivo') {
      //Ferramentas
      $ferramentas = $this->db->from('ativo_externo ate');
      if ($inicio && $fim) {
        $ferramentas->where("ate.data_inclusao >= '$inicio'")
                  ->where("ate.data_inclusao <= '$fim'");
      }

      if ($data['id_obra']) {
        $ferramentas->where("ate.id_obra = {$data['id_obra']}");
      }
      $ferramentas = $ferramentas->get()->result();
    }

    //Ferramentas total
    $this->db->reset_query();
    $ferramentas_total = $this->db
          ->from('ativo_externo ates')
          ->select("SUM(ates.valor) as valor");

    if ($inicio && $fim) {
      $ferramentas_total->where("ates.data_inclusao >= '$inicio'")
                  ->where("ates.data_inclusao <= '$fim'");
    }

    if ($data['id_obra']) {
      $ferramentas_total->where("ates.id_obra = {$data['id_obra']}");
    }
    $ferramentas_total = $ferramentas_total->get()->row();

    
    if ($tipo && $tipo == 'arquivo') {
      return (object) [
        'lista' =>  $ferramentas,
        'total' => $this->formata_moeda($ferramentas_total->valor),
      ];
    }

    return (object) [
      'lista' =>  $ferramentas,
      'total' => $this->formata_moeda($ferramentas_total->valor, true),
    ];
  }

  public function custos_equipamentos($data, $tipo=null){
    $equipamentos =  null;
    $equipamentos_total = null;
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    if ($tipo && $tipo == 'arquivo') {
      //Equipamentos
      $equipamentos = $this->db->from('ativo_interno ati');
      if ($inicio && $fim) {
        $equipamentos->where("ati.data_inclusao >= '$inicio'")
                  ->where("ati.data_inclusao <= '$fim'");
      }

      if ($data['id_obra']) {
        $equipamentos->where("ati.id_obra = {$data['id_obra']}");
      }
      $equipamentos = $equipamentos->get()->result();
   }

    //Equipamentos total
    $this->db->reset_query();
    $equipamentos_total = $this->db
          ->from('ativo_interno atei')
          ->select("SUM(atei.valor) as valor");

    if ($inicio && $fim) {
      $equipamentos_total->where("atei.data_inclusao >= '$inicio'")
                  ->where("atei.data_inclusao <= '$fim'");
    }

    if ($data['id_obra']) {
      $equipamentos_total->where("atei.id_obra = {$data['id_obra']}");
    }
    $equipamentos_total = $equipamentos_total->get()->row();

    if ($tipo && $tipo == 'arquivo') {
      return (object) [
        'lista' =>  $equipamentos,
        'total' => $this->formata_moeda($equipamentos_total->valor),
      ];
    }

    return (object) [
      'lista' =>  $equipamentos,
      'total' => $this->formata_moeda($equipamentos_total->valor, true),
    ];
  }

  public function custos_equipamentos_manutecoes($data, $tipo=null){
    $equipamentos_manutencao =  null;
    $equipamentos_manutencao_total = null;
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    if ($tipo && $tipo == 'arquivo') {
      //Equipamentos Manutenções
      $equipamentos_manutencao = $this->db->from('ativo_interno_manutencao atm')
                                        ->select('atm.*, atv.*, atm.valor as manutencao_valor, atv.valor as equipamento_valor')
                                        ->join('ativo_interno atv', 'atv.id_ativo_interno = atm.id_ativo_interno');
      if ($inicio && $fim) {
        $equipamentos_manutencao
            ->where("atm.data_retorno >= '$inicio'")
            ->where("atm.data_retorno <= '$fim'");
      }

      $equipamentos_manutencao = $equipamentos_manutencao
                                      ->group_by('atm.id_manutencao')
                                      ->get()->result();
   }

    //Equipamentos Manutenções total
    $this->db->reset_query();
    $equipamentos_manutencao_total = $this->db
          ->from('ativo_interno_manutencao atmc')
          ->select("SUM(atmc.valor) as valor");

    if ($inicio && $fim) {
      $equipamentos_manutencao_total
                  ->where("atmc.data_retorno >= '$inicio'")
                  ->where("atmc.data_retorno <= '$fim'");
    }

    $equipamentos_manutencao_total = $equipamentos_manutencao_total->get()->row();

    if ($tipo && $tipo == 'arquivo') {
      return (object) [
        'lista' =>  $equipamentos_manutencao,
        'total' => $this->formata_moeda($equipamentos_manutencao_total->valor),
      ];
    }

    return (object) [
      'lista' =>  $equipamentos_manutencao,
      'total' => $this->formata_moeda($equipamentos_manutencao_total->valor, true),
    ];
  }

  public function custos_veiculos_manutecoes($data, $tipo=null){
    $veiculos_manutencao =  null;
    $veiculos_manutencao_total = null;
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    if ($tipo && $tipo == 'arquivo') {
      //Veiculos Manutenções
      $veiculos_manutencao = $this->db->from('ativo_veiculo_manutencao atvm')
                                      ->select('atvm.*, atv.*, fn.id_fornecedor, fn.razao_social as fornecedor')
                                      ->join('ativo_veiculo atv', 'atv.id_ativo_veiculo = atvm.id_ativo_veiculo')
                                      ->join('fornecedor fn', 'fn.id_fornecedor = atvm.id_fornecedor');
      if ($inicio && $fim) {
        $veiculos_manutencao
            ->where("atvm.data_saida >= '$inicio'")
            ->where("atvm.data_saida <= '$fim'");
      }

      if ($data['id_obra']) {
        $veiculos_manutencao->where("atvm.id_obra = {$data['id_obra']}");
      }
      $veiculos_manutencao = $veiculos_manutencao
                              ->group_by('atvm.id_ativo_veiculo_manutencao')
                              ->get()->result();
   }

    //Veiculos Manutenções total
    $this->db->reset_query();
    $veiculos_manutencao_total = $this->db
          ->from('ativo_veiculo_manutencao atvmc')
          ->select("SUM(atvmc.veiculo_custo) as valor");

    if ($inicio && $fim) {
      $veiculos_manutencao_total
                  ->where("atvmc.data_saida >= '$inicio'")
                  ->where("atvmc.data_saida <= '$fim'");
    }

    $veiculos_manutencao_total = $veiculos_manutencao_total->get()->row();

    if ($tipo && $tipo == 'arquivo') {
      return (object) [
        'lista' =>  $veiculos_manutencao,
        'total' => $this->formata_moeda($veiculos_manutencao_total->valor),
      ];
    }

    return (object) [
      'lista' =>  $veiculos_manutencao,
      'total' => $this->formata_moeda($veiculos_manutencao_total->valor, true),
    ];
  }

  public function custos_veiculos_abastecimentos($data, $tipo=null){
    $inicio = $data['periodo']['inicio'];
    $fim = $data['periodo']['fim'];

    //Veiculos abastecimentos
    $veiculos_abastecimento = $this->db->from('ativo_veiculo_quilometragem km')
                                        ->select('km.*, atv.*')
                                        ->join('ativo_veiculo atv', 'atv.id_ativo_veiculo = km.id_ativo_veiculo');
    if ($inicio && $fim) {
      $veiculos_abastecimento
          ->where("km.data >= '$inicio'")
          ->where("km.data <= '$fim'");
    }

    if (isset($data['veiculo_placa'])) {
      $veiculos_abastecimento->like("veiculo_placa", $data['veiculo_placa']);
    }

    if (isset($data['id_interno_maquina'])) {
      $veiculos_abastecimento->like("id_interno_maquina", $data['id_interno_maquina']);
    }

    $veiculos_abastecimento = $veiculos_abastecimento->get()->result();
  
    $valor = 0;
    if (count($veiculos_abastecimento) > 0){
      foreach($veiculos_abastecimento as $ab => $abastecimento) {
        $litros = (float) $abastecimento->veiculo_litros;
        $custo = (float) $abastecimento->veiculo_custo;
        $veiculos_abastecimento[$ab]->veiculo_custo_total = ($litros * $custo);
        $valor += $veiculos_abastecimento[$ab]->veiculo_custo_total;
      }
    }
    return (object) [
      'lista' =>  $veiculos_abastecimento,
      'total' => $this->formata_moeda($valor, ($tipo != 'arquivo')),
    ];
  }

  public function centro_de_custo($data=null, $tipo=null){
    $data = $this->extract_data('centro_de_custo', $data);
    $equipamentos =  $this->custos_equipamentos($data, $tipo);
    $equipamentos_manutecoes = $this->custos_equipamentos_manutecoes($data, $tipo);
    $ferramentas =  $this->custos_ferramentas($data, $tipo);
    $veiculos_manutecoes = $this->custos_veiculos_manutecoes($data, $tipo);
    //$veiculos_abastecimentos = $this->custos_veiculos_abastecimentos($data, $tipo);

    if ($tipo && $tipo == 'arquivo') {
      return (object) [
        'ferramentas' =>  $ferramentas->lista,
        'ferramentas_total' => $ferramentas->total,
        'equipamentos' =>  $equipamentos->lista,
        'equipamentos_total' => $equipamentos->total,
        'equipamentos_manutecoes' => $equipamentos_manutecoes->lista, 
        'equipamentos_manutecoes_total' => $equipamentos_manutecoes->total, 
        // 'veiculos_abastecimentos' => $veiculos_abastecimentos->lista, 
        // 'veiculos_abastecimentos_total' => $veiculos_abastecimentos->total, 
        'veiculos_manutecoes' => $veiculos_manutecoes->lista, 
        'veiculos_manutecoes_total' => $veiculos_manutecoes->total, 
        'total' => $this->formata_moeda(array_sum([
          $ferramentas->total,
          $equipamentos->total
        ]))
      ];
    }

    $relatorio = [
        'ferramentas' =>  $ferramentas->total,
        'equipamentos' =>  $equipamentos->total,
        'equipamentos_manutecoes' => $equipamentos_manutecoes->total,
        //'veiculos_abastecimentos' => $veiculos_abastecimentos->total, 
        'veiculos_manutecoes' => $veiculos_manutecoes->total, 
        'total' => $this->formata_moeda(array_sum([
          $ferramentas->total,
          $equipamentos->total,
          $equipamentos_manutecoes->total,
          $veiculos_manutecoes->total,
          //$veiculos_abastecimentos->total
        ]), true)
    ];
    return (object) $relatorio;       
  }

  private function get_patrimonio_obra_items($obra = null, $show_valor_total = true){
    if ($obra) {
      $obra->equipamentos = $this->ativo_interno_model->get_lista($obra->id_obra);
      if ($show_valor_total) {
        $obra->equipamentos_total = 0;
        foreach($obra->equipamentos as $equipamento){
            $obra->equipamentos_total  +=  floatval($equipamento->valor);
        }
      }

      $obra->ferramentas = $this->ativo_externo_model->get_ativos($obra->id_obra);
      if ($show_valor_total) {
        $obra->ferramentas_total = 0;
        foreach($obra->ferramentas as $ferramenta){
          $obra->ferramentas_total  += floatval($ferramenta->valor);
        }
      }
      return $obra;
    }
    return null;
  }

  public function patrimonio_disponivel($data=null, $tipo=null){
    $obras = [];
    $show_valor_total = isset($data['valor_total']) && $data['valor_total'] === "true";
    $data = $this->extract_data('patrimonio_disponivel', $data);

    if ($tipo && $tipo == 'arquivo') {
      $relatorio = $this->db ->from('ativo_veiculo atv');
      if (isset($data['tipo_veiculo']) && $data['tipo_veiculo'] !== 'todos') {
        $relatorio->where("tipo_veiculo = {$data['tipo_veiculo']}");
      }
      $veiculos = $relatorio->select("atv.*")->where("situacao = '0'")->get()->result();

      $relatorio = $this->db ->from('ativo_veiculo atv');
      if (isset($data['tipo_veiculo']) && $data['tipo_veiculo'] !== 'todos') {
        $relatorio->where("tipo_veiculo = {$data['tipo_veiculo']}");
      }
      $veiculos_total = $relatorio->select("SUM(atv.valor_fipe) as valor")->where("situacao = '0'")->get()->row();
      
      if (isset($data['id_obra']) && $data['id_obra'] != null) {
        $obra = $this->obra_model->get_obra($data['id_obra']);
        $obras[] = $this->get_patrimonio_obra_items($obra, $show_valor_total);
      } else {
        $obras_models = $this->obra_model->get_obras();
        foreach($obras_models as $obra){
          $obras[] = $this->get_patrimonio_obra_items($obra, $show_valor_total);
        }
      }

      return (object) [
        'veiculos' => $veiculos,
        'veiculos_total' => $veiculos_total->valor,
        'obras' => $obras,
        'show_valor_total' => $show_valor_total
      ];
    }
  
    $ativo_interno = $this->db
      ->from('ativo_interno ati')
      ->select('COUNT(ati.id_ativo_interno) as equipamentos')
      ->where('ati.situacao = 0');

    if ($data['id_obra']){
      $ativo_interno->where("ati.id_obra = {$data['id_obra']}");
    }
    $ativos_internos = $ativo_interno->get()->row();

    $ativo_externo = $this->db
      ->from('ativo_externo ate')
      ->select('COUNT(ate.id_ativo_externo) as ferramentas')
      ->where('ate.situacao = 12');

    if ($data['id_obra']){
      $ativo_externo->where("ate.id_obra = {$data['id_obra']}");
    }
    $ativos_externos =  $ativo_externo->get()->row();

    $ativos_veiculos = null;
    if (!$data['id_obra']){
      $ativos_veiculos = $this->db
        ->from('ativo_veiculo atv')
        ->select('COUNT(atv.id_ativo_veiculo) as veiculos')
        ->where("atv.situacao = '0'")
        ->get()->row();
    }

    return (object) array_merge(
      (array) $ativos_internos, 
      (array) $ativos_externos,
      !$data['id_obra'] ? (array) $ativos_veiculos : [],
      [
        'total_de_items' => ($ativos_internos->equipamentos + $ativos_externos->ferramentas) + (!$data['id_obra'] ? $ativos_veiculos->veiculos : 0)
      ]
    );
  }


  private function filter_by_periodo($query, $column, $periodo_inicio = null, $periodo_fim = null) {
    if ($periodo_inicio) {
      $query->where("{$column} >= '$periodo_inicio'");
    }

    if ($periodo_fim) {
      $query->where("{$column} <= '$periodo_fim'");
    }
    return $query;
  }


  public function count_ativos_externos($periodo_inicio = null, $periodo_fim = null){
    $ativos = $this->filter_by_periodo($this->ativo_externo_model->ativos(),'data_inclusao',$periodo_inicio, $periodo_fim);
    return $ativos->where("data_descarte IS NULL")->get()->num_rows();
  }

  public function count_ativos_internos($periodo_inicio = null, $periodo_fim = null){
    $ativos = $this->filter_by_periodo($this->ativo_interno_model->ativos(), 'data_inclusao', $periodo_inicio, $periodo_fim);
    return $ativos->where("data_descarte IS NULL")->get()->num_rows();
  }

  public function count_ativos_veiculos($periodo_inicio = null, $periodo_fim = null){
    $veiculos = $this->filter_by_periodo($this->ativo_veiculo_model->ativos(), 'data', $periodo_inicio, $periodo_fim);
    return $veiculos->where("situacao = '0'")->get()->num_rows();   
  }

  public function count_colaboradores($periodo_inicio = null, $periodo_fim = null){
    $funcionarios = $this->filter_by_periodo($this->db->from('funcionario'), 'data_criacao', $periodo_inicio, $periodo_fim);
    return $funcionarios->where("situacao = '0'")->get()->num_rows();
  }

  public function crescimento_empresa(){
    $meses_porcentagens = $meses_total = $meses = [];
    $inicio =  date("1991-07-20 06:20:00");
    $ultimo_dia = date('t');
    $fim = date("Y-m-{$ultimo_dia} 23:59:59", strtotime("-13 months"));

    for($i=0; $i < 12; $i++){
      $inicio = date('Y-m-01 00:00:00', strtotime("-{$i} months"));
      $ultimo_dia = date('t', strtotime($inicio));
      $fim = date("Y-m-{$ultimo_dia} 23:59:59", strtotime("-{$i} months"));

      $mes = (int) date('Ym', strtotime($inicio));
      $mes_atual = 0;
      $mes_atual += (int) $this->count_ativos_externos($inicio, $fim);
      $mes_atual += (int) $this->count_ativos_internos($inicio, $fim);
      $mes_atual += (int) $this->count_ativos_veiculos($inicio, $fim);
      $mes_atual += (int) $this->count_colaboradores($inicio, $fim);
      $meses[$mes] = $mes_atual;

      $total_fim = date("Y-m-d 23:59:59", strtotime("$fim -1 days"));
      $total = 0; 
      $total += (int) $this->count_ativos_externos(null, $total_fim);
      $total += (int) $this->count_ativos_internos(null, $total_fim);
      $total += (int) $this->count_ativos_veiculos(null, $total_fim);
      $total += (int) $this->count_colaboradores(null, $total_fim);

      $index_mes_anterior = (int) date('Ym', strtotime("$inicio -1 days"));
      $mes_anterior = array_key_exists($index_mes_anterior, $meses) ? (int) $meses[$index_mes_anterior][1] : 0;

      $meses_total[$i] = (object) [
        "total" => $total,
        "mes" => (int) date('m', strtotime($inicio)),
        "mes_ano" => $mes,
        "mes_anterior" => $mes_anterior,
        "mes_atual" => $mes_atual
      ];
    }


    $i = 0;
    foreach(array_reverse($meses_total, true) as  $mes) {
      $crescimento = 0;
      if ($mes->total > 0) {
        $crescimento = (float) ($mes->mes_atual / $mes->total) * 100;
      }

      $meses_porcentagens[$i][0] = $mes->mes;
      $meses_porcentagens[$i][1] =  number_format($crescimento, 2);
      $i++;
    }

    return $meses_porcentagens;
  }

  public function limpar_uploads(){
    $delete_files = [];
    $path = __DIR__."/../../../../assets/uploads/";

    foreach ($this->uploads as $dir => $table) $delete_files = array_merge($delete_files, $this->anexo_model->getOrphans($dir, $table));

    foreach(glob("{$path}/relatorio/relatorio_*") as $file) {
      $filetime = strtotime(explode(".", substr(strrchr($file, "_"), 1))[0]);
      if ($filetime <= strtotime('-2 minutes')) $delete_files[] = $file;
    }

    foreach ($delete_files as $filename) if(file_exists($filename)) unlink($filename); 
    return $this->limpar_anexos_excluidos();
  }

  public function limpar_anexos_excluidos(){
      $anexos = $this->anexo_model->get_anexos();
      $anexos_excluir_id = [];
      $path = APPPATH."../assets/uploads/";

      foreach ($anexos as $anexo) {
        if (!file_exists($path.$anexo->anexo)) $anexos_excluir_id = array_merge($anexos_excluir_id, [$anexo->id_anexo]);
      }

      $this->db->where("id_anexo  IN ('".implode("','", $anexos_excluir_id)."')")->delete('anexo');
      return true;
  }

  public function informe_vencimentos($days = 0, $id_obra = null){
    $date = date('Y-m-d', strtotime("+{$days} days"));
    $now = date('Y-m-d H:i:s');
    $results = [];
    $id_modulo = "";

    foreach($this->relatorio_model->vencimentos as $modulo => $vencimentos){
      foreach($vencimentos as $vencimento){
        $relatorio = $this->db->select("{$vencimento['tabela']}.*");
        
        if (isset($vencimento['coluna_formato']) && $vencimento['coluna_formato'] == 'date') $now = date("Y-m-d", strtotime($now));

        if ($id_obra && $vencimento['tabela'] == 'ativo_interno') {
          $relatorio->where("{$vencimento['tabela']}.id_obra = '{$id_obra}'");
        }

        if ($modulo == 'ativo_veiculo') {
          $id_modulo = "id_{$modulo}";
          $relatorio->join($modulo, "$modulo.$id_modulo = {$vencimento['tabela']}.{$id_modulo}")
                    ->select("$modulo.*");

         if ($vencimento['nome'] == 'manutencao') {
            $relatorio
              ->select('frn.razao_social as fornecedor')
              ->select('ativo_configuracao.id_ativo_configuracao, ativo_configuracao.titulo as servico')
              ->join("fornecedor frn", "frn.id_fornecedor={$vencimento['tabela']}.id_fornecedor", 'left')
              ->join('ativo_configuracao', "ativo_configuracao.id_ativo_configuracao={$vencimento['tabela']}.id_ativo_configuracao", 'left');

            if (in_array($vencimento['coluna'], ['veiculo_km_proxima_revisao', 'veiculo_hora_proxima_revisao']) && isset($vencimento['id_configuracao_revisao'])) {
              switch ($vencimento['coluna']) {
                case "veiculo_hora_proxima_revisao":
                  $horas_credito_select = "(select sum(veiculo_hora_proxima_revisao) from ativo_veiculo_manutencao where id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo AND (veiculo_hora_proxima_revisao IS NOT NULL AND veiculo_hora_proxima_revisao > 0))";
                  $horas_debito_select = "(select sum(operacao_tempo) from ativo_veiculo_operacao where id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo)";
                  
                  $relatorio
                    ->select("$horas_debito_select as horas_debito, ($horas_credito_select) as horas_credito, ($horas_credito_select - $horas_debito_select) as horas_saldo")
                    ->order_by("{$vencimento['tabela']}.id_ativo_veiculo_manutencao", 'desc')
                    ->group_by("{$vencimento['tabela']}.id_ativo_veiculo_manutencao")
                    ->where("($horas_credito_select - $horas_debito_select) <= {$vencimento['alerta']} AND ({$vencimento['tabela']}.veiculo_hora_proxima_revisao IS NOT NULL AND {$vencimento['tabela']}.veiculo_hora_proxima_revisao > 0)")
                    ->or_where("({$vencimento['tabela']}.{$vencimento['coluna_vencimento']} IS NOT NULL AND {$vencimento['tabela']}.{$vencimento['coluna_vencimento']} BETWEEN '{$now}' AND '{$date}')");
                break;
                
                case "veiculo_km_proxima_revisao":
                  $kms_credito_select = "(select veiculo_km_proxima_revisao from ativo_veiculo_manutencao where (id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo AND (veiculo_km_proxima_revisao IS NOT NULL AND veiculo_km_proxima_revisao > 0)) order by id_ativo_veiculo_manutencao desc limit 1)";
                  $kms_debito_select = "(select veiculo_km from ativo_veiculo_quilometragem where id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo order by id_ativo_veiculo_quilometragem desc limit 1)";
                  
                  $relatorio
                    ->select("$kms_debito_select as km_debito, $kms_credito_select as km_credito, ($kms_credito_select - $kms_debito_select) as km_saldo")
                    ->order_by("{$vencimento['tabela']}.id_ativo_veiculo_manutencao", 'desc')
                    ->group_by("{$vencimento['tabela']}.id_ativo_veiculo_manutencao")
                    ->where("($kms_credito_select - $kms_debito_select) <= {$vencimento['alerta']} AND ({$vencimento['tabela']}.id_ativo_configuracao = {$vencimento['id_configuracao_revisao']} AND ({$vencimento['tabela']}.veiculo_km_proxima_revisao IS NOT NULL AND {$vencimento['tabela']}.veiculo_km_proxima_revisao > 0))")
                    ->or_where("{$vencimento['tabela']}.{$vencimento['coluna_vencimento']} IS NOT NULL AND {$vencimento['tabela']}.{$vencimento['coluna_vencimento']} BETWEEN '{$now}' AND '{$date}'");
                break;
              }
            }
         }
          
          $relatorio->group_by("{$vencimento['tabela']}.$id_modulo");
        }

        if ($modulo == 'ativo_externo' && $vencimento['tabela'] == "ativo_externo_certificado_de_calibracao") {
          $id_modulo = "id_{$modulo}";
          $relatorio->join($modulo, "$modulo.$id_modulo = {$vencimento['tabela']}.{$id_modulo}")
                    ->select("$modulo.nome as ativo_nome, $modulo.codigo as ativo_codigo, $modulo.data_inclusao as ativo_data_inclusao")
                    ->select("({$vencimento['tabela']}.data_vencimento > '{$now}') as vigencia");
        }

        $deny_by_coluna = ['veiculo_km_proxima_revisao', 'veiculo_hora_proxima_revisao'];
        if (!in_array($vencimento['coluna'], $deny_by_coluna)) {
          if ($days > 0) {
            $relatorio->where("{$vencimento['coluna']} BETWEEN '{$now}' AND '{$date}'");
          } else {
            $relatorio->where("{$vencimento['coluna']} = '{$date}'");
          }
        }

        $relatorio_data = $relatorio->get($vencimento['tabela'])->result();
        if (count($relatorio_data) >= 1) {
          if (!isset($results[$vencimento['nome']])) {
            $results[$vencimento['nome']] = (object) [
              'data' => [],
              'modulo' => $modulo,
              'tipo' => $vencimento['nome']
            ];
          }

          $results[$vencimento['nome']]->data = array_merge($results[$vencimento['nome']]->data, $relatorio_data);
        }
      }
    }
      
    return (object) $results;
  }

  public function enviar_informe_vencimentos($dias_restantes = 30, $showhtml = false){
    $relatorio_data = $this->informe_vencimentos($dias_restantes);

    if (count((array) $relatorio_data) > 0) {
      $data = [
          'data_hora' => date('d/m/Y H:i:s', strtotime('now')),
          'relatorio' => $relatorio_data,
          'dias' => $dias_restantes,
          'styles' => $this->notificacoes_model->getEmailStyles(), 
      ];
      $html = $this->load->view("relatorio/relatorio_informe_vencimentos", $data, true);

      if ($showhtml) {
        echo $html;
        return true;
      }

      $send_address = $this->config->item("notifications_address") != null ? $this->config->item("notifications_address") : [];
      array_map(function($user) use (&$send_address) {
        $send_address = array_merge($send_address, ["$user->nome" => $user->email]);
      }, $this->db->where("nivel = '1' and permit_notification_email = '1' and email_confirmado_em IS NOT NULL")->get('usuario')->result());

      return count($send_address) > 0 ? $this->notificacoes_model->enviar_email("Informe de Vencimentos", $html, $send_address) : false;
    }
    return true;
  }


  public function informe_retiradas_pendentes($devolucao_prevista = "now", $id_obra = null){
    $now = date("Y-m-d H:i:s", strtotime($devolucao_prevista));
    $retiradas = $this->db;

    if ($id_obra) {
      $retiradas->where("atv.id_obra = {$id_obra}");
    }

    return $retiradas
            ->where("status NOT IN (1,2,9)")
            ->where("devolucao_prevista <= '{$now}'")
            ->join("funcionario fn", "fn.id_funcionario = atv.id_funcionario")
            ->select("fn.nome as funcionario, fn.data_nascimento as funcionario_nascimento, fn.rg as funcionario_rg, fn.cpf as funcionario_cpf")
            ->join("obra ob", "ob.id_obra = atv.id_obra")
            ->select("ob.codigo_obra as obra, ob.endereco as obra_endereco")
            ->select("atv.*")
            ->get('ativo_externo_retirada atv')->result();
  }

  public function enviar_informe_retiradas_pendentes($data_hora_vencimento = "now", $showhtml = false){
    $data_hora_vencimento = date("Y-m-d 23:59:59", strtotime($data_hora_vencimento));
    $relatorio_data = $this->informe_retiradas_pendentes($data_hora_vencimento);
    if (count($relatorio_data) > 0) {
      $data = [
          'data_hora' => date("Y-m-d H:i:s", strtotime("now")),
          'relatorio' => $relatorio_data,
          'vencimento' => $data_hora_vencimento,
          'styles' => $this->notificacoes_model->getEmailStyles(),
      ];

      $html = $this->load->view("relatorio/relatorio_informe_retiradas_pendentes", $data, true);
      if ($showhtml) {
        echo $html;
        return true;
      }

      $date = date("d/m/Y", strtotime($data_hora_vencimento));
      return $this->notificacoes_model->enviar_email("Retiradas Pêndentes de Devolução | $date", $html, $this->config->item("notifications_address"));
    }
    return true;
  }
}