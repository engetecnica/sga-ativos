<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Interno_Manutencao_Obs extends CI_Migration {
	private $table = 'ativo_interno_manutencao_obs';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_obs int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_manutencao int(11) NOT NULL')
			->add_field('id_usuario int(11) NOT NULL')
			->add_field('texto text NOT NULL')
			->add_field('data_inclusao timestamp NOT NULL DEFAULT current_timestamp()')
			->add_field('data_edicao timestamp NULL DEFAULT NULL')
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