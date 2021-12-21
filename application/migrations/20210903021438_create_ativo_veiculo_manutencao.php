<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Veiculo_Manutencao extends CI_Migration {
	private $table = 'ativo_veiculo_manutencao';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_ativo_veiculo_manutencao int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_fornecedor int(10) NOT NULL')
			->add_field('id_ativo_configuracao int(10) NOT NULL')
			->add_field('id_ativo_veiculo int(10) NOT NULL')
			->add_field('veiculo_km_atual int(10) NOT NULL')
			->add_field('veiculo_custo DECIMAL(13, 2) NOT NULL')
			->add_field('descricao text NULL DEFAULT NULL')
			->add_field('data_entrada timestamp NOT NULL DEFAULT current_timestamp()')
			->add_field('data_saida timestamp NULL DEFAULT NULL')
			->add_field('data_vencimento date NULL DEFAULT NULL')
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