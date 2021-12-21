<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Externo extends CI_Migration {
	private $table = 'ativo_externo';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_ativo_externo int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_ativo_externo_categoria int(10) NOT NULL DEFAULT 0')
			->add_field('id_ativo_externo_grupo int(11) DEFAULT NULL')
			->add_field('id_obra int(10) DEFAULT NULL')
			->add_field('nome varchar(255) NOT NULL')
			->add_field('codigo varchar(255) NOT NULL')
			->add_field('observacao text DEFAULT NULL')
			->add_field('data_inclusao timestamp NOT NULL DEFAULT current_timestamp()')
			->add_field('data_descarte timestamp NULL DEFAULT NULL')
			->add_field('situacao int(1) NOT NULL DEFAULT 0')
			->add_field('tipo int(1) NOT NULL DEFAULT 0')
			->add_field('valor decimal(65,2) NOT NULL DEFAULT 0.00')
			->add_field("necessita_calibracao enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Não,1=Sim'")
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