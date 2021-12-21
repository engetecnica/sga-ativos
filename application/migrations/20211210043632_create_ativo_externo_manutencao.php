<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Externo_Manutencao extends CI_Migration {
	private $table = 'ativo_externo_manutencao';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_manutencao int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_ativo_externo int(11) NOT NULL')
			->add_field('valor DECIMAL(13, 2) NOT NULL')
			->add_field('data_saida timestamp NULL DEFAULT current_timestamp()')
			->add_field('data_retorno timestamp NULL DEFAULT NULL')
			->add_field("situacao enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0: Em manutenção, 1: Retorno OK, 2:Retorno com pendência'")
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