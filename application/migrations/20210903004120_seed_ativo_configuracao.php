<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Ativo_Configuracao extends CI_Migration {
	private $table = 'ativo_configuracao';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table)) {
			$this->db->query("INSERT INTO `ativo_configuracao` VALUES 
				(2,0,'Tipo de Ferramenta', 'tipo-ferramenta', '0'),
				(3,0,'Tipo de Equipamento','tipo-equipamento','0'),
				(4,0,'Tipo de Custo','tipo-custo','0'),
				(5,4,'IPVA','ipva','0'),
				(6,4,'Manutenção','manutencao','0'),
				(7,4,'Combustível','combustivel','0'),
				(8,4,'Seguro','seguro','0'),
				(9,4,'Mão de Obra','mao-de-obra','0'),
				(10,0,'Serviços Mecânicos','servico-mecanico','0'),
				(11,10,'Troca de Óleo','troca-oleo','0'),
				(12,10,'Substituição de Peças','troca-pecas','0');"
			);
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->dbforge->drop_table($this->table);
		}
	}
}