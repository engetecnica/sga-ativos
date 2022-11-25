<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campos_Ferramental_Estoque_Id_Ativo_Externo extends CI_Migration {
	private $table = 'ativo_externo_retirada_item';

    //Upgrade migration
	public function up(){
		if (
			$this->db->table_exists($this->table) && 
			!$this->db->field_exists('id_ativo_externo', $this->table) 
		) {
			$this->db->query("
				alter table {$this->table} 
				add column `id_ativo_externo` INT(10) NULL DEFAULT NULL after `id_retirada`;
			");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->dbforge->drop_table($this->table);
		}
	}
}