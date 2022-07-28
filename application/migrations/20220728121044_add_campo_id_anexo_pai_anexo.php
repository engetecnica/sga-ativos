<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campo_Id_Anexo_Pai_Anexo extends CI_Migration {
	private $table = 'anexo';

    //Upgrade migration
	public function up(){
		if (
			$this->db->table_exists($this->table) && 
			!$this->db->field_exists('id_anexo_pai', $this->table)
		) {
			$this->db->query("
				alter table {$this->table} 
				add column `id_anexo_pai` INT(10) NULL DEFAULT NULL after `id_anexo`;
			");
		}
	}
	
    //Downgrade migration
	public function down(){
		if (
			$this->db->table_exists($this->table) && 
			$this->db->field_exists('id_anexo_pai', $this->table)
		) {
			$this->db->query("alter table {$this->table} drop column `id_anexo_pai`;");
		}
	}
}