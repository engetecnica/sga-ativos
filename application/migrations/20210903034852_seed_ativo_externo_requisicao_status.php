<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Ativo_Externo_Requisicao_Status extends CI_Migration {
	private $table = 'ativo_externo_requisicao_status';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) &&  $this->db->where("id_requisicao_status BETWEEN 1 AND 15")->get($this->table)->num_rows() == 0) {
			$this->db->query("INSERT INTO `{$this->table}` VALUES 
			(1,'pendente','Pendente','danger'),
			(2,'liberado','Liberado','primary'),
			(3,'emtransito','Em Trânsito','warning'),
			(4,'recebido','Recebido','success'),
			(5,'emoperacao','Em Operação','light'),
			(6,'semestoque','Sem Estoque','info'),
			(7,'transferido','Transferido','danger'),
			(8,'comdefeito','Com Defeito','danger'),
			(9,'devolvido','Devolvido','secondary'),
			(10,'foradeoperacao','Fora de Operação','dark'),
			(11,'liberadoparcialmente','Liberado Parcialmente','primary2'),
			(12,'estoque','Estoque','success'),
			(13,'recebidoparcialmente','Recebido Parcialmente','success'),
			(14,'aguardandoautorizacao','Aguardando Autorizacao','warning'),
			(15,'recusado','Recusado','secondary');");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->db->query("DELETE FROM `{$this->table}` WHERE id_requisicao_status BETWEEN 1 AND 15;");
		}
	}
}