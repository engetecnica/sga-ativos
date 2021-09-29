<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Usuario extends CI_Migration {
	private $table = 'usuario';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_usuario int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_empresa int(10) NOT NULL')
			->add_field('id_obra int(10) NOT NULL')
			->add_field('usuario varchar(50) NOT NULL')
			->add_field('senha varchar(255) NOT NULL')
			->add_field('data_criacao timestamp NOT NULL DEFAULT current_timestamp()')
			->add_field('nivel int(10) NOT NULL DEFAULT 0')
			->add_field("situacao enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Ativo, 1=Inativo'")
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