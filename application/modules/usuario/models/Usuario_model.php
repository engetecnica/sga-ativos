<?php 

class usuario_model extends MY_Model {

	function __construct() {
        parent::__construct();
        $this->load->model('relatorio/notificacoes_model');
    }

	public function salvar_formulario($data=null){
		$this->db->where('usuario', $data['usuario']);
		$usuario = $this->db->get('usuario')->row();

		if(!isset($data['id_usuario']) || $data['id_usuario'] === null){
			$data['data_criacao'] = date('Y-m-d H:i:s', strtotime('now'));
			if ($usuario) {
				return "salvar_error";
			}

			// Salvar LOG
			$this->salvar_log(2, null, 'adicionar', $data);

			$this->db->insert('usuario', $data);
			
			if ($data['email'] != null) {
				$this->usuario_model->solicitar_confirmacao_email($this->db->insert_id(), "email_bem_vindo");
			}
			return "salvar_ok";
		} 

		$usuario = $this->db->where('id_usuario', $data['id_usuario'])->get('usuario')->row();
		if ($this->user->id_usuario != $data['id_usuario'] && $this->user->nivel == 2) {
			return "salvar_error";
		}
		
		if (isset($data['email']) && (strtolower($usuario->email) != strtolower($data['email']))) {
			$data['email_confirmado_em'] = null;
			$data['codigo_recuperacao'] = null;
		}

		$this->db->where('id_usuario', $data['id_usuario'])->update('usuario', $data);

		// Salvar LOG
		$this->salvar_log(2, $data['id_usuario'], 'editar', $data);

		$usuario = $this->db->where('id_usuario', $data['id_usuario'])->get('usuario')->row();
		if ($usuario->email && (!$usuario->email_confirmado_em && !$usuario->codigo_recuperacao)) {
			$this->usuario_model->solicitar_confirmacao_email($usuario->id_usuario, "email_confirmacao");
		}
		return "salvar_ok";
		
	}

	public function verificaSenha($senha, $confirmar_senha) {
        $senha = strlen($senha) > 0 ? $senha : null;
        $confirmar_senha = strlen($confirmar_senha) > 0 ? $confirmar_senha : null;

        if($senha && $confirmar_senha) {
            if ($senha == $confirmar_senha ) {
                return sha1($confirmar_senha);
            }

            if ($senha != $confirmar_senha) {
                return null;
            }
        }
        return null;
    }

	public function query() : \CI_DB_mysqli_driver
	{
		$usuario = $this->db
			->from('usuario')
			->select('usuario.*')
			->group_by('usuario.id_usuario');

		$this->join_empresa($usuario, 'usuario.id_empresa');
		$this->join_obra($usuario, 'usuario.id_obra');
		$this->join_usuario_nivel($usuario, 'usuario.nivel');

		return $usuario;
	}

	public function gerar_codigo(){
		$codigo = rand(100001, 999999);
		while($this->query()->where('codigo_recuperacao', $codigo)->get()->num_rows() > 0){
			$codigo = rand(100001, 999999);
		}
		return $codigo;
	}

	public function get_lista($id_empresa = null, $id_obra = null, $situacao = null){
		$lista = $this->query();

		if ($id_empresa) {
			$$lista->where("usuario.id_empresa = {$id_empresa}");
		}

		if ($id_obra) {
			$$lista->where("usuario.id_obra = {$id_obra}");
		}

		if ($situacao) {
			if(is_array($situacao)) {
				$$lista->where("usuario.situacao IN (".implode(',',$situacao).")");
			} else {
				$$lista->where("usuario.situacao = {$situacao}");
			}
		}

		return $lista->group_by('usuario.id_usuario')
								->order_by('usuario.id_usuario', 'desc')
								->get()->result();
							}

	public function get_usuario($id=null, $includes_pass = false){
		$usuario = $this->query()
					->where('id_usuario', $id)
					->get()->row();
		if ($includes_pass == false) {
			unset($usuario->senha);
		}
		return $usuario;
	}

	public function get_usuario_email($email, $includes_pass = false){
		$usuario = $this->query()->where("email = '{$email}'")->get()->row();
		if ($includes_pass == false) {
			unset($usuario->senha);
		}
		return $usuario;
	}

	public function get_usuario_codigo($codigo_recuperacao, $includes_pass = false){
		$now = date('Y-m-d H:i:s');
		$usuario = $this->query()
			->where('codigo_recuperacao', $codigo_recuperacao)
			->where("codigo_recuperacao_validade >= '$now'")
			->get()->row();

		if ($includes_pass == false) {
			unset($usuario->senha);
		}
		return $usuario;
	}

	public function exists_email($email, $id = null){
		$usuario = $this->query()->where("email = '{$email}'");
		if ($id) {
			$usuario->where("id_usuario != $id");
		} 
		return $usuario->get()->num_rows() > 0;
	}

	public function exists_usuario($usuario, $id = null){
		$usuario = $this->query()->where('usuario', $usuario);
		if ($id) {
			$usuario->where("id_usuario != $id");
		} 
		return $usuario->get()->num_rows() > 0;
	}

	public function solicitar_confirmacao_email($id_usuario, $template = "email_confirmacao"){
        $usuario = $this->get_usuario($id_usuario);
        if ($usuario) {
            $validade = date('Y-m-d H:i:s', strtotime("+30 days"));
            $codigo = $this->gerar_codigo();
            $enviado = $this->enviar_email_confirmacao($usuario, $codigo, $validade, $template);
            if ($enviado) {
                $this->db
                ->where("id_usuario = {$usuario->id_usuario}")
                ->update('usuario', [
                    "codigo_recuperacao" => $codigo,
                    "codigo_recuperacao_validade" => $validade
                ]);
                return $enviado;
            }
        }
        return false;
    }

	public function enviar_email_confirmacao($usuario, $codigo, $validade = "+30 days", $template = "email_confirmacao"){
		$html = $this->load->view(
			"relatorio/{$template}", 
			[
				'usuario' => $usuario, 
				"codigo" => $codigo, 
				"validade" => date("d/m/Y H:i:s", strtotime($validade)),
				'styles' => $this->notificacoes_model->getEmailStyles(),
			], 
			true
		);
		return $this->notificacoes_model->enviar_email(
			$template == "email_confirmacao" ? "Confirmar Email" : "Boas Vindas", 
			$html, 
			["{$usuario->nome}" => $usuario->email],  
			["ilustration" => $template == "email_confirmacao" ? "images/ilustrations/order_confirmed.png": "images/ilustrations/welcoorder_confirmedme.png"]
		);
	}

	public function enviar_email_recuperacao($usuario, $codigo, $validade = "+60 minutes"){
		$html = $this->load->view(
				'relatorio/email_recuperar_senha', 
				[
					'usuario' => $usuario, 
					"codigo" => $codigo, 
					"validade" =>  date("d/m/Y H:i:s", strtotime($validade)),
					'styles' => $this->notificacoes_model->getEmailStyles(),
				], 
				true
		);
		return $this->notificacoes_model->enviar_email(
			"Redefinição de Senha", 
			$html, 
			["{$usuario->nome}" => $usuario->email],
			["ilustration" => "images/ilustrations/forgot_password.png"]
		);
	}

	/* Campos Selecionados */
	public function get_lista_simples(){
		return $this->db->select('id_usuario, nome, usuario, email')->order_by('nome', 'ASC')->get('usuario')->result();
	}
}