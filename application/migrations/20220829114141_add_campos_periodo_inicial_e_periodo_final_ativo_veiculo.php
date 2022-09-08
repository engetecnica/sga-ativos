<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campos_Periodo_Inicial_E_Periodo_Final_Ativo_Veiculo extends CI_Migration {
	private $table = 'ativo_veiculo';

    //Upgrade migration
	public function up(){
		if (
			$this->db->table_exists($this->table) && 
			!$this->db->field_exists('periodo_inicial', $this->table) &&
			!$this->db->field_exists('periodo_final', $this->table)
		) {
			$this->db->query("
				alter table {$this->table} 
				add column `periodo_inicial` DATE NULL DEFAULT NULL after `situacao`,
				add column `periodo_final` DATE NULL DEFAULT NULL after `periodo_inicial`;
			");
		}
	}
	
    //Downgrade migration
	public function down(){
		if (
			$this->db->table_exists($this->table) && 
			$this->db->field_exists('periodo_inicial', $this->table) &&
			$this->db->field_exists('periodo_final', $this->table)
		) {
			$this->db->query("
				alter table {$this->table} 
				drop column `periodo_inicial`,
				drop column `periodo_final`;
			");
		}
	}
}