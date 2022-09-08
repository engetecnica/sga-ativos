<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_depreciacao_model {
	//@todo remove
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

	public function depreciacao_query($id_ativo_veiculo = null, $id_ativo_veiculo_depreciacao = null){
		$this->db->reset_query();

		$query = $this->db
			->from('ativo_veiculo_depreciacao depreciacao')
			->select('depreciacao.*');


		if (is_array($id_ativo_veiculo)) {
			$query->where("depreciacao.id_ativo_veiculo IN ('".implode(',', $id_ativo_veiculo)."')");
		} else {
			$query->where("depreciacao.id_ativo_veiculo = {$id_ativo_veiculo}");
		}

		if ($id_ativo_veiculo_depreciacao) {
			$query->where("depreciacao.id_ativo_veiculo_depreciacao = {$id_ativo_veiculo_depreciacao}");
		}

		$this->join_veiculo($query, 'depreciacao.id_ativo_veiculo');

		$query->select('veiculo.id_ativo_veiculo, veiculo.valor_fipe as valor_aquisicao');
		
		return $query
			->order_by("depreciacao.fipe_mes_referencia", "asc")
			->order_by("depreciacao.fipe_ano_referencia", "desc")
			->order_by('depreciacao.id_ativo_veiculo_depreciacao', 'desc')
			->group_by('depreciacao.id_ativo_veiculo_depreciacao');
	}

	public function get_ativo_veiculo_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao){
		$depreciacao = $this->depreciacao_query($id_ativo_veiculo, $id_ativo_veiculo_depreciacao)->get()->row();

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

	//@todo remove
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

    public function permit_add_depreciacao($id_ativo_veiculo){
		$veiculo = $this->get_ativo_veiculo($id_ativo_veiculo);
		return ($veiculo && !$veiculo->codigo_fipe);
	}

	public function permit_edit_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao){
		$depreciacao = $this->depreciacao_query($id_ativo_veiculo, $id_ativo_veiculo_depreciacao)->get()->row();
		return ($this->permit_add_depreciacao($id_ativo_veiculo) && $depreciacao) && 
		$this->permit_delete_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao);
	}
	
	public function permit_update_depreciacao($id_ativo_veiculo, int $mes_atual = null, int $ano_atual = null) {
		if($this->get_ativo_veiculo($id_ativo_veiculo)) {
			$mes = $mes_atual ?? (int) date("m");
			$ano = $ano_atual ?? (int) date("Y");
			$depreciacao = $this->depreciacao_query($id_ativo_veiculo);
			return $depreciacao
			->where("depreciacao.fipe_mes_referencia = {$mes} and depreciacao.fipe_ano_referencia = {$ano}")
			->get()->num_rows() === 0;
		}
		return false;
	}

	public function permit_delete_depreciacao($id_ativo_veiculo, $id_ativo_veiculo_depreciacao){
		$now = date("Y-m-d H:i:s");
		$depreciacao = $this->depreciacao_query($id_ativo_veiculo);
		return $depreciacao
			->where("depreciacao.id_ativo_veiculo_depreciacao > '{$id_ativo_veiculo_depreciacao}'")
			->where("depreciacao.data < '{$now}'")
			->order_by("depreciacao.fipe_ano_referencia", "desc")
			->order_by("depreciacao.fipe_mes_referencia", "desc")
			->get()->num_rows() === 0;
	}
}