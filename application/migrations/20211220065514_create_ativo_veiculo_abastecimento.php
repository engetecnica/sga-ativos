<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Veiculo_Abastecimento extends CI_Migration {
	private $table = 'ativo_veiculo_abastecimento';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_ativo_veiculo_abastecimento int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_ativo_veiculo int(10) NOT NULL')
			->add_field('id_fornecedor int(10) NULL DEFAULT NULL')
			->add_field('veiculo_km int(10) NOT NULL')
			->add_field('combustivel varchar(15) NOT NULL')
			->add_field("combustivel_unidade_tipo enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Litro,1=Metro Cubico'")
			->add_field('combustivel_unidade_valor DECIMAL(13, 2) NOT NULL')
			->add_field('combustivel_unidade_total DECIMAL(13, 2) NULL DEFAULT NULL')
			->add_field('abastecimento_custo DECIMAL(13, 2) NOT NULL')
			->add_field('abastecimento_data timestamp NOT NULL DEFAULT current_timestamp()')
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