<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Recreate_Ativo_Veiculo_Depreciacao extends CI_Migration {
	private $table = 'ativo_veiculo_depreciacao';

     //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && !$this->db->field_exists('fipe_ano_referencia', $this->table)) {
			$this->dbforge->drop_table($this->table);
		}

		if (!$this->db->table_exists($this->table)) {
		$this->dbforge
			->add_field('id_ativo_veiculo_depreciacao int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_ativo_veiculo int(10) NOT NULL')
			->add_field('fipe_mes_referencia int(2) NOT NULL')
			->add_field('fipe_ano_referencia  int(4) NOT NULL')
			->add_field('fipe_valor DECIMAL(13, 2) NOT NULL DEFAULT 0')
			->add_field('`data` timestamp NOT NULL DEFAULT current_timestamp()')
			->create_table($this->table);
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && $this->db->field_exists('fipe_ano_referencia', $this->table)) {
			$this->dbforge->drop_table($this->table);
		}

		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_ativo_veiculo_depreciacao int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_ativo_veiculo int(10) NOT NULL')
			->add_field('fipe_mes_referencia varchar(255) NULL DEFAULT NULL')
			->add_field('veiculo_valor_fipe DECIMAL(13, 2) NULL DEFAULT 0')
			->add_field('veiculo_km varchar(20) NULL DEFAULT NULL')
			->add_field('veiculo_valor_depreciacao DECIMAL(13, 2) NOT NULL DEFAULT 0')
			->add_field('veiculo_observacoes text NULL DEFAULT NULL')
			->add_field('veiculo_data timestamp NOT NULL DEFAULT current_timestamp()')
			->create_table($this->table);
		}
	}
}