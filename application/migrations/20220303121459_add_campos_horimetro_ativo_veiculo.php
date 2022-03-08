<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campos_Horimetro_Ativo_Veiculo extends CI_Migration {
	private $table = 'ativo_veiculo';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && !$this->db->field_exists('veiculo_horimetro', $this->table)) {
			$this->db->query("
				alter table {$this->table}
				add column veiculo_horimetro int(10) NULL DEFAULT NULL after veiculo_km_data,
				add column veiculo_horimetro_data date NULL DEFAULT NULL after veiculo_horimetro;
			");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && $this->db->field_exists('veiculo_horimetro', $this->table)) {
			$this->db->query("
				alter table {$this->table}
				drop column veiculo_horimetro,
				drop column veiculo_horimetro_data;
			");
		}
	}
}