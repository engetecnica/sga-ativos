<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Default_Admin extends CI_Migration {
	private $table = 'usuario';

	//Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && $this->db->where("usuario='engetecnica'")->get($this->table)->num_rows() == 0) {
			/**
			 * Usuario: engetecnica
			 * Senha: 123456
			 * Nivel: Administrador
			*/
			
			$this->db->query(
				"INSERT INTO `usuario` (id_usuario, id_empresa, id_obra, usuario, nome, senha, data_criacao, nivel, situacao)
				VALUES (1,1,1,'engetecnica','Engetecnica' ,'7c4a8d09ca3762af61e59520943dc26494f8941b','2020-08-13 15:58:49',1,'0');"
			);
		}
	}

	//Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && $this->db->where("usuario='engetecnica'")->get($this->table)->num_rows() == 1) {
			$this->db->query("DELETE FROM {$this->table} WHERE usuario='engetecnica';");
		}
	}
}