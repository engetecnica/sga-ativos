<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/****
* 
* Classe para gerenciar Log de Requições e suas alteraçãoes
* Gerenciar Log de outras ações dentro do sistema
*
*/
	

class Syslog extends MX_Controller
{

	public    $tipo_acao			= null;
	protected $usuario 				= null; 
	protected $id_usuario			= null; 
	protected $item 				= null;
	protected $quantidade 			= null;
	protected $obra 				= null;
	protected $tabela 				= null;
	protected $id_reg 				= null;
	protected $id_reg_v				= null;
	protected $id_requisicao 		= null;
	protected $status_requisicao 	= null;

	protected $tabela_log 			= 'ferramental_requisicao_historico';


	# Construct - Inicial
	public function __construct(){}

	# Função para registrar log de requisição
	# Possibilidades de tipos de ação: Requisitou Item | Modificou Item (para dentro das possibilidades)
	# dentro da tabela ferramental_requisicao_status
	# infs são informações do registro do item em questão


	/**
	*	var tipo_acao : requisitou cancelou modificou 
	*	var usuario   : session->userdata(logado)->usuario
	*	var item      : item do pedido
	*	var quantidade: quantidade solicitada
	*	var obra 	  : obra para onde será direcionado o item
	*	var tabela 	  :	tabela de pesquisa do item
	*   var id_reg    : primary key tabela
	**/

	static function SetLogRequisicao(
										$usuario 			= null,
										$id_usuario			= null, 
										$tipo_acao			= null,
										$item 				= null,
										$quantidade 		= null,
										$obra 				= null,
										$tabela 			= null,
										$id_reg 			= null,
										$id_reg_v			= null,
										$id_requisicao 		= null,
										$status_requisicao 	= null
									)
	{

		#
		# Texto do Log
		# {usuario} {tipo_acao} o item {item} ({quantidade}) para a obra {obra}.
		# srandrebaill requisitou o item XXX (4) para a obra YYY
		#
		# {usuario} {tipo_acao} a requisicao {id_requisicao} para o status {status_requisicao}
		# srandrebaill modificou a requisicao 74 para o status Pendente
		#

		# Variáveis Globais
		$data 				= date("d/m/Y H:i:s");
		
		# Existindo Item -> Muda Texto
		if($item)
		{

			$historico 		= mb_strtolower("
								<b>usuário {$usuario}</b> 
								{$tipo_acao} 
								o item <b>{$item}</b> ({$quantidade}) 
								para a obra <b>{$obra}</b> 
							");

		}

		# Não existe Item -> Requisição
		else 
		{
			$historico 		= mb_strtolower("
								<b>usuário {$usuario}</b> 
								{$tipo_acao} 
								a requisição <b>{$id_requisicao}</b> 
								para o status <b>{$status_requisicao}</b> 
							");
		}

		# Liberação para inserir
		if($historico)
		{

			# Dados para Inserir
			$dados['id_requisicao'] 	= $id_requisicao;
			$dados['id_usuario'] 		= $id_usuario;
			$dados['tipo_acao'] 		= $tipo_acao;
			$dados['historico'] 		= preg_replace('/[\s]+/mu', ' ', $historico);
			
			return $dados;

		}
		else 
		{
			return false;
		}

	}


}

