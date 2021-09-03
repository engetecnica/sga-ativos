<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Externo_Retirada_Item extends CI_Migration {
	private $table = 'ativo_externo_retirada_item';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_retirada_item int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_retirada int(11) NOT NULL')
			->add_field('id_ativo_externo_grupo int(11) NOT NULL')
			->add_field('quantidade int(11) NOT NULL DEFAULT 1')
			->add_field('data_retirada datetime DEFAULT NULL')
			->add_field('data_devolucao datetime DEFAULT NULL')
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