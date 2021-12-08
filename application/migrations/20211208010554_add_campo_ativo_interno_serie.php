<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campo_Ativo_Interno_Serie extends CI_Migration {
	private $table = 'ativo_interno';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && !$this->db->field_exists('serie', $this->table)) {
			$this->db->query("alter table {$this->table} add column serie text NOT NULL after id_obra");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && $this->db->field_exists('serie', $this->table)) {
			$this->db->query("alter table {$this->table} drop column serie;");
		}
	}
}