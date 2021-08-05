<?php 

class ferramental_requisicao_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->log = new Syslog();
		$this->load->model('ativo_externo/ativo_externo_model'); 
	}

	# Lista dos ativos externos
	function get_grupos($id_obra = null)
	{
		return $this->ativo_externo_model->get_grupos($id_obra);
	}

	# Salvar Requisicao
	public function salvar_formulario($data)
	{
		if (isset($data['id_requisicao'])) {
			return $this->db->where('id_requisicao', $data['id_requisicao'])
											->update('ativo_externo_requisicao', $data);
		}

		$this->db->insert('ativo_externo_requisicao', $data);
		return $this->db->insert_id();
	}

	private function requisicoes(){
		return $this->db->select("requisicao.*, 
				requisicao.id_requisicao as id_requisicao,
				origem.codigo_obra as origem, origem.endereco as origem_endereco, 
				origem.endereco_numero as origem_endereco_numero, origem.responsavel as origem_responsavel,
				origem.responsavel_celular as origem_responsavel_celular, origem.responsavel_email as origem_responsavel_email,
				destino.codigo_obra as destino, destino.endereco as destino_endereco, 
				destino.endereco_numero as destino_endereco_numero, destino.responsavel as destino_responsavel,
				destino.responsavel_celular as destino_responsavel_celular, destino.responsavel_email as destino_responsavel_email,    
				sol.usuario as solicitante, adm.usuario as despachante"
			)
			->from('ativo_externo_requisicao requisicao')
			->join('obra origem', 'origem.id_obra=requisicao.id_origem', 'left')
			->join('obra destino', 'destino.id_obra=requisicao.id_destino', 'left')
			->join('usuario sol', "sol.id_usuario = requisicao.id_solicitante", 'left')
			->join('usuario adm', "adm.id_usuario = requisicao.id_despachante", 'left');
	}

	# Listagem
	public function get_lista_requisicao($status = null, $offset = 0, $limite = null)
	{
		$requisicoes =  $this->requisicoes();

		if ($this->user->nivel == 2) {
			$requisicoes->where("requisicao.id_solicitante = {$this->user->id_usuario}");
			$requisicoes->or_where("requisicao.id_despachante = {$this->user->id_usuario}");
		}

		if ($status) {
			if (is_array($status)) {
				$requisicoes->where("requisicao.status IN (".implode(',', $status).")");
			} else {
				$requisicoes->where("requisicao.status = {$status}");
			}
		}

		$requisicoes->group_by('requisicao.id_requisicao')->order_by('requisicao.data_inclusao', 'DESC');

			if ($limite) {
				$requisicoes->limit($limite, $offset);
			}
			return $requisicoes->get()->result();
	}

	public function lista_requisicao_count($status = null){
		$requisicoes = $this->db->from('ativo_externo_requisicao requisicao')->select('requisicao.*');

		if ($status) {
			if (is_array($status)) {
				$requisicoes->where("requisicao.status IN (".implode(',', $status).")");
			} else {
				$requisicoes->where("requisicao.status = {$status}");
			}
		}
		
		return $requisicoes->get()->num_rows();
	}

	public function get_requisicao($id_requisicao)
	{
			return $this->requisicoes()
				->where("requisicao.id_requisicao = {$id_requisicao}")
				->group_by('requisicao.id_requisicao')
				->get()->row();
	}

	public function get_requisicao_com_items($id_requisicao, $id_ativo_externo_grupo = null)
	{
		$requisicao = $this->get_requisicao($id_requisicao);
		if($requisicao) {
			$requisicao->items = $this->get_requisicao_items($id_requisicao);
			$requisicao->devolucao = $requisicao->requisicao = null;

			$relativo = $this->db->from('ativo_externo_requisicao_devolucao dev')->select('dev.*, req.*');
			if ($requisicao->tipo == 1) {
				$requisicao->devolucao = $relativo
																->join("ativo_externo_requisicao req", "req.id_requisicao = dev.id_devolucao")
																->where("dev.id_requisicao = {$id_requisicao}")
																->get('ativo_externo_requisicao_devolucao')
																->row();
			}

			if ($requisicao->tipo == 2) {
				$requisicao->requisicao =  $relativo
																->join("ativo_externo_requisicao req", "req.id_requisicao = dev.id_requisicao")
																->where("dev.id_devolucao = {$id_requisicao}")
																->get('ativo_externo_requisicao_devolucao')
																->row();
			}
			return $requisicao;
		}
		return null;
	}

	public function get_requisicao_items($id_requisicao, $status=null){
			$this->db->reset_query();
			$items_query = $this->db->select('item.*, atv.id_ativo_externo, atv.codigo, atv.nome, 
			atv.id_ativo_externo_grupo, COUNT(atv.id_ativo_externo) as estoque')
			->from('ativo_externo_requisicao_item item')
			->where("item.id_requisicao={$id_requisicao}");

			if ($status != null) {
				$items_query->where("item.status = {$status}");
			}

			$items = $items_query->join('ativo_externo atv', 'atv.id_ativo_externo_grupo=item.id_ativo_externo_grupo')
														->group_by('item.id_requisicao_item')->get()->result();

			foreach($items as $k => $item){
				$items[$k]->ativos = $this
														->get_requisicao_item($id_requisicao, $item->id_requisicao_item)
														->ativos;
			}
			return $items;
	}

	public function get_requisicao_item($id_requisicao, $id_requisicao_item){
			$this->db->reset_query();
			$requisicao_item = $this->db
					->select('item.*, atv.id_ativo_externo_grupo, atv.nome')
					->from('ativo_externo_requisicao_item item')
					->where("item.id_requisicao={$id_requisicao}")
					->where("item.id_requisicao_item={$id_requisicao_item}")
					->join('ativo_externo atv', "atv.id_ativo_externo_grupo=item.id_ativo_externo_grupo", 'left')
					->get()->row();

			if($requisicao_item) {
				$this->db->reset_query();
				$requisicao_item->ativos = $this->db->select('ativo.*, atv.*, kit.*')
						->from('ativo_externo_requisicao_ativo ativo')
						->where("ativo.id_requisicao={$id_requisicao}")
						->where("ativo.id_requisicao_item={$id_requisicao_item}")
						->join('ativo_externo atv', "atv.id_ativo_externo=ativo.id_ativo_externo", 'left')
						->join('ativo_externo_kit kit', "kit.id_ativo_externo_kit = atv.id_ativo_externo", 'left')
						->group_by('atv.id_ativo_externo')
						->get()
						->result();
			}
			return $requisicao_item;
	}

	public function get_lista_status(){
		$this->db->order_by("id_requisicao_status", "ASC");
		return $this->db->get('ativo_externo_requisicao_status')->result();
	}

	public function get_status($id_requisicao_status)
	{
			return $this->db
			->select('ativo_externo_requisicao_status.*')
			->where("id_requisicao_status={$id_requisicao_status}")
			->get()
			->row();
	}

	public function aceite_items_requisicao($id_requisicao, $id_requisicao_item = null, array $ativos = [], array $status = [4,4]){
			$requisicao = $this->get_requisicao_com_items($id_requisicao);	
			$requisicao_item = $this->get_requisicao_item($id_requisicao, $id_requisicao_item);

			if ($requisicao && in_array($requisicao->status, [3, 13])) {
			  $requisicao_ativos = $ativos_externos = $requisicao_items = [];
				$contador = $this->get_contadores($requisicao);

				foreach($requisicao->items as $item) {
					if (!$id_requisicao_item | (is_array($id_requisicao_item) && in_array($item->id_requisicao_item, $id_requisicao_item)) | $item->id_requisicao_item == $id_requisicao_item) {
						$k = 0;
						foreach($item->ativos as $ativo) {
							$ativos_vazio = (count($ativos) == 0);
							$ativos_in_array = ((count($ativos) > 0) && in_array($ativo->id_requisicao_ativo, $ativos));

							if ($ativos_vazio | $ativos_in_array) {
								  $situacao = 12;
									$st = is_array($status[1]) ? $status[1][$k] : $status[1];

									if ($requisicao->tipo == 1) {
										switch($st){
											case 4:
												$contador->ativos->recebido++;
											break;
											case 8:
												$situacao = $st;
												$contador->ativos->recebido++;
											break;
											case 9:
												$situacao = $st;
												$contador->ativos->devolvido++;
											break;
										}
									}

									if ($requisicao->tipo == 2) {
										$situacao = 12;
										$contador->ativos->recebido++;
										if($ativo->status == 8){
											$situacao = $ativo->status;
										}
									}

									$requisicao_ativos[] = [
										'id_requisicao_ativo' => $ativo->id_requisicao_ativo,
										'status' => $st,
										'data_recebido' => date('Y-m-d H:i:s', strtotime('now'))
									];

									$ativos_externos[] = [
										'id_ativo_externo' => $ativo->id_ativo_externo,
										'id_obra' => $requisicao->id_destino,
										'situacao' => $situacao,
									];

									$ativo_externo = $this->ativo_externo_model->get_ativo($ativo->id_ativo_externo);
                  if ($ativo_externo->tipo == 1) {
                    $this->liberar_kit($ativo_externo, $ativos_externos, 12, $requisicao->id_destino);
                  }
									$k++;
								}
						}

						$status_item = $status[0];
						if ($requisicao->tipo == 1) {
							if($contador->ativos->recebido == count($item->ativos)) {
								$status_item = 4;
							}

							if($contador->ativos->com_defeito == count($item->ativos)) {
								$status_item = 8;
							}

							if($contador->ativos->devolvido == count($item->ativos)) {
								$status_item = 9;
							}
						}
						
						$requisicao_items[] = [
							'id_requisicao_item' => 	$item->id_requisicao_item,
							'status' => $status_item,
							'data_recebido' => date('Y-m-d H:i:s', strtotime('now'))
						];
						$contador->items->recebido++;
					}
				}

				$status_requisicao = 13;
				if(count($requisicao->items) == array_sum([$contador->items->recebido, $contador->items->com_defeito, $contador->items->devolvido])) {
					$status_requisicao = 4;
				}

				$data_requisicao = [
					'id_requisicao' => $id_requisicao,
					'status' => $status_requisicao,
					'data_recebido' => date('Y-m-d H:i:s', strtotime('now'))
				];

				$this->db->update_batch("ativo_externo_requisicao_ativo", $requisicao_ativos, 'id_requisicao_ativo');
				$this->db->update_batch("ativo_externo_requisicao_item", $requisicao_items, 'id_requisicao_item');
				$this->db->update_batch("ativo_externo", $ativos_externos, 'id_ativo_externo');
				$this->ferramental_requisicao_model->salvar_formulario($data_requisicao);

				//criar requisicao (tipo 2) de devolucao para items devolvidos ou com defeito se usuario é almoxarifado
				if (($status_requisicao == 4 && $requisicao->tipo == 1) && $this->user->nivel == 2) {
					return $this->devolver_items_requisicao($requisicao->id_requisicao);
				}
				return true;
			}
			return false;
	}

	public function devolver_items_requisicao($id_requisicao) {
			$requisicao = $this->get_requisicao_com_items($id_requisicao);

			if($requisicao && ($this->user->id_obra == $requisicao->id_destino)) {
				if ($this->user->nivel == 1 || $requisicao->tipo == 2) { 
					return false;
				}
			
				$contador = $this->get_contadores($requisicao);
				if ($contador->ativos->devolvido > 0 || $contador->ativos->com_defeito > 0) {
					$nova_requisicao = [
							'tipo' => 2,
							'status' => 2,
							'id_origem' => $requisicao->id_destino,
							'id_destino' => $requisicao->id_origem,
							'data_liberado' => date('Y-m-d H:i:s', strtotime('now')),
							'id_solicitante' => $requisicao->id_despachante,
							'id_despachante' => $requisicao->id_solicitante,
					];

					$id_nova_requisicao = $this->salvar_formulario($nova_requisicao);
					$ativos_externos = $requisicao_ativos = $requisicao_items  = [];

					foreach($requisicao->items as $item) {
							$quantidade = 0;
							foreach($item->ativos as $ativo) {
									if (in_array($ativo->status, [8, 9])) {
											$requisicao_ativos[] = [
													'status' => $ativo->status,
													'id_ativo_externo' => $ativo->id_ativo_externo,
													'data_liberado' => date('Y-m-d H:i:s', strtotime('now')),
													'id_requisicao' => $id_nova_requisicao,
													'id_requisicao_item' => null, //$id_requisicao_item,
													'id_ativo_externo_grupo' => $item->id_ativo_externo_grupo,
											];

											$ativo_status = $ativo->status == 9 ? 12 : $ativo->status;
											$ativos_externos[] = [
													'id_ativo_externo' => $ativo->id_ativo_externo,
													'situacao' => $ativo_status,
													'id_obra' => $requisicao->id_origem,
											];

											$ativo_externo = $this->ativo_externo_model->get_ativo($ativo->id_ativo_externo);
											if ($ativo_externo->tipo == 1) {
												$this->liberar_kit($ativo_externo, $ativos_externos, $ativo_status, $requisicao->id_origem);
											}
											$quantidade++;
									}
							}

							if ($quantidade > 0) {
								$this->db->insert('ativo_externo_requisicao_item', [
										'status' => 2,
										'id_ativo_externo_grupo' => $item->id_ativo_externo_grupo,
										'id_requisicao' => $id_nova_requisicao,
										'quantidade' => $quantidade,
										'quantidade_liberada' => $quantidade,
										'data_liberado' => date('Y-m-d H:i:s', strtotime('now')),
								]);

								$requisicao_items[] = [
									'id_requisicao_item' => $this->db->insert_id(),
									'id_ativo_externo_grupo' => $item->id_ativo_externo_grupo,
								];
							}
					}

					foreach($requisicao_items as $i => $item) {
						foreach($requisicao_ativos as $a => $ativo) {
							if (isset($ativo['id_ativo_externo_grupo']) && ($ativo['id_ativo_externo_grupo'] == $item['id_ativo_externo_grupo'])) {
								$requisicao_ativos[$a]['id_requisicao_item'] = $item['id_requisicao_item'];
								unset($requisicao_ativos[$a]['id_ativo_externo_grupo']);
							}
						}
					}

					$this->db->insert_batch("ativo_externo_requisicao_ativo", $requisicao_ativos);
					$this->db->insert('ativo_externo_requisicao_devolucao', ['id_devolucao' => $id_nova_requisicao, 'id_requisicao' => $id_requisicao]);
					return true;
				}
				return false;
		}
		return true;
	}

	public function get_requisicao_status($id = null){
		$status = $this->db->from('ativo_externo_requisicao_status');
		if ($id) {
			return $status->where("id_requisicao_status = {$id}")->get()->row();
		}
		return $status->group_by('id_requisicao_status')->get()->result();
	}

	private function get_contadores($requisicao) {
			if ($requisicao) {
				$total_items_recebido = $total_items_com_defeito = $total_items_devolvido = 0;
				$total_ativos_recebido = $total_ativos_com_defeito = $total_ativos_devolvido = 0;

				foreach($requisicao->items as $item) {
					switch($item->status){
						case 4:
							$total_items_recebido++;
						break;
						case 8:
							$total_items_com_defeito++;
						break;
						case 9:
							$total_items_devolvido++;
						break;
					}

					foreach($item->ativos as $ativo) {
						switch($ativo->status){
							case 4:
								$total_ativos_recebido++;
							break;
							case 8:
								$total_ativos_com_defeito++;
							break;
							case 9:
								$total_ativos_devolvido++;
							break;
						}
					}
				}

				return (object) [
					'items' => (object) [
						'recebido' => $total_items_recebido,
						'com_defeito' => $total_items_com_defeito,
						'devolvido' => $total_items_devolvido,
					],
					'ativos' => (object) [
						'recebido' => $total_ativos_recebido,
						'com_defeito' => $total_ativos_com_defeito,
						'devolvido' => $total_ativos_devolvido,
					],
				];
			}
			return null;
	}

	public function liberar_kit($ativo, array &$dados, $situacao = 2, $id_obra = null){
		$items = $this->ativo_externo_model->get_kit_items($ativo->id_ativo_externo);
		foreach($items as $item) {
				$dados[] = [
						'situacao' => $situacao,
						'id_ativo_externo' => $item->id_ativo_externo,
						'id_obra' => $id_obra ? $id_obra : $item->id_obra,
				];

				if ($item->tipo == 1) {
						$this->liberar_kit($item, $dados, $situacao, $id_obra);
				}
		}
	}
}
