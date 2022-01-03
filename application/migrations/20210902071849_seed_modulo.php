<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Modulo extends CI_Migration {
	private $table = 'modulo';

    //Upgrade migration
	public function up(){
		if ($this->db->table_exists($this->table) && $this->db->where('id_modulo BETWEEN 1 AND 18')->get($this->table)->num_rows() == 0) {
			//Seeding
			$this->db->query("INSERT INTO `{$this->table}` VALUES 
				(1,0,'Cadastros','#','fa fa-archive'),	
				(2,1,'Usuários','usuario','fas fa-users'),
				(3,1,'Funcionários','funcionario','fa fa-address-card'),
				(4,1,'Empresas','empresa','fa fa-suitcase'),
				(5,1,'Fornecedores','fornecedor','fa fa-handshake-o'),
				(6,1,'Obras','obra','fa fa-university'),
				(7,0,'Ativos','#','fa fa-cube'),
				(9,7,'Veículos','ativo_veiculo','fas fa-truck'),
				(10,7,'Equipamentos','ativo_interno','fa fa-television'),
				(11,12,'Externos','ativo_externo','fas fa-wrench'),
				(12,0,'Ferramental','#','fa fa-gavel'),
				(13,12,'Retiradas','ferramental_estoque','fas fa-cubes'),
				(14,12,'Requisições','ferramental_requisicao','fas fa-dolly-flatbed'),
				(15,0,'Relatórios','relatorio','fa fa-line-chart'),
				(16,16,'Gerar','relatorio','fa fa-filter'),
				(17,0,'Anexos','anexo','fa fa-files-o'),
				(18,18,'Listar','anexo','fa fa-file-archive-o')
				(19,0,'Configurações','#','fas fa-cog'),
				(20,19,'Geral','configuracao','fa fa-cogs'),
				(21,19,'Ativos','ativo_configuracao','fa fa-sliders'),;"
			);
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table) && $this->db->where('id_modulo BETWEEN 1 AND 18')->get($this->table)->num_rows() == 18) {
			$this->db->query("DELETE FROM {$this->table} WHERE id_modulo BETWEEN 1 AND 18;");
		}
	}
}