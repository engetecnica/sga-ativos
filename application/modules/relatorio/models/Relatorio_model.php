<?php
class Relatorio_model  extends MY_Model
{
	public $relatorio, $periodos, $situacao_lista, $relatorios, $tipos_veiculos, $vencimentos, $uploads;

	public function __construct()
	{
		parent::__construct();

		$this->relatorio = $this->db;
		$this->load->model('obra/obra_model');
		$this->load->model('funcionario/funcionario_model');
		$this->load->model('ativo_interno/ativo_interno_model');
		$this->load->model('ativo_externo/ativo_externo_model');
		$this->load->model('ativo_veiculo/ativo_veiculo_model');
		$this->load->model('ferramental_requisicao/ferramental_requisicao_model');
		$this->load->model('configuracao/configuracao_model');
		$this->load->model("anexo/anexo_model");
		$this->load->model("notificacoes_model");

		try {
			$this->status_lista = $this->status_lista();
			$this->tipos_veiculos = $this->ativo_veiculo_model->tipos_pt;

			//Require config
			require(APPPATH . "/config/relatorio_tipos.php");
			require(APPPATH . "/config/relatorio_vencimentos.php");

			$this->relatorios = getReleatoriosTipos($this->obra_model->get_obras());
			$this->vencimentos = getVencimentos($this->configuracao_model->get_configuracao(1));
			$this->periodos = require(APPPATH . "/config/relatorio_periodos.php");
			$this->uploads =  require(APPPATH . "/config/relatorio_uploads.php");
		} catch (\Exception $e) {
			$this->status_lista = [];
			$this->tipos_veiculos = [];
			$this->periodos  = [];
			$this->relatorios = [];
			$this->vencimentos = [];
			$this->uploads = [];
		}
	}

	public function status_lista($type = null)
	{
		$lista = $this->session->status_lista;
		if (!$lista) {
			$lista = $this->ferramental_requisicao_model->get_requisicao_status();
			$this->session->status_lista = json_encode($lista);
		}

		return array_map(function ($item) {
			return (object) [
				'texto' => $item->texto,
				'class' => $item->classe,
				'slug' => $item->slug,
				'id_status' => $item->id_requisicao_status
			];
		}, is_string($lista) ? json_decode($lista) : $lista);
	}

	private function extract_data($tipo, $data)
	{
		$extracted_data = [];
		foreach ($this->relatorios[$tipo]['filtros'] as $filtro) {
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

	public function funcionario($data = null, $tipo = null)
	{
		$data = $this->extract_data('funcionario', $data);
		$inicio = $data['periodo']['inicio'];
		$fim = $data['periodo']['fim'];

		$relatorio = null;
		if ($tipo && $tipo == 'arquivo') {
			$relatorio = $this->db->from('funcionario fnc')->select('fnc.*')->order_by('nome', 'ASC');
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
			->join('empresa emp', 'fnc.id_empresa = emp.id_empresa', 'left');

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

	public function empresa($data = null, $tipo = null)
	{
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

	public function obra($data = null, $tipo = null)
	{
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

			// if ($data['id_empresa']) {
			//   $select .= " and id_empresa = ob.id_empresa";
			//   $select2 .= " and id_empresa = ob.id_empresa";
			// }

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

		// if ($data['id_empresa']) {
		//   $relatorio->where("ob.id_empresa = {$data['id_empresa']}");
		// }

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

	public function ferramentas_disponiveis_na_obra($data = null, $tipo = null)
	{
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
					foreach ($grupos as $grupo) {
						$grupo->total_grupo = 0;
						foreach ($grupo->ativos as $ativo) {
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
			foreach ($obras as $obra) {
				$grupos = $this->ativo_externo_model->get_grupos($obra->id_obra);

				if ($obras_data['show_valor_total']) {
					$obra->total_obra = 0;
					foreach ($grupos as $grupo) {
						$grupo->total_grupo = 0;
						foreach ($grupo->ativos as $ativo) {
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

	public function ferramentas_em_estoque($data = null, $tipo = null)
	{
		$data = $this->extract_data('ferramentas_em_estoque', $data);
		$relatorio = null;

		if ($tipo && $tipo == 'arquivo') {
			if ($data['id_obra']) {
				$obra = $this->obra_model->get_obra($data['id_obra']);
				$obra->grupos = $this->ativo_externo_model->get_grupos($obra->id_obra);
				return [$obra];
			}

			$obras = $this->obra_model->get_obras();
			foreach ($obras as $obra) {
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
				$relatorio[str_replace([' ', '-'], ['_', ''], strtolower($obra->nome))] = (int) $obra->total;
				$relatorio['total'] += (int) $obra->total;
			}
			return (object) $relatorio;
		}
	}

	public function equipamentos_em_estoque($data = null, $tipo = null)
	{
		$data = $this->extract_data('equipamentos_em_estoque', $data);
		$relatorio = null;

		if ($tipo && $tipo == 'arquivo') {
			if ($data['id_obra']) {
				$obra = $this->obra_model->get_obra($data['id_obra']);
				$obra->equipamentos = $this->ativo_interno_model->get_lista($obra->id_obra, 0);
				return [$obra];
			}

			$obras = $this->obra_model->get_obras();
			foreach ($obras as $obra) {
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
				$relatorio[str_replace([' ', '-'], ['_', ''], strtolower($obra->nome))] = (int) $obra->total;
				$relatorio['total'] += (int) $obra->total;
			}

			return (object) $relatorio;
		}
	}

	public function veiculos_disponiveis($data = null, $tipo = null)
	{
		$data = $this->extract_data('veiculos_disponiveis', $data);

		if ($tipo && $tipo == 'arquivo') {
			$relatorio = $this->db->from('ativo_veiculo atv');
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

	public function veiculos_depreciacao($data = null)
	{
		$data = $this->extract_data('veiculos_depreciacao', $data);
		$relatorio = $this->db
			->select('vdp.*')
			->from('ativo_veiculo_depreciacao vdp')
			->join('ativo_veiculo atv', 'vdp.id_ativo_veiculo = atv.id_ativo_veiculo')
			->select(
				'atv.id_ativo_veiculo,  atv.valor_fipe as valor_aquisicao, 
          atv.modelo, atv.marca,, atv.veiculo_placa, atv.id_interno_maquina, atv.tipo_veiculo'
			)
			->group_by('id_ativo_veiculo_depreciacao')
			->order_by("vdp.id_ativo_veiculo", "desc")
			->order_by("fipe_ano_referencia", "desc")
			->order_by("fipe_mes_referencia", "desc");


		$inicio = $data['periodo']['inicio'];
		$fim = $data['periodo']['fim'];
		if ($inicio && $fim) {
			$relatorio->where("vdp.veiculo_data >= '$inicio'")
				->where("vdp.veiculo_data <= '$fim'");
		}

		if (
			(isset($data['tipo_veiculo']) && !empty($data['tipo_veiculo'])) &&
			!in_array($data['tipo_veiculo'], ['all', 'todos'])
		) {
			$relatorio->where("atv.tipo_veiculo = '{$data['tipo_veiculo']}'");
		}

		if (isset($data['veiculo_placa']) && !empty($data['veiculo_placa'])) {
			$relatorio->where("atv.veiculo_placa = '{$data['veiculo_placa']}'");
		}

		if (isset($data['id_interno_maquina']) && !empty($data['id_interno_maquina'])) {
			$relatorio->where("atv.id_interno_maquina = '{$data['id_interno_maquina']}'");
		}

		$lista = (object) [];
		$lista->data = $relatorio->get()->result();

		if ($lista->data) return $this->ativo_veiculo_model->format_depreciacao_lista($lista);

		return $lista;
	}


	public function veiculos_quilometragem($data = null)
	{
		$data = $this->extract_data('veiculos_quilometragem', $data);
		$credito_select = "(select veiculo_km_proxima_revisao from ativo_veiculo_manutencao where (id_ativo_veiculo = atv.id_ativo_veiculo AND (veiculo_km_proxima_revisao IS NOT NULL AND veiculo_km_proxima_revisao > 0)) order by id_ativo_veiculo_manutencao desc limit 1)";

		$relatorio = $this->db
			->from('ativo_veiculo_quilometragem km')
			->join('ativo_veiculo atv', 'km.id_ativo_veiculo = atv.id_ativo_veiculo', 'right')
			->select('km.veiculo_km as km_atual, atv.veiculo_km as km_inicial, (km.veiculo_km  - atv.veiculo_km) as km_rodado')
			->select("atv.id_ativo_veiculo, atv.veiculo_placa, atv.veiculo, atv.veiculo_placa, atv.id_interno_maquina, atv.tipo_veiculo, atv.situacao, atv.data, atv.id_marca, atv.id_modelo")
			->select("(({$credito_select} - km.veiculo_km) + atv.veiculo_km)  as km_ultima_revisao, {$credito_select} as km_proxima_revisao");

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
		if (count($veiculos) > 0) {
			foreach ($veiculos as $k => $veiculo) {
				if ($veiculo->tipo_veiculo == 'maquina') $veiculos[$k] = $this->ativo_veiculo_model->set_outros_dados_veiculo($veiculo);
			}
		}
		return $veiculos;
	}

	public function veiculos_operacao($data = null)
	{
		$data = $this->extract_data('veiculos_operacao', $data);
		$credito_select = "(select veiculo_horimetro_proxima_revisao from ativo_veiculo_manutencao where (id_ativo_veiculo = atv.id_ativo_veiculo AND (veiculo_horimetro_proxima_revisao IS NOT NULL AND veiculo_horimetro_proxima_revisao > 0)) order by id_ativo_veiculo_manutencao desc limit 1)";

		$relatorio = $this->db
			->from('ativo_veiculo_operacao horimetro')
			->join('ativo_veiculo atv', 'horimetro.id_ativo_veiculo = atv.id_ativo_veiculo', 'right')
			->select('horimetro.veiculo_horimetro as horimetro_atual, atv.veiculo_horimetro as horimetro_inicial, (horimetro.veiculo_horimetro  - atv.veiculo_horimetro) as horimetro_rodado')
			->select("atv.id_ativo_veiculo, atv.veiculo_placa, atv.veiculo, atv.veiculo_placa, atv.id_interno_maquina, atv.tipo_veiculo, atv.situacao, atv.data, atv.id_marca, atv.id_modelo")
			->select("(({$credito_select} - horimetro.veiculo_horimetro) + atv.veiculo_horimetro)  as horimetro_ultima_revisao, {$credito_select} as horimetro_proxima_revisao");

		$inicio = $data['periodo']['inicio'];
		$fim = $data['periodo']['fim'];
		if ($inicio && $fim) {
			$relatorio->where("horimetro.data >= '$inicio'")
				->where("horimetro.data <= '$fim'");
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
		if (count($veiculos) > 0) {
			foreach ($veiculos as $k => $veiculo) {
				if ($veiculo->tipo_veiculo == 'maquina') $veiculos[$k] = $this->ativo_veiculo_model->set_outros_dados_veiculo($veiculo);
			}
		}
		return $veiculos;
	}

	public function veiculos_abastecimentos($data = null)
	{
		$veiculos_abastecimentos = $this->custos_veiculos_abastecimentos($this->extract_data('veiculos_abastecimentos', $data), 'arquivo');
		return (object) [
			'abastecimentos' => $veiculos_abastecimentos->lista,
			'total' => $veiculos_abastecimentos->total,
			'consumo_medio' => $veiculos_abastecimentos->consumo_medio,
			'km_rodados' => $veiculos_abastecimentos->km_rodados,
			'unidades' => $veiculos_abastecimentos->unidades,
			'show_resultados_todos' => $veiculos_abastecimentos->show_resultados_todos,
		];
	}

	public function custos_ferramentas($data, $tipo = null)
	{
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

	public function custos_equipamentos($data, $tipo = null)
	{
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

	public function custos_equipamentos_manutecoes($data, $tipo = null)
	{
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

	public function custos_ferramentas_manutecoes($data, $tipo = null)
	{
		$ferramentas_manutencao =  null;
		$ferramentas_manutencao_total = null;
		$inicio = $data['periodo']['inicio'];
		$fim = $data['periodo']['fim'];

		if ($tipo && $tipo == 'arquivo') {
			//Ferramentas Manutenções
			$ferramentas_manutencao = $this->db->from('ativo_externo_manutencao atm')
				->select('atm.*, atv.*, atm.valor as manutencao_valor, atv.valor as equipamento_valor')
				->join('ativo_externo atv', 'atv.id_ativo_externo = atm.id_ativo_externo');
			if ($inicio && $fim) {
				$ferramentas_manutencao
					->where("atm.data_retorno >= '$inicio'")
					->where("atm.data_retorno <= '$fim'");
			}

			$ferramentas_manutencao = $ferramentas_manutencao
				->group_by('atm.id_manutencao')
				->get()->result();
		}

		//Ferramentas Manutenções total
		$this->db->reset_query();
		$ferramentas_manutencao_total = $this->db
			->from('ativo_externo_manutencao atmc')
			->select("SUM(atmc.valor) as valor");

		if ($inicio && $fim) {
			$ferramentas_manutencao_total
				->where("atmc.data_retorno >= '$inicio'")
				->where("atmc.data_retorno <= '$fim'");
		}

		$ferramentas_manutencao_total = $ferramentas_manutencao_total->get()->row();

		if ($tipo && $tipo == 'arquivo') {
			return (object) [
				'lista' =>  $ferramentas_manutencao,
				'total' => $this->formata_moeda($ferramentas_manutencao_total->valor),
			];
		}

		return (object) [
			'lista' =>  $ferramentas_manutencao,
			'total' => $this->formata_moeda($ferramentas_manutencao_total->valor, true),
		];
	}


	public function custos_veiculos_manutecoes($data, $tipo = null)
	{
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

	public function custos_veiculos_abastecimentos($data, $tipo = null)
	{
		$inicio = $data['periodo']['inicio'];
		$fim = $data['periodo']['fim'];

		//Veiculos abastecimentos
		$veiculos_abastecimento = $this->db->from("ativo_veiculo_abastecimento abt")
			->select('abt.*, atv.marca, atv.modelo, atv.veiculo_placa, atv.id_interno_maquina')
			->select("fn.nome_fantasia as fornecedor")
			->join("ativo_veiculo atv", "atv.id_ativo_veiculo=abt.id_ativo_veiculo")
			->join("fornecedor fn", "fn.id_fornecedor=abt.id_fornecedor")
			->order_by('abt.id_ativo_veiculo_abastecimento', 'asc')
			->group_by('abt.id_ativo_veiculo_abastecimento');



		if ($inicio && $fim) {
			$veiculos_abastecimento
				->where("abt.abastecimento_data >= '$inicio'")
				->where("abt.abastecimento_data <= '$fim'");
		}

		$show_resultados_todos = true;
		if (isset($data['veiculo_placa']) && $data['veiculo_placa'] != null) {
			$veiculos_abastecimento->like("veiculo_placa", $data['veiculo_placa']);
			$show_resultados_todos = false;
		}

		if (isset($data['id_interno_maquina']) && $data['id_interno_maquina'] != null) {
			$veiculos_abastecimento->like("id_interno_maquina", $data['id_interno_maquina']);
			$show_resultados_todos = false;
		}

		$veiculos_abastecimento = $veiculos_abastecimento->get()->result();

		$valor = $unidades = $km_inicial = $km_final = 0;
		foreach ($veiculos_abastecimento as $abt => $abastecimento) {
			$valor += $abastecimento->abastecimento_custo;
			$unidades += $abastecimento->combustivel_unidade_total;
			if ($abt === 0) $km_inicial = $abastecimento->veiculo_km;
			if ($abt === (count($veiculos_abastecimento) - 1)) $km_final = $abastecimento->veiculo_km;
		}

		$km_rodados = $km_final - $km_inicial;

		return (object) [
			'lista' =>  $veiculos_abastecimento,
			'total' => $this->formata_moeda($valor, ($tipo != 'arquivo')),
			'consumo_medio' => $km_rodados > 0 ? number_format(($unidades / $km_rodados), 2) : 0,
			'km_rodados' => $km_rodados,
			'unidades' => $unidades,
			'show_resultados_todos' => $show_resultados_todos,
		];
	}

	public function centro_de_custo($data = null, $tipo = null)
	{
		$data = $this->extract_data('centro_de_custo', $data);
		$equipamentos =  $this->custos_equipamentos($data, $tipo);
		$equipamentos_manutecoes = $this->custos_equipamentos_manutecoes($data, $tipo);
		$ferramentas_manutecoes = $this->custos_ferramentas_manutecoes($data, $tipo);
		$ferramentas =  $this->custos_ferramentas($data, $tipo);
		$veiculos_manutecoes = $this->custos_veiculos_manutecoes($data, $tipo);
		$veiculos_abastecimentos = $this->custos_veiculos_abastecimentos($data, $tipo);

		if ($tipo && $tipo == 'arquivo') {
			return (object) [
				'ferramentas' =>  $ferramentas->lista,
				'ferramentas_total' => $ferramentas->total,
				'equipamentos' =>  $equipamentos->lista,
				'equipamentos_total' => $equipamentos->total,
				'equipamentos_manutecoes' => $equipamentos_manutecoes->lista,
				'equipamentos_manutecoes_total' => $equipamentos_manutecoes->total,
				'ferramentas_manutecoes' => $ferramentas_manutecoes->lista,
				'ferramentas_manutecoes_total' => $ferramentas_manutecoes->total,
				'veiculos_abastecimentos' => $veiculos_abastecimentos->lista,
				'veiculos_abastecimentos_total' => $veiculos_abastecimentos->total,
				'veiculos_manutecoes' => $veiculos_manutecoes->lista,
				'veiculos_manutecoes_total' => $veiculos_manutecoes->total,
				'total' => $this->formata_moeda(array_sum([
					$ferramentas->total,
					$equipamentos->total,
					$veiculos_abastecimentos->total
				]))
			];
		}

		$relatorio = [
			'ferramentas' =>  $ferramentas->total,
			'ferramentas_manutecoes' => $ferramentas_manutecoes->total,
			'equipamentos' =>  $equipamentos->total,
			'equipamentos_manutecoes' => $equipamentos_manutecoes->total,
			'veiculos_abastecimentos' => $veiculos_abastecimentos->total,
			'veiculos_manutecoes' => $veiculos_manutecoes->total,
			'total' => $this->formata_moeda(array_sum([
				$ferramentas->total,
				$ferramentas_manutecoes->total,
				$equipamentos->total,
				$equipamentos_manutecoes->total,
				$veiculos_manutecoes->total,
				$veiculos_abastecimentos->total
			]), true)
		];
		return (object) $relatorio;
	}

	private function get_patrimonio_obra_items($obra = null, $show_valor_total = true)
	{
		if ($obra) {
			$obra->equipamentos = $this->ativo_interno_model->get_lista($obra->id_obra);
			if ($show_valor_total) {
				$obra->equipamentos_total = 0;
				foreach ($obra->equipamentos as $equipamento) {
					$obra->equipamentos_total  +=  floatval($equipamento->valor);
				}
			}

			$obra->ferramentas = $this->ativo_externo_model->get_ativos($obra->id_obra);
			if ($show_valor_total) {
				$obra->ferramentas_total = 0;
				foreach ($obra->ferramentas as $ferramenta) {
					$obra->ferramentas_total  += floatval($ferramenta->valor);
				}
			}
			return $obra;
		}
		return null;
	}

	public function patrimonio_disponivel($data = null, $tipo = null)
	{
		$obras = [];
		$show_valor_total = isset($data['valor_total']) && $data['valor_total'] === "true";
		$data = $this->extract_data('patrimonio_disponivel', $data);

		if ($tipo && $tipo == 'arquivo') {
			$kms_atual_select = "(select veiculo_km from ativo_veiculo_quilometragem where id_ativo_veiculo = atv.id_ativo_veiculo order by id_ativo_veiculo_quilometragem desc limit 1)";
			$horimetro_atual_select = "(select veiculo_horimetro from ativo_veiculo_operacao where id_ativo_veiculo = atv.id_ativo_veiculo order by id_ativo_veiculo_operacao desc limit 1)";
			$relatorio = $this->db->from('ativo_veiculo atv')->select("atv.*, $kms_atual_select as veiculo_km_atual, $horimetro_atual_select as veiculo_horimetro_atual");
			if (isset($data['tipo_veiculo']) && $data['tipo_veiculo'] !== 'todos') {
				$relatorio->where("tipo_veiculo = {$data['tipo_veiculo']}");
			}
			$veiculos = $relatorio->select("atv.*")->where("situacao = '0'")->get()->result();

			$relatorio = $this->db->from('ativo_veiculo atv');
			if (isset($data['tipo_veiculo']) && $data['tipo_veiculo'] !== 'todos') {
				$relatorio->where("tipo_veiculo = {$data['tipo_veiculo']}");
			}
			$veiculos_total = $relatorio->select("SUM(atv.valor_fipe) as valor")->where("situacao = '0'")->get()->row();

			if (isset($data['id_obra']) && $data['id_obra'] != null) {
				$obra = $this->obra_model->get_obra($data['id_obra']);
				$obras[] = $this->get_patrimonio_obra_items($obra, $show_valor_total);
			} else {
				$obras_models = $this->obra_model->get_obras();
				foreach ($obras_models as $obra) {
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

		if ($data['id_obra']) {
			$ativo_interno->where("ati.id_obra = {$data['id_obra']}");
		}
		$ativos_internos = $ativo_interno->get()->row();

		$ativo_externo = $this->db
			->from('ativo_externo ate')
			->select('COUNT(ate.id_ativo_externo) as ferramentas')
			->where('ate.situacao = 12');

		if ($data['id_obra']) {
			$ativo_externo->where("ate.id_obra = {$data['id_obra']}");
		}
		$ativos_externos =  $ativo_externo->get()->row();

		$ativos_veiculos = null;
		if (!$data['id_obra']) {
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


	private function filter_by_periodo($query, $column, $periodo_inicio = null, $periodo_fim = null)
	{
		if ($periodo_inicio) {
			$query->where("{$column} >= '$periodo_inicio'");
		}

		if ($periodo_fim) {
			$query->where("{$column} <= '$periodo_fim'");
		}
		return $query;
	}


	public function count_ativos_externos($periodo_inicio = null, $periodo_fim = null)
	{
		$ativos = $this->filter_by_periodo($this->ativo_externo_model->query(), 'atv.data_inclusao', $periodo_inicio, $periodo_fim);
		return $ativos->where("atv.data_descarte IS NULL")->get()->num_rows();
	}

	public function count_ativos_internos($periodo_inicio = null, $periodo_fim = null)
	{
		$ativos = $this->filter_by_periodo($this->ativo_interno_model->query(), 'data_inclusao', $periodo_inicio, $periodo_fim);
		return $ativos->where("data_descarte IS NULL")->get()->num_rows();
	}

	public function count_ativos_veiculos($periodo_inicio = null, $periodo_fim = null)
	{
		$veiculos = $this->filter_by_periodo($this->ativo_veiculo_model->query(), 'data', $periodo_inicio, $periodo_fim);
		return $veiculos->where("ativo_veiculo.situacao = '0'")->get()->num_rows();
	}

	public function count_colaboradores($periodo_inicio = null, $periodo_fim = null)
	{
		$funcionarios = $this->filter_by_periodo($this->db->from('funcionario'), 'data_criacao', $periodo_inicio, $periodo_fim);
		return $funcionarios->where("situacao = '0'")->get()->num_rows();
	}

	public function crescimento_empresa()
	{
		$meses_porcentagens = $meses_total = $meses = [];
		$inicio =  date("1991-07-20 06:20:00");
		$ultimo_dia = date('t');
		$fim = date("Y-m-{$ultimo_dia} 23:59:59", strtotime("-13 months"));

		for ($i = 0; $i < 12; $i++) {
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
		foreach (array_reverse($meses_total, true) as  $mes) {
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

	public function limpar_uploads()
	{
		$delete_files = [];
		$path = __DIR__ . "/../../../../assets/uploads/";

		foreach ($this->uploads as $dir => $table) $delete_files = array_merge($delete_files, $this->anexo_model->getOrphans($dir, $table));

		foreach (glob("{$path}/relatorio/relatorio_*") as $file) {
			$filetime = strtotime(explode(".", substr(strrchr($file, "_"), 1))[0]);
			if ($filetime <= strtotime('-2 minutes')) $delete_files[] = $file;
		}

		foreach ($delete_files as $filename) if (file_exists($filename)) unlink($filename);
		return $this->limpar_anexos_excluidos();
	}

	public function limpar_anexos_excluidos()
	{
		$anexos = $this->anexo_model->get_anexos();
		$anexos_excluir_id = [];
		$path = APPPATH . "../assets/uploads/";

		foreach ($anexos as $anexo) {
			if (!file_exists($path . $anexo->anexo)) $anexos_excluir_id = array_merge($anexos_excluir_id, [$anexo->id_anexo]);
		}

		$this->db->where("id_anexo  IN ('" . implode("','", $anexos_excluir_id) . "')")->delete('anexo');
		return true;
	}

	public function getSendAdrress(): array
	{
		$send_address = $this->config->item("notifications_email_to") != null ? $this->config->item("notifications_email_to") : [];
		array_map(function ($user) use (&$send_address) {
			$send_address = array_merge($send_address, ["$user->nome" => $user->email]);
		}, $this->db->where("nivel = '1' and permit_notification_email = '1' and email_confirmado_em IS NOT NULL")->get('usuario')->result());
		return $send_address;
	}

	public function informe_vencimentos($days = 0, $id_obra = null)
	{
		$date = date('Y-m-d', strtotime("+{$days} days"));
		$now = date('Y-m-d H:i:s');
		$results = [];
		$veiculos_modulos_ids = [];
		$ativo_externo_modulos_ids = [];
		$id_modulo = "";

		foreach ($this->relatorio_model->vencimentos as $modulo => $vencimentos) {
			foreach ($vencimentos as $vencimento) {
				$relatorio = $this->db->select("{$vencimento['tabela']}.*");

				if (isset($vencimento['coluna_formato']) && $vencimento['coluna_formato'] == 'date') $now = date("Y-m-d", strtotime($now));

				if ($id_obra && $vencimento['tabela'] == 'ativo_interno') {
					$relatorio->where("{$vencimento['tabela']}.id_obra = '{$id_obra}'");
				}

				if ($modulo == 'ativo_veiculo' && $vencimento['nome'] != 'manutencao') {
					$relatorio
						->join($modulo, "$modulo.$id_modulo = {$vencimento['tabela']}.{$id_modulo}")
						->select("{$modulo}.*");
				}

				if ($modulo == 'ativo_veiculo' && $vencimento['nome'] == 'manutencao') {
					$id_modulo = "id_{$modulo}";
					$relatorio
						->join($modulo, "$modulo.$id_modulo = {$vencimento['tabela']}.{$id_modulo}")
						->select("{$modulo}.veiculo_placa, {$modulo}.id_interno_maquina, {$modulo}.marca, {$modulo}.modelo")
						->select('frn.razao_social as fornecedor')
						->select('ativo_configuracao.id_ativo_configuracao, ativo_configuracao.titulo as servico')
						->join("fornecedor frn", "frn.id_fornecedor={$vencimento['tabela']}.id_fornecedor", 'left')
						->join('ativo_configuracao', "ativo_configuracao.id_ativo_configuracao={$vencimento['tabela']}.id_ativo_configuracao", 'left');

					$veiculo_manutencao_colunas = ['veiculo_km_proxima_revisao', 'veiculo_horimetro_proxima_revisao'];

					if (in_array($vencimento['coluna'], $veiculo_manutencao_colunas) && isset($vencimento['id_configuracao_revisao'])) {
						$column = "veiculo_km";
						$table = "ativo_veiculo_quilometragem";
						if ($vencimento['coluna'] === "veiculo_horimetro_proxima_revisao") {
							$table = "ativo_veiculo_operacao";
							$column = "veiculo_horimetro";
						}

						$credito_select = "(select {$column}_proxima_revisao from ativo_veiculo_manutencao where (id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo AND ({$column}_proxima_revisao IS NOT NULL AND {$column}_proxima_revisao > 0)) order by id_ativo_veiculo_manutencao desc limit 1)";
						$debito_select = "(select {$column} from {$table} where id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo order by id_{$table} desc limit 1)";

						$relatorio
							->select("$debito_select as {$column}_debito, $credito_select as {$column}_credito, ($credito_select - $debito_select) as {$column}_saldo")
							->select("{$column}_atual as manutencao_{$column}_atual, $debito_select as {$column}_atual")
							->where("($credito_select - $debito_select) <= {$vencimento['alerta']} AND ({$vencimento['tabela']}.id_ativo_configuracao = {$vencimento['id_configuracao_revisao']} AND ({$vencimento['tabela']}.{$column}_proxima_revisao IS NOT NULL AND {$vencimento['tabela']}.{$column}_proxima_revisao > 0))")
							->or_where("{$vencimento['tabela']}.{$vencimento['coluna_vencimento']} IS NOT NULL AND {$vencimento['tabela']}.{$vencimento['coluna_vencimento']} BETWEEN '{$now}' AND '{$date}'");
					}
				}

				if ($modulo == 'ativo_externo' && $vencimento['tabela'] == "ativo_externo_certificado_de_calibracao") {
					$id_modulo = "id_{$modulo}";
					$relatorio
						->join($modulo, "$modulo.$id_modulo = {$vencimento['tabela']}.{$id_modulo}")
						->select("$modulo.nome as ativo_nome, $modulo.codigo as ativo_codigo, $modulo.data_inclusao as ativo_data_inclusao")
						->select("({$vencimento['tabela']}.data_vencimento > '{$now}') as vigencia");
				}

				if (!in_array($vencimento['coluna'], $veiculo_manutencao_colunas) || $modulo != 'ativo_externo') {
					if ($days > 0) {
						$relatorio->where("{$vencimento['coluna']} BETWEEN '{$now}' AND '{$date}'");
					} else {
						$relatorio->where("{$vencimento['coluna']} = '{$date}'");
					}
				}

				$relatorio_data =  $relatorio
					->order_by("{$vencimento['tabela']}.{$vencimento['group_by']}", 'desc')
					->group_by("{$vencimento['tabela']}.{$vencimento['group_by']}")
					->get($vencimento['tabela'])->result();

				if (count($relatorio_data) > 0) {
					if (!isset($results[$vencimento['nome']])) {
						$results[$vencimento['nome']] = (object) [
							'data' => [],
							'modulo' => $modulo,
							'tipo' => $vencimento['nome']
						];
					}

					if ($modulo == 'ativo_veiculo') {
						if (!isset($veiculos_modulos_ids[$modulo])) $veiculos_modulos_ids[$modulo] = [];
						if (!isset($veiculos_modulos_ids[$modulo][$vencimento['nome']])) $veiculos_modulos_ids[$modulo][$vencimento['nome']] = [];

						foreach ($relatorio_data as $data) {
							$id = "{$id_modulo}_{$vencimento['nome']}";
							if (!in_array($data->$id, $veiculos_modulos_ids[$modulo][$vencimento['nome']])) {
								$veiculos_modulos_ids[$modulo][$vencimento['nome']][] = $data->$id;
								$results[$vencimento['nome']]->data[$data->$id] = $data;
							} else {
								if ($data->$id === $results[$vencimento['nome']]->data[$data->$id]->$id) {
									$diff_array = array_diff_assoc(
										(array) $data,
										(array) $results[$vencimento['nome']]->data[$data->$id]
									);
									$results[$vencimento['nome']]->data[$data->$id] = (object) array_merge((array) $data, $diff_array);
								}
							}
						}
					} else {
						if (!isset($ativo_externo_modulos_ids[$modulo])) $ativo_externo_modulos_ids[$modulo] = [];

						foreach ($relatorio_data as $data) {
							if (!in_array($data->$id_modulo, $ativo_externo_modulos_ids[$modulo])) {
								$ativo_externo_modulos_ids[$modulo][] = $data->$id_modulo;
								$results[$vencimento['nome']]->data[$data->$id_modulo] = $data;
							}
						}
					}
				}
			}
		}

		return (object) $results;
	}

	public function enviar_informe_vencimentos($dias_restantes = 30, $debug = false)
	{
		$relatorio_data = $this->informe_vencimentos($dias_restantes);

		if (count((array) $relatorio_data) > 0) {
			$data = [
				'data_hora' => date('d/m/Y H:i:s', strtotime('now')),
				'relatorio' => $relatorio_data,
				'dias' => $dias_restantes,
				'styles' => $this->notificacoes_model->getEmailStyles(),
			];
			$html = $this->load->view("relatorio/relatorio_informe_vencimentos", $data, true);

			if ($debug) {
				echo $html;
				return true;
			}

			$send_address = $this->getSendAdrress();
			return count($send_address) > 0 ? $this->notificacoes_model->enviar_email(
				"Informe de Vencimentos",
				$html,
				$send_address,
				["ilustration" => "images/ilustrations/schedule_meeting.png"]
			) : false;
		}
		return true;
	}


	public function informe_retiradas_pendentes($devolucao_prevista = "now", $id_obra = null)
	{
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

	public function enviar_informe_retiradas_pendentes($data_hora_vencimento = "now", $debug = false)
	{
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
			if ($debug) {
				echo $html;
				return true;
			}

			$date = date("d/m/Y", strtotime($data_hora_vencimento));
			$send_address = $this->getSendAdrress();

			return count($send_address) > 0 ? $this->notificacoes_model->enviar_email(
				"Retiradas Pêndentes de Devolução | {$date}",
				$html,
				$send_address,
				["ilustration" => "images/ilustrations/schedule_meeting.png"]
			) : false;
		}
		return true;
	}

	public function atualiza_veiculos_depreciacao($day = 1, $debug = false)
	{
		$success = [];
		$messages = [];

		if ((int) date("d") === $day) {
			$veiculos = $this->ativo_veiculo_model->get_lista();
			foreach ($veiculos as $veiculo) {
				if (
					$this->ativo_veiculo_model->permit_update_depreciacao($veiculo->id_ativo_veiculo) &&
					!in_array($this->tipos_veiculos[$veiculo->tipo_veiculo], ['machine']) &&
					($veiculo->codigo_fipe && $veiculo->ano)
				) {
					$fipe = $this->ativo_veiculo_model->fipe_get_veiculo($this->tipos_veiculos[$veiculo->tipo_veiculo], $veiculo->codigo_fipe, $veiculo->ano);
					if ($fipe && $fipe->success) {
						$data = [
							'id_ativo_veiculo' => $veiculo->id_ativo_veiculo,
							'fipe_valor' => $fipe->data->fipe_valor,
							'fipe_mes_referencia' =>  $fipe->data->fipe_mes_referencia,
							'fipe_ano_referencia' =>  $fipe->data->fipe_ano_referencia,
						];

						if ($debug === false) $this->db->insert('ativo_veiculo_depreciacao', $data);
						else {
							echo "<br><pre>";
							print_r([$data]);
							echo "</pre>";
						}
					}

					$success[$veiculo->id_ativo_veiculo] = $fipe->success;
					$messages[$veiculo->id_ativo_veiculo] = $fipe->message;
				}
			}
		}
		return (object) ['success' => !in_array(false, $success), 'messages' => $messages];
	}


	/* Máquina - Manutenção */
	function maquina_manutencao_hora()
	{
		return $this->db->get('v_ativo_veiculo_manutencao_horimetro')->result();
	}

	/* Máquina - Manutenção */
	function revisao_por_km()
	{

		$veiculo = [];

		$proxima_revisao = $this->db
			->select(
				'id_ativo_veiculo, 
				veiculo_km_proxima_revisao,
				id_ativo_veiculo_manutencao'
			)
			->select_max('veiculo_km_proxima_revisao')
			->where('veiculo_horimetro_atual', '0')
			->group_by('id_ativo_veiculo')
			->get('ativo_veiculo_manutencao')
			->result();

		$i = 0;

		foreach ($proxima_revisao as $revisao) {
			$revisao->quilometragem_atual = $this->db
				->select_max('veiculo_km')
				->where('id_ativo_veiculo', $revisao->id_ativo_veiculo)
				->group_by('id_ativo_veiculo')
				->get('ativo_veiculo_quilometragem')
				->row('veiculo_km');

			$revisao->configuracao_km = $this->db
				->select('km_alerta')
				->where('id_configuracao', 1)
				->get('configuracao')
				->row('km_alerta');

			$revisao->veiculo = $this->db
				->select('marca, modelo')
				->where('id_ativo_veiculo', $revisao->id_ativo_veiculo)
				->get('ativo_veiculo')
				->row();

			$revisao->saldo_quilometragem = ($revisao->veiculo_km_proxima_revisao  - $revisao->quilometragem_atual);

			if ($revisao->saldo_quilometragem != 1) {
				if ($revisao->saldo_quilometragem < $revisao->configuracao_km && isset($revisao->veiculo->marca)) {
					$veiculo[$i]['id_ativo_veiculo'] = $revisao->id_ativo_veiculo;
					$veiculo[$i]['id_ativo_veiculo_manutencao'] = $revisao->id_ativo_veiculo_manutencao;
					$veiculo[$i]['veiculo_km_proxima_revisao'] = $revisao->veiculo_km_proxima_revisao;
					$veiculo[$i]['quilometragem_atual'] = $revisao->quilometragem_atual;
					$veiculo[$i]['marca'] = ($revisao->veiculo->marca) ?? '-';
					$veiculo[$i]['modelo'] = ($revisao->veiculo->modelo) ?? '-';
					$veiculo[$i]['saldo_quilometragem'] = $revisao->saldo_quilometragem;
					$i++;
				}
			}
		}

		return $veiculo;
	}

	/* Logs */
	public function logs()
	{
	
		$query = $this->db->from('logs AS c1');
		
		if($this->input->post('id_submodulo'))
		{
			$query = $query->where('c1.id_modulo', $this->input->post('id_submodulo'));
		}

		if($this->input->post('id_usuario'))
		{
			$query = $query->where('c1.id_usuario', $this->input->post('id_usuario'));
		}

		if($this->input->post('acao'))
		{
			$query = $query->where('c1.acao', $this->input->post('acao'));
		}

		// By Periodo
		$data = $this->extract_data('logs', $this->input->post());
		$inicio = $data['periodo']['inicio'];
		$fim = $data['periodo']['fim'];
		
		if ($inicio && $fim) {
			$query = $query->where("c1.created_at BETWEEN '{$inicio}' AND '{$fim}'");
		}		

		$query = $query->get()->result();		
		return $query;

	}
	
	
	/* Informe de Vencimentos - Seguro */
	public function informe_seguros()
	{

		$consulta = $this->db
								->select_max('id_ativo_veiculo_seguro')
								->group_by('id_ativo_veiculo')
								->get('ativo_veiculo_seguro')
								->result();

		foreach($consulta as &$subconsulta){			
			$subconsulta->sub = $this->db
									->where('c1.id_ativo_veiculo_seguro', $subconsulta->id_ativo_veiculo_seguro)
									->join('ativo_veiculo as c2', 'c2.id_ativo_veiculo=c1.id_ativo_veiculo')
									->where("c1.carencia_fim BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ")
									->get('ativo_veiculo_seguro AS c1')
									->row();			
		}

		return $consulta;	

	}
}