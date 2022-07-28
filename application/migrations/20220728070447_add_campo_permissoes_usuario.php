<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campo_Permissoes_Usuario extends CI_Migration {
	private $table = 'usuario';

     //Upgrade migration
	public function up(){
		if (
			$this->db->table_exists($this->table) && 
			!$this->db->field_exists('permissoes', $this->table)
		) {
			$this->db->query("
				alter table {$this->table} 
				add column `permissoes` TEXT NULL DEFAULT NULL after `nivel`;
			");
		}
	}
	
    //Downgrade migration
	public function down(){
		if (
			$this->db->table_exists($this->table) && 
			$this->db->field_exists('permissoes', $this->table)
		) {
			$this->db->query("alter table {$this->table} drop column `permissoes`;");
		}
	}
}