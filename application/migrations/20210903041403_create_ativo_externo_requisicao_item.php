<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Externo_Requisicao_Item extends CI_Migration {
	private $table = 'ativo_externo_requisicao_item';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_requisicao_item int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_requisicao int(10) NOT NULL')
			->add_field('id_ativo_externo_grupo int(10) NOT NULL')
			->add_field('quantidade int(10) NOT NULL DEFAULT 1')
			->add_field('quantidade_liberada int(10) NOT NULL DEFAULT 0')
			->add_field('data_liberado timestamp NULL DEFAULT NULL')
			->add_field('data_transferido datetime DEFAULT NULL')
			->add_field('data_recebido datetime DEFAULT NULL')
			->add_field('status int(10) NOT NULL DEFAULT 1')
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