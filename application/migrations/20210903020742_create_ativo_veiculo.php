<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Veiculo extends CI_Migration {
	private $table = 'ativo_veiculo';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_ativo_veiculo int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field("tipo_veiculo enum('carro','moto','caminhao', 'maquina') NOT NULL DEFAULT 'carro'")
			->add_field('id_marca int(10) NULL DEFAULT NULL')
			->add_field("id_modelo varchar(10) NULL DEFAULT NULL")
			->add_field('ano varchar(10) NOT NULL')
			->add_field('veiculo varchar(255) NOT NULL')
			->add_field('marca varchar(255) NULL DEFAULT NULL')
			->add_field('modelo varchar(255) NULL DEFAULT NULL')
			->add_field('combustivel varchar(255) NULL DEFAULT NULL')
			->add_field('valor_fipe DECIMAL(13, 2) NULL DEFAULT 0')
			->add_field('codigo_fipe varchar(50) NULL DEFAULT NULL')
			->add_field('fipe_mes_referencia varchar(100) NULL DEFAULT NULL')
			->add_field('veiculo_placa varchar(20) NULL DEFAULT NULL')
			->add_field('id_interno_maquina varchar(20) NULL DEFAULT NULL')
			->add_field('veiculo_renavam varchar(255) NULL DEFAULT NULL')
			->add_field('veiculo_km varchar(50) NULL DEFAULT NULL')
			->add_field('veiculo_km_data date NULL DEFAULT NULL')
			->add_field('valor_funcionario DECIMAL(13, 2) NOT NULL')
			->add_field('valor_adicional DECIMAL(13, 2) NOT NULL')
			->add_field('veiculo_observacoes text NULL DEFAULT NULL')
			->add_field('data timestamp NOT NULL DEFAULT current_timestamp()')
			->add_field("situacao enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Ativo,1=Inativo'")
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