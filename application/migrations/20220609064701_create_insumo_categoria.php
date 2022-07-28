<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Insumo_Categoria extends CI_Migration {
	private $table = 'insumo_categoria';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_insumo int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_usuario int(11) NOT NULL')
			->add_field('titulo varchar(255) NOT NULL')
			->add_field('created_at timestamp NULL DEFAULT current_timestamp()')
			->add_field('updated_at timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp()')			
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