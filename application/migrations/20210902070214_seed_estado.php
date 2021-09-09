<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Estado extends CI_Migration {
	private $table = 'estado';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && $this->db->where('id_estado BETWEEN 1 AND 27')->get($this->table)->num_rows() == 0) {
            $this->db->query("INSERT INTO `{$this->table}` VALUES (1,12,'Acre','AC',1),(2,27,'Alagoas','AL',2),
            (3,16,'Amapá','AP',1),(4,13,'Amazonas','AM',1),(5,29,'Bahia','BA',2),
            (6,23,'Ceará','CE',2),(7,53,'Distrito Federal','DF',5),(8,32,'Espírito Santo','ES',3),
            (9,52,'Goiás','GO',5),(10,21,'Maranhão','MA',2),(11,51,'Mato Grosso','MT',5),
            (12,50,'Mato Grosso do Sul','MS',5),(13,31,'Minas Gerais','MG',3),(14,15,'Pará','PA',1),
            (15,25,'Paraíba','PB',2),(16,41,'Paraná','PR',4),(17,26,'Pernambuco','PE',2),
            (18,22,'Piauí','PI',2),(19,33,'Rio de Janeiro','RJ',3),(20,24,'Rio Grande do Norte','RN',2),
            (21,43,'Rio Grande do Sul','RS',4),(22,11,'Rondônia','RO',1),(23,14,'Roraima','RR',1),
            (24,42,'Santa Catarina','SC',4),(25,35,'São Paulo','SP',3),(26,28,'Sergipe','SE',2),
            (27,17,'Tocantins','TO',1);");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->db->query("DELETE FROM {$this->table} WHERE id_estado BETWEEN 1 AND 27;");
		}
	}
}