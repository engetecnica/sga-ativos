<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Interno extends CI_Migration {
	private $table = 'ativo_interno';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_ativo_interno int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_obra int(11) NULL DEFAULT NULL')
			->add_field('nome varchar(255) NOT NULL')
			->add_field('marca varchar(255) NULL DEFAULT NULL')
			->add_field('valor DECIMAL(13, 2) NOT NULL')
			->add_field('quantidade int(10) NOT NULL DEFAULT 1')
			->add_field('observacao text NOT NULL')
			->add_field('data_inclusao timestamp NOT NULL DEFAULT current_timestamp()')
			->add_field('data_descarte timestamp NULL DEFAULT NULL')
			->add_field("situacao int(1) NOT NULL DEFAULT 0 COMMENT '0:Ativo , 1: Inativo , 2: Descartado'")
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