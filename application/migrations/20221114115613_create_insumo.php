<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Insumo extends CI_Migration {
	private $table = 'table_name';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_insumo int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_insumo_configuracao int(11) NOT NULL')
			->add_field('id_fornecedor int(11) NOT NULL ')
			->add_field('titulo varchar(255) NOT NULL')
			->add_field('codigo_insumo varchar(255) NOT NULL')
			->add_field('quantidade int(11) NOT NULL')
			->add_field('valor DECIMAL(13, 2) NOT NULL DEFAULT 0')
			->add_field('funcao varchar(255) NULL')
			->add_field('composicao varchar(255) NULL')
			->add_field('descricao text NULL')
			->add_field("situacao enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Ativo,1=Inativo'")
			->add_field('data timestamp NOT NULL DEFAULT current_timestamp()')

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