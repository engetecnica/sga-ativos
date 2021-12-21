<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_Campos_Usuario extends CI_Migration {
	private $table = 'usuario';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && !$this->db->field_exists('nome', $this->table)) {
			$this->dbforge->add_column($this->table, 'nome varchar(100) NULL DEFAULT NULL AFTER id_obra');
			$this->dbforge->add_column($this->table, 'email varchar(50) NULL DEFAULT NULL AFTER usuario');
			$this->dbforge->add_column($this->table, 'email_confirmado_em timestamp NULL DEFAULT NULL AFTER email');
			$this->dbforge->add_column($this->table, 'codigo_recuperacao varchar(50) NULL DEFAULT NULL AFTER email_confirmado_em');
			$this->dbforge->add_column($this->table, 'codigo_recuperacao_validade timestamp NULL DEFAULT NULL AFTER codigo_recuperacao');
			$this->dbforge->add_column($this->table, 'avatar varchar(255) NULL DEFAULT NULL AFTER codigo_recuperacao_validade');
			$this->dbforge->add_column($this->table, "permit_notification_email enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Não, 1=Sim' after situacao");
			$this->dbforge->add_column($this->table, "permit_notification_push enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Não, 1=Sim' after permit_notification_email" );
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && $this->db->field_exists('nome', $this->table)) {
			$this->dbforge->drop_column($this->table, 'nome');
			$this->dbforge->drop_column($this->table, 'email');
			$this->dbforge->drop_column($this->table, 'email_confirmado_em');
			$this->dbforge->drop_column($this->table, 'codigo_recuperacao');
			$this->dbforge->drop_column($this->table, 'codigo_recuperacao_validade');
			$this->dbforge->drop_column($this->table, 'avatar');
			$this->dbforge->drop_column($this->table, 'permit_notification_email');
			$this->dbforge->drop_column($this->table, 'permit_notification_push');
		}
	}
}