<?php 
class Relatorio_model_base extends MY_Model {

  public $relatorio, $periodos, $situacao_lista, $relatorios, $tipos_veiculos, $vencimentos;
  
  public function __construct() {
      parent::__construct();
      $this->relatorio = $this->db;
      $this->load->model('obra/obra_model');
      $this->load->model('funcionario/funcionario_model');
      $this->load->model('ativo_interno/ativo_interno_model');
      $this->load->model('ativo_externo/ativo_externo_model'); 
      $this->load->model('ativo_veiculo/ativo_veiculo_model');
      $this->load->model('ferramental_requisicao/ferramental_requisicao_model');
      $this->load->model('configuracao/configuracao_model');
      $configuracao = $this->configuracao_model->get_configuracao(1); //default config

      $this->tipos_veiculos =  [
        'todos' => 'Todos',
        'carro' => 'Carro', 
        'moto' => 'Moto', 
        'caminhao' => 'Caminhão',
        'maquina' => 'Máquina'
      ];

      $this->periodos = [
        'hoje' => [
          'titulo' => 'Hoje',
          'periodo_inicio' => date('Y-m-d 00:00:00', strtotime('now')),
          'periodo_fim' => date('Y-m-d 23:59:59', strtotime('now')),
        ],
        'ontem' => [
          'titulo' => 'Ontem',
          'periodo_inicio' => date('Y-m-d 00:00:00', strtotime('-1 day')),
          'periodo_fim' => date('Y-m-d 23:59:59', strtotime('-1 day')),
        ],
        'ultimos_7_dias' => [
          'titulo' => 'Últimos 7 dias',
          'periodo_inicio' => date('Y-m-d 00:00:00', strtotime('-7 day')),
          'periodo_fim' => date('Y-m-d 23:59:59', strtotime('now')),
        ],
        'ultimos_30_dias' => [
          'titulo' => 'Últimos 30 dias',
          'periodo_inicio' => date('Y-m-d 00:00:00', strtotime('-30 day')),
          'periodo_fim' => date('Y-m-d 23:59:59', strtotime('now')),
        ],
        'ultimos_60_dias' => [
          'titulo' => 'Últimos 60 dias',
          'periodo_inicio' => date('Y-m-d 00:00:00', strtotime('-60 day')),
          'periodo_fim' => date('Y-m-d 23:59:59', strtotime('now')),
        ],
        'ultimos_90_dias' => [
          'titulo' => 'Últimos 90 dias',
          'periodo_inicio' => date('Y-m-d 00:00:00', strtotime('-90 day')),
          'periodo_fim' => date('Y-m-d 23:59:59', strtotime('now')),
        ],
        'ultimos_6_messes' => [
          'titulo' => 'Últimos 6 messes',
          'periodo_inicio' => date('Y-m-d 00:00:00', strtotime('-180 day')),
          'periodo_fim' => date('Y-m-d 23:59:59', strtotime('now')),
        ],
        'ultimo_ano' => [
          'titulo' => 'Último Ano',
          'periodo_inicio' => date('Y-m-d 00:00:00', strtotime('-365 day')),
          'periodo_fim' => date('Y-m-d 23:59:59', strtotime('now')),
        ],
        'ultimos_2_anos' => [
          'titulo' => 'Últimos 2 Anos',
          'periodo_inicio' => date('Y-m-d 00:00:00', strtotime('-365 day')),
          'periodo_fim' => date('Y-m-d 23:59:59', strtotime('now')),
        ],
        'todo_periodo' => [
          'titulo' => 'Todo Período',
          'periodo_inicio' => null,
          'periodo_fim' => null
        ],
        'outro' => [
          'titulo' => 'Outro',
          'periodo_inicio' => null,
          'periodo_fim' => null,
        ],
      ];
      
      $this->status_lista = $this->status_lista();
      
      $this->relatorios = [
        'funcionario' => [
          'titulo' => 'Relatório de Funcionários',
          'filtros'=> ['id_empresa', 'id_obra', 'periodo'],
          'grafico' => [
            'column' => ['Ativos', 'Inativos', 'Total'],
            'legend_marker' => [ "circle",  "circle", 'triangle'],
            'tipo' => 'doughnut'
          ],
          'tipo' => ['grafico','arquivo'],
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ]
        ],
        'empresa' => [
          'titulo' => 'Relatório de Empresas',
          'filtros'=> ['periodo'],
          'grafico' => [
            'column' => ['Ativos', 'Inativos', 'Total'],
            'legend_marker' => [ "circle",  "circle", 'triangle'],
            'tipo' => 'column'
          ],
          'tipo' => ['grafico','arquivo'],
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ]
        ],
        'obra' => [
          'titulo' => 'Relatório de Obras',
          'filtros'=> ['id_empresa', 'periodo'],
          'grafico' => [
            'column' => ['Ativos', 'Inativos', 'Total'],
            'legend_marker' => [ "circle",  "circle", 'triangle'],
            'tipo' => 'pie'
          ],
          'tipo' => ['grafico','arquivo'],
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ]
        ],
        'ferramentas_disponiveis_na_obra' => [
          'titulo' => 'Ferramentas Diponíveis na Obra (Em uso ou não)',
          'filtros'=> ['id_obra', 'valor_total'], //todas as situacoes
          'grafico' => [
            'column' => ['Em Estoque', 'Liberado' ,'Em Transito', 'Em Operação', 'Fora de Operação', 'Com Defeito', 'Total'],
            'color' => ['Green', 'Blue', 'Yellow', '#909090', 'Black', 'Red', '#ccc'],
            'legend_marker' => [ "circle", "circle", "circle", "circle", "circle", "circle", 'triangle'],
            'tipo' => 'doughnut'
          ],
          'tipo' => ['grafico','arquivo'],
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ]
        ],
        'ferramentas_em_estoque' => [
          'titulo' => 'Ferramentas em Estoque',
          'filtros'=> ['id_obra'], //situacao = 12
          'grafico' => [
            'column' => array_merge(array_map(function($obra) {return str_replace('-', '', $obra->codigo_obra); }, $this->obra_model->get_obras()), ['Total']),
            'tipo' => 'doughnut'
          ],
          'tipo' => ['grafico','arquivo'],
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ]
        ],
        'equipamentos_em_estoque' => [
          'titulo' => 'Equipamentos em Estoque',
          'filtros'=> ['id_obra'], 
          'grafico' => [
            'column' => array_merge(array_map(function($obra) {return str_replace('-', '', $obra->codigo_obra); }, $this->obra_model->get_obras()), ['Total']),
            'tipo' => 'doughnut'
          ],
          'tipo' => ['grafico','arquivo'],
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ]
        ],
        'veiculos_disponiveis' => [
          'titulo' => 'Veículos Diponíveis',
          'filtros'=> ['tipo_veiculo', 'periodo'],
          'grafico' => [
            'column' => ['Carro', 'Moto', 'Caminhão', 'Máquina', 'Total'],
            'tipo' => 'doughnut'
          ],
          'tipo' => ['grafico','arquivo'],
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ]
        ],
        'veiculos_depreciacao' => [
          'titulo' => 'Veículos Depreciação',
          'filtros'=> ['tipo_veiculo', 'veiculo_placa', 'id_interno_maquina', 'periodo'],
          'tipo' => 'arquivo',
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ]
        ],
        'veiculos_quilometragem' => [
          'titulo' => 'Veículos Quilometragem',
          'filtros'=> ['tipo_veiculo',   'veiculo_placa', 'id_interno_maquina', 'periodo'],
          'tipo' => 'arquivo',
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ]
        ],
        'veiculos_abastecimentos' => [
          'titulo' => 'Veículos Abastecimento',
          'filtros'=> ['tipo_veiculo',  'veiculo_placa', 'id_interno_maquina', 'periodo'],
          'tipo' => 'arquivo',
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ]
        ],
        'centro_de_custo' => [
          'titulo' => 'Centro de Custo',
          'filtros'=> ['id_obra','periodo'],
          'grafico' => [
            'column' => [
                'Ferramentas', 
                'Ferramentas Manuteções',
                'Equipamentos', 
                'Equipamentos Manuteções',
                'Veiculos Abastecimentos', 
                'Veiculos Manutenções', 
                'Total'
              ],
            'tipo' => 'column',
          ],
          'tipo' => ['grafico','arquivo'],
          'arquivo_saida' => [
            "PDF" => "pdf",
            // "XLS (Excel)" => "xls",
            // "XLSX (Excel)" => "xlsx",
          ],
          'format' => 'money'
        ],
        'patrimonio_disponivel' => [
          'titulo' => 'Patromônio Disponível',
          'filtros'=> ['id_obra', 'valor_total'],
          'grafico' => [
            'column' => ['Ferramentas', 'Equipamentos', 'Veiculos', 'Total de Items'],
            'tipo' => 'pie'
          ],
          'tipo' => ['grafico','arquivo'],
          'arquivo_saida' => [
            "PDF" => "pdf",
            "XLS (Excel)" => "xls",
            "XLSX (Excel)" => "xlsx",
          ]
        ],
      ];

      $this->vencimentos  = [
        "ativo_veiculo" => [
          [
            "nome" => "manutencao",
            "tabela" => "ativo_veiculo_manutencao",
            "group_by" => "id_ativo_veiculo_manutencao",
            "coluna" => "data_vencimento",
          ],
          [
            "nome" => "manutencao",
            "tabela" => "ativo_veiculo_manutencao",
            "group_by" => "id_ativo_veiculo_manutencao",
            "coluna" => "veiculo_km_proxima_revisao",
            "coluna_vencimento" => "data_vencimento",
            "alerta" => $configuracao->km_alerta,
            "id_configuracao_revisao" => "14"
          ],
          [
            "nome" => "manutencao",
            "tabela" => "ativo_veiculo_manutencao",
            "group_by" => "id_ativo_veiculo_manutencao",
            "coluna" => "veiculo_horimetro_proxima_revisao",
            "coluna_vencimento" => "data_vencimento",
            "alerta" => $configuracao->operacao_alerta,
            "id_configuracao_revisao" => "14"
          ],
          [
            "nome" => "ipva",
            "tabela" => "ativo_veiculo_ipva",
            "group_by" => "id_ativo_veiculo_ipva",
            "coluna" => "ipva_data_vencimento"
          ],
          [
            "nome" => "seguro",
            "tabela" => "ativo_veiculo_seguro",
            "group_by" => "id_ativo_veiculo_seguro",
            "coluna" => "carencia_fim",
          ],
        ],
        "ativo_externo" => [
          [
            "nome" => "calibracao",
            "tabela" => "ativo_externo_certificado_de_calibracao",
            "group_by" => "id_certificado",
            "coluna" => "data_vencimento",
            "coluna_formato" => "date"
          ],
        ]
      ];
  }

  public function status_lista() {
    $lista = $this->session->status_lista;
    if (!$lista) {
      $lista = $this->ferramental_requisicao_model->get_requisicao_status();
      $this->session->status_lista = json_encode($lista);
    }

    return array_map(function($item) {
      return (object) [
        'texto' => $item->texto,
        'class' => $item->classe,
        'slug' => $item->slug,
        'id_status' => $item->id_requisicao_status
      ];
    }, is_string($lista) ? json_decode($lista) : $lista);
  }
}