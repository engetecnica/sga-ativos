<?php 

class funcionario_model extends MY_Model {

	public function salvar_formulario($data=null){

		if($data['id_funcionario']==''){
			$this->db->insert('funcionario', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_funcionario', $data['id_funcionario']);
			$this->db->update('funcionario', $data);
			return "salvar_ok";
		}

	}

	public function funcionarios($id_empresa = null, $id_obra = null)
	{
		$funcionarios = $this->db
			->from('funcionario fn')->select('*')
			->join("obra ob", "ob.id_obra = fn.id_obra")
			->select("ob.codigo_obra as codigo_obra, ob.endereco as obra_endereco", "left")
			->join("empresa ep", "ep.id_empresa = fn.id_empresa", "left")
			->select("ep.razao_social as empresa_social, ep.nome_fantasia as empresa")
			->order_by('fn.nome', 'ASC');

		if ($id_empresa) {
			$funcionarios->where("fn.id_empresa = {$id_empresa}");
		}

		if ($id_obra) {
			$funcionarios->where("fn.id_obra = {$id_obra}");
		}
		return $funcionarios;
	}

	public function get_lista($id_empresa = null, $id_obra = null, $situacao = null){
		$funcionarios = $this->funcionarios($id_empresa, $id_obra);

		if ($situacao) {
			if(is_array($situacao)) {
				$funcionarios->where("fn.situacao IN (".implode(',',$situacao).")");
			} else {
				$funcionarios->where("fn.situacao = {$situacao}");
			}
		}

		return $funcionarios->get()->result();
	}

	public function get_funcionario($id_funcionario=null){
		$this->db->where('id_funcionario', $id_funcionario);
		$funcionario = $this->db->get('funcionario')->row();
		return $funcionario;
	}

	
	public function search_funcionarios($id_empresa = null, $id_obra = null, $search = null){
		$funcionarios = $this->funcionarios($id_empresa, $id_obra);
		
		if($search = $this->input->get('search', null)) {
			$funcionarios->like("fn.id_funcionario", $search)
				->or_like("fn.nome", $search)
				->or_like("fn.rg", $search)
				->or_like("fn.cpf", $search)
				->or_like("fn.matricula", $search);
		}

		return $this->paginate($funcionarios, ['table' => 'fn']);
	}

}