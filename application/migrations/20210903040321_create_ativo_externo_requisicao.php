<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Externo_Requisicao extends CI_Migration {
	private $table = 'ativo_externo_requisicao';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_requisicao int(10) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_origem int(10) DEFAULT NULL')
			->add_field('id_destino int(11) NOT NULL')
			->add_field('id_solicitante int(11) NOT NULL')
			->add_field('id_despachante int(11) DEFAULT NULL')
			->add_field('data_inclusao timestamp NOT NULL DEFAULT current_timestamp()')
			->add_field('data_liberado timestamp NULL DEFAULT NULL')
			->add_field('data_transferido datetime DEFAULT NULL')
			->add_field('data_recebido datetime DEFAULT NULL')
			->add_field("tipo int(11) DEFAULT 1 COMMENT '1: Requisição, 2:Devolução'")
			->add_field("status int(10) NOT NULL DEFAULT 1 COMMENT '1: Pendente, 2: Liberado'")
			->add_field('id_requisicao_mae int(11) NULL DEFAULT NULL')
			->add_field('id_requisicao_filha int(11) NULL DEFAULT NULL')
			->add_field('data_inclusao_filha timestamp NULL DEFAULT NULL')
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