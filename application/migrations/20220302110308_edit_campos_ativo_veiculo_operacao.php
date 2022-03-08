<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Edit_Campos_Ativo_Veiculo_Operacao extends CI_Migration {
	private $table = 'ativo_veiculo_operacao';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && $this->db->field_exists('operacao_tempo', $this->table)) {
			$this->db->query("
				alter table {$this->table} 
				rename column operacao_tempo to veiculo_horimetro,
				rename column data_inclusao to `data`,
				drop column operacao_periodo_inicio,
				drop column operacao_periodo_fim;
			");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && $this->db->field_exists('veiculo_horimetro', $this->table)) {
			$this->db->query("
				alter table {$this->table} 
				rename column veiculo_horimetro to operacao_tempo,
				rename column `data` to data_inclusao,
				add column operacao_periodo_inicio timestamp NULL DEFAULT NULL after operacao_tempo,
				add column operacao_periodo_fim timestamp NULL DEFAULT NULL after operacao_periodo_inicio;
			");
		}
	}
}