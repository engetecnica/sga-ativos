<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Estado extends CI_Migration {
	private $table = 'estado';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_estado int(11) NOT NULL PRIMARY KEY')
			->add_field('codigo_uf int(11) NOT NULL')
			->add_field('estado varchar(50) NOT NULL')
			->add_field('uf char(2) NOT NULL')
			->add_field('regiao int(11) NOT NULL')
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