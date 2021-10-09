<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Externo_Requisicao_Ativo extends CI_Migration {
	private $table = 'ativo_externo_requisicao_ativo';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_requisicao_ativo int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_requisicao int(11) NOT NULL')
			->add_field('id_requisicao_item int(11) NOT NULL')
			->add_field('id_ativo_externo int(11) NOT NULL')
			->add_field('data_liberado datetime NOT NULL DEFAULT current_timestamp()')
			->add_field('data_transferido timestamp NULL DEFAULT NULL')
			->add_field('data_recebido datetime DEFAULT NULL')
			->add_field('status int(11) NOT NULL DEFAULT 1')
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