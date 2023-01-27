<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

/**
 * Description of site
 *
 * @author André Baill | https://github.com/srandrebaill
 */
class Anexo  extends MY_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('anexo_model');

		# Login
		if ($this->session->userdata('logado') == null) {
			echo redirect(base_url('login'));
		}
		$this->load->model('ativo_externo/ativo_externo_model');
		$this->load->model('ativo_interno/ativo_interno_model');
		$this->load->model('ativo_veiculo/ativo_veiculo_model');
		$this->load->model('ferramental_estoque/ferramental_estoque_model');
	}

	function index(
		$modulo_nome = null, //rota
		$id_item = null, //id item do modulo ex: id_ativo_externo
		$tipo = null, //tipo do anexo ex: manutencao, ipva, seguro
		$id_subitem = null, //id subitem do modulo ex: id_ativo_externo_manutencao
		$pagina = null,
		$limite = null
	) {
		return $this->get_template("index", $this->anexo_model->getData($modulo_nome, $id_item, $tipo, $id_subitem, $this->getRef(), $pagina, $limite));
	}

	function adicionar(
		$modulo_nome = null, //rota
		$id_item = null, //id item do modulo ex: id_ativo_externo
		$tipo = null, //tipo do anexo ex: manutencao, ipva, seguro
		$id_subitem = null, //id subitem do modulo ex: id_ativo_externo_manutencao
		$pagina = null,
		$limite = null
	) {
		return $this->get_template("index_form", $this->anexo_model->getData($modulo_nome, $id_item, $tipo, $id_subitem, $this->getRef(), $pagina, $limite));
	}


	function salvar()
	{
		$modulo_name = str_replace("_", " ", ucfirst($this->input->post('modulo')));
		$titulo = $this->input->post('titulo') ? $this->input->post('titulo') : $modulo_name . " - " . ucfirst($this->input->post('tipo')) . " - " . date("d/m/Y H:i:s", strtotime('now'));
		$descricao = $this->input->post('descricao') ? $this->input->post('descricao') : $titulo;

		//upload file
		$anexo = 'anexo/';
		if (!is_readable(APPPATH . '../assets/uploads/anexo')) {
			$this->session->set_flashdata('msg_erro', "A pasta de destino do upload não parece ser gravável.");
			if ($this->input->post('back_url')) {
				echo redirect(base_url($this->input->post('back_url')));
				return;
			}
			echo redirect(base_url("anexo/adicionar"));
			return;
		}

		if ($_FILES['anexo']['error'] == 0 && $_FILES['anexo']['size'] > 0) {
			$anexo .= $this->upload_arquivo('anexo');
			if (!$anexo || $anexo == '') {
				$this->session->set_flashdata('msg_erro', "O tamanho do comprovante deve ser menor ou igual a " . ini_get('upload_max_filesize'));
				if ($this->input->post('back_url')) {
					echo redirect(base_url($this->input->post('back_url')));
					return;
				}
				echo redirect(base_url("anexo/adicionar"));
				return;
			}
		}

		$data = [
			"id_anexo" => $this->input->post('id_anexo'),
			"id_usuario" => $this->user->id_usuario,
			"id_modulo" => $this->input->post('id_modulo'),
			"id_modulo_item" => $this->input->post('item'),
			"id_modulo_subitem" => $this->input->post('subitem'),
			"id_configuracao" => $this->input->post('servico'),
			"tipo" => $this->input->post('tipo'),
			"titulo" => $titulo,
			"descricao" => $descricao,
			"anexo" => $anexo
		];

		if (isset($data['id_anexo']) &&  $anexo === 'anexo/') unset($data['anexo']);
		$salvar = $this->anexo_model->salvar_formulario($data);

		if ($salvar !== null) {
			if ($this->input->post('back_url')) {
				$this->session->set_flashdata('msg_success', "Registro atualizado com sucesso!");
				echo redirect(base_url($this->input->post('back_url')));
			} else {
				$this->session->set_flashdata('msg_error', "Não foi possível salvar.");
				echo redirect(base_url("anexo"));
			}
		} else {
			$this->session->set_flashdata('msg_error', "Não foi possível salvar.");
			echo redirect(base_url("anexo"));
		}

	}

	function deletar($id_anexo)
	{
		$success = false;
		if ($this->input->method() == "post") {
			$anexo = $this->db->where('id_anexo', $id_anexo)->get('anexo')->row();

			if ($anexo) {
				$success = $this->db->where('id_anexo', $anexo->id_anexo)->delete('anexo');
				$path = __DIR__ . "/../../../../assets/uploads";
				$file = "$path/{$anexo->anexo}";

				if ($success && file_exists($file)) {
					unlink($file);
				}
			}
		}
		return $this->json(['success' => $success],  $success ? 200 : 404);
	}

	function items($modulo, $search = null)
	{
		$data = [];

		switch ($modulo) {
			case 'ferramental_estoque':
				$data = array_map(function ($retirada) {
					return (object) [
						"id" => $retirada->id_retirada,
						"descricao" => sprintf(
							"%s - %s - %s - %s",
							$retirada->id_retirada,
							$retirada->funcionario,
							$retirada->obra,
							date('d/m/Y H:i:s', strtotime($retirada->data_inclusao))
						)
					];
				}, $this->ferramental_estoque_model->search_retiradas($search));
				break;

			case 'ativo_externo':
				$data = array_map(function ($ativo) {
					return (object) [
						"id" => $ativo->id_ativo_externo,
						"descricao" => $ativo->codigo . " - " . $ativo->nome,
						"data" => date('d/m/Y H:i:s', strtotime($ativo->data_inclusao)),
						"valor" => $ativo->valor,
					];
				}, $this->ativo_externo_model->search_ativos($search));
				break;

			case 'ativo_interno':
				$data = array_map(function ($ativo) {
					return (object) [
						"id" => $ativo->id_ativo_interno,
						"descricao" => $ativo->id_ativo_interno . " - " . $ativo->nome,
						"data" => date('d/m/Y H:i:s', strtotime($ativo->data_inclusao)),
						"valor" => $ativo->valor,
					];
				}, $this->ativo_interno_model->search_ativos($search));
				break;

			case 'ativo_veiculo':
				$data = array_map(function ($ativo) {
					return (object) [
						"id" => $ativo->id_ativo_veiculo,
						"descricao" => $ativo->id_ativo_veiculo . " - " . $ativo->veiculo_placa . " - " . $ativo->veiculo,
						"data" => date('d/m/Y H:i:s', strtotime($ativo->data)),
						"valor" => $ativo->valor_fipe,
					];
				}, $this->ativo_veiculo_model->search_ativos($search));
				break;
		}

		$this->json($data, 200);
	}


	function subitems($modulo, $tipo, $id_item)
	{
		$data = [];

		switch ($modulo) {
			case 'ferramental_estoque':
				$data = [];
				break;

			case 'ativo_externo':
			case 'ativo_interno':
				if ($tipo == "manutencao") {
					$model_name = $modulo . "_model";
					$data = array_map(function ($manutencao) {
						return (object) [
							"id" => $manutencao->id_manutencao,
							"descricao" => $manutencao->id_manutencao . " - " . $manutencao->servico . " - " . date("d/m/Y H:i:s", strtotime($manutencao->data_saida)),
							"data" => $manutencao->data_saida,
							"valor" => $manutencao->valor,
						];
					}, $this->$model_name->get_lista_manutencao($id_item));
				}
				break;

			case 'ativo_veiculo':
				if ($tipo == "manutencao") {
					$data = array_map(function ($manutencao) {
						return (object) [
							"id" => $manutencao->id_ativo_veiculo_manutencao,
							"descricao" => $manutencao->id_ativo_veiculo_manutencao . " - " . $manutencao->servico . " - " . date("d/m/Y H:i:s", strtotime($manutencao->data_entrada)),
							"data" => $manutencao->data_entrada,
							"valor" => $manutencao->veiculo_custo,
						];
					}, $this->ativo_veiculo_model->get_ativo_veiculo_manutencao_lista($id_item));
				}

				if ($tipo == "ipva") {
					$data = array_map(function ($ipva) {
						return (object) [
							"id" => $ipva->id_ativo_veiculo_ipva,
							"descricao" => $ipva->id_ativo_veiculo_ipva . " - " . $ipva->ipva_ano . " - " . $ipva->veiculo . " - " . date("d/m/Y H:i:s", strtotime($ipva->ipva_data_pagamento)),
							"data" => $ipva->ipva_data_pagamento,
							"valor" => $ipva->ipva_custo,
						];
					}, $this->ativo_veiculo_model->get_ativo_veiculo_ipva_lista($id_item));
				}

				if ($tipo == "kilometragem") {
					$data = array_map(function ($km) {
						return (object) [
							"id" => $km->id_ativo_veiculo_quilometragem,
							"descricao" => $km->id_ativo_veiculo_quilometragem . " - " . $km->veiculo_km_inicial . "Km à " . $km->veiculo_km_final . "Km - " .  date("d/m/Y H:i:s", strtotime($km->data)),
							"data" => $km->data,
							"valor" => $km->veiculo_custo,
						];
					}, $this->ativo_veiculo_model->get_ativo_veiculo_km_lista($id_item));
				}

				if ($tipo == "seguro") {
					$data = array_map(function ($seguro) {
						return (object) [
							"id" => $seguro->id_ativo_veiculo_seguro,
							"descricao" => $seguro->id_ativo_veiculo_seguro . " - " . $seguro->veiculo_km_final . " - " . $seguro->veiculo_km_final . "KM - " .  date("d/m/Y H:i:s", strtotime($seguro->seguro_carencia_inicio)),
							"data" => $seguro->data,
							"valor" => $seguro->veiculo_custo,
						];
					}, $this->ativo_veiculo_model->get_ativo_veiculo_seguro_lista($id_item));
				}
				break;
		}

		$this->json($data, 200);
	}

	public function historico($id_anexo){

		$data['historico'] = $this->anexo_model->get_historico_anexo($id_anexo);
	
		return $this->get_template("lista_anexo_historico", $data);
	}
}
