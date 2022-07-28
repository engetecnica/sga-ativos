<?php 
require_once __DIR__ . "/../controllers/Ativo_veiculo_trait.php";

class Ativo_veiculo_model extends MY_Model {

	use Ativo_veiculo_trait;
	use MY_Trait;

	public $tipos, $tipos_pt, $tipos_vetor;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('configuracao/configuracao_model');
		$this->tipos =  $this->config->item('veiculos_tipos');
		$this->tipos_pt =  $this->config->item('veiculos_tipos_pt');
		$this->tipos_vetor  = $this->config->item('veiculos_tipos_vetor');
	}

	public function salvar_formulario($data=null){
		if($data['id_ativo_veiculo']==''){

			$this->db->insert('ativo_veiculo', $data);
			$data['id_ativo_veiculo'] = $this->db->insert_id();

			/* Salvar LOG */
			$this->salvar_log(7, $data['id_ativo_veiculo'], 'adicionar', $data);

		} else {
			$this->db->where('id_ativo_veiculo', $data['id_ativo_veiculo'])->update('ativo_veiculo', $data);

			/* Salvar LOG */
			$this->salvar_log(7, $data['id_ativo_veiculo'], 'editar', $data);			
		}

		if ($data['id_interno_maquina'] == '' && $data['tipo_veiculo'] == 'maquina') {
			$this->db
				->where('id_ativo_veiculo', $data['id_ativo_veiculo'])
				->update('ativo_veiculo', ["id_interno_maquina" => $this->get_id_maquina($data['id_ativo_veiculo'])]);

				/* Salvar LOG */
				$this->salvar_log(7, $data['id_ativo_veiculo'], 'editar', $data);	
		}
		return $data['id_ativo_veiculo'];
	}

	public function ativos(){
		$select_km_atual = "(select veiculo_km from ativo_veiculo_quilometragem where id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo order by `data` limit 1)";
		$select_km_atual_data = "(select `data` from ativo_veiculo_quilometragem where id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo order by `data` limit 1)";
		$select_horimetro_atual = "(select veiculo_horimetro from ativo_veiculo_quilometragem where id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo order by `data` limit 1)";
		$select_horimetro_atual_data = "(select `data` from ativo_veiculo_quilometragem where id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo order by `data` limit 1)";
		$select_fipe_valor_atual = "(select fipe_valor from ativo_veiculo_depreciacao where id_ativo_veiculo = ativo_veiculo.id_ativo_veiculo order by `data` limit 1)";
		$select_obra = "(select codigo_obra from obra where id_obra = ativo_veiculo.id_obra limit 1)";

		return $this->db->from('ativo_veiculo')
				->select("*, ativo_veiculo.valor_fipe as valor_aquisicao, $select_fipe_valor_atual as valor_atual")
				->select("$select_km_atual as veiculo_km_atual, $select_km_atual_data as veiculo_km_atual_data")
				->select("$select_horimetro_atual as veiculo_horimetro_atual, $select_horimetro_atual_data as veiculo_horimetro_atual_data")
				->select("$select_obra as obra")
				->order_by('data', 'desc')
				->group_by('id_ativo_veiculo');
	}

	public function search_ativos($search){
		return $this->ativos()
			->group_by('id_ativo_veiculo')
			->order_by('id_ativo_veiculo', 'desc')
			->like('veiculo', $search)
			->or_like('veiculo_placa', $search)
			->or_like('id_interno_maquina', $search)
			->or_like('codigo_fipe', $search)
			->or_like('id_ativo_veiculo', $search)
			->or_like('data', $search)
			->get()->result();
	}

	public function get_categoria_lista(){
		return $this->ativos()
				->where('id_ativo_veiculo_vinculo', 0)
				->get()->result();
	}

	public function set_outros_dados_veiculo(stdClass $veiculo = null){
		if ($veiculo) {
			if ($veiculo->tipo_veiculo == "maquina" && !$veiculo->id_interno_maquina)
				$veiculo->id_interno_maquina = $this->get_id_maquina($veiculo->id_ativo_veiculo);
		}
		return $veiculo;
	}

	public function get_lista($page = null, $limit = null){
		$veiculos = $this->ativos();
		if ($page && $limit) $veiculos->limit(((int) $page * (int) $limit), (int) $page - 1);
		$lista = $veiculos->get()->result();
		return array_map(function($veiculo) {return $this->set_outros_dados_veiculo($veiculo);}, $lista);
	}

	public function get_ativo_veiculo($id_ativo_veiculo, $coluna = "id_ativo_veiculo"){
        return $this->set_outros_dados_veiculo($this->ativos()->where($coluna, $id_ativo_veiculo)->get()->row());
    }

	public function ativo_veiculo_manutencao(){
			$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = mnt.id_ativo_veiculo AND tipo = 'ordem_de_servico' 
			AND id_modulo_subitem = mnt.id_ativo_veiculo_manutencao ORDER BY id_anexo DESC LIMIT 1";

			return 	$this->db
			->from('ativo_veiculo_manutencao mnt')
			->select('
				mnt.*, atv.veiculo, 
				atv.veiculo_placa, atv.id_interno_maquina,
				atv.modelo as veiculo
			')
			->select('frn.razao_social as fornecedor')
			->select('ativo_configuracao.titulo as servico')
			->join("ativo_veiculo atv", "atv.id_ativo_veiculo=mnt.id_ativo_veiculo")
			->join("fornecedor frn", "frn.id_fornecedor=mnt.id_fornecedor")
			->join('ativo_configuracao', 'ativo_configuracao.id_ativo_configuracao=mnt.id_ativo_configuracao')
			->order_by('mnt.id_ativo_veiculo_manutencao', 'desc')
			->select("($select_anexo) as ordem_de_servico");
	}

	public function get_ativo_veiculo_manutencao_lista($id_ativo_veiculo = null, $em_andamento = null){
		$manutencoes = $this->ativo_veiculo_manutencao();

		if ($id_ativo_veiculo) {
			$manutencoes->where("mnt.id_ativo_veiculo", $id_ativo_veiculo);
		}
		
		if ($em_andamento != null) {
			if ($em_andamento) {
				$manutencoes->where("mnt.data_saida IS NULL");
			} else {
				$manutencoes->where("mnt.data_saida NO IS NULL");
			}
		}

		return $manutencoes
				->group_by('id_ativo_veiculo_manutencao')
				->get('ativo_veiculo_manutencao')
				->result();
	}

	public function count_ativo_veiculo_em_manutencao(){
		return $this->ativo_veiculo_manutencao()
				->group_by('id_ativo_veiculo')
				->get()->num_rows();
	}

	public function get_ativo_veiculo_abastecimento_lista($id_ativo_veiculo, array $limit = null){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = ativo_veiculo_abastecimento.id_ativo_veiculo AND tipo = 'abastecimento' 
			AND id_modulo_subitem = ativo_veiculo_abastecimento.id_ativo_veiculo_abastecimento ORDER BY id_anexo DESC LIMIT 1";

		$this->db->select('ativo_veiculo_abastecimento.*, ativo_veiculo.veiculo, ativo_veiculo.marca')
				->select('ativo_veiculo.modelo, ativo_veiculo.veiculo_placa, ativo_veiculo.id_interno_maquina')
				->select("($select_anexo) as comprovante")
				->select("fn.nome_fantasia as fornecedor")
				->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_abastecimento.id_ativo_veiculo")
				->join("fornecedor fn", "fn.id_fornecedor=ativo_veiculo_abastecimento.id_fornecedor")
				->where("ativo_veiculo_abastecimento.id_ativo_veiculo", $id_ativo_veiculo)
				->order_by('ativo_veiculo_abastecimento.id_ativo_veiculo_abastecimento', 'desc');

		if ($limit) {
			if (is_array($limit)) {
				$this->db->limit($limit[0], isset($limit[1]) ? $limit[1] : null);
			} else {
				$this->db->limit($limit);
			}
		}
		return $this->db
				->group_by('id_ativo_veiculo_abastecimento')
				->get('ativo_veiculo_abastecimento')
				->result();
	}

	public function get_ativo_veiculo_km_lista($id_ativo_veiculo, $limit = null){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = {$id_ativo_veiculo} AND tipo = 'quilometragem' 
			AND id_modulo_subitem = ativo_veiculo_quilometragem.id_ativo_veiculo_quilometragem ORDER BY id_anexo DESC LIMIT 1";

		$lista =$this->db->select('
					ativo_veiculo_quilometragem.*, 
					ativo_veiculo.veiculo, ativo_veiculo.veiculo_placa, ativo_veiculo.id_interno_maquina,
					ativo_veiculo.modelo as veiculo
				')
				->select("($select_anexo) as comprovante")
				->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_quilometragem.id_ativo_veiculo")
				->join("anexo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_quilometragem.id_ativo_veiculo");
		
		if (is_array($id_ativo_veiculo)) {
			$lista->where("ativo_veiculo.id_ativo_veiculo IN ('".implode(',', $id_ativo_veiculo)."')");
		} else {
			$lista->where("ativo_veiculo.id_ativo_veiculo = {$id_ativo_veiculo}");
		}
		
		$lista->order_by('ativo_veiculo_quilometragem.id_ativo_veiculo_quilometragem', 'desc');

		if ($limit) {
			if (is_array($limit)) {
				$lista->limit($limit[0], isset($limit[1]) ? $limit[1] : null);
			} else {
				$lista->limit($limit);
			}
		}

		return $lista
				->group_by('id_ativo_veiculo_quilometragem')
				->get('ativo_veiculo_quilometragem')
				->result();
	}

	public function get_ativo_veiculo_operacao_lista($id_ativo_veiculo, $limit = null){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = ativo_veiculo_operacao.id_ativo_veiculo AND tipo = 'operacao' 
			AND id_modulo_subitem = ativo_veiculo_operacao.id_ativo_veiculo_operacao ORDER BY id_anexo DESC LIMIT 1";

		$this->db->select('
					ativo_veiculo_operacao.*, 
					ativo_veiculo.veiculo, ativo_veiculo.veiculo_placa, ativo_veiculo.id_interno_maquina,
					ativo_veiculo.modelo as veiculo
				')
				->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_operacao.id_ativo_veiculo")
				->where("ativo_veiculo_operacao.id_ativo_veiculo", $id_ativo_veiculo)
				->select("({$select_anexo}) as comprovante")
				->order_by('ativo_veiculo_operacao.id_ativo_veiculo_operacao', 'desc');

		if ($limit) {
			if (is_array($limit)) {
				$this->db->limit($limit[0], isset($limit[1]) ? $limit[1] : null);
			} else {
				$this->db->limit($limit);
			}
		}
		return $this->db
				->group_by('id_ativo_veiculo_operacao')
				->get('ativo_veiculo_operacao')
				->result();
	}

	public function get_extrato($tipo = 'km', $id_ativo_veiculo, $returnObject = true){
		$column = "veiculo_km";
		$table = "ativo_veiculo_quilometragem";
		if($tipo === 'operacao') {
			$table = "ativo_veiculo_operacao";
			$column = "veiculo_horimetro";
		}

		$veiculo = $this->get_ativo_veiculo($id_ativo_veiculo);
		if ($veiculo) {
			if($veiculo->$column === 0 || $veiculo->$column === null) $veiculo->$column = 1;
			$credito_select = "((select {$column}_proxima_revisao from ativo_veiculo_manutencao where (id_ativo_veiculo = atv.id_ativo_veiculo AND ({$column}_proxima_revisao IS NOT NULL AND {$column}_proxima_revisao > 0)) order by id_ativo_veiculo_manutencao desc limit 1) - {$veiculo->$column})";
			$debito_select = "((select {$column} from {$table} where id_ativo_veiculo = atv.id_ativo_veiculo order by id_{$table} desc limit 1) - {$veiculo->{$column}})";
			$valor_atual = "(select {$column} from {$table} where id_ativo_veiculo = atv.id_ativo_veiculo order by id_{$table} desc limit 1)";
			
			$extrato = $this->db
				->select("atv.id_ativo_veiculo as id_ativo_veiculo, $credito_select as credito, $debito_select as debito")
				->select("($credito_select - $debito_select - 1) as saldo, $valor_atual as {$column}_atual")
				->where("atv.id_ativo_veiculo = {$id_ativo_veiculo}")
				->get('ativo_veiculo atv')->row();

			if ($extrato) {
				$extrato->tipo = $tipo === 'operacao' ? "Horas" : "KM";
				return $returnObject ? $extrato : $extrato->saldo;
			}
		}
		return null;
	}

	public function get_operacao_saldo($id_ativo_veiculo){
		return $this->get_extrato("operacao", $id_ativo_veiculo, false);
	}

	public function get_km_saldo($id_ativo_veiculo){
		return $this->get_extrato("km", $id_ativo_veiculo, false);
	}

	public function get_ativo_veiculo_ipva_lista($id_ativo_veiculo){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND  id_modulo_item = ativo_veiculo_ipva.id_ativo_veiculo AND tipo = 'ipva' 
			AND id_modulo_subitem = ativo_veiculo_ipva.id_ativo_veiculo_ipva ORDER BY id_anexo DESC LIMIT 1";

		return 	$this->db
				->select('ativo_veiculo_ipva.*, ativo_veiculo.veiculo, ativo_veiculo.veiculo_placa')
				->select("({$select_anexo}) as comprovante")
				->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_ipva.id_ativo_veiculo")
				->where("ativo_veiculo_ipva.id_ativo_veiculo", $id_ativo_veiculo)
				->order_by('ativo_veiculo_ipva.id_ativo_veiculo_ipva', 'desc')
				->group_by('id_ativo_veiculo_ipva')
				->get('ativo_veiculo_ipva')
				->result();
	}
	
	public function get_ativo_veiculo_seguro_lista($id_ativo_veiculo){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = ativo_veiculo_seguro.id_ativo_veiculo AND tipo = 'seguro' 
			AND id_modulo_subitem = ativo_veiculo_seguro.id_ativo_veiculo_seguro ORDER BY id_anexo DESC LIMIT 1";

		return $this->db->select('ativo_veiculo_seguro.*, ativo_veiculo.veiculo, ativo_veiculo.veiculo_placa')
				->select("({$select_anexo}) as comprovante")
				->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_seguro.id_ativo_veiculo")
				->where("ativo_veiculo_seguro.id_ativo_veiculo", $id_ativo_veiculo)	
				->order_by('ativo_veiculo_seguro.id_ativo_veiculo_seguro', 'desc')
				->group_by('id_ativo_veiculo_seguro')
				->get('ativo_veiculo_seguro')
				->result();
	}	

	public function get_tipo_servico($id_ativo_configuracao=null){
		$this->db
				->where("(id_ativo_configuracao_vinculo={$id_ativo_configuracao})")
				->where("situacao = '0'");

		return $this->db->group_by('id_ativo_configuracao')
										->get('ativo_configuracao')
										->result();
	}	

	public function get_fornecedores(){
		$this->db->order_by("razao_social", "asc")->where("situacao = '0'");;
		return $this->db->group_by('id_fornecedor')->get('fornecedor')->result();
	}


	public function get_combustiveis(){
		$configuracao = $this->configuracao_model->get_configuracao(1);
		try {
			require(APPPATH."/config/combustiveis.php");
			return get_combustiveis($configuracao);
		} catch (\Exception $e){
			return [];
		}
	}

	public function get_ativo_veiculo_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao){
		$depreciacao = $this->db
			->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_depreciacao.id_ativo_veiculo")
			->where('ativo_veiculo_depreciacao.id_ativo_veiculo', $id_ativo_veiculo)
			->where('id_ativo_veiculo_depreciacao', $id_ativo_veiculo_depreciacao)
			->select('ativo_veiculo.id_ativo_veiculo, ativo_veiculo.valor_fipe as valor_aquisicao')
			->select('ativo_veiculo_depreciacao.*')
			->order_by("fipe_ano_referencia", "desc")
			->order_by("fipe_mes_referencia", "desc")
			->get('ativo_veiculo_depreciacao')
			->row();

		if($depreciacao) {
			$depreciacao->total = 0;
			$depreciacao->direcao = "up";

			$valores = $this->ativo_veiculo_model->calc_ativo_veiculo_depreciacao_values([$depreciacao], 0);
			if($valores->direcao === "up") $depreciacao->total -= $valores->valor;
			else  $depreciacao->total += $valores->valor;

			if($depreciacao->total < 0) $depreciacao->total_direcao = "down";
		}
		
		return $depreciacao;
	}


	public function get_ativo_veiculo_depreciacao_lista($id_ativo_veiculo){
		$lista = (object) [];
		$lista->data = $this->db
			->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_depreciacao.id_ativo_veiculo")
			->where('ativo_veiculo_depreciacao.id_ativo_veiculo', $id_ativo_veiculo)
			->select('ativo_veiculo.id_ativo_veiculo, ativo_veiculo.valor_fipe as valor_aquisicao')
			->select('ativo_veiculo_depreciacao.*')
			->group_by('id_ativo_veiculo_depreciacao')
			->order_by("fipe_ano_referencia", "desc")
			->order_by("fipe_mes_referencia", "desc")
			->get('ativo_veiculo_depreciacao')
			->result();

		if($lista->data) return $this->format_depreciacao_lista($lista, $id_ativo_veiculo);
		return $lista;
	}

	public function format_depreciacao_lista(object $lista, $id_ativo_veiculo = null){
		$lista->total = 0;
		$lista->total_direcao = "up";

		if($lista->data) {
			foreach($lista->data as $l => $valor){
				$valores = $this->calc_ativo_veiculo_depreciacao_values($lista->data, $l);

				if($valores->direcao === "up") $lista->total += $valores->valor;
				else $lista->total -= $valores->valor;


                $lista->data[$l]->direcao = $valores->direcao;
                $lista->data[$l]->depreciacao_valor = $valores->valor;
                $lista->data[$l]->depreciacao_porcentagem = $valores->porcentagem;
				
				if($id_ativo_veiculo) $lista->data[$l]->permit_edit = $this->permit_edit_depreciacao($id_ativo_veiculo, $valor->id_ativo_veiculo_depreciacao);
				if($id_ativo_veiculo) $lista->data[$l]->permit_delete = $this->permit_delete_depreciacao($id_ativo_veiculo, $valor->id_ativo_veiculo_depreciacao);
			}

			if($lista->total < 0) $lista->total_direcao = "down";
		}
		return $lista;
	}

	public function calc_ativo_veiculo_depreciacao_values(array $lista, int $index = 0){
        $anterior = 0;
        $direcao = "up";

		if (isset($lista[$index - 1])) $anterior = (float) $lista[$index - 1]->fipe_valor;
		else $anterior = (float) $lista[$index]->valor_aquisicao;

		$maior = (float) $lista[$index]->fipe_valor;
		$menor = (float) $anterior;

		if($anterior >= $lista[$index]->fipe_valor) {
			$direcao = "down";
			$menor = (float) $lista[$index]->fipe_valor;
			$maior = (float) $anterior;
		}

		$valor = (float) ($maior - $menor);
		return (object) [
			"valor" => $valor,
			"porcentagem" =>  number_format((($valor * 100) / $maior), 2),
			"direcao" => $direcao
		];
	}

	public function formata_mes_referecia($mes = 1, $ano = null){
    	$referencia = null;
    	$meses_ano = $this->config->item('meses_ano');
    	array_filter($meses_ano, function($m) use ($mes, &$referencia) {
    	  if ($m['id'] === (int) $mes) $referencia = $m;
    	});
    	return isset($referencia['nome']) ? "{$referencia['nome']} de {$ano}" : $mes;
    }

    public function get_mes_referecia($mes_referencia){
		$referencia = explode("de", $mes_referencia);
		$meses_ano = $this->config->item('meses_ano');
		if(count($referencia) === 2) {
			$mes = trim(strtolower($referencia[0]));
			$ano = trim(strtolower($referencia[1]));  
			if($meses_ano[$mes]) {
			return (object) [
				"mes" => $meses_ano[$mes]['nome'],
				"id" =>$meses_ano[$mes]['id'],
				"ano" => $ano
			];
			}
		}
		return null;
    }

	public function permit_delete($id_ativo_veiculo){
		return !$id_ativo_veiculo || !in_array(true, [
			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_manutencao")
				->num_rows() >= 1,

			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_ipva")
				->num_rows() >= 1,
			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_seguro")
				->num_rows() >= 1,
			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_quilometragem")
				->num_rows() >= 1,
			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_operacao")
				->num_rows() >= 1,
			$this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->limit(5)
				->get("ativo_veiculo_abastecimento")
				->num_rows() >= 1,
		]);
	}

	public function permit_edit_quilometragem($id_ativo_veiculo, $id_ativo_veiculo_quilometragem){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = {$id_ativo_veiculo} AND tipo = 'quilometragem' 
			AND id_modulo_subitem = ativo_veiculo_quilometragem.id_ativo_veiculo_quilometragem ORDER BY id_anexo DESC LIMIT 1";

		$quilometragem = $this->db
					->select("($select_anexo) as comprovante")
					->where('id_ativo_veiculo', $id_ativo_veiculo)
					->where('id_ativo_veiculo_quilometragem', $id_ativo_veiculo_quilometragem)
					->get("ativo_veiculo_quilometragem")
					->row();

		return !$quilometragem->comprovante || $this->permit_delete_quilometragem($id_ativo_veiculo, $id_ativo_veiculo_quilometragem);
	}


	public function permit_delete_quilometragem($id_ativo_veiculo, $id_ativo_veiculo_quilometragem){
		return $this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->where("id_ativo_veiculo_quilometragem > '{$id_ativo_veiculo_quilometragem}'")
				->get("ativo_veiculo_quilometragem")
				->num_rows() === 0;
	}


	public function permit_edit_abastecimento($id_ativo_veiculo, $id_ativo_veiculo_abastecimento){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = {$id_ativo_veiculo} AND tipo = 'abastecimento' 
			AND id_modulo_subitem = ativo_veiculo_abastecimento.id_ativo_veiculo_abastecimento ORDER BY id_anexo DESC LIMIT 1";

		$abastecimento = $this->db
					->select("({$select_anexo}) as comprovante")
					->where('id_ativo_veiculo', $id_ativo_veiculo)
					->where('id_ativo_veiculo_abastecimento', $id_ativo_veiculo_abastecimento)
					->get("ativo_veiculo_abastecimento")
					->row();
		return !$abastecimento->comprovante || $this->permit_delete_abastecimento($id_ativo_veiculo, $id_ativo_veiculo_abastecimento);
	}


	public function permit_delete_abastecimento($id_ativo_veiculo, $id_ativo_veiculo_abastecimento){
		return $this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->where("id_ativo_veiculo_abastecimento > '{$id_ativo_veiculo_abastecimento}'")
				->get("ativo_veiculo_abastecimento")
				->num_rows() === 0;
	}

	public function permit_edit_operacao($id_ativo_veiculo, $id_ativo_veiculo_operacao){
		$operacoes = $this->db
					->where('id_ativo_veiculo', $id_ativo_veiculo)
					->order_by('id_ativo_veiculo_operacao', 'desc')
					->limit(5)
					->get("ativo_veiculo_operacao")
					->result();
		if (count($operacoes) > 0) { 
			$operacao = end($operacoes);
			return !$operacao->id_ativo_veiculo_operacao == $id_ativo_veiculo_operacao || $this->permit_delete_operacao($id_ativo_veiculo, $id_ativo_veiculo_operacao);
		}
		return false;
	}

	public function permit_delete_operacao($id_ativo_veiculo, $id_ativo_veiculo_operacao){
		return $this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->where("id_ativo_veiculo_operacao > '{$id_ativo_veiculo_operacao}'")
				->order_by('id_ativo_veiculo_operacao', 'desc')
				->get("ativo_veiculo_operacao")
				->num_rows() === 0;
	}

	public function permit_add_ipva($id_ativo_veiculo, $ano){
		return $this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->where("ipva_ano = '{$ano}'")
				->get("ativo_veiculo_ipva")
				->num_rows() === 0;
	}

	public function permit_edit_ipva($id_ativo_veiculo, $id_ativo_veiculo_ipva){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = {$id_ativo_veiculo} AND tipo = 'ipva' 
			AND id_modulo_subitem = ativo_veiculo_ipva.id_ativo_veiculo_ipva ORDER BY id_anexo DESC LIMIT 1";

		$ipva = $this->db
					->select("({$select_anexo}) as comprovante")
					->where('id_ativo_veiculo', $id_ativo_veiculo)
					->where('id_ativo_veiculo_ipva', $id_ativo_veiculo_ipva)
					->get("ativo_veiculo_ipva")
					->row();
		return !$ipva->comprovante || $this->permit_delete_ipva($id_ativo_veiculo, $id_ativo_veiculo_ipva);
	}

	public function permit_delete_ipva($id_ativo_veiculo, $id_ativo_veiculo_ipva){
		return $this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->where("id_ativo_veiculo_ipva > '{$id_ativo_veiculo_ipva}'")
				->get("ativo_veiculo_ipva")
				->num_rows() === 0;
	}

	public function permit_edit_seguro($id_ativo_veiculo, $id_ativo_veiculo_seguro){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = {$id_ativo_veiculo} AND tipo = 'seguro' 
			AND id_modulo_subitem = ativo_veiculo_seguro.id_ativo_veiculo_seguro ORDER BY id_anexo DESC LIMIT 1";
			
		$seguro = $this->db
					->select("({$select_anexo}) as comprovante")
					->where('id_ativo_veiculo', $id_ativo_veiculo)
					->where('id_ativo_veiculo_seguro', $id_ativo_veiculo_seguro)
					->get("ativo_veiculo_seguro")
					->row();
		return !$seguro->comprovante || $this->permit_delete_seguro($id_ativo_veiculo, $id_ativo_veiculo_seguro);
	}

	public function permit_delete_seguro($id_ativo_veiculo, $id_ativo_veiculo_seguro){
		$now = date("Y-m-d");
		return $this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->where("id_ativo_veiculo_seguro > '{$id_ativo_veiculo_seguro}'")
				->where("carencia_fim > '{$now}'")
				->get("ativo_veiculo_seguro")
				->num_rows() === 0;
	}

	public function permit_add_depreciacao($id_ativo_veiculo){
		$veiculo = $this->get_ativo_veiculo($id_ativo_veiculo);
		return ($veiculo && !$veiculo->codigo_fipe);
	}

	public function permit_edit_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao){
		$depreciacao = $this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->where('id_ativo_veiculo_depreciacao', $id_ativo_veiculo_depreciacao)
				->order_by("fipe_ano_referencia", "desc")
				->order_by("fipe_mes_referencia", "desc")
				->get("ativo_veiculo_depreciacao")
				->row();
		return ($this->permit_add_depreciacao($id_ativo_veiculo) && $depreciacao) && 
		$this->permit_delete_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao);
	}

	
	public function permit_update_depreciacao($id_ativo_veiculo, int $mes_atual = null, int $ano_atual = null) {
		if($this->get_ativo_veiculo($id_ativo_veiculo)) {
			$mes = $mes_atual ?: (int) date("m"); $ano = $ano_atual ?: (int) date("Y");
			return $this->db
			->where("id_ativo_veiculo = {$id_ativo_veiculo}")
			->where("fipe_mes_referencia = {$mes} and fipe_ano_referencia = {$ano}")
			->get("ativo_veiculo_depreciacao")
			->num_rows() === 0;
		}
		return false;
	}

	public function permit_delete_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao){
		$now = date("Y-m-d H:i:s");
		return $this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->where("id_ativo_veiculo_depreciacao > '{$id_ativo_veiculo_depreciacao}'")
				->where("data < '{$now}'")
				->order_by("fipe_ano_referencia", "desc")
				->order_by("fipe_mes_referencia", "desc")
				->get("ativo_veiculo_depreciacao")
				->num_rows() === 0;
	}

	public function get_id_maquina($id_ativo_veiculo) : string
	{
		return strtoupper("ENG-MAQ-".str_pad($id_ativo_veiculo, 4, '0', STR_PAD_LEFT));
	}

	public function get_historico_veiculo($id_ativo_veiculo){
		return $this->db
				->select('a.*, b.veiculo, b.marca, b.modelo, c.codigo_obra')
				->join('ativo_veiculo b', 'b.id_ativo_veiculo=a.id_veiculo')
				->join('obra c', 'c.id_obra=a.id_obra')
				->where('a.id_veiculo', $id_ativo_veiculo)
				->where('a.deleted_at', null)
				->order_by('a.created_at', 'DESC')
				->get('ativo_veiculo_obra a')
				->result();
	}

	public function excluir_historico($id_veiculo_obra){
		return $this->db->where('id_veiculo_obra', $id_veiculo_obra)->update('ativo_veiculo_obra', array('deleted_at' => date("Y-m-d H:i:s")));
	}
}