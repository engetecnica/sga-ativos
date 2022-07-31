<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Veiculo_Obra extends CI_Migration {
	private $table = 'ativo_veiculo_obra';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_veiculo_obra int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_veiculo int(10) NULL DEFAULT NULL')
			->add_field('id_obra int(10) NULL DEFAULT NULL')
			->add_field('periodo_inicial date NULL DEFAULT NULL')
			->add_field('periodo_final date NULL DEFAULT NULL')
			->add_field('created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP')
			->add_field('deleted_at timestamp NULL DEFAULT NULL')
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