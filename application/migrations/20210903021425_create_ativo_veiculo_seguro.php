<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Veiculo_Seguro extends CI_Migration {
	private $table = 'ativo_veiculo_seguro';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_ativo_veiculo_seguro int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_ativo_veiculo int(10) NOT NULL')
			->add_field('seguro_custo DECIMAL(13, 2) NOT NULL')
			->add_field('carencia_inicio date NOT NULL')
			->add_field('carencia_fim date NOT NULL')
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