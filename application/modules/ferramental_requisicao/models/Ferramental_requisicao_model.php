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
	//@todo remove
	private function requisicoes(){
		$romaneio = "SELECT anexo FROM anexo WHERE id_modulo_item = requisicao.id_requisicao AND tipo = 'romaneio' ORDER BY id_anexo DESC LIMIT 1";
		return $this->db->select("requisicao.*,
				origem.codigo_obra as origem, origem.endereco as origem_endereco, 
				origem.endereco_numero as origem_endereco_numero, origem.responsavel as origem_responsavel,
				origem.responsavel_celular as origem_responsavel_celular, origem.responsavel_email as origem_responsavel_email,
				destino.codigo_obra as destino, destino.endereco as destino_endereco, 
				destino.endereco_numero as destino_endereco_numero, destino.responsavel as destino_responsavel,
				destino.responsavel_celular as destino_responsavel_celular, destino.responsavel_email as destino_responsavel_email,    
				sol.usuario as solicitante, adm.usuario as despachante,
				sol.nome as solicitante_nome, adm.nome as despachante_nome,
				($romaneio) as romaneio"
			)
			->from('ativo_externo_requisicao requisicao')
			->join('obra origem', 'origem.id_obra=requisicao.id_origem', 'left')
			->join('obra destino', 'destino.id_obra=requisicao.id_destino', 'left')
			->join('usuario sol', "sol.id_usuario = requisicao.id_solicitante", 'left')
			->join('usuario adm', "adm.id_usuario = requisicao.id_despachante", 'left');
	}

	public function query($id_obra = null, $id_usuario = null){
		$romaneio = "SELECT anexo FROM anexo WHERE id_modulo_item = requisicao.id_requisicao AND tipo = 'romaneio' ORDER BY id_anexo DESC LIMIT 1";
		$query = $this->db->select("
			requisicao.*,
			origem.codigo_obra as origem, origem.endereco as origem_endereco, 
			origem.endereco_numero as origem_endereco_numero, origem.responsavel as origem_responsavel,
			origem.responsavel_celular as origem_responsavel_celular, origem.responsavel_email as origem_responsavel_email,
			destino.codigo_obra as destino, destino.endereco as destino_endereco, 
			destino.endereco_numero as destino_endereco_numero, destino.responsavel as destino_responsavel,
			destino.responsavel_celular as destino_responsavel_celular, destino.responsavel_email as destino_responsavel_email,    
			solicitante.usuario as solicitante, despachante.usuario as despachante,
			solicitante.nome as solicitante_nome, despachante.nome as despachante_nome,
			($romaneio) as romaneio
		")
		->from('ativo_externo_requisicao requisicao')
		->join('obra origem', 'origem.id_obra=requisicao.id_origem', 'left')
		->join('obra destino', 'destino.id_obra=requisicao.id_destino', 'left')
		->join('usuario solicitante', "solicitante.id_usuario = requisicao.id_solicitante", 'left')
		->join('usuario despachante', "despachante.id_usuario = requisicao.id_despachante", 'left');

		if ($id_obra) {
			$query->group_start();
			if (is_array($id_obra)) {
				$query
				->where("requisicao.id_origem IN (".implode(',', $id_obra).")")
				->or_where("requisicao.id_destino IN (".implode(',', $id_obra).")");
			} else {
				$query
				->where("requisicao.id_origem = {$id_obra}")
				->or_where("requisicao.id_destino = {$id_obra}");
			}
			$query->group_end();
		}

		if ($id_usuario) {
			$query->group_start();
			if (is_array($id_usuario)) {
				$query
				->where("requisicao.id_solicitante IN (".implode(',', $id_usuario).")")
				->or_where("requisicao.id_despachante IN (".implode(',', $id_usuario).")");
			} else {
				$query
				->where("requisicao.id_solicitante = {$id_usuario}")
				->or_where("requisicao.id_despachante = {$id_usuario}")
				->or_where("requisicao.id_despachante IS NULL");
			}
			$query->group_end();
		}

		$this->join_status($query, 'requisicao.status');
		return $query->group_by('requisicao.id_requisicao');
	}

	# Listagem
	public function get_lista_requisicao($status = null, $offset = 0, $limite = null, $id_obra = null)
	{
		$id_obra = null;
        $id_usuario = null;
        if ($this->user->nivel == 2) {
            $id_obra = $this->user->id_obra;
            $id_usuario = $this->user->id_usuario;
        }

		$query = $this->query($id_obra, $id_usuario);

		if ($status) {
			if (is_array($status)) {
				$query->where("requisicao.status IN (".implode(',', $status).")");
			} else {
				$query->where("requisicao.status = {$status}");
			}
		}

		$query->order_by('requisicao.id_requisicao', 'desc');

		if ($limite) $query->limit($limite, $offset);
		return $query->get()->result();
	}

	public function lista_requisicao_count($status = null, $id_obra = null){
		$requisicoes = $this->db->from('ativo_externo_requisicao requisicao')
														->select('requisicao.*');
		if ($id_obra) {
			$requisicoes->where("requisicao.id_origem = {$id_obra} OR requisicao.id_origem = {$id_obra}");
		}

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
		if (!$id_requisicao) {
			return null;
		}
		return $this->query()
			->where("requisicao.id_requisicao = {$id_requisicao}")
			->group_by('requisicao.id_requisicao')
			->get()->row();
	}

	public function get_requisicao_com_items($id_requisicao)
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
			$estoque = "select COUNT(id_ativo_externo) FROM ativo_externo WHERE (id_ativo_externo_grupo=atv.id_ativo_externo_grupo and situacao=12)";

			if ($this->user->id_obra) $estoque .= " and id_obra = {$this->user->id_obra}";

			$items_query = $this->db->select("item.*, atv.id_ativo_externo, atv.codigo, atv.nome, 
			atv.id_ativo_externo_grupo, atv.id_obra, ($estoque) as estoque")
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
				$requisicao_item->ativos = $this->db
						->select('ativo.*, atv.*, kit.*, atc.nome as categoria')
						->from('ativo_externo_requisicao_ativo ativo')
						->where("ativo.id_requisicao={$id_requisicao}")
						->where("ativo.id_requisicao_item={$id_requisicao_item}")
						->join('ativo_externo atv', "atv.id_ativo_externo=ativo.id_ativo_externo", 'left')
						->join('ativo_externo_kit kit', "kit.id_ativo_externo_kit = atv.id_ativo_externo", 'left')
						->join("ativo_externo_categoria as atc", "atc.id_ativo_externo_categoria = atv.id_ativo_externo_categoria", 'left')
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

			if ($requisicao && in_array($requisicao->status, [3, 13])) {
			  	$requisicao_ativos = $ativos_externos = $requisicao_items = [];
	
				$k = 0;
				$contador_items = $this->get_contadores($requisicao)->items;
				foreach($requisicao->items as $item) {
					$contador_ativos = $this->get_contadores($requisicao)->ativos;
					if (!$id_requisicao_item | (is_array($id_requisicao_item) && in_array($item->id_requisicao_item, $id_requisicao_item)) || $item->id_requisicao_item == $id_requisicao_item) {
						foreach($item->ativos as $ativo) {
							$ativos_vazio = (count($ativos) == 0);
							$ativos_in_array = ((count($ativos) > 0) && in_array($ativo->id_requisicao_ativo, $ativos));

							if ($ativos_vazio | $ativos_in_array) {
								  	$situacao = 12;
									$st = is_array($status[1]) ? $status[1][$k] : $status[1];

									if ($requisicao->tipo == 1) {
										switch($st){
											case 4:
												if (count($ativos) > $contador_ativos->recebido) $contador_ativos->recebido++;
											break;
											case 8:
												$situacao = $st;
												if (count($ativos) > $contador_ativos->com_defeito) $contador_ativos->com_defeito++;
											break;
											case 9:
												$situacao = $st;
												if (count($ativos) > $contador_ativos->devolvido) $contador_ativos->devolvido++;
											break;
										}
									}

									if ($requisicao->tipo == 2) {
										$situacao = 12;
										$contador_ativos->recebido++;
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
								}
							$k++;
						}

						$status_item = $status[0];
						if ($requisicao->tipo == 1) {
							if($contador_ativos->com_defeito == count($item->ativos)) $status_item = 8;
							elseif($contador_ativos->devolvido == count($item->ativos)) $status_item = 9;
							elseif($contador_ativos->recebido == count($item->ativos)) $status_item = 4;
							elseif(array_sum([$contador_ativos->recebido, $contador_ativos->com_defeito, $contador_ativos->devolvido]) == 0) $status_item = 2;
							else $status_item = 13;
						}
						
						$requisicao_items[] = [
							'id_requisicao_item' => 	$item->id_requisicao_item,
							'status' => $status_item,
							'data_recebido' => date('Y-m-d H:i:s', strtotime('now'))
						];
						$contador_items->recebido++;
					}
				}

				$status_requisicao = 13;
				if(count($requisicao->items) == array_sum([$contador_items->recebido, $contador_items->com_defeito, $contador_items->devolvido])) {
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
				if ($status_requisicao == 4 && $requisicao->tipo == 1) {
					$this->devolver_items_requisicao($id_requisicao);
				}
				return true;
			}
			return false;
	}

	public function permit_devolver_items_requisicao($id_requisicao){
		$requisicao = $this->get_requisicao_com_items($id_requisicao);
		if(
			($requisicao && (isset($requisicao->id_devolucao) && $requisicao->tipo == 1)) &&
			($requisicao->id_solicitante == $this->user->id_usuario || $requisicao->id_destino == $this->user->id_obra)
		) {
			$contador = $this->get_contadores($requisicao);
			$quantidade = 0;
			if ($contador->ativos->devolvido > 0 || $contador->ativos->com_defeito > 0) {
				foreach($requisicao->items as $item) {
					foreach($item->ativos as $ativo) if (in_array($ativo->status, [8, 9])) $quantidade++;
				}
			}
			return $quantidade > 0;
		}
		return false;
	}

	public function devolver_items_requisicao($id_requisicao) {
		$requisicao = $this->get_requisicao_com_items($id_requisicao);
		
		if($requisicao) {
			if ($requisicao->tipo == 2) { 
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

				foreach($requisicao_items as $item) {
					foreach($requisicao_ativos as $a => $ativo) {
						if (isset($ativo['id_ativo_externo_grupo']) && ($ativo['id_ativo_externo_grupo'] == $item['id_ativo_externo_grupo'])) {
							$requisicao_ativos[$a]['id_requisicao_item'] = $item['id_requisicao_item'];
							unset($requisicao_ativos[$a]['id_ativo_externo_grupo']);
						}
					}
				}

				$this->db->insert_batch("ativo_externo_requisicao_ativo", $requisicao_ativos);
				$this->db->insert('ativo_externo_requisicao_devolucao', ['id_devolucao' => $id_nova_requisicao, 'id_requisicao' => $id_requisicao]);
			}
			return true;
		}
		return false;
	}

	public function permit_solicitar_items_nao_inclusos($id_requisicao) {
		$requisicao = $id_requisicao;
		if (!$requisicao instanceof stdClass) {
			$requisicao = $this->get_requisicao_com_items($id_requisicao);
		}

		if (
			($requisicao instanceof stdClass && (!$requisicao->data_inclusao_filha && in_array($requisicao->status, [4, 13, 15]))) &&
			($requisicao->id_solicitante == $this->user->id_usuario || $requisicao->id_destino == $this->user->id_obra)
		) {
			$quantidades = [];
			foreach($requisicao->items as $item) {
				$quantidades[] = ((int) $item->quantidade - (int) $item->quantidade_liberada) > 0;
			}
			return in_array(true, $quantidades);
		}
		return false;
	}

	public function solicitar_items_nao_inclusos_requisicao($id_requisicao) {
		$requisicao = $id_requisicao;
		if (!$requisicao instanceof stdClass) {
			$requisicao = $this->get_requisicao_com_items($id_requisicao);
		}

		if ($requisicao instanceof stdClass && $this->permit_solicitar_items_nao_inclusos($requisicao)) {
			$nova_requisicao = [
				'id_requisicao_mae' => $requisicao->id_requisicao,
				'id_destino' => $requisicao->id_destino,
				'id_solicitante' => $requisicao->id_solicitante,
				'tipo' => $requisicao->tipo,
				'status' => 1,
			];
			$requisicao_items = []; 
			foreach($requisicao->items as $i => $item){
				$quantidade = (int) $item->quantidade - (int) $item->quantidade_liberada;
				if ($quantidade > 0) {
					$requisicao_items[$i] = [
						//'id_requisicao' => null,
						'id_ativo_externo_grupo' => $item->id_ativo_externo_grupo,
						'quantidade' => $quantidade,
						'status' => 1,
					];
				}
			}

			if (count($requisicao_items) > 0) {
				$id_nova_requisicao = $this->salvar_formulario($nova_requisicao);
				$this->salvar_formulario([
					'id_requisicao' => $requisicao->id_requisicao,
					'id_requisicao_filha' => $id_nova_requisicao,
					'data_inclusao_filha' => date('Y-m-d H:i:s')
				]);

				$requisicao_items = array_map(function($item) use ($id_nova_requisicao) {
					return array_merge($item, ['id_requisicao' => $id_nova_requisicao]);
				}, $requisicao_items);
				$this->db->insert_batch("ativo_externo_requisicao_item", $requisicao_items);

				$msg = "Nova Requisição Complementar de Ferramentas {$id_nova_requisicao} criada e Pendênte de aprovação ";
				if (isset($nova_requisicao['id_origem'])) {
					$obra = $this->obra_model->get_obra($nova_requisicao['id_origem']);
					$msg .= " Da obra {$obra->codigo_obra}";
				}

				if (isset($nova_requisicao['id_destino'])) {
					$obra = $this->obra_model->get_obra($nova_requisicao['id_destino']);
					$msg .= " para a obra {$obra->codigo_obra}";
				}
				$this->notificacoes_model->enviar_push(
					"Requisição Complementar de Ferramentas Pendênte",
               	 	"{$msg}. Clique na Notificação para mais detalhes.",
					[
						"filters" => [
							["field" => "tag", "key" => "nivel", "relation" => "=", "value" => "1"],
							["operator" => "AND"],
							["field" => "tag", "key" => "id_obra", "relation" => "!=", "value" => $this->user->id_obra],
						],
						"include_external_user_ids" => [$this->user->id_usuario],
						"url" => "ferramental_requisicao/detalhes/{$id_nova_requisicao}"
					]
				);
				return true;
			}
		}
		return false;
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

				foreach($requisicao->items as $i => $item) {
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
