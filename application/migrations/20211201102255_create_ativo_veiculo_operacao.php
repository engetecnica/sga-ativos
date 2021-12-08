<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Veiculo_Operacao extends CI_Migration {
    private $table = 'ativo_veiculo_operacao';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_ativo_veiculo_operacao int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_ativo_veiculo int(10) NOT NULL')
			->add_field('operacao_tempo int NOT NULL')
			->add_field('operacao_periodo_inicio timestamp NULL DEFAULT NULL')
			->add_field('operacao_periodo_fim timestamp NULL DEFAULT NULL')
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