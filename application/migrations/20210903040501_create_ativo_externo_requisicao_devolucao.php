<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Externo_Requisicao_Devolucao extends CI_Migration {
	private $table = 'ativo_externo_requisicao_devolucao';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_devolucao int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_requisicao int(11) NOT NULL')
			->create_table($this->table);
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->dbforge->drop_table($this->table);
		}
	}
}