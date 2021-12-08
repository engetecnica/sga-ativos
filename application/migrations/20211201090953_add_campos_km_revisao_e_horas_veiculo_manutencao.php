<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campos_Km_Revisao_E_Horas_Veiculo_Manutencao extends CI_Migration {
	private $table = 'ativo_veiculo_manutencao';

    //Upgrade migration
	public function up(){
		if (
			$this->db->table_exists($this->table) && 
			(!$this->db->field_exists('veiculo_km_proxima_revisao', $this->table) && !$this->db->field_exists('veiculo_hora_proxima_revisao', $this->table))
		) {
			$this->db->query("alter table {$this->table} add column veiculo_km_proxima_revisao int(10) NULL DEFAULT NULL after veiculo_km_atual,
			add column veiculo_hora_proxima_revisao int(12) NULL DEFAULT NULL after veiculo_km_proxima_revisao;");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && 
			($this->db->field_exists('veiculo_km_proxima_revisao', $this->table) && $this->db->field_exists('veiculo_hora_proxima_revisao', $this->table))
		) {
			$this->db->query("alter table {$this->table} drop column veiculo_km_proxima_revisao, drop column veiculo_hora_proxima_revisao;");
		}
	}
}