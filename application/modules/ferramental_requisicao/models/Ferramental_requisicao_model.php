<?php 

class ferramental_requisicao_model extends MY_Model {

	public function __construct()
	{
		$this->log = new Syslog();
	}

	# Lista dos ativos externos
	function get_ativo_externo_lista()
	{
		$this->db->order_by('nome', 'asc');
		return $this->db->get('v_itens')->result();
	}

	# Salvar Requisicao
	public function salvar_formulario($data)
	{
		$this->db->insert('ativo_externo_requisicao', $data);
		return $this->db->insert_id();
	}

	# Listagem
	public function get_lista($user=null)
	{
		$requisicoes =  $this->db->select('requisicao.*, ob.codigo_obra, ob.endereco, 
		ob.endereco_numero, ob.responsavel, ob.responsavel_celular, ob.responsavel_email, us.usuario, us.id_usuario')
		->from('ativo_externo_requisicao requisicao')
		->join('obra ob', 'ob.id_obra=requisicao.id_obra')
		->join('usuario us', "us.id_usuario = requisicao.id_usuario");

		if ($user && $user->nivel == 2) {
			$requisicoes->where("requisicao.id_usuario = {$user->id_usuario}");
		}

		return $requisicoes->group_by('requisicao.id_requisicao')->get()->result();
	}


	public function get_requisicao($id_requisicao, $user)
	{
			$requisicao = $this->db->select(
				'requisicao.*, ob.codigo_obra, ob.endereco, 
				ob.endereco_numero, ob.responsavel, ob.responsavel_celular, 
				ob.responsavel_email, us.usuario as usuario_solicitante, us.id_usuario'
			)->from('ativo_externo_requisicao requisicao')
			->where("requisicao.id_requisicao={$id_requisicao}")
			->join('obra ob', 'ob.id_obra=requisicao.id_obra');

			switch ($user->nivel) {
				case 1:
					$requisicao->join('usuario us', 'us.id_usuario=requisicao.id_usuario');
				break;
				case 2:
					$requisicao->join('usuario us', "us.id_usuario={$user->id_usuario}");
				break;
			}

			return $requisicao->group_by('requisicao.id_requisicao')->get()->row();
	}

	public function get_requisicao_itens($id_requisicao, $status=null){
		$itens = $this->db
		->select('item.*, atv.id_ativo_externo, atv.nome')
		->from('ativo_externo_requisicao_item item')
		->where("item.id_requisicao={$id_requisicao}");

		switch ($status) {
			case 1:
				$itens->where('item.quantidade != item.quantidade_liberada')
							->or_where('item.quantidade_liberada < item.quantidade')
							->where("item.id_requisicao={$id_requisicao}");
			break;

			case 2:
				$itens->where('item.quantidade = item.quantidade_liberada')
							->or_where('item.quantidade_liberada > 0')
							->where("item.id_requisicao={$id_requisicao}");
			break;
		}

		return $itens->join('ativo_externo atv', 'atv.id_ativo_externo=item.id_ativo_externo')
		->group_by('item.id_requisicao')
		->get()
		->result();
	}

	public function get_itens_estoque($id_obra, $itens_requisicao = []){
		$itens_estoque = [];
		$itens_estoque_array = array_map(
			function($item) {return "'{$item->nome}'";}, 
			$itens_requisicao
		);

		if (count($itens_estoque_array) > 0) {
			foreach($itens_estoque_array as $item) {
				$itens_estoque[str_replace("'", "", $item)] = $this->db
					->select('item.*')
					->from('ativo_externo item')
					->where("item.id_obra = {$id_obra}")
					->where("item.nome IN (".implode(',', $itens_estoque_array).")")
					->group_by('item.id_ativo_externo')
					->get()
					->num_rows();
			}
		}
		return $itens_estoque;
	}


	//public function get_requisicao($id_requisicao)
	// 	# Dados da Requisição
	// 	$consulta = $this->db
	// 						->select('
	// 								c1.id_requisicao,	
	// 								c1.id_obra, 
	// 								c1.id_usuario, 
	// 								c1.data_inclusao, 
	// 								c1.status,

	// 								c2.codigo_obra,
	// 								c2.endereco,

	// 								c3.usuario as usuario_solicitante,

	// 								c4.texto as status_texto,
	// 								c4.classe


	// 						')
	// 						->where('c1.id_requisicao', $id_requisicao)
	// 						->join('obra as c2', 'c2.id_obra=c1.id_obra')
	// 						->join('usuario as c3', 'c3.id_usuario=c1.id_usuario')
	// 						->join('ativo_externo_requisicao_status as c4', 'c4.id_requisicao_status=c1.status')
	// 						->get('ativo_externo_requisicao as c1')
	// 						->row();

	// 	# Dados dos itens solicitados dentro da requisição
	// 	$consulta->itens = $this->db
	// 						->select('
	// 									c1.id_requisicao,
	// 									c1.id_requisicao_item,
	// 									c1.id_ativo_externo,
	// 									c1.quantidade, 
	// 									c1.quantidade_liberada, 
	// 									c1.data_liberado,
	// 									c1.status,

	// 									c2.nome as item,
	// 									c2.codigo,

	// 									c3.texto as status_texto,
	// 									c3.classe, 

	// 									(
	// 										SELECT count(id_ativo_externo) 
	// 										FROM ativo_externo 
	// 										WHERE id_ativo_externo_requisicao_item = 0 
	// 										AND nome = c2.nome
	// 									) as total_estoque

	// 						')
	// 						->join('ativo_externo as c2', 'c2.id_ativo_externo=c1.id_ativo_externo')
	// 						->join('ativo_externo_requisicao_status as c3', 'c3.id_requisicao_status=c1.status')
	// 						->where('c1.id_requisicao', $id_requisicao)
	// 						->where('c1.quantidade_liberada', 0)
	// 						->get('ativo_externo_requisicao_item as c1')
	// 						->result(); 

  //       if(!$consulta)
  //           {
  //               $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
  //               echo redirect(base_url('ferramental_requisicao')); 
  //           }		

  //       # Saída
	// 	return $consulta;
	// }

	// public function get_requisicao_item($id_requisicao)
	// {
	// 	$consulta = $this->db
	// 						->select('
	// 								c1.id_requisicao,	
	// 								c1.id_obra, 
	// 								c1.id_usuario, 
	// 								c1.data_inclusao, 
	// 								c1.status,

	// 								c2.codigo_obra,
	// 								c2.endereco,

	// 								c3.usuario as usuario_solicitante,

	// 								c4.texto as status_texto,
	// 								c4.classe


	// 						')
	// 						->where('c1.id_requisicao', $id_requisicao)
	// 						->join('obra as c2', 'c2.id_obra=c1.id_obra')
	// 						->join('usuario as c3', 'c3.id_usuario=c1.id_usuario')
	// 						->join('ativo_externo_requisicao_status as c4', 'c4.id_requisicao_status=c1.status')
	// 						->get('ativo_externo_requisicao as c1')
	// 						->row(); // sempre será uma só por pesquisa

	// 						//echo $this->db->last_query()."<br>";
	// 	#print_r($consulta);
	// 	#echo $id_requisicao;
	// 	#die();
		

	// 	if($consulta){

	// 		$consulta->itens = $this->db
	// 							->select('quantidade, quantidade_liberada, data_liberado, nome, c3.texto as status_item, c3.classe as status_item_classe, c2.codigo')
	// 							->where('c1.id_requisicao', $id_requisicao)
	// 							->join('ativo_externo_requisicao_status as c3', 'c3.id_requisicao_status=c1.status')
	// 							->join('ativo_externo as c2', 'c2.id_ativo_externo=c1.id_ativo_externo')
	// 							->get('ativo_externo_requisicao_item as c1')
	// 							->result();

  //       }	


  //       if(!$consulta)
  //           {
  //               $this->session->set_flashdata('msg_erro', "Esta requisição ainda não foi liberada pelo administrador.");
  //               echo redirect(base_url('ferramental_requisicao')); 
  //           }	



	// 		return $consulta;


	// 		echo "<pre>";
	// 		print_r($consulta);
	// 		die();
	// }



	public function get_lista_condicao()
	{
		$this->db->order_by("id_requisicao_status", "ASC");
		return $this->db->get('ativo_externo_requisicao_status')->result();
	}


	public function get_requisicao_manual($id_requisicao)
	{


		$consulta = $this->db
							->where('id_requisicao', $id_requisicao)
							->get('ativo_externo_requisicao')
							->row();

		$consulta->ativo = $this->db
									->select('id_ativo_externo, id_requisicao_item')
									->where('id_requisicao', $id_requisicao)
									->get('ativo_externo_requisicao_item')
									->row();
		/*
		$consulta->itens = $this->db
									->select('c1.*, c2.nome as titulo_item')
									->join('ativo_externo as c2', 'c2.id_ativo_externo=c1.id_ativo_externo')
									->where('c1.id_requisicao', $id_requisicao)
									->get('ativo_externo_requisicao_item as c1')
									->result();*/


									
		$consulta->itens = $this->db
									//->where('id_ativo_externo', $consulta->ativo->id_ativo_externo)
									->select('c1.*, c2.observacoes, c2.status as status_item')
									->join('ativo_externo_obra as c2', 'c2.id_ativo_externo=c1.id_ativo_externo', 'LEFT')
									->where('c1.id_ativo_externo', $consulta->ativo->id_ativo_externo)

									->get('ativo_externo as c1')
									->result();

		return $consulta;

		echo "<pre>";
		echo $this->db->last_query();
		echo "<br>";
		print_r($consulta);
		die();




	}
}