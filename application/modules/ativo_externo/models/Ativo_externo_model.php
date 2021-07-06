<?php 

class Ativo_externo_model extends MY_Model {

	public function salvar_formulario($data=null){

		if($data['id_ativo_externo']==''){
			$this->db->insert('ativo_externo', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_ativo_externo', $data['id_ativo_externo']);
			$this->db->update('ativo_externo', $data);
			return "salvar_ok";
		}

	}

	public function get_lista(){
		return $this->db->get('v_ativo_externo')->result();
	}

	public function get_lista_verificada($id_ativo_externo)
	{

		$k = 0;
		$obra = $this->db->get('obra')->result();

		foreach($obra as $valor)
		{

			//$arr[$k] = array();

			$this->db->where('id_ativo_externo', $id_ativo_externo);			
			$arr[$k]['item'] = $this->db->get('ativo_externo')->row('nome'); 

			$this->db->where('nome', $arr[$k]['item']);
			$this->db->where('condicao', 'Em Operação');
			$this->db->where('id_obra', $valor->id_obra);
			$arr[$k]['emuso'] = $this->db->get('ativo_externo')->num_rows();

			$this->db->where('nome', $arr[$k]['item']);
			$this->db->where('condicao', 'Liberado');
			$this->db->where('id_obra', $valor->id_obra);
			$arr[$k]['liberado'] = $this->db->get('ativo_externo')->num_rows();

			$this->db->where('nome', $arr[$k]['item']);
			$this->db->where('condicao', 'Liberado');
			$this->db->where('id_obra', $valor->id_obra);
			$this->db->where('situacao', '10'); // Fora de Operação
			$arr[$k]['foradeoperacao'] = $this->db->get('ativo_externo')->num_rows();

			$arr[$k]['obra'] = $valor->codigo_obra;	

			$k++;
		}	

		return $arr;

	}

	public function get_ativo_externo($id_ativo_externo=null){
		$this->db->where('id_ativo_externo', $id_ativo_externo);
		$ativo_externo = $this->db->get('ativo_externo')->row();
		return $ativo_externo;
	}

	# Busca da Obra
	public function get_obra(){
		return $this->db->get('obra')->result();
	}	

	# Busca Categoria
	public function get_categoria(){
		$this->db->order_by('nome', 'asc');
		return $this->db->get('ativo_externo_categoria')->result();
	}	
}