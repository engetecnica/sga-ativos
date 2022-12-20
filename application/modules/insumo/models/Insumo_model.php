<?php 

class Insumo_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->log = new Syslog();
		$this->load->model('ativo_externo/ativo_externo_model'); 
		$this->load->model('insumo_configuracao/insumo_configuracao_model');
		$this->tipo_medicao = $this->config->item('insumos_tipo_medicao');
		$this->tipo_medicao_by_id = $this->config->item('insumos_tipo_medicao_by_id');
	}
	
	public function salvar_formulario($data = []){
		if ($data['id_insumo'] == '') {

			// Salvar LOG
			$this->salvar_log(23, null, 'adicionar', $data);

			$this->db->insert('insumo', $data);
			return $this->db->insert_id();
		}

		$this->db
			->where('id_insumo', $data['id_insumo'])
			->update('insumo', $data);

			$this->salvar_log(23, $data['id_insumo'], 'editar', $data);

	}

	public function query(){
		return $this->db
			->from('insumo ')
			->select('*');
	}

	public function get_insumo($id_insumo=null){
		$query = $this->query()
			->where('id_insumo', $id_insumo)
			->where('id_obra', $this->user->id_obra)
			->get()->row();

			if(!$query) return false;

			$query->entrada = ($this->db
			->join('usuario u', 'u.id_usuario=i.id_usuario', 'left')
			->where('i.id_insumo', $id_insumo)
			->where('i.tipo', 'entrada')
			->get('insumo_estoque i')
			->result()) ?? 0;

			$query->saida = ($this->db
			->join('usuario u', 'u.id_usuario=i.id_usuario', 'left')
			->where('i.id_insumo', $id_insumo)
			->where('i.tipo', 'saida')
			->get('insumo_estoque i')
			->result()) ?? 0;

		return $query;
	}

	public function get_insumos_by_obra($id_obra)
	{

		$consulta = $this->db
						->select('i.*, ic.titulo as id_insumo_configuracao, ic.medicao, im.titulo as medicao_titulo, im.sigla as medicao_sigla, ic.codigo_insumo')
						->join('insumo_configuracao ic', 'ic.id_insumo_configuracao=i.id_insumo_configuracao')
						->join('insumo_medicao im', 'im.id_insumo_medicao=ic.medicao')
						->where('id_obra', $this->user->id_obra)
						->get('insumo i')
						->result();

					foreach($consulta as $valor){
						
						$valor->entrada = ($this->db
							->select('sum(quantidade) as total_entrada')
							->where('id_insumo', $valor->id_insumo)
							->where('tipo', 'entrada')
							->get('insumo_estoque')
							->row('total_entrada')) ?? 0;

						$valor->saida = ($this->db
							->select('sum(quantidade) as total_saida')
							->where('id_insumo', $valor->id_insumo)
							->where('tipo', 'saida')
							->where('status', 0)
							->or_where('status', 1)
							->get('insumo_estoque')
							->row('total_saida')) ?? 0;								
							
					}
				
					return $consulta;
	}	

	public function salvar_insumo_estoque($data)
	{
		if(!$data) return [];
		return $this->db->insert('insumo_estoque', $data);
	}

	public function salvar_insumo_retirada($data)
	{
		if(!$data) return [];
		$this->db->insert('insumo_retirada', $data);
		return $this->db->insert_id();
	}

	public function salvar_insumo_estoque_batch($data)
	{
		if(!$data) return [];
		return $this->db->insert_batch('insumo_estoque', $data);
	}

	public function get_retirada_lista()
	{
		$consulta = $this->db
						->select('r.*, concat(u.nome, " - " , u.usuario) as id_usuario, f.nome as id_funcionario')
						->join('usuario u', 'u.id_usuario=r.id_usuario')
						->join('funcionario f', 'f.id_funcionario=r.id_funcionario')
						->get('insumo_retirada r')
						->result();

						foreach($consulta as &$valor){
							$valor->insumos = $this->db
												->select('ie.*, i.titulo as id_insumo')
												->join('insumo i', 'i.id_insumo=ie.id_insumo')
												->where('ie.id_insumo_retirada', $valor->id_insumo_retirada)
												->where('ie.tipo', 'saida')
												->get('insumo_estoque ie')
												->result();

							$valor->anexo = $this->db
												->where('id_modulo_item', $valor->id_insumo_retirada)
												->where('tipo', 'retirada_insumo')
												->get('anexo')
												->row('anexo');
						}

					

		return $consulta;
	}

	public function get_retirada($id_insumo_retirada){
		if(!$id_insumo_retirada) return false;
		$consulta = $this->db
							->select("i.*, concat(u.nome, ' - ', u.usuario) as id_usuario, f.nome as id_funcionario")
							->join('usuario u', 'u.id_usuario=i.id_usuario')
							->join('funcionario f', 'f.id_funcionario=i.id_funcionario')
							->where('i.id_insumo_retirada', $id_insumo_retirada)
							->get('insumo_retirada i')
							->row();

							if($consulta){

								 	$consulta->estoque = $this->db
														->where('id_insumo_retirada', $id_insumo_retirada)
														->get('insumo_estoque')
														->result();

								
									$consulta->insumos = $this->db
														->select('ie.*, i.titulo as id_insumo, i.id_insumo as id')
														->join('insumo i', 'i.id_insumo=ie.id_insumo')
														->where('ie.id_insumo_retirada', $id_insumo_retirada)
														->where('ie.tipo', 'saida')
														->get('insumo_estoque ie')
														->result();

									if($consulta->insumos)
									{
										foreach($consulta->insumos as $insumo)
										{
											$insumo->devolucao = $this->db
																		->where('id_insumo_retirada', $id_insumo_retirada)
																		->where('id_insumo', $insumo->id)
																		->where('tipo', 'entrada')
																		->get('insumo_estoque')
																		->row();
										}
									}

							}

		return $consulta;
	}

	public function set_estoque_entregue($id_insumo_retirada, $status=null)
	{

		if($id_insumo_retirada == null 
			or $status == null) return false;			

		return $this->db
						->where('id_insumo_retirada', $id_insumo_retirada)
						->update('insumo_estoque', $status);
	}


	public function set_estoque_retirada($id_insumo_retirada, $status=null)
	{

		if($id_insumo_retirada == null 
			or $status == null) return false;			

		return $this->db
						->where('id_insumo_retirada', $id_insumo_retirada)
						->update('insumo_retirada', $status);
	}

	public function cancelar_retirada($id_insumo_retirada, $status=null)
	{

		if($id_insumo_retirada == null 
			or $status == null) return false;			

		return $this->db
						->where('id_insumo_retirada', $id_insumo_retirada)
						->update('insumo_retirada', $status);
	}

	






}