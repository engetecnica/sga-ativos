<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_V_Ativo_Veiculo_Manutencao_Km extends CI_Migration {
	private $table = 'v_ativo_veiculo_manutencao_km';

    //Upgrade migration
	public function up(){
		if (!$this->db->table_exists($this->table)) {
			$this->db->query("
			CREATE VIEW `v_ativo_veiculo_manutencao_km` 
			AS SELECT `c1`.`id_ativo_veiculo_manutencao` AS `id_ativo_veiculo_manutencao`, `c1`.`veiculo_km_atual` AS `veiculo_km_atual`, `c1`.`veiculo_km_proxima_revisao` AS `veiculo_km_proxima_revisao`, `c1`.`id_ativo_veiculo` AS `id_ativo_veiculo`, `c2`.`marca` AS `marca`, `c2`.`modelo` AS `modelo`, `c1`.`veiculo_custo` AS `veiculo_custo` FROM (`ativo_veiculo_manutencao` `c1` join `ativo_veiculo` `c2` on((`c2`.`id_ativo_veiculo` = `c1`.`id_ativo_veiculo`))) WHERE ((`c1`.`veiculo_horimetro_atual` = 0) AND ((`c1`.`veiculo_km_proxima_revisao` - (select `ativo_veiculo_quilometragem`.`veiculo_km` from `ativo_veiculo_quilometragem` where (`ativo_veiculo_quilometragem`.`id_ativo_veiculo` = `c1`.`id_ativo_veiculo`) order by `ativo_veiculo_quilometragem`.`id_ativo_veiculo_quilometragem` desc limit 1)) <= (select `configuracao`.`km_alerta` from `configuracao` where (`configuracao`.`id_configuracao` = 1))));");
		}
	}
	
    //Downgrade migration
	public function down(){
		if ($this->db->table_exists($this->table)) {
			$this->db->query("DROP VIEW {$this->table}");
		}
	}
}