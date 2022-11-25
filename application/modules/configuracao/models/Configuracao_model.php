<?php 

class Configuracao_model extends MY_Model {

	public function salvar_formulario($data=null){
		if($this->db->where('id_configuracao', 1)->get('configuracao')->num_rows() === 0){

			// Salvar LOG
			$this->salvar_log(20, null, 'adicionar', $data);
			
			$this->db->insert('configuracao', $data);
			return "salvar_ok";
		} else {
			$this->db->where('id_configuracao', $data['id_configuracao'])
								->update('configuracao', $data);

			// Salvar LOG
			$this->salvar_log(20, $data['id_configuracao'], 'editar', $data);	

			return "salvar_ok";
		}
	}

	public function get_configuracao($id_configuracao = 1){
		$configuracao = $this->db
					->where('id_configuracao', $id_configuracao)
					->get('configuracao')->row();
					
		if ($configuracao) {
			if ($configuracao->permit_notificacoes == 1) {
				$configuracao->permit_notificacoes = true;
				if (!empty($configuracao->origem_email)) $configuracao->permit_notificacoes_email = true;

				$one_signal = [
					!empty($configuracao->one_signal_apiurl),
					!empty($configuracao->one_signal_apikey),
					!empty($configuracao->one_signal_appid),
					!empty($configuracao->one_signal_safari_web_id),
				];
				if (!in_array(false, $one_signal)) $configuracao->permit_notificacoes_push = true;
			}
		}
		else {
			$configuracao = (object) [
				"permit_notificacoes" => false,
				"permit_notificacoes_email" => false,
				"permit_notificacoes_push" => false,
				"permit_notificacoes" => false,
				"km_alerta" => 10000,
				"operacao_alerta" => 10000,
			];
		}

		return $configuracao;
	}
}