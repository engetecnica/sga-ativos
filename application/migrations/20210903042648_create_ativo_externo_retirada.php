<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Externo_Retirada extends CI_Migration {
	private $table = 'ativo_externo_retirada';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_retirada int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_obra int(11) NOT NULL')
			->add_field('id_funcionario int(11) NOT NULL')
			->add_field('data_inclusao datetime NOT NULL DEFAULT current_timestamp()')
			->add_field('devolucao_prevista datetime NULL DEFAULT NULL')
			->add_field("status int(11) DEFAULT 1 COMMENT '1:Pendente, 9:Devolvido'")
			->add_field('observacoes text NOT NULL')
			->add_field('termo_de_responsabilidade text DEFAULT NULL')
			->create_table($this->table);
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->dbforge->drop_table($this->table);
		}
	}
}