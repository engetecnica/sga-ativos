<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Funcionario extends CI_Migration {
	private $table = 'funcionario';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_funcionario int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_empresa int(11) NOT NULL')
			->add_field('id_obra int(11) NOT NULL')
			->add_field('nome varchar(255) NOT NULL')
			->add_field('rg varchar(255) NOT NULL')
			->add_field('cpf varchar(100) NOT NULL')
			->add_field('data_nascimento date NOT NULL')
			->add_field('endereco varchar(255) NULL DEFAULT NULL')
			->add_field('endereco_numero varchar(30) NULL DEFAULT NULL')
			->add_field('endereco_complemento varchar(255) NULL DEFAULT NULL')
			->add_field('endereco_bairro varchar(255) NULL DEFAULT NULL')
			->add_field('endereco_cep varchar(15) NULL DEFAULT NULL')
			->add_field('endereco_cidade varchar(255) NULL DEFAULT NULL')
			->add_field('endereco_estado int(10) NULL DEFAULT 0')
			->add_field('email varchar(255) NULL DEFAULT NULL')
			->add_field('telefone varchar(255) NULL DEFAULT NULL')
			->add_field('celular varchar(255) NULL DEFAULT NULL')
			->add_field('observacao text NULL DEFAULT NULL')
			->add_field('data_criacao timestamp NOT NULL DEFAULT current_timestamp()')
			->add_field('data_atualizacao timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()')
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