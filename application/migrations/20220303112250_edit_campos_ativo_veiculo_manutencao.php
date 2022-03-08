<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Edit_Campos_Ativo_Veiculo_Manutencao extends CI_Migration {
	private $table = 'ativo_veiculo_manutencao';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && $this->db->field_exists('veiculo_hora_proxima_revisao', $this->table)) {
			$this->db->query("
				alter table {$this->table} 
				rename column veiculo_hora_proxima_revisao to veiculo_horimetro_proxima_revisao,
				add column veiculo_horimetro_atual int(10) NULL DEFAULT NULL after veiculo_km_proxima_revisao;
			");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && $this->db->field_exists('veiculo_horimetro_proxima_revisao', $this->table)) {
			$this->db->query("
				alter table {$this->table} 
				rename column veiculo_horimetro_proxima_revisao to veiculo_hora_proxima_revisao,
				drop column veiculo_horimetro_atual;
			");
		}
	}
}