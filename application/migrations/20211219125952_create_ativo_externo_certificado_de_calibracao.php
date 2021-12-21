<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_Ativo_Externo_Certificado_De_Calibracao extends CI_Migration {
	private $table = 'ativo_externo_certificado_de_calibracao';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->dbforge
			->add_field('id_certificado int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY')
			->add_field('id_ativo_externo int(11) NOT NULL')
			->add_field('id_anexo int(11) NOT NULL')
			->add_field('observacao text NULL DEFAULT NULL')
			->add_field('data_inclusao date')
			->add_field('data_vencimento date')
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