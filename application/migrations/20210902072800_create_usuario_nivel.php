<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Usuario_Nivel extends CI_Migration {
	private $table = 'usuario_nivel';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_usuario_nivel int(10) NOT NULL DEFAULT 0 PRIMARY KEY')
			->add_field('nivel varchar(255) NOT NULL')
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