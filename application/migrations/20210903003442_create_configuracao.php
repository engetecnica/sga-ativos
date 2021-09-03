<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Configuracao extends CI_Migration {
	private $table = 'configuracao';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_configuracao int(10) NOT NULL PRIMARY KEY')
			->add_field('id_categoria int(10) NOT NULL DEFAULT 0')
			->add_field('categoria varchar(255) NOT NULL')
			->add_field('data_inclusao timestamp NOT NULL DEFAULT current_timestamp()')
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