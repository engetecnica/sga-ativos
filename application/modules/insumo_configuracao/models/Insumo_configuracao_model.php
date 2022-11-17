<?php 

class Insumo_configuracao_model extends MY_Model {

	public $tipo_medicao;

	public function __construct()
	{
		parent::__construct();
		$this->tipo_medicao = $this->config->item('insumos_tipo_medicao');
	}

	public function salvar_formulario($data=null){
		if($data['id_insumo_configuracao']==''){
			$this->db->insert('insumo_configuracao', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_insumo_configuracao', $data['id_insumo_configuracao'])
								->update('insumo_configuracao', $data);
			return "salvar_ok";
		}
	}

	public function get_categoria_lista($situacao=null){
		$lista = $this->db->where('id_insumo_configuracao_vinculo', 0)
												->from('insumo_configuracao ac');
		if($situacao) {
			if (is_array($situacao)) {
				$lista->where("ac.situacao IN ('".implode(',', $situacao)."')");
			} else {
				$lista->where("ac.situacao = {$situacao}");
			}
		}
		return $lista->order_by('titulo', 'ASC')
							->get()
							->result();
	}

	public function get_lista($situacao=null){
		$lista = $this->db
				->select('insumo_configuracao.*, ac.titulo as id_insumo_configuracao_vinculo')
				->join("insumo_configuracao as ac", 'ac.id_insumo_configuracao=insumo_configuracao.id_insumo_configuracao_vinculo', "left");
				
		if($situacao) {
			if (is_array($situacao)) {
				$lista->where("ac.situacao IN ('".implode(',', $situacao)."')");
			} else {
				$lista->where("ac.situacao = {$situacao}");
			}
		}
				
		return $lista->order_by('insumo_configuracao.id_insumo_configuracao', 'desc')
								->get('insumo_configuracao')->result();
	}

	public function get_insumo_configuracao($id_insumo_configuracao){
		return $this->db
					->where('id_insumo_configuracao', $id_insumo_configuracao)
					->get('insumo_configuracao')->row();
	}
}