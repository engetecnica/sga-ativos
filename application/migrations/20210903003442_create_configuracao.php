<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Configuracao extends CI_Migration {
	private $table = 'configuracao';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_configuracao int(10) NOT NULL PRIMARY KEY')
			->add_field('app_descricao varchar(255) NULL DEFAULT NULL')
			->add_field('origem_email varchar(50) NULL DEFAULT NULL')
			->add_field('km_alerta int(10) NULL DEFAULT NULL')
			->add_field('operacao_alerta int(10) NULL DEFAULT NULL')
			->add_field("permit_notificacoes enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=Inativo,1=Ativo'")
			->add_field('one_signal_apiurl varchar(255) NULL DEFAULT NULL')
			->add_field('one_signal_appid varchar(255) NULL DEFAULT NULL')
			->add_field('one_signal_apikey varchar(255) NULL DEFAULT NULL')
			->add_field('one_signal_safari_web_id varchar(255) NULL DEFAULT NULL')
			->add_field('valor_medio_gasolina varchar(255) NULL DEFAULT NULL')
			->add_field('valor_medio_disel varchar(255) NULL DEFAULT NULL')
			->add_field('valor_medio_etanol varchar(255) NULL DEFAULT NULL')
			->add_field('valor_medio_gnv varchar(255) NULL DEFAULT NULL')
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