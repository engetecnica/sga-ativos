<?php
return [
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
