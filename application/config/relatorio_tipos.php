<?php

function getReleatoriosTipos(array $obras = [])
{
	$get_obra = function ($obra) {
		return $obra ? str_replace('-', '', $obra->codigo_obra) : "-";
	};

	return [

		'funcionario' => [
			'titulo' => 'Relatório de Funcionários',
			'filtros' => ['id_empresa', 'id_obra', 'periodo'],
			'grafico' => [
				'column' => ['Ativos', 'Inativos', 'Total'],
				'legend_marker' => ["circle",  "circle", 'triangle'],
				'tipo' => 'doughnut'
			],
			'tipo' => ['grafico', 'arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'empresa' => [
			'titulo' => 'Relatório de Empresas',
			'filtros' => ['periodo'],
			'grafico' => [
				'column' => ['Ativos', 'Inativos', 'Total'],
				'legend_marker' => ["circle",  "circle", 'triangle'],
				'tipo' => 'column'
			],
			'tipo' => ['grafico', 'arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'obra' => [
			'titulo' => 'Relatório de Obras',
			'filtros' => ['id_empresa', 'periodo'],
			'grafico' => [
				'column' => ['Ativos', 'Inativos', 'Total'],
				'legend_marker' => ["circle",  "circle", 'triangle'],
				'tipo' => 'pie'
			],
			'tipo' => ['grafico', 'arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'ferramentas_disponiveis_na_obra' => [
			'titulo' => 'Ferramentas Diponíveis na Obra (Em uso ou não)',
			'filtros' => ['id_obra', 'valor_total'], //todas as situacoes
			'grafico' => [
				'column' => ['Em Estoque', 'Liberado', 'Em Transito', 'Em Operação', 'Fora de Operação', 'Com Defeito', 'Total'],
				'color' => ['Green', 'Blue', 'Yellow', '#909090', 'Black', 'Red', '#ccc'],
				'legend_marker' => ["circle", "circle", "circle", "circle", "circle", "circle", 'triangle'],
				'tipo' => 'doughnut'
			],
			'tipo' => ['grafico', 'arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'ferramentas_em_estoque' => [
			'titulo' => 'Ferramentas em Estoque',
			'filtros' => ['id_obra'], //situacao = 12
			'grafico' => [
				'column' => array_merge(array_map($get_obra, $obras), ['Total']),
				'tipo' => 'doughnut'
			],
			'tipo' => ['grafico', 'arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'equipamentos_em_estoque' => [
			'titulo' => 'Equipamentos em Estoque',
			'filtros' => ['id_obra'],
			'grafico' => [
				'column' => array_merge(array_map($get_obra, $obras), ['Total']),
				'tipo' => 'doughnut'
			],
			'tipo' => ['grafico', 'arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'veiculos_disponiveis' => [
			'titulo' => 'Veículos Diponíveis',
			'filtros' => ['tipo_veiculo', 'periodo'],
			'grafico' => [
				'column' => ['Carro', 'Moto', 'Caminhão', 'Máquina', 'Total'],
				'tipo' => 'doughnut'
			],
			'tipo' => ['grafico', 'arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'veiculos_depreciacao' => [
			'titulo' => 'Veículos Depreciação',
			'filtros' => ['tipo_veiculo', 'veiculo_placa', 'id_interno_maquina', 'periodo'],
			'tipo' => 'arquivo',
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'veiculos_quilometragem' => [
			'titulo' => 'Veículos Quilometragem',
			'filtros' => ['tipo_veiculo', 'veiculo_placa', 'id_interno_maquina', 'periodo'],
			'tipo' => 'arquivo',
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'veiculos_operacao' => [
			'titulo' => 'Veículos Operação',
			'filtros' => ['tipo_veiculo', 'veiculo_placa', 'id_interno_maquina', 'periodo'],
			'tipo' => 'arquivo',
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'veiculos_abastecimentos' => [
			'titulo' => 'Veículos Abastecimento',
			'filtros' => ['tipo_veiculo',  'veiculo_placa', 'id_interno_maquina', 'periodo'],
			'tipo' => 'arquivo',
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			]
		],

		'centro_de_custo' => [
			'titulo' => 'Centro de Custo',
			'filtros' => ['id_obra', 'periodo'],
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
			'tipo' => ['grafico', 'arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				// "XLSX (Excel)" => "xlsx",
			],
			'format' => 'money'
		],

		'patrimonio_disponivel' => [
			'titulo' => 'Patromônio Disponível',
			'filtros' => ['id_obra', 'valor_total'],
			'grafico' => [
				'column' => ['Ferramentas', 'Equipamentos', 'Veiculos', 'Total de Items'],
				'tipo' => 'pie'
			],
			'tipo' => ['grafico', 'arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
				"XLSX (Excel)" => "xlsx",
			]
		],

		'logs' => [
			'titulo' => 'Relatório de Logs',
			'filtros' => ['id_modulo', 'id_usuario', 'acao', 'periodo'],
			'grafico' => [
				'column' => ['Módulo', 'Usuário', 'Ação'],
				'tipo' => 'column'
			],
			'tipo' => ['arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
			]
		],		

		'insumos' => [
			'titulo' => 'Insumos em Obra',
			'filtros' => ['id_obra', 'id_funcionario', 'insumo_configuracao', 'periodo'],
			'grafico' => [
				'column' => ['Obra', 'Tipo de Insumo', 'Quantidade em Estoque', 'Total de Items'],
				'tipo' => 'pie'
			],
			'tipo' => ['grafico', 'arquivo'],
			'arquivo_saida' => [
				"PDF" => "pdf",
				"XLS (Excel)" => "xls",
			]
		],
	];
}
