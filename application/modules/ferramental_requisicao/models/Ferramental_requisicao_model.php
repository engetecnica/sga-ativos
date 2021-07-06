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
	public function get_lista()
	{

		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));

		if($this->session->userdata('logado')->nivel > 1)
		{
			$this->db->where('id_obra', $this->session->userdata('logado')->obra->id_obra);
		}		
		$consulta = $this->db->get('v_requisicao')->result();

		$data = [];

		foreach($consulta as $r) {	

			$botao = "<button class='btn-sm btn-".$r->classe."' type='button'>".$r->status."</button>";
			$options = "<a href=".base_url('ferramental_requisicao/detalhes/').$r->id_requisicao."><button name='detalhar-solicitacao' class='btn-sm btn-info'>Detalhes da Solicitação</button></a>";

			$data[] = array(
				$r->data_inclusao,
				$r->codigo_obra." - ".$r->endereco." - ".$r->endereco_cidade,
				$r->usuario,
				$botao,
				$options
			);

		}


		$result = array(
			"draw" => $draw,
			"recordsTotal" => count($consulta),
			"recordsFiltered" => count($consulta),
			"data" => $data
		);

		echo json_encode($result);
		exit();
	}


	public function get_requisicao($id_requisicao)
	{


		# Dados da Requisição
		$consulta = $this->db
							->select('
									c1.id_requisicao,	
									c1.id_obra, 
									c1.id_usuario, 
									c1.data_inclusao, 
									c1.status,

									c2.codigo_obra,
									c2.endereco,

									c3.usuario as usuario_solicitante,

									c4.texto as status_texto,
									c4.classe


							')
							->where('c1.id_requisicao', $id_requisicao)
							->join('obra as c2', 'c2.id_obra=c1.id_obra')
							->join('usuario as c3', 'c3.id_usuario=c1.id_usuario')
							->join('ativo_externo_requisicao_status as c4', 'c4.id_requisicao_status=c1.status')
							->get('ativo_externo_requisicao as c1')
							->row();

		# Dados dos itens solicitados dentro da requisição
		$consulta->itens = $this->db
							->select('
										c1.id_requisicao,
										c1.id_requisicao_item,
										c1.id_ativo_externo,
										c1.quantidade, 
										c1.quantidade_liberada, 
										c1.data_liberado,
										c1.status,

										c2.nome as item,
										c2.codigo,

										c3.texto as status_texto,
										c3.classe, 

										(
											SELECT count(id_ativo_externo) 
											FROM ativo_externo 
											WHERE id_ativo_externo_requisicao_item = 0 
											AND nome = c2.nome
										) as total_estoque

							')
							->join('ativo_externo as c2', 'c2.id_ativo_externo=c1.id_ativo_externo')
							->join('ativo_externo_requisicao_status as c3', 'c3.id_requisicao_status=c1.status')
							->where('c1.id_requisicao', $id_requisicao)
							->where('c1.quantidade_liberada', 0)
							->get('ativo_externo_requisicao_item as c1')
							->result(); 

        if(!$consulta)
            {
                $this->session->set_flashdata('msg_erro', "Requisição não localizada.");
                echo redirect(base_url('ferramental_requisicao')); 
            }		

        # Saída
		return $consulta;
	}

	public function get_requisicao_item($id_requisicao)
	{


		$consulta = $this->db
							->select('
									c1.id_requisicao,	
									c1.id_obra, 
									c1.id_usuario, 
									c1.data_inclusao, 
									c1.status,

									c2.codigo_obra,
									c2.endereco,

									c3.usuario as usuario_solicitante,

									c4.texto as status_texto,
									c4.classe


							')
							->where('c1.id_requisicao', $id_requisicao)
							->join('obra as c2', 'c2.id_obra=c1.id_obra')
							->join('usuario as c3', 'c3.id_usuario=c1.id_usuario')
							->join('ativo_externo_requisicao_status as c4', 'c4.id_requisicao_status=c1.status')
							->get('ativo_externo_requisicao as c1')
							->row(); // sempre será uma só por pesquisa

							//echo $this->db->last_query()."<br>";
		#print_r($consulta);
		#echo $id_requisicao;
		#die();
		

		if($consulta){

			$consulta->itens = $this->db
								->select('quantidade, quantidade_liberada, data_liberado, nome, c3.texto as status_item, c3.classe as status_item_classe, c2.codigo')
								->where('c1.id_requisicao', $id_requisicao)
								->join('ativo_externo_requisicao_status as c3', 'c3.id_requisicao_status=c1.status')
								->join('ativo_externo as c2', 'c2.id_ativo_externo=c1.id_ativo_externo')
								->get('ativo_externo_requisicao_item as c1')
								->result();

        }	


        if(!$consulta)
            {
                $this->session->set_flashdata('msg_erro', "Esta requisição ainda não foi liberada pelo administrador.");
                echo redirect(base_url('ferramental_requisicao')); 
            }	



			return $consulta;


			echo "<pre>";
			print_r($consulta);
			die();

	}


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