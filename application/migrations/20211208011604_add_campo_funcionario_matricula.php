<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campo_Funcionario_Matricula extends CI_Migration {
	private $table = 'funcionario';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && !$this->db->field_exists('matricula', $this->table)) {
			$this->db->query("alter table {$this->table} add column matricula text NOT NULL after nome");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && $this->db->field_exists('matricula', $this->table)) {
			$this->db->query("alter table {$this->table} drop column matricula;");
		}
	}
}