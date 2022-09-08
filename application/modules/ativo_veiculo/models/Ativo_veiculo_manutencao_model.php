<?php
# Require AutoLoad
require_once APPPATH . "../vendor/autoload.php";

trait Ativo_veiculo_manutencao_model {
    //@todo remove
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

    //@todo remove
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

    public function manutencao_query($id_ativo_veiculo = null, $id_ativo_veiculo_manutencao = null){
		$this->db->reset_query();

		$select_anexo = "SELECT anexo FROM anexo WHERE id_anexo_pai != NULL AND id_modulo_item = manutencao.id_ativo_veiculo AND tipo = 'ordem_de_servico' 
			AND id_modulo_subitem = manutencao.id_ativo_veiculo_manutencao ORDER BY id_anexo DESC LIMIT 1";

		$query = $this->db
			->from('ativo_veiculo_manutencao manutencao')
			->select('manutencao.*')
			->select("($select_anexo) as ordem_de_servico")
            ->select('config.titulo as servico')
			->join("anexo", "anexo.id_modulo_item = manutencao.id_ativo_veiculo")
            ->join('ativo_configuracao config', 'config.id_ativo_configuracao=manutencao.id_ativo_configuracao');


		if (is_array($id_ativo_veiculo)) {
			$query->where("manutencao.id_ativo_veiculo IN ('".implode(',', $id_ativo_veiculo)."')");
		} else {
			$query->where("manutencao.id_ativo_veiculo = {$id_ativo_veiculo}");
		}

		if ($id_ativo_veiculo_manutencao) {
			$query->where("manutencao.id_ativo_veiculo_manutencao = {$id_ativo_veiculo_manutencao}");
		}

		$this->join_veiculo($query, 'manutencao.id_ativo_veiculo');
        $this->join_fornecedor($query, 'manutencao.id_fornecedor');
		
		return $query
			->order_by('manutencao.id_ativo_veiculo_manutencao', 'desc')
			->group_by('manutencao.id_ativo_veiculo_manutencao');
	}

    public function count_ativo_veiculo_em_manutencao(){
        return $this->manutencao_query()
                ->group_by('manutencao.id_ativo_veiculo')
                ->get()->num_rows();
    }
}