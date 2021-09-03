<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Externo_Requisicao_Status extends CI_Migration {
	private $table = 'ativo_externo_requisicao_status';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_requisicao_status int(11) NOT NULL PRIMARY KEY')
			->add_field('slug varchar(255) NOT NULL')
			->add_field('texto varchar(255) NOT NULL')
			->add_field('classe varchar(255) NOT NULL')
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