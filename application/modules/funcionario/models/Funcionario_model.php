<?php 

class funcionario_model extends MY_Model {

	public function salvar_formulario($data=null){
		if($data['id_funcionario']==''){

			// Salvar LOG
			$this->salvar_log(3, null, 'adicionar', $data);

			$this->db->insert('funcionario', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_funcionario', $data['id_funcionario']);
			$this->db->update('funcionario', $data);

			// Salvar LOG
			$this->salvar_log(3, $data['id_funcionario'], 'editar', $data);

			return "salvar_ok";
		}
	}

	public function query($id_empresa = null, $id_obra = null) : \CI_DB_mysqli_driver
	{
		$this->db->reset_query();
		$funcionarios = $this->db->from('funcionario fn')->select('fn.*');

		$this->join_empresa($funcionarios, 'fn.id_empresa');
		$this->join_obra($funcionarios, 'fn.id_obra');

		if ($id_empresa) {
			$funcionarios->where("fn.id_empresa = {$id_empresa}");
		}

		if ($id_obra) {
			$funcionarios->where("fn.id_obra = {$id_obra}");
		}
		return $funcionarios;
	}

	public function count($id_empresa = null, $id_obra = null, $situacao = null){
		$funcionarios = $this->query($id_empresa, $id_obra);

		if ($situacao) {
			if(is_array($situacao)) {
				$funcionarios->where("fn.situacao IN (".implode(',',$situacao).")");
			} else {
				$funcionarios->where("fn.situacao = {$situacao}");
			}
		}

		return $funcionarios->get()->num_rows();
	}

	public function get_lista($id_empresa = null, $id_obra = null, $situacao = null){
		$funcionarios = $this->query($id_empresa, $id_obra);

		if ($situacao) {
			if(is_array($situacao)) {
				$funcionarios->where("fn.situacao IN (".implode(',',$situacao).")");
			} else {
				$funcionarios->where("fn.situacao = {$situacao}");
			}
		}

		return $funcionarios->order_by('nome', 'ASC')->get()->result();
	}

	public function get_funcionario($id_funcionario=null){
		$this->db->where('id_funcionario', $id_funcionario);
		$funcionario = $this->db->get('funcionario')->row();
		return $funcionario;
	}

	public function search_funcionarios($id_empresa = null, $id_obra = null, $search = null)
	{
		$funcionarios = $this->query($id_empresa, $id_obra);

		if($search = $this->input->get('search') ?? $search) {
			$funcionarios->like("fn.id_funcionario", $search)
				->or_like("fn.nome", $search)
				->or_like("fn.rg", $search)
				->or_like("fn.cpf", $search)
				->or_like("fn.matricula", $search);
		}

		return [
			"query"=> $funcionarios, 
			"after" => function(&$row) {
				$row->retiradas = [];
			}
		];
	}

}