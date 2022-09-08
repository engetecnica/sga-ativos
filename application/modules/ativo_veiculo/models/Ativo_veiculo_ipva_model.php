<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_ipva_model {
	//@todo remove
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

	public function ipva_query($id_ativo_veiculo = null, $id_ativo_veiculo_ipva = null){
		$this->db->reset_query();

		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = ipva.id_ativo_veiculo AND tipo = 'ipva' 
			AND id_modulo_subitem = ipva.id_ativo_veiculo_ipva ORDER BY id_anexo DESC LIMIT 1";

		$query = $this->db
			->from('ativo_veiculo_ipva ipva')
			->select('ipva.*')
			->select("($select_anexo) as comprovante")
			->join("anexo", "anexo.id_modulo_item = ipva.id_ativo_veiculo");


		if (is_array($id_ativo_veiculo)) {
			$query->where("ipva.id_ativo_veiculo IN ('".implode(',', $id_ativo_veiculo)."')");
		} else {
			$query->where("ipva.id_ativo_veiculo = {$id_ativo_veiculo}");
		}

		if ($id_ativo_veiculo_ipva) {
			$query->where("ipva.id_ativo_veiculo_ipva = {$id_ativo_veiculo_ipva}");
		}

		$this->join_veiculo($query, 'ipva.id_ativo_veiculo');
		
		return $query
				->order_by('ipva.id_ativo_veiculo_ipva', 'desc')
				->group_by('ipva.id_ativo_veiculo_ipva');
	}

    public function permit_add_ipva($id_ativo_veiculo, $ano){
		$ipva = $this->ipva_query($id_ativo_veiculo);
		return $ipva 
			->where("ipva.ipva_ano = '{$ano}'")
			->get()->num_rows() === 0;
	}

	public function permit_edit_ipva($id_ativo_veiculo, $id_ativo_veiculo_ipva){
		$ipva = $this->ipva_query($id_ativo_veiculo, $id_ativo_veiculo_ipva)->get()->row();
		return !$ipva->comprovante || $this->permit_delete_ipva($id_ativo_veiculo, $id_ativo_veiculo_ipva);
	}

	public function permit_delete_ipva($id_ativo_veiculo, $id_ativo_veiculo_ipva){
		$ipva = $this->ipva_query($id_ativo_veiculo);
		return $ipva
			->where("ipva.id_ativo_veiculo_ipva > '{$id_ativo_veiculo_ipva}'")
			->get()->num_rows() === 0;
	}
}