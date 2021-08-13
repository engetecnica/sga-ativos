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


	public function get_lista($id_empresa = null, $id_obra = null, $situacao = null){
		$funcionarios = $this->db->from('funcionario fn')->select('*');

		if ($id_empresa) {
			$funcionarios->where("fn.id_empresa = {$id_empresa}");
		}

		if ($id_obra) {
			$funcionarios->where("fn.id_obra = {$id_obra}");
		}

		if ($situacao) {
			if(is_array($situacao)) {
				$funcionarios->where("fn.situacao IN (".implode(',',$situacao).")");
			} else {
				$funcionarios->where("fn.situacao = {$situacao}");
			}
		}

		return $funcionarios->order_by('fn.nome', 'ASC')->get()->result();
	}

	public function get_funcionario($id_funcionario=null){
		$this->db->where('id_funcionario', $id_funcionario);
		$funcionario = $this->db->get('funcionario')->row();
		return $funcionario;
	}

}