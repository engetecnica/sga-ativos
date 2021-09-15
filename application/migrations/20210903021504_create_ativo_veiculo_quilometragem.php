<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Veiculo_Quilometragem extends CI_Migration {
	private $table = 'ativo_veiculo_quilometragem';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_ativo_veiculo_quilometragem int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_ativo_veiculo int(10) NOT NULL')
			->add_field('veiculo_km_inicial int(10) NOT NULL')
			->add_field('veiculo_km_final int(10) NOT NULL')
			->add_field('veiculo_litros float NOT NULL')
			->add_field('veiculo_custo DECIMAL(13, 2) NOT NULL')
			->add_field('veiculo_km_data date NOT NULL')
			->add_field('comprovante_fiscal varchar(255) NOT NULL')
			->add_field('data timestamp NOT NULL DEFAULT current_timestamp()')
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