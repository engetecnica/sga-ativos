<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_operacao_model {

    //@todo remove
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

	public function operacao_query($id_ativo_veiculo = null, $id_ativo_veiculo_operacao = null){
		$this->db->reset_query();
		
		$query = $this->db
			->from('ativo_veiculo_operacao operacao')
			->select('operacao.*');


		if (is_array($id_ativo_veiculo)) {
			$query->where("operacao.id_ativo_veiculo IN ('".implode(',', $id_ativo_veiculo)."')");
		} else {
			$query->where("operacao.id_ativo_veiculo = {$id_ativo_veiculo}");
		}

		if ($id_ativo_veiculo_operacao) {
			$query->where("operacao.id_ativo_veiculo_operacao = {$id_ativo_veiculo_operacao}");
		}

		$this->join_veiculo($query, 'operacao.id_ativo_veiculo');
		
		return $query
			->order_by('operacao.id_ativo_veiculo_operacao', 'desc')
			->group_by('operacao.id_ativo_veiculo_operacao');
	}

    public function get_operacao_saldo($id_ativo_veiculo){
		return $this->get_extrato("operacao", $id_ativo_veiculo, false);
	}

	
	public function permit_edit_operacao($id_ativo_veiculo, $id_ativo_veiculo_operacao){
		$operacoes = $this->operacao_query($id_ativo_veiculo)->limit(5)->get()->result();
		if (count($operacoes) > 0) { 
			$operacao = end($operacoes);
			return !$operacao->id_ativo_veiculo_operacao == $id_ativo_veiculo_operacao || $this->permit_delete_operacao($id_ativo_veiculo, $id_ativo_veiculo_operacao);
		}
		return false;
	}

	public function permit_delete_operacao($id_ativo_veiculo, $id_ativo_veiculo_operacao){
		return $this->operacao_query($id_ativo_veiculo)
			->where("operacao.id_ativo_veiculo_operacao > '{$id_ativo_veiculo_operacao}'")
			->order_by('operacao.id_ativo_veiculo_operacao', 'desc')
			->get()->num_rows() === 0;
	}
}