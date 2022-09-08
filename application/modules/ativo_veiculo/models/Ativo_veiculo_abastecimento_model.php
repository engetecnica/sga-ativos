<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_abastecimento_model {
	//@todo remove
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

	public function abastecimento_query($id_ativo_veiculo = null, $id_ativo_veiculo_abastecimento = null){
		$this->db->reset_query();

		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = {$id_ativo_veiculo} AND tipo = 'abastecimento' 
			AND id_modulo_subitem = abastecimento.id_ativo_veiculo_abastecimento ORDER BY id_anexo DESC LIMIT 1";

		$query = $this->db
			->from('ativo_veiculo_abastecimento abastecimento')
			->select('abastecimento.*')
			->select("($select_anexo) as comprovante")
			->join("anexo", "anexo.id_modulo_item = abastecimento.id_ativo_veiculo");


		if (is_array($id_ativo_veiculo)) {
			$query->where("abastecimento.id_ativo_veiculo IN ('".implode(',', $id_ativo_veiculo)."')");
		} else {
			$query->where("abastecimento.id_ativo_veiculo = {$id_ativo_veiculo}");
		}

		if ($id_ativo_veiculo_abastecimento) {
			$query->where("abastecimento.id_ativo_veiculo_abastecimento = {$id_ativo_veiculo_abastecimento}");
		}

		$this->join_veiculo($query, 'abastecimento.id_ativo_veiculo');
		$this->join_fornecedor($query, 'abastecimento.id_fornecedor');
		
		return $query
				->order_by('abastecimento.id_ativo_veiculo_abastecimento', 'desc')
				->group_by('abastecimento.id_ativo_veiculo_abastecimento');
	}
	
    public function permit_edit_abastecimento($id_ativo_veiculo, $id_ativo_veiculo_abastecimento){
		$abastecimento = $this->abastecimento_query($id_ativo_veiculo, $id_ativo_veiculo_abastecimento)->get()->row();
		return !$abastecimento->comprovante || $this->permit_delete_abastecimento($id_ativo_veiculo, $id_ativo_veiculo_abastecimento);
	}

	public function permit_delete_abastecimento($id_ativo_veiculo, $id_ativo_veiculo_abastecimento){
		$abastecimento = $this->abastecimento_query($id_ativo_veiculo, $id_ativo_veiculo_abastecimento);
		return $abastecimento->get()->num_rows() === 0;
	}
}