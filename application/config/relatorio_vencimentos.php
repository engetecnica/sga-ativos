<?php
function getVencimentos(object $configuracao)
{
	return [
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
