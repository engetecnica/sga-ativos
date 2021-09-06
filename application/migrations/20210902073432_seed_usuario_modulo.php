<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Usuario_Modulo extends CI_Migration {
	private $table = 'usuario_modulo';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table)) {
			$this->db->query("INSERT INTO `{$this->table}` VALUES 
			(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),
			(1,14),(2,12),(2,14),(1,13),(2,13),(1,15),(1,16),(1,17),(1,18);");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->db->query("DELETE FROM {$this->table} WHERE 1;");
		}
	}
}