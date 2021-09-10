<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Anexo extends CI_Migration {
	private $table = 'anexo';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_anexo int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_usuario int(11) NULL')
			->add_field('id_modulo int(11) NULL')
			->add_field('id_modulo_item int(11) NULL')
			->add_field('id_modulo_subitem int(11) NULL')
			->add_field('id_configuracao int(11) NULL')
			->add_field('titulo varchar(255) NULL')
			->add_field('tipo varchar(255) NULL')
			->add_field('descricao text NULL')
			->add_field('anexo varchar(255) NOT NULL')
			->add_field('data_inclusao timestamp NOT NULL DEFAULT current_timestamp()')
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