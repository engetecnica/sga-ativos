<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Modulo extends CI_Migration {
	private $table = 'modulo';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table)) {
			//Seeding
			$this->db->query("INSERT INTO `{$this->table}` VALUES 
				(1,0,'Cadastros','#','fas fa-hashtag'),
				(2,1,'Usuários','usuario','fas fa-smile'),
				(3,1,'Funcionários','funcionario','fas fa-users'),
				(4,1,'Empresas','empresa','fas fa-coffee'),
				(5,1,'Fornecedores','fornecedor','fas fa-minus'),
				(6,1,'Obras','obra','fas fa-check'),
				(7,0,'Ativos','#','fas fa-tasks'),
				(8,7,'Configurações','ativo_configuracao','fas fa-cog'),
				(9,7,'Veículos','ativo_veiculo','fas fa-truck'),
				(10,7,'Internos','ativo_interno','fas fa-folder'),
				(11,12,'Externos','ativo_externo','fas fa-wrench'),
				(12,0,'Ferramental','#','fas fa-bars'),
				(13,12,'Estoque','ferramental_estoque','fas fa-cubes'),
				(14,12,'Requisição','ferramental_requisicao','fas fa-dolly-flatbed'),
				(15,0,'Relatórios','#','fa fa-line-chart'),
				(16,15,'Gerar','relatorio','fa fa-filter');"
			);
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->db->query("DELETE FROM {$this->table} WHERE 1;");
		}
	}
}