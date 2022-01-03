<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Seed_Default_Values extends CI_Migration {

    //Upgrade migration
	public function up(){
		$this->up_empresa();
		$this->up_obra();
		$this->up_funcionario();
		$this->up_fornecedor();
		$this->up_usuario();
		$this->up_ativo_externo();
		$this->up_ativo_interno();
		$this->up_ativo_veiculo();
	}
	
    //Downgrade migration
	public function down(){
		$this->down_empresa();
		$this->down_obra();
		$this->down_funcionario();
		$this->down_fornecedor();
		$this->down_usuario();
		$this->down_ativo_externo();
		$this->down_ativo_interno();
		$this->down_ativo_veiculo();
	}

	/* Empresa */
	private function up_empresa(){
		if ($this->db->table_exists('empresa') && 
			$this->db->where("id_empresa=1")->get('empresa')->num_rows() == 0) {
			$this->db->query(
				"INSERT INTO `empresa`
				(id_empresa, razao_social, nome_fantasia, cnpj, endereco, endereco_numero, data_criacao, situacao)
				VALUES
				(1,'ENGETECNICA ENGENHARIA E CONSTRUÇÃO LTDA','ENGETECNICA ENGENHARIA E CONSTRUÇÃO','37.475.129/0001-66','Rua Olho Dagua Das Flores','165','2021-09-17 16:06:13','0');
				"
			);
		}
	}

	private function down_empresa(){
		if ($this->db->table_exists('empresa') && 
			$this->db->where("id_empresa=1")->get('empresa')->num_rows() == 1) {
			$this->db->query("DELETE from empresa WHERE id_empresa=1");
		}
	}

	/* Obra */
	private function up_obra(){
		if ($this->db->table_exists('obra') && 
			$this->db->where("id_obra BETWEEN 1 AND 3")->get('obra')->num_rows() == 0) {
			$this->db->query(
				"INSERT INTO `obra`
				(id_obra, id_empresa, codigo_obra, endereco, data_criacao, situacao, obra_base)
				VALUES
				(1,1,'Base', 'Rua olho Dagua das Flores','2021-08-17 16:13:53','0', '1'),
				(2,1,'OBR-001', 'Praça da Sé','2021-09-26 06:37:16','0', '0'),
				(3,1,'OBR-002', 'Rua josé Camilo','2020-04-26 06:37:16','0', '0');"
			);
		}
	}

	private function down_obra(){
		if ($this->db->table_exists('obra') && 
			$this->db->where("id_obra BETWEEN 1 AND 3")->get('obra')->num_rows() == 3) {
			$this->db->query("DELETE from obra WHERE id_obra BETWEEN 1 AND 3");
		}
	}	
	
	/* Funcionario */
	private function up_funcionario(){
		if ($this->db->table_exists('funcionario') && 
			$this->db->where("id_funcionario BETWEEN 1 AND 3")->get('funcionario')->num_rows() == 0) {
			$this->db->query(
				"INSERT INTO `funcionario` 
				(id_funcionario, id_empresa, id_obra, nome, rg, cpf, data_nascimento, data_criacao, situacao)
				VALUES  
				(1,1,1,'José Fernando de Lima','7.762.635-26','266.872.867-73','1972-10-18','2021-09-20 04:35:10', '0'),
				(2,1,2,'Arnaldo Antunes','6.545.646','098.376.763-22','1991-07-20','2021-03-20 05:57:11','0'),
				(3,1,3,'Armando Antunes','6.545.647','098.376.763-23','1991-07-20','2021-03-20 05:57:11','0');;"
			);
		}
	}

	private function down_funcionario(){
		if ($this->db->table_exists('funcionario') && 
			$this->db->where("id_funcionario BETWEEN 1 AND 2")->get('funcionario')->num_rows() == 3) {
			$this->db->query("DELETE from funcionario WHERE id_funcionario BETWEEN 1 AND 3");
		}
	}


	/* Usuário */
	private function up_usuario(){
		if ($this->db->table_exists('usuario') && $this->db->where("id_usuario BETWEEN 2 AND 5")->get('usuario')->num_rows() == 0) {
			$this->db->query(
				"INSERT INTO `usuario` 
				(id_usuario, id_empresa, id_obra, usuario, nome, senha, data_criacao, nivel, situacao)
				VALUES
				(2,1,2,'adm', 'Adm OBR-001', '7c4a8d09ca3762af61e59520943dc26494f8941b','2020-08-13 15:58:49',1,'0'),
				(3,1,2,'obra', 'Alm OBR-001', '7c4a8d09ca3762af61e59520943dc26494f8941b','2020-08-13 15:58:49',2,'0'),
				(4,1,3,'adm2', 'Adm OBR-002', '7c4a8d09ca3762af61e59520943dc26494f8941b','2020-08-13 15:58:49',1,'0'),
				(5,1,3,'obra2', 'Alm OBR-002', '7c4a8d09ca3762af61e59520943dc26494f8941b','2020-08-13 15:58:49',2,'0');"
			);
		}
	}

	private function down_usuario(){
		if ($this->db->table_exists('usuario') && 
			$this->db->where("id_usuario BETWEEN 2 AND 5")->get('usuario')->num_rows() == 4) {
			$this->db->query("DELETE from usuario WHERE id_usuario BETWEEN 2 AND 5");
		}
	}


	/* Fornecedor */
	private function up_fornecedor(){
		if ($this->db->table_exists('fornecedor') && 
			$this->db->where("id_fornecedor=1")->get('fornecedor')->num_rows() == 0) {
			$this->db->query(
				"INSERT INTO `fornecedor`
				(id_fornecedor, razao_social, nome_fantasia, data_criacao, situacao)
				VALUES
				(1,'Fornecedor Teste','Fornecedor Teste','2021-09-17 16:06:13','0');"
			);
		}
	}

	private function down_fornecedor(){
		if ($this->db->table_exists('fornecedor') && 
			$this->db->where("id_fornecedor=1")->get('fornecedor')->num_rows() == 1) {
			$this->db->query("DELETE from fornecedor WHERE id_fornecedor=1");
		}
	}


	/* Ativo Externo */
	private function up_ativo_externo(){
		if ($this->db->table_exists('ativo_externo') && 
			$this->db->where("id_ativo_externo BETWEEN 1 AND 5")->get('ativo_externo')->num_rows() == 0) {
			$this->db->query(
				"INSERT INTO `ativo_externo` 
				(id_ativo_externo, id_ativo_externo_categoria, id_ativo_externo_grupo, id_obra, nome, codigo, data_inclusao, valor, necessita_calibracao, situacao) 
				VALUES 
				(1,1,1,1,'Moto Bomba','MB-001','2020-12-05 16:13:53',1062.00,'1','12'),
				(2,1,1,1,'Moto Bomba','MB-002','2021-01-17 16:17:45',1062.89,'1','12'),
				(3,1,1,1,'Moto Bomba','MB-003','2021-04-17 16:23:35',1062.00,'1','12'),
				(4,1,1,1,'Moto Bomba','MB-004','2021-04-26 06:37:16',1062.89,'1','12'),
				(5,1,1,1,'Moto Bomba','MB-005','2021-09-26 06:37:16',1062.89,'1','12');"
			);
		}
	}

	private function down_ativo_externo(){
		if ($this->db->table_exists('ativo_externo') && 
			$this->db->where("id_ativo_externo BETWEEN 1 AND 5")->get('ativo_externo')->num_rows() == 5) {
			$this->db->query("DELETE from ativo_externo WHERE id_ativo_externo BETWEEN 1 AND 5");
		}
	}


	/* Ativo Interno */
	private function up_ativo_interno(){
		if ($this->db->table_exists('ativo_interno') && 
			$this->db->where("id_ativo_interno BETWEEN 1 AND 3")->get('ativo_interno')->num_rows() == 0) {
			$this->db->query(
				"INSERT INTO `ativo_interno` 
				(id_ativo_interno, id_obra, nome, data_inclusao, valor, situacao) 
				VALUES 
				(1,1,'Impressora Brother', '2020-12-17 16:13:53',1062.00,'0'),
				(2,2,'Notebook Macbook', '2021-04-17 16:23:35',15052.86,'0'),
				(3,2,'Rotuladora Brother', '2021-04-26 06:37:16',162.89,'0');
				"
			);
		}
	}

	private function down_ativo_interno(){
		if ($this->db->table_exists('ativo_interno') && 
			$this->db->where("id_ativo_interno BETWEEN 1 AND 3")->get('ativo_interno')->num_rows() == 3) {
			$this->db->query("DELETE from ativo_interno WHERE id_ativo_interno BETWEEN 1 AND 3");
		}
	}


	/* Ativo Veículo */
	private function up_ativo_veiculo(){
		if ($this->db->table_exists('ativo_veiculo') && 
			$this->db->where("id_ativo_veiculo BETWEEN 1 AND 3")->get('ativo_veiculo')->num_rows() == 0) {
			$this->db->query(
			"INSERT INTO `ativo_veiculo`
			(id_ativo_veiculo, tipo_veiculo, id_marca, id_modelo, ano, veiculo, valor_fipe, codigo_fipe, fipe_mes_referencia, veiculo_placa, veiculo_km, veiculo_km_data, valor_funcionario, valor_adicional, data, situacao) 
			VALUES 
			(1,'moto',101,'3102','2008-1','YBR 125 ED',4745.00,'827045-7','setembro de 2021 ','PFM-2984','','267897','0000-00-00',12.50,1.50,'','2021-09-17 15:53:58','0'),
			(2, 'carro', '59', '5009', '2010-1', 'Saveiro 1.6 Mi Total Flex 8V CE', '33814.00', '005298-1', 'outubro de 2021 ', 'PFM-3E89', '', '267896', '0000-00-00', '12.50', '1.50', '', '2021-10-03 22:37:50', '0'),
			(3, 'caminhao', '109', '3382', '1989-3', 'L-1113 2p (diesel)', '42737.00', '509037-7', 'outubro de 2021 ', 'PFM-2O86', '', '295876', '0000-00-00', '20.50', '2.50', '', '2021-10-03 22:39:00', '0');"
			);
		}
	}

	private function down_ativo_veiculo(){
		if ($this->db->table_exists('ativo_veiculo') && 
			$this->db->where("id_ativo_veiculo BETWEEN 1 AND 3")->get('ativo_veiculo')->num_rows() == 3) {
			$this->db->query("DELETE from ativo_veiculo WHERE id_ativo_veiculo BETWEEN 1 AND 3");
		}
	}
}