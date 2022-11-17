<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Insumo extends CI_Migration {
	private $table = 'table_name';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_insumo int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_insumo_configuracao')
			->add_field('titulo')
			->add_field('quantidade')
			->add_field('valor')
			->add_field('')
			->add_field('')
			// Outras colunas
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