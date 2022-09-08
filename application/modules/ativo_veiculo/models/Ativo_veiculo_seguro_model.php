<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_seguro_model {
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
	
	public function seguro_query($id_ativo_veiculo = null, $id_ativo_veiculo_seguro = null){
		$this->db->reset_query();

		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = seguro.id_ativo_veiculo AND tipo = 'seguro' 
			AND id_modulo_subitem = seguro.id_ativo_veiculo_seguro ORDER BY id_anexo DESC LIMIT 1";

		$query = $this->db
			->from('ativo_veiculo_seguro seguro')
			->select('seguro.*')
			->select("($select_anexo) as comprovante")
			->join("anexo", "anexo.id_modulo_item = seguro.id_ativo_veiculo");


		if (is_array($id_ativo_veiculo)) {
			$query->where("seguro.id_ativo_veiculo IN ('".implode(',', $id_ativo_veiculo)."')");
		} else {
			$query->where("seguro.id_ativo_veiculo = {$id_ativo_veiculo}");
		}

		if ($id_ativo_veiculo_seguro) {
			$query->where("seguro.id_ativo_veiculo_seguro = {$id_ativo_veiculo_seguro}");
		}

		$this->join_veiculo($query, 'seguro.id_ativo_veiculo');
		
		return $query
				->order_by('seguro.id_ativo_veiculo_seguro', 'desc')
				->group_by('seguro.id_ativo_veiculo_seguro');
	}

    public function permit_edit_seguro($id_ativo_veiculo, $id_ativo_veiculo_seguro){
		$seguro = $this->seguro_query($id_ativo_veiculo, $id_ativo_veiculo_seguro)->get()->row();
		return !$seguro->comprovante || $this->permit_delete_seguro($id_ativo_veiculo, $id_ativo_veiculo_seguro);
	}

	public function permit_delete_seguro($id_ativo_veiculo, $id_ativo_veiculo_seguro){
		$now = date("Y-m-d");
		return $this->seguro_query($id_ativo_veiculo)
			->where("seguro.id_ativo_veiculo_seguro > '{$id_ativo_veiculo_seguro}'")
			->where("seguro.carencia_fim > '{$now}'")
			->get()->num_rows() === 0;
	}
}