<?php 
require_once __DIR__ . "/../controllers/Ativo_veiculo_trait.php";

class Ativo_veiculo_model extends MY_Model {

	use Ativo_veiculo_trait;

	public function __construct()
	{
		parent::__construct();
	}

	public function salvar_formulario($data=null){
		if($data['id_ativo_veiculo']==''){
			$this->db->insert('ativo_veiculo', $data);
			$data['id_ativo_veiculo'] = $this->db->insert_id();
		} else {
			$this->db->where('id_ativo_veiculo', $data['id_ativo_veiculo'])->update('ativo_veiculo', $data);
		}

		if ($data['id_interno_maquina'] == '' && $data['tipo_veiculo'] == 'maquina') {
			$this->db
				->where('id_ativo_veiculo', $data['id_ativo_veiculo'])
				->update('ativo_veiculo', ["id_interno_maquina" => $this->get_id_maquina($data['id_ativo_veiculo'])]);
		}
		return "salvar_ok";
	}

	public function ativos(){
		return $this->db->from('ativo_veiculo')
					->select('')
					->order_by('data', 'desc')
					->group_by('id_ativo_veiculo');
	}

	public function search_ativos($search){
		return $this->ativos()
			->group_by('id_ativo_veiculo')
			->order_by('id_ativo_veiculo', 'desc')
			->like('veiculo', $search)
			->or_like('veiculo_placa', $search)
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

			$ultimo_km = $this->db
							->where("id_ativo_veiculo = {$veiculo->id_ativo_veiculo}")
							->order_by('data', 'desc')
							->limit(1)
							->get('ativo_veiculo_quilometragem')
							->row();

			if ($ultimo_km) {
				$veiculo->veiculo_km_data = $ultimo_km->data;
				$veiculo->veiculo_km_atual = (int) $ultimo_km->veiculo_km; 
			}
		}
		return $veiculo;
	}

	public function get_lista($page = 1, $limit = 100){
		$lista = $this->ativos()->limit(((int) $page * (int) $limit), (int) $page - 1)->get()->result();
		return array_map(function($veiculo) {return $this->set_outros_dados_veiculo($veiculo);}, $lista);
	}

	public function get_ativo_veiculo($id_ativo_veiculo, $coluna = "id_ativo_veiculo"){
        return $this->set_outros_dados_veiculo($this->ativos()->where($coluna, $id_ativo_veiculo)->get()->row());
    }

	public function ativo_veiculo_manutencao(){
			$select_anexo = "SELECT anexo FROM anexo WHERE id_modulo_item = mnt.id_ativo_veiculo AND tipo = 'ordem_de_servico' 
			AND id_modulo_subitem = mnt.id_ativo_veiculo_manutencao ORDER BY id_anexo DESC LIMIT 1";

			return 	$this->db
			->from('ativo_veiculo_manutencao mnt')
			->select('mnt.*, atv.veiculo, atv.veiculo_placa')
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
		$select_anexo = "SELECT anexo FROM anexo WHERE id_modulo_item = ativo_veiculo_abastecimento.id_ativo_veiculo AND tipo = 'abastecimento' 
			AND id_modulo_subitem = ativo_veiculo_abastecimento.id_ativo_veiculo_abastecimento ORDER BY id_anexo DESC LIMIT 1";

		$this->db->select('ativo_veiculo_abastecimento.*, ativo_veiculo.veiculo, ativo_veiculo.veiculo_placa')
				->select("($select_anexo) as comprovante")
				->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_abastecimento.id_ativo_veiculo")
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
		$select_anexo = "SELECT anexo FROM anexo WHERE id_modulo_item = {$id_ativo_veiculo} AND tipo = 'quilometragem' 
			AND id_modulo_subitem = ativo_veiculo_quilometragem.id_ativo_veiculo_quilometragem ORDER BY id_anexo DESC LIMIT 1";

		$lista =$this->db->select('ativo_veiculo_quilometragem.*, ativo_veiculo.veiculo, ativo_veiculo.veiculo_placa')
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


	public function get_km_extrato($id_ativo_veiculo, $returnObject = true){
		$veiculo = $this->get_ativo_veiculo($id_ativo_veiculo);
		if ($veiculo) {
			$kms_credito_select = "((select veiculo_km_proxima_revisao from ativo_veiculo_manutencao where (id_ativo_veiculo = atv.id_ativo_veiculo AND (veiculo_km_proxima_revisao IS NOT NULL AND veiculo_km_proxima_revisao > 0)) order by id_ativo_veiculo_manutencao desc limit 1) - {$veiculo->veiculo_km})";
			$kms_debito_select = "((select veiculo_km from ativo_veiculo_quilometragem where id_ativo_veiculo = atv.id_ativo_veiculo order by id_ativo_veiculo_quilometragem desc limit 1) - {$veiculo->veiculo_km})";
			$km_atual = "(select veiculo_km from ativo_veiculo_quilometragem where id_ativo_veiculo = 2 order by id_ativo_veiculo_quilometragem desc limit 1)";

			$extrato = $this->db
				->select("atv.id_ativo_veiculo as id_ativo_veiculo, $kms_credito_select as credito, $kms_debito_select as debito")
				->select("($kms_credito_select - $kms_debito_select) as saldo, $km_atual as veiculo_km_atual")
				->where("atv.id_ativo_veiculo = {$id_ativo_veiculo}")
				->get('ativo_veiculo atv')->row();

			if ($extrato) {
				$extrato->tipo = "KM";
				return $returnObject ? $extrato : $extrato->saldo;
			}
		}
		return null;
	}

	public function get_km_saldo($id_ativo_veiculo){
		return $this->get_km_extrato($id_ativo_veiculo, false);
	}


	public function get_ativo_veiculo_operacao_lista($id_ativo_veiculo, $limit = null){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_modulo_item = ativo_veiculo_operacao.id_ativo_veiculo AND tipo = 'operacao' 
			AND id_modulo_subitem = ativo_veiculo_operacao.id_ativo_veiculo_operacao ORDER BY id_anexo DESC LIMIT 1";

		$this->db->select('ativo_veiculo_operacao.*, ativo_veiculo.veiculo, ativo_veiculo.veiculo_placa')
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

	public function get_operacao_extrato($id_ativo_veiculo, $returnObject = true){
		$horas_credito_select = "(select sum(veiculo_hora_proxima_revisao) from ativo_veiculo_manutencao where id_ativo_veiculo = atv.id_ativo_veiculo AND (veiculo_hora_proxima_revisao IS NOT NULL AND veiculo_hora_proxima_revisao > 0))";
		$horas_debito_select = "(select sum(operacao_tempo) from ativo_veiculo_operacao where id_ativo_veiculo = atv.id_ativo_veiculo)";
		$extrato = $this->db
        	 ->select("atv.id_ativo_veiculo as id_ativo_veiculo, $horas_credito_select as credito, $horas_debito_select as debito, ($horas_credito_select - $horas_debito_select) as saldo")
			 ->where("atv.id_ativo_veiculo = {$id_ativo_veiculo}")
			 ->get('ativo_veiculo atv')->row();

		if ($extrato) {
			$extrato->tipo = "Horas";
			return $returnObject ? $extrato : $extrato->saldo;
		}
		return null;
	}

	public function get_operacao_saldo($id_ativo_veiculo){
		return $this->get_operacao_extrato($id_ativo_veiculo, false);
	}


	public function get_ativo_veiculo_ipva_lista($id_ativo_veiculo){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_modulo_item = ativo_veiculo_ipva.id_ativo_veiculo AND tipo = 'ipva' 
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
		$select_anexo = "SELECT anexo FROM anexo WHERE id_modulo_item = ativo_veiculo_seguro.id_ativo_veiculo AND tipo = 'seguro' 
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

	public function get_fornecedor(){
		$this->db->order_by("razao_social", "asc")->where("situacao = '0'");;
		return $this->db->group_by('id_fornecedor')->get('fornecedor')->result();
	}

	public function get_ativo_veiculo_depreciacao_lista($id_ativo_veiculo){
		$this->db->where('id_ativo_veiculo', $id_ativo_veiculo);
		return $this->db->group_by('id_ativo_veiculo_depreciacao')
							->select('*')
							->get('ativo_veiculo_depreciacao')
							->result();
	}

	public function permit_delete($id_ativo_veiculo){
		return !in_array(true,[
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
				->num_rows() >= 1
		]);
	}

	public function permit_edit_quilometragem($id_ativo_veiculo, $id_ativo_veiculo_quilometragem){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_modulo_item = {$id_ativo_veiculo} AND tipo = 'quilometragem' 
			AND id_modulo_subitem = ativo_veiculo_quilometragem.id_ativo_veiculo_quilometragem ORDER BY id_anexo DESC LIMIT 1";
-
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
		$select_anexo = "SELECT anexo FROM anexo WHERE id_modulo_item = {$id_ativo_veiculo} AND tipo = 'abastecimento' 
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
		$select_anexo = "SELECT anexo FROM anexo WHERE id_modulo_item = {$id_ativo_veiculo} AND tipo = 'ipva' 
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
		$select_anexo = "SELECT anexo FROM anexo WHERE id_modulo_item = {$id_ativo_veiculo} AND tipo = 'seguro' 
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

	public function permit_edit_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao){
		$depreciacao = $this->db
					->where('id_ativo_veiculo', $id_ativo_veiculo)
					->where('id_ativo_veiculo_depreciacao', $id_ativo_veiculo_depreciacao)
					->get("ativo_veiculo_depreciacao")
					->row();
		return $depreciacao || $this->permit_delete_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao);
	}

	public function permit_delete_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao){
		return $this->db
				->where('id_ativo_veiculo', $id_ativo_veiculo)
				->where("id_ativo_veiculo_depreciacao > '{$id_ativo_veiculo_depreciacao}'")
				->get("ativo_veiculo_depreciacao")
				->num_rows() === 0;
	}

	public function get_id_maquina($id_ativo_veiculo) : string
	{
		return strtoupper("ENG-MAQ-".str_pad($id_ativo_veiculo, 4, '0', STR_PAD_LEFT));
	}
}