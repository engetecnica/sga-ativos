<?php 

class Ativo_configuracao_model extends MY_Model {

	public function salvar_formulario($data=null){
		if($data['id_ativo_configuracao']==''){

			//Salvar LOG
			$this->salvar_log(21, null, 'adicionar', $data);

			$this->db->insert('ativo_configuracao', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_ativo_configuracao', $data['id_ativo_configuracao'])
								->update('ativo_configuracao', $data);

			//Salvar LOG
			$this->salvar_log(21, $data['id_ativo_configuracao'], 'editar', $data);

			return "salvar_ok";
		}
	}

	public function get_categoria_lista($situacao=null){
		$lista = $this->db->where('id_ativo_configuracao_vinculo', 0)
												->from('ativo_configuracao ac');
		if($situacao) {
			if (is_array($situacao)) {
				$lista->where("ac.situacao IN ('".implode(',', $situacao)."')");
			} else {
				$lista->where("ac.situacao = {$situacao}");
			}
		}
		return $lista->order_by('titulo', 'ASC')
							->get()
							->result();
	}

	//@to-do remove
	public function get_lista($situacao=null){
		$lista = $this->db
				->select('ativo_configuracao.*, ac.titulo as id_ativo_configuracao_vinculo')
				->join("ativo_configuracao as ac", 'ac.id_ativo_configuracao=ativo_configuracao.id_ativo_configuracao_vinculo', "left");
				
		if($situacao) {
			if (is_array($situacao)) {
				$lista->where("ac.situacao IN ('".implode(',', $situacao)."')");
			} else {
				$lista->where("ac.situacao = {$situacao}");
			}
		}
				
		return $lista->order_by('ativo_configuracao.id_ativo_configuracao', 'desc')
								->get('ativo_configuracao')->result();
	}

	public function query($situacao=null){
		$this->db->reset_query();
		$query = $this->db
				->from('ativo_configuracao as configuracao')
				->select('configuracao.*, ac.titulo as id_ativo_configuracao_vinculo')
				->join("ativo_configuracao as ac", 'ac.id_ativo_configuracao = configuracao.id_ativo_configuracao_vinculo', "left");
				
		if($situacao) {
			if (is_array($situacao)) {
				$query->where("ac.situacao IN ('".implode(',', $situacao)."')");
			} else {
				$query->where("ac.situacao = {$situacao}");
			}
		}	
		return $query;
	}

	public function get_ativo_configuracao($id_ativo_configuracao){
		return $this->db
					->where('id_ativo_configuracao', $id_ativo_configuracao)
					->get('ativo_configuracao')->row();
	}
}