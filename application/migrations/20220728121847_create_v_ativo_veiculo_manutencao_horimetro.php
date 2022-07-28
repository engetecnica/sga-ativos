<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_V_Ativo_Veiculo_Manutencao_Horimetro extends CI_Migration {
	private $table = 'v_ativo_veiculo_manutencao_horimetro';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->db->query("
			CREATE VIEW `v_ativo_veiculo_manutencao_horimetro` AS 
			SELECT `c1`.`veiculo_horimetro_atual` AS `veiculo_horimetro_atual`, `c1`.`id_ativo_veiculo_manutencao` AS `id_ativo_veiculo_manutencao`, `c1`.`veiculo_horimetro_proxima_revisao` AS `veiculo_horimetro_proxima_revisao`, `c1`.`id_ativo_veiculo` AS `id_ativo_veiculo`, `c2`.`marca` AS `marca`, `c2`.`modelo` AS `modelo`, `c1`.`veiculo_custo` AS `veiculo_custo`, (select `ativo_veiculo_operacao`.`veiculo_horimetro` from `ativo_veiculo_operacao` where (`ativo_veiculo_operacao`.`id_ativo_veiculo` = `c2`.`id_ativo_veiculo`) order by `ativo_veiculo_operacao`.`id_ativo_veiculo_operacao` desc limit 1) AS `veiculo_horimetro`, `c2`.`veiculo` AS `veiculo`, `c2`.`id_interno_maquina` AS `id_interno_maquina`, `c2`.`data` AS `data` FROM (`ativo_veiculo_manutencao` `c1` join `ativo_veiculo` `c2` on((`c2`.`id_ativo_veiculo` = `c1`.`id_ativo_veiculo`))) WHERE ((`c1`.`veiculo_km_atual` = 0) AND ((`c1`.`veiculo_horimetro_proxima_revisao` - (select `ativo_veiculo_operacao`.`veiculo_horimetro` from `ativo_veiculo_operacao` where (`ativo_veiculo_operacao`.`id_ativo_veiculo` = `c2`.`id_ativo_veiculo`) order by `ativo_veiculo_operacao`.`id_ativo_veiculo_operacao` desc limit 1)) <= (select `configuracao`.`operacao_alerta` from `configuracao` where (`configuracao`.`id_configuracao` = 1)))) ;");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->db->query("DROP VIEW {$this->table}");
		}
	}
}