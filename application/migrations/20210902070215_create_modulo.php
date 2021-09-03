<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Modulo extends CI_Migration {
	private $table = 'modulo';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_modulo int(10) NOT NULL PRIMARY KEY')
			->add_field('id_vinculo int(10) NOT NULL DEFAULT 0')
			->add_field('titulo varchar(255) NOT NULL')
			->add_field('rota varchar(255) NOT NULL')
			->add_field('icone varchar(20) NOT NULL')
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