<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_quilometragem_model {
    	//@todo remove
	public function get_ativo_veiculo_km_lista($id_ativo_veiculo, $limit = null){
		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = {$id_ativo_veiculo} AND tipo = 'quilometragem' 
			AND id_modulo_subitem = ativo_veiculo_quilometragem.id_ativo_veiculo_quilometragem ORDER BY id_anexo DESC LIMIT 1";

		//ativo_veiculo_km_query
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

	public function km_query($id_ativo_veiculo = null, $id_ativo_veiculo_quilometragem = null){
		$this->db->reset_query();

		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = {$id_ativo_veiculo} AND tipo = 'quilometragem' 
			AND id_modulo_subitem = km.id_ativo_veiculo_quilometragem ORDER BY id_anexo DESC LIMIT 1";

		$query = $this->db
			->from('ativo_veiculo_quilometragem km')
			->select('km.*')
			->select("($select_anexo) as comprovante")
			->join("anexo", "anexo.id_modulo_item = km.id_ativo_veiculo");


		if (is_array($id_ativo_veiculo)) {
			$query->where("km.id_ativo_veiculo IN ('".implode(',', $id_ativo_veiculo)."')");
		} else {
			$query->where("km.id_ativo_veiculo = {$id_ativo_veiculo}");
		}

		if ($id_ativo_veiculo_quilometragem) {
			$query->where("km.id_ativo_veiculo_quilometragem = {$id_ativo_veiculo_quilometragem}");
		}

		$this->join_veiculo($query, 'km.id_ativo_veiculo');
		
		return $query
				->order_by('km.id_ativo_veiculo_quilometragem', 'desc')
				->group_by('km.id_ativo_veiculo_quilometragem');
	}

	public function get_km_saldo($id_ativo_veiculo){
		return $this->get_extrato("km", $id_ativo_veiculo, false);
	}

    public function permit_edit_km($id_ativo_veiculo, $id_ativo_veiculo_quilometragem){
		$quilometragem = $this->km_query($id_ativo_veiculo, $id_ativo_veiculo_quilometragem)->get()->row();
		return !$quilometragem->comprovante || $this->permit_delete_km($id_ativo_veiculo, $id_ativo_veiculo_quilometragem);
	}

	public function permit_delete_km($id_ativo_veiculo, $id_ativo_veiculo_quilometragem){
		return 	$this->km_query($id_ativo_veiculo)
			->where("km.id_ativo_veiculo_quilometragem > '{$id_ativo_veiculo_quilometragem}'")
			->get()->num_rows() === 0;
	}
}