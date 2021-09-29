<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Obra extends CI_Migration {
	private $table = 'obra';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_obra int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_empresa int(10) DEFAULT NULL')
			->add_field('codigo_obra varchar(255) NOT NULL')
			->add_field('endereco varchar(255) NULL DEFAULT NULL')
			->add_field('endereco_numero varchar(30) NULL DEFAULT NULL')
			->add_field('endereco_complemento varchar(255) NULL DEFAULT NULL')
			->add_field('endereco_bairro varchar(255) NULL DEFAULT NULL')
			->add_field('endereco_cep varchar(15) NULL DEFAULT NULL')
			->add_field('endereco_cidade varchar(255) NULL DEFAULT NULL')
			->add_field('endereco_estado int(10) NULL DEFAULT NULL')
			->add_field('responsavel varchar(255) NULL DEFAULT NULL')
			->add_field('responsavel_telefone varchar(50) NULL DEFAULT NULL')
			->add_field('responsavel_celular varchar(50) NULL DEFAULT NULL')
			->add_field('responsavel_email varchar(255) NULL DEFAULT NULL')
			->add_field('observacao text NULL DEFAULT NULL')
			->add_field('data_criacao timestamp NOT NULL DEFAULT current_timestamp()')
			->add_field('data_atualizacao timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()')
			->add_field("situacao enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Ativo, 1=Inativo'")
			->add_field("obra_base enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Não, 1=Sim'")
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