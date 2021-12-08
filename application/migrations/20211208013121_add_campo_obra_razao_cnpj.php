<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campo_Obra_Razao_Cnpj extends CI_Migration {
	private $table = 'obra';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) &&
		 	(!$this->db->field_exists('obra_razaosocial', $this->table) && !$this->db->field_exists('obra_cnpj', $this->table))	) {
			$this->db->query("alter table {$this->table} add column obra_razaosocial text NOT NULL after codigo_obra, add column obra_cnpj text NOT NULL after obra_razaosocial");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && 
			($this->db->field_exists('obra_razaosocial', $this->table) && $this->db->field_exists('obra_cnpj', $this->table))) {
			$this->db->query("alter table {$this->table} drop column obra_razaosocial, drop column obra_cnpj;");
		}
	}
}