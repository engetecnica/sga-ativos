<?php 

class Ativo_interno_model extends MY_Model {

	public function salvar_formulario($data=null){
		if($data['id_ativo_interno']==''){
			$this->db->insert('ativo_interno', $data);

			 // Salvar LOG
			 $this->salvar_log(10, null, 'adicionar', $data);

			return "salvar_ok";
		} else {
			$this->db->where('id_ativo_interno', $data['id_ativo_interno']);
			$this->db->update('ativo_interno', $data);

            // Salvar LOG
			$this->salvar_log(10, $data['id_ativo_interno'], 'editar', $data);

			return "salvar_ok";
		}
	}

	public function query(){
		$query = $this->db
			->from('ativo_interno')
			->group_by('id_ativo_interno')
			->select('ativo_interno.*');
		$this->join_obra($query, 'ativo_interno.id_obra');
		return $query;
	}

	public function search_ativos($search){
		return $this->query()
			->group_by('id_ativo_interno')
			->order_by('nome')
			->like('nome', $search)
			->or_like('id_ativo_interno', $search)
			->or_like('data_inclusao', $search)
			->or_like('data_descarte', $search)
			->get()->result();
	}

	public function get_lista($id_obra = null, $situacao = null){
		$lista = $this->query();

		if ($id_obra != null){
			$lista->where("ativo_interno.id_obra = $id_obra");
		}

		if ($situacao) {
			if (is_array($situacao)) {
				$lista->where("situacao IN (".implode(',',$situacao).")");
			} else {
				$lista->where("situacao = $situacao");
			}
		}
		return $lista->get()->result();
	}

	public function get_ativo($id_ativo_interno=null, $situacao=null){
		$ativo = $this->query()->where('id_ativo_interno', $id_ativo_interno);

		if ($situacao) {
			if (is_array($situacao)) {
				$ativo->where("situacao IN (".implode(',',$situacao).")");
			} else {
				$ativo->where("situacao = $situacao");
			}
		}

		return $ativo->get()->row();
	}

	public function get_manutencao($id_ativo_interno, $id_manutencao){
		return $this->db
		->where('id_manutencao', $id_manutencao)
		->where('id_ativo_interno', $id_ativo_interno)
		->get('ativo_interno_manutencao')
		->row();
	}

	public function get_lista_manutencao($id_ativo_interno = null, $situacao = null, $obs = false){
		$manutencoes = $this->db
		->select('manutencao.*, manutencao.valor as manutencao_valor, manutencao.situacao as manutencao_situacao')
		->select('ativo.id_ativo_interno, ativo.nome, ativo.marca, ativo.id_obra, ativo.situacao as ativo_situacao')
		->select('ob.id_obra, ob.codigo_obra')
		->order_by('manutencao.id_manutencao', 'desc')
		->from('ativo_interno_manutencao manutencao');

		if ($id_ativo_interno) {
			$manutencoes->where("manutencao.id_ativo_interno = {$id_ativo_interno}");
		}

		if ($this->user->nivel == 2 && $this->user->id_obra) {
			$manutencoes->where("ativo.id_obra = {$this->user->id_obra}");
		}
		
		if ($situacao) {
			if (is_array($situacao)) {

				$situacao_string = "";
			 	foreach($situacao as $i => $sit) {
					$situacao_string .= "'{$sit}'";
					if($i < count($situacao) - 1)   $situacao_string .= ',';
				}

				$manutencoes->where("manutencao.situacao IN ({$situacao_string})");
			} else {
				$manutencoes->where("manutencao.situacao = {$situacao}");
			}
		}

		$manutencoes = $manutencoes
			->join('ativo_interno ativo', "ativo.id_ativo_interno = manutencao.id_ativo_interno")
			->join('obra ob', "ob.id_obra = ativo.id_obra", "left")
			->group_by('manutencao.id_manutencao')
			->get()->result();

		if ($obs) {
			foreach($manutencoes as $k => $manutencao) {
				$manutencoes[$k]->observacoes = $this->get_lista_manutencao_obs($manutencao->id_manutencao);
				foreach ($manutencoes[$k]->observacoes as $o => $obs) {
					if ($manutencoes[$k]->observacoes[$o]->permissoes) {
						$manutencoes[$k]->observacoes[$o]->permissoes = json_decode($obs->permissoes); 
					}
				}
			}
		}
		return $manutencoes;
	}

	public function get_obs($id_manutencao, $id_obs){
		return $this->db
		->where('id_manutencao', $id_manutencao)
		->where('id_obs', $id_obs)
		->get('ativo_interno_manutencao_obs')
		->row();
	}

	public function get_lista_manutencao_obs($id_manutencao) {
			return $this->db->select('obs.*, usuario.*')
			->from('ativo_interno_manutencao_obs obs')
			->order_by('obs.data_inclusao', 'desc')
			->where('id_manutencao', $id_manutencao)
			->join('usuario', 'usuario.id_usuario=obs.id_usuario')
			->group_by('obs.id_obs')
			->get()->result();
	}

	public function permit_create_manutencao($id_ativo_interno){
		return $this->db
			->where('id_ativo_interno', $id_ativo_interno)
			->where("situacao != 2")
			->get('ativo_interno_manutencao')->num_rows() == 0;
	}

	public function permit_edit_manutencao($id_ativo_interno, $id_manutencao){
		$manutencao = $this->db
			->where('id_ativo_interno', $id_ativo_interno)
			->order_by('id_manutencao', 'desc')
			->limit(1)
			->get('ativo_interno_manutencao')->row();
		return $manutencao && $manutencao->id_manutencao == $id_manutencao;
	}

	public function permit_delete_manutencao($id_ativo_interno, $id_manutencao) {
		$manutencao = $this->db
			->where('id_ativo_interno', $id_ativo_interno)
			->where('id_manutencao', $id_manutencao)
			->get('ativo_interno_manutencao')->row();
		return $this->permit_edit_manutencao($id_ativo_interno, $id_manutencao) && ($manutencao && $manutencao->situacao == 0);
	}

	public function permit_create($data){
		$ativos = $this->db->where('serie', $data['serie']);
		if($data['id_ativo_interno']) {
			$ativos->where("id_ativo_interno != {$data['id_ativo_interno']}");
		}
		return $ativos->get('ativo_interno')->num_rows() == 0;
	}

	public function permit_update($data){
		$ativos = $this->db
			->where("serie = {$data['serie']}")
			->where("id_ativo_interno != {$data['id_ativo_interno']}");
		return $ativos->get('ativo_interno')->num_rows() == 0;
	}
}