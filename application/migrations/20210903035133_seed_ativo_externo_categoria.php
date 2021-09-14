<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Ativo_Externo_Categoria extends CI_Migration {
	private $table = 'ativo_externo_categoria';

   //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && 
			$this->db->where("id_ativo_externo_categoria BETWEEN 1 AND 3")->get($this->table)->num_rows() == 0) {
			$this->db->query(
				"INSERT INTO `{$this->table}` VALUES 
				(1, 'Materiais Elétricos'), 
				(2, 'Materiais Hidráulicos'), 
				(3, 'Equipamentos de Proteção Individual (EPI)');"
			);
		}
	}

	//Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && 
			$this->db->where("id_ativo_externo_categoria BETWEEN 1 AND 3")->get($this->table)->num_rows() == 3) {
			$this->db->query("DELETE FROM {$this->table} WHERE id_ativo_externo_categoria BETWEEN 1 AND 3;");
		}
	}
}