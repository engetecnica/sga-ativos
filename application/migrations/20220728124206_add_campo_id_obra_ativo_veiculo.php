<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campo_Id_Obra_Ativo_Veiculo extends CI_Migration {
	private $table = 'ativo_veiculo';

     //Upgrade migration
	public function up(){
		if (
			$this->db->table_exists($this->table) && 
			!$this->db->field_exists('id_obra', $this->table)
		) {
			$this->db->query("
				alter table {$this->table} 
				add column `id_obra` INT(10) NOT NULL DEFAULT 0 after `tipo_veiculo`;
			");
		}
	}
	
    //Downgrade migration
	public function down(){
		if (
			$this->db->table_exists($this->table) && 
			$this->db->field_exists('id_obra', $this->table)
		) {
			$this->db->query("alter table {$this->table} drop column `id_obra`;");
		}
	}
}