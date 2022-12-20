<?php 

class Insumo_configuracao_model extends MY_Model {

	public $tipo_medicao;

	public function __construct()
	{
		parent::__construct();
	}

	public function salvar_formulario($data=null){
		if($data['id_insumo_configuracao']==''){

			// salvar LOG
			$this->salvar_log(22, null, 'adicionar', $data);
			
			$this->db->insert('insumo_configuracao', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_insumo_configuracao', $data['id_insumo_configuracao'])
								->update('insumo_configuracao', $data);
						
			// salvar LOG
			$this->salvar_log(22, $data['id_insumo_configuracao'], 'editar', $data);

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

	public function get_lista_principal($situacao=null){
		
		$consulta = $this->db->where('id_insumo_configuracao_vinculo', 0);				
		return $consulta->order_by('id_insumo_configuracao', 'desc')
								->get('insumo_configuracao')->result();
	}

	public function get_lista($situacao=null){
		$lista = $this->db
				->select('insumo_configuracao.*, ac.titulo as id_insumo_configuracao_vinculo, im.titulo as medicao_titulo, im.sigla as medicao_sigla')
				->join("insumo_configuracao as ac", 'ac.id_insumo_configuracao=insumo_configuracao.id_insumo_configuracao_vinculo', "left")
				->join("insumo_medicao as im", "im.id_insumo_medicao=insumo_configuracao.medicao");
				
		if($situacao) {
			if (is_array($situacao)) {
				$lista->where("ac.situacao IN ('".implode(',', $situacao)."')");
			} else {
				$lista->where("ac.situacao = {$situacao}");
			}
		}
		$lista->where('insumo_configuracao.id_insumo_configuracao_vinculo != ', 0);
				
		return $lista->order_by('insumo_configuracao.id_insumo_configuracao', 'desc')
								->get('insumo_configuracao')->result();
	}

	public function get_insumo_configuracao($id_insumo_configuracao){
		return $this->db
					->where('id_insumo_configuracao', $id_insumo_configuracao)
					->get('insumo_configuracao')->row();
	}

	public function get_insumo_lista_completa(){
		$consulta = $this->db
						->where('id_insumo_configuracao_vinculo', 0)
						->order_by('titulo', 'ASC')
						->get('insumo_configuracao')
						->result();

						foreach($consulta as $valor){
							$valor->subitem = $this->db
													->where('id_insumo_configuracao_vinculo', $valor->id_insumo_configuracao)
													->order_by('titulo', 'ASC')
													->get('insumo_configuracao')
													->result();
						}

		return $consulta;
	}

	public function get_tipo_medicao($id=null)
	{

		if($id)
		{
			$consulta = $this->db
				->where('id', $id)
				->get('insumo_medicao')
				->result();
		} else {
			$consulta = $this->db
				->get('insumo_medicao')
				->result();
		}
		return $consulta;
	}
}