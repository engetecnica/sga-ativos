<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Obra_Gerencia_Usuario extends CI_Migration {
	private $table = 'usuario';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && !$this->db->field_exists('id_obra_gerencia', $this->table)) {
			$this->db->query("alter table {$this->table} add column id_obra_gerencia int(10) NULL DEFAULT NULL after id_obra;");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && $this->db->field_exists('id_obra_gerencia', $this->table)) {
			$this->db->query("alter table {$this->table} drop column id_obra_gerencia;");
		}
	}
}