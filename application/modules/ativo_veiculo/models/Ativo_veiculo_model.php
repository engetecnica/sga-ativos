<?php 

class Ativo_veiculo_model extends MY_Model {

	public function salvar_formulario($data=null){

		if($data['id_ativo_veiculo']==''){
			$this->db->insert('ativo_veiculo', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_ativo_veiculo', $data['id_ativo_veiculo']);
			$this->db->update('ativo_veiculo', $data);
			return "salvar_ok";
		}

	}

	public function get_categoria_lista(){
		$this->db->where('id_ativo_veiculo_vinculo', 0);
		$this->db->order_by('titulo', 'ASC');
		return $this->db->get('ativo_veiculo')->result();
	}

	public function get_lista(){
		$this->db->order_by('ativo_veiculo.tipo_veiculo', 'ASC');
		return $this->db->get('ativo_veiculo')->result();
	}

	public function get_ativo_veiculo($id_ativo_veiculo=null){
		$this->db->where('id_ativo_veiculo', $id_ativo_veiculo);
		$ativo_veiculo = $this->db->get('ativo_veiculo')->row();
		return $ativo_veiculo;
	}

	public function get_ativo_veiculo_manutencao_lista($id_ativo_veiculo){
		$this->db->select('ativo_veiculo_manutencao.*, ativo_veiculo.veiculo, ativo_veiculo.veiculo_placa, fornecedor.razao_social as id_fornecedor, ativo_configuracao.titulo as id_ativo_configuracao');
		$this->db->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_manutencao.id_ativo_veiculo");
		$this->db->join("fornecedor", "fornecedor.id_fornecedor=ativo_veiculo_manutencao.id_fornecedor");
		$this->db->join('ativo_configuracao', 'ativo_configuracao.id_ativo_configuracao=ativo_veiculo_manutencao.id_ativo_configuracao');
		$this->db->where("ativo_veiculo_manutencao.id_ativo_veiculo", $id_ativo_veiculo);
		$consulta = $this->db->get('ativo_veiculo_manutencao')->result();
		return $consulta;
	}

	public function get_ativo_veiculo_km_lista($id_ativo_veiculo){
		$this->db->select('ativo_veiculo_quilometragem.*, ativo_veiculo.veiculo, ativo_veiculo.veiculo_placa');
		$this->db->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_quilometragem.id_ativo_veiculo");
		$this->db->where("ativo_veiculo_quilometragem.id_ativo_veiculo", $id_ativo_veiculo);
		$consulta = $this->db->get('ativo_veiculo_quilometragem')->result();
		return $consulta;
	}


	public function get_ativo_veiculo_ipva_lista($id_ativo_veiculo){
		$this->db->select('ativo_veiculo_ipva.*, ativo_veiculo.veiculo, ativo_veiculo.veiculo_placa');
		$this->db->join("ativo_veiculo", "ativo_veiculo.id_ativo_veiculo=ativo_veiculo_ipva.id_ativo_veiculo");
		$this->db->where("ativo_veiculo_ipva.id_ativo_veiculo", $id_ativo_veiculo);
		$consulta = $this->db->get('ativo_veiculo_ipva')->result();
		return $consulta;
	}	

	public function get_tipo_servico($id_ativo_configuracao){
		$this->db->where("id_ativo_configuracao_vinculo", $id_ativo_configuracao);
		return $this->db->get('ativo_configuracao')->result();
	}	

	public function get_fornecedor(){
		$this->db->order_by("razao_social", "asc");
		return $this->db->get('fornecedor')->result();
	}

	public function get_ativo_veiculo_depreciacao_lista($id_ativo_veiculo){
		$this->db->where('id_ativo_veiculo', $id_ativo_veiculo);
		return $this->db->get('ativo_veiculo_depreciacao')->result();
	}

	# Gerenciamento de veículos
	# Função de Base
	public function get_ativo_veiculo_detalhes($id_ativo_veiculo){
		$this->db->where("id_ativo_veiculo", $id_ativo_veiculo);
		return $this->db->get('ativo_veiculo')->row();
	}


}