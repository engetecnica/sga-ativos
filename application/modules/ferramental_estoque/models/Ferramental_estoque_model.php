<?php 

class ferramental_estoque_model extends MY_Model {

	public function __construct()
	{
		parent::__construct();
		$this->log = new Syslog();
		$this->load->model('ativo_externo/ativo_externo_model'); 
  }
	
	public function get_lista_estoque($id_obra){
		
	}
  
}