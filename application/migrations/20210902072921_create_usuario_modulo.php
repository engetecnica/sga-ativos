<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Usuario_Modulo extends CI_Migration {
	private $table = 'usuario_modulo';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_usuario_nivel int(10) NOT NULL DEFAULT 0')
			->add_field('id_modulo int(10) NOT NULL DEFAULT 0')
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