-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12-Dez-2022 às 16:51
-- Versão do servidor: 10.4.22-MariaDB
-- versão do PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `engetecnica3`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `insumo`
--

CREATE TABLE `insumo` (
  `id_insumo` int(11) NOT NULL,
  `id_insumo_configuracao` int(11) NOT NULL,
  `id_fornecedor` int(11) NOT NULL,
  `id_obra` int(10) NOT NULL DEFAULT 0,
  `titulo` varchar(255) NOT NULL,
  `codigo_insumo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `situacao` enum('0','1') NOT NULL COMMENT '0=Ativo,1=Inativo',
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `insumo_configuracao`
--

CREATE TABLE `insumo_configuracao` (
  `id_insumo_configuracao` int(10) NOT NULL,
  `id_insumo_configuracao_vinculo` int(10) NOT NULL DEFAULT 0,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `medicao` int(10) NOT NULL DEFAULT 0,
  `situacao` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Ativo,1=Inativo',
  `permit_edit` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Não,1=Sim',
  `permit_delete` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Não,1=Sim'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `insumo_estoque`
--

CREATE TABLE `insumo_estoque` (
  `id_insumo_estoque` int(10) NOT NULL,
  `id_insumo` int(10) NOT NULL DEFAULT 0,
  `id_usuario` int(10) NOT NULL DEFAULT 0,
  `id_insumo_retirada` int(10) DEFAULT NULL,
  `quantidade` int(10) NOT NULL DEFAULT 0,
  `tipo` enum('entrada','saida') NOT NULL DEFAULT 'entrada',
  `valor` float NOT NULL,
  `status` int(10) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `insumo_medicao`
--

CREATE TABLE `insumo_medicao` (
  `id_insumo_medicao` int(10) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `sigla` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `insumo_medicao`
--

INSERT INTO `insumo_medicao` (`id_insumo_medicao`, `titulo`, `sigla`, `created_at`) VALUES
(1, 'Nenhum', 'N/0', '2022-12-12 10:21:17'),
(2, 'Metro Quadrado', 'M²', '2022-12-12 10:31:38'),
(3, 'Metro Cúbico', 'M³', '2022-12-12 10:31:38'),
(4, 'Pacote', 'PC', '2022-12-12 10:31:38'),
(5, 'Metro', 'M', '2022-12-12 10:31:38'),
(6, 'Peça', 'PÇ', '2022-12-12 10:31:38'),
(7, 'Unidade', 'UN', '2022-12-12 10:31:38'),
(8, 'Quilo', 'KG', '2022-12-12 10:31:38'),
(9, 'Litro', 'L', '2022-12-12 10:31:38'),
(10, 'Dúzia', 'DZ', '2022-12-12 10:31:38'),
(11, 'Caixa', 'CX', '2022-12-12 10:31:38'),
(12, 'Centímetro', 'CM', '2022-12-12 10:31:38'),
(13, 'Barra', 'B', '2022-12-12 10:31:38');

-- --------------------------------------------------------

--
-- Estrutura da tabela `insumo_retirada`
--

CREATE TABLE `insumo_retirada` (
  `id_insumo_retirada` int(10) NOT NULL,
  `id_usuario` int(10) NOT NULL DEFAULT 0,
  `id_funcionario` int(10) NOT NULL DEFAULT 0,
  `status` int(10) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `insumo`
--
ALTER TABLE `insumo`
  ADD PRIMARY KEY (`id_insumo`),
  ADD UNIQUE KEY `codigo_insumo` (`codigo_insumo`);

--
-- Índices para tabela `insumo_configuracao`
--
ALTER TABLE `insumo_configuracao`
  ADD PRIMARY KEY (`id_insumo_configuracao`);

--
-- Índices para tabela `insumo_estoque`
--
ALTER TABLE `insumo_estoque`
  ADD PRIMARY KEY (`id_insumo_estoque`);

--
-- Índices para tabela `insumo_medicao`
--
ALTER TABLE `insumo_medicao`
  ADD PRIMARY KEY (`id_insumo_medicao`);

--
-- Índices para tabela `insumo_retirada`
--
ALTER TABLE `insumo_retirada`
  ADD PRIMARY KEY (`id_insumo_retirada`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `insumo`
--
ALTER TABLE `insumo`
  MODIFY `id_insumo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `insumo_configuracao`
--
ALTER TABLE `insumo_configuracao`
  MODIFY `id_insumo_configuracao` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `insumo_estoque`
--
ALTER TABLE `insumo_estoque`
  MODIFY `id_insumo_estoque` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `insumo_medicao`
--
ALTER TABLE `insumo_medicao`
  MODIFY `id_insumo_medicao` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `insumo_retirada`
--
ALTER TABLE `insumo_retirada`
  MODIFY `id_insumo_retirada` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
