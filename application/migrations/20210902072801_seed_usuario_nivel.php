<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Usuario_Nivel extends CI_Migration {
	private $table = 'usuario_nivel';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && 
			$this->db->where('id_usuario_nivel BETWEEN 1 AND 2')->get($this->table)->num_rows() == 0) {
			$this->db->query("INSERT INTO `{$this->table}` VALUES (1,'Administrador'),(2,'Almoxarifado');");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->db->query("DELETE FROM {$this->table} WHERE id_usuario_nivel BETWEEN 1 AND 2;");
		}
	}
}