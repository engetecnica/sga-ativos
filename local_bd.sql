-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:8889
-- Tempo de geração: 31/07/2021 às 20:37
-- Versão do servidor: 5.7.30
-- Versão do PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Banco de dados: `codigosd_engetec`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_configuracao`
--

CREATE TABLE `ativo_configuracao` (
  `id_ativo_configuracao` int(10) NOT NULL,
  `id_ativo_configuracao_vinculo` int(10) NOT NULL DEFAULT '0',
  `titulo` varchar(255) NOT NULL,
  `situacao` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=ATIVO,1=INATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `ativo_configuracao`
--

INSERT INTO `ativo_configuracao` (`id_ativo_configuracao`, `id_ativo_configuracao_vinculo`, `titulo`, `situacao`) VALUES
(1, 0, 'Tipo de Veículo', '0'),
(2, 0, 'Tipo de Ferramenta', '0'),
(3, 0, 'Tipo de Equipamento', '0'),
(4, 0, 'Tipo de Custo', '0'),
(5, 4, 'IPVA', '0'),
(6, 4, 'Manutenção', '0'),
(7, 4, 'Combustível', '0'),
(8, 4, 'Seguro', '0'),
(9, 4, 'Mão de Obra', '0'),
(10, 0, 'Serviços Mecânicos', '0'),
(11, 10, 'Troca de Óleo', '1'),
(12, 10, 'Substituição de Peças', '0'),
(13, 10, 'Troca de Lâmpadas', '0'),
(1, 0, 'Tipo de Veículo', '0'),
(2, 0, 'Tipo de Ferramenta', '0'),
(3, 0, 'Tipo de Equipamento', '0'),
(4, 0, 'Tipo de Custo', '0'),
(5, 4, 'IPVA', '0'),
(6, 4, 'Manutenção', '0'),
(7, 4, 'Combustível', '0'),
(8, 4, 'Seguro', '0'),
(9, 4, 'Mão de Obra', '0'),
(10, 0, 'Serviços Mecânicos', '0'),
(11, 10, 'Troca de Óleo', '1'),
(12, 10, 'Substituição de Peças', '0'),
(13, 10, 'Troca de Lâmpadas', '0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo`
--

CREATE TABLE `ativo_externo` (
  `id_ativo_externo` int(10) NOT NULL,
  `id_ativo_externo_categoria` int(10) NOT NULL DEFAULT '0',
  `id_ativo_externo_grupo` int(11) DEFAULT NULL,
  `id_obra` int(10) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `observacao` text NOT NULL,
  `data_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_descarte` timestamp NULL DEFAULT NULL,
  `situacao` int(1) NOT NULL DEFAULT '0',
  `tipo` int(1) NOT NULL DEFAULT '0',
  `valor` decimal(65,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `ativo_externo`
--

INSERT INTO `ativo_externo` (`id_ativo_externo`, `id_ativo_externo_categoria`, `id_ativo_externo_grupo`, `id_obra`, `nome`, `codigo`, `observacao`, `data_inclusao`, `data_descarte`, `situacao`, `tipo`, `valor`) VALUES
(1, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-01', 'Capacete de Proteção BYB 01', '2021-05-27 18:44:00', '2021-07-31 02:05:38', 12, 0, '38.90'),
(2, 3, 2, 1, 'Kit máscara EPI', 'EPI-701', '', '2021-05-27 18:44:00', NULL, 12, 0, '0.00'),
(3, 3, 2, 1, 'Kit máscara EPI', 'EPI-702', '', '2021-05-27 18:44:00', NULL, 12, 0, '0.00'),
(4, 3, 2, 1, 'Kit máscara EPI', 'EPI-703', '', '2021-05-27 18:44:00', NULL, 12, 0, '0.00'),
(5, 3, 2, 1, 'Kit máscara EPI', 'EPI-704', '', '2021-05-27 18:44:00', NULL, 12, 0, '0.00'),
(8, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-02', 'Capacete de Proteção BYB 01', '2021-06-01 11:30:29', NULL, 2, 0, '38.90'),
(9, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-03', 'Capacete de Proteção BYB 01', '2021-06-01 11:30:29', NULL, 2, 0, '38.90'),
(10, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-04', 'Capacete de Proteção BYB 01', '2021-06-01 11:30:29', NULL, 2, 0, '38.90'),
(11, 1, 3, 1, 'Fio 10mm rolo com 50 mts ', 'FIO-10-01', '', '2021-06-01 11:34:25', NULL, 2, 0, '0.00'),
(12, 1, 3, 1, 'Fio 10mm rolo com 50 mts ', 'FIO-10-02', '', '2021-06-01 11:34:25', NULL, 2, 0, '0.00'),
(1, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-01', 'Capacete de Proteção BYB 01', '2021-05-27 18:44:00', '2021-07-31 02:05:38', 12, 0, '38.90'),
(2, 3, 2, 1, 'Kit máscara EPI', 'EPI-701', '', '2021-05-27 21:44:00', NULL, 12, 0, '0.00'),
(3, 3, 2, 1, 'Kit máscara EPI', 'EPI-702', '', '2021-05-27 18:44:00', NULL, 12, 0, '0.00'),
(4, 3, 2, 1, 'Kit máscara EPI', 'EPI-703', '', '2021-05-27 18:44:00', NULL, 12, 0, '0.00'),
(5, 3, 2, 1, 'Kit máscara EPI', 'EPI-704', '', '2021-05-27 18:44:00', NULL, 12, 0, '0.00'),
(8, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-02', 'Capacete de Proteção BYB 01', '2021-06-01 11:30:29', NULL, 2, 0, '38.90'),
(9, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-03', 'Capacete de Proteção BYB 01', '2021-06-01 11:30:29', NULL, 2, 0, '38.90'),
(10, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-04', 'Capacete de Proteção BYB 01', '2021-06-01 11:30:29', NULL, 2, 0, '38.90'),
(11, 1, 3, 1, 'Fio 10mm rolo com 50 mts ', 'FIO-10-01', '', '2021-06-01 14:34:25', NULL, 2, 0, '0.00'),
(12, 1, 3, 1, 'Fio 10mm rolo com 50 mts ', 'FIO-10-02', '', '2021-06-01 14:34:25', NULL, 2, 0, '0.00'),
(66, 3, 2, 1, 'Kit máscara EPI', 'EPI-700', '...', '2021-07-09 10:19:59', NULL, 12, 0, '0.00'),
(1, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-01', 'Capacete de Proteção BYB 01', '2021-05-27 18:44:00', '2021-07-31 02:05:38', 12, 0, '38.90'),
(1, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-01', 'Capacete de Proteção BYB 01', '2021-05-27 18:44:00', '2021-07-31 02:05:38', 12, 0, '38.90'),
(67, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-01', 'Tudo PVC 3/4 Branco.', '2021-07-09 13:43:21', NULL, 5, 0, '0.00'),
(68, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-02', 'Tudo PVC 3/4 Branco.', '2021-07-09 13:43:21', NULL, 5, 0, '0.00'),
(71, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-05', 'Capacete de Proteção BYB 01', '2021-07-09 15:53:17', NULL, 2, 0, '38.90'),
(72, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-06', 'Capacete de Proteção BYB 01', '2021-07-09 15:53:17', NULL, 2, 0, '38.90'),
(73, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-07', 'Capacete de Proteção BYB 01', '2021-07-09 15:53:17', NULL, 2, 0, '38.90'),
(74, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-08', 'Capacete de Proteção BYB 01', '2021-07-09 15:53:17', NULL, 12, 0, '38.90'),
(75, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-09', 'Capacete de Proteção BYB 01', '2021-07-09 15:53:17', NULL, 12, 0, '38.90'),
(76, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-10', 'Capacete de Proteção BYB 01', '2021-07-09 15:53:17', NULL, 12, 0, '38.90'),
(77, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-11', 'Capacete de Proteção BYB 01', '2021-07-09 21:06:30', NULL, 12, 0, '38.90'),
(78, 2, 6, 1, 'Kit Teste', 'Kit-Teste-01', '...', '2021-07-11 20:27:17', NULL, 12, 1, '50.64'),
(82, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-12', 'Capacete de Proteção BYB 01', '2021-07-18 04:21:33', NULL, 12, 0, '38.90'),
(83, 0, 7, 1, 'Test-ATV', 'Test-03-01', '...', '2021-07-18 07:37:54', NULL, 12, 0, '0.00'),
(84, 0, 7, 1, 'Test-ATV', 'Test-03-02', '...', '2021-07-18 07:37:54', NULL, 12, 0, '0.00'),
(87, 0, 7, 1, 'Test-ATV', 'Test-03-03', '...', '2021-07-18 08:49:16', NULL, 12, 0, '0.00'),
(88, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-13', 'Capacete de Proteção BYB 01', '2021-07-18 10:01:46', NULL, 12, 0, '38.90'),
(89, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-14', 'Capacete de Proteção BYB 01', '2021-07-18 10:01:46', NULL, 12, 0, '38.90'),
(8, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-02', 'Capacete de Proteção BYB 01', '2021-06-01 11:30:29', NULL, 2, 0, '38.90'),
(8, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-02', 'Capacete de Proteção BYB 01', '2021-06-01 11:30:29', NULL, 2, 0, '38.90'),
(8, 3, 1, 1, 'Capacete De Proteção BYB', 'CAP-02', 'Capacete de Proteção BYB 01', '2021-06-01 11:30:29', NULL, 2, 0, '38.90'),
(94, 0, 13, 1, 'Grupo OK', 'GOK-01', 'Grupo OK, 2 itens.', '2021-07-18 10:16:21', NULL, 12, 0, '0.00'),
(95, 0, 13, 1, 'Grupo OK', 'GOK-02', 'Grupo OK, 2 itens.', '2021-07-18 10:16:21', NULL, 12, 0, '0.00'),
(96, 0, 11, 1, 'Grupo OK2', 'GOK2-01', '', '2021-07-18 10:19:16', NULL, 12, 0, '0.00'),
(97, 0, 12, 1, 'Grupo OK3', 'GOK3-01', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(98, 0, 12, 1, 'Grupo OK3', 'GOK3-02', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(99, 0, 12, 1, 'Grupo OK3', 'GOK3-03', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(100, 0, 11, 1, 'Grupo OK2', 'GOK2-02', '', '2021-07-19 15:56:17', NULL, 12, 0, '0.00'),
(101, 0, 11, 1, 'Grupo OK2', 'GOK2-03', '', '2021-07-19 15:56:17', NULL, 12, 0, '0.00'),
(102, 0, 11, 1, 'Grupo OK2', 'GOK2-04', '', '2021-07-19 15:56:17', NULL, 12, 0, '0.00'),
(97, 0, 12, 1, 'Grupo OK3', 'GOK3-01', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(98, 0, 12, 1, 'Grupo OK3', 'GOK3-02', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(97, 0, 12, 1, 'Grupo OK3', 'GOK3-01', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(98, 0, 12, 1, 'Grupo OK3', 'GOK3-02', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(97, 0, 12, 1, 'Grupo OK3', 'GOK3-01', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(98, 0, 12, 1, 'Grupo OK3', 'GOK3-02', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(97, 0, 12, 1, 'Grupo OK3', 'GOK3-01', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(98, 0, 12, 1, 'Grupo OK3', 'GOK3-02', '', '2021-07-18 10:26:17', NULL, 12, 0, '0.00'),
(103, 2, 6, 1, 'Kit Teste', 'Kit-Teste-02', '...', '2021-07-22 02:13:46', NULL, 12, 1, '50.64'),
(104, 2, 6, 1, 'Kit Teste', 'Kit-Teste-03', '...', '2021-07-22 02:13:46', NULL, 12, 1, '50.64'),
(105, 2, 6, 1, 'Kit Teste', 'Kit-Teste-04', '...', '2021-07-22 02:13:46', NULL, 12, 1, '50.64'),
(106, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-03', 'Tudo PVC 3/4 Branco.', '2021-07-23 10:57:08', NULL, 12, 0, '0.00'),
(107, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-04', 'Tudo PVC 3/4 Branco.', '2021-07-23 10:57:08', NULL, 12, 0, '0.00'),
(108, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-05', 'Tudo PVC 3/4 Branco.', '2021-07-23 10:57:08', NULL, 12, 0, '0.00'),
(109, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-06', 'Tudo PVC 3/4 Branco.', '2021-07-23 10:57:08', NULL, 12, 0, '0.00'),
(110, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-07', 'Tudo PVC 3/4 Branco.', '2021-07-23 10:57:08', NULL, 12, 0, '0.00'),
(111, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-08', 'Tudo PVC 3/4 Branco.', '2021-07-23 10:57:08', NULL, 12, 0, '0.00'),
(112, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-09', 'Tudo PVC 3/4 Branco.', '2021-07-23 10:57:08', NULL, 12, 0, '0.00'),
(113, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-10', 'Tudo PVC 3/4 Branco.', '2021-07-23 10:57:08', NULL, 12, 0, '0.00'),
(114, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-11', 'Tudo PVC 3/4 Branco.', '2021-07-23 10:57:08', NULL, 12, 0, '0.00'),
(115, 2, 4, 1, 'Tudo PVC 3/4', 'PVC-3_4-12', 'Tudo PVC 3/4 Branco.', '2021-07-23 10:57:08', NULL, 12, 0, '0.00'),
(126, 3, 14, 1, 'Luvas', 'LVA-01', '', '2021-07-23 11:07:32', NULL, 12, 0, '0.00'),
(127, 3, 14, 1, 'Luvas', 'LVA-02', '', '2021-07-23 11:07:32', NULL, 12, 0, '0.00'),
(128, 3, 14, 1, 'Luvas', 'LVA-03', '', '2021-07-23 11:07:32', NULL, 12, 0, '0.00'),
(129, 3, 14, 1, 'Luvas', 'LVA-04', '', '2021-07-23 11:07:32', NULL, 12, 0, '0.00'),
(130, 3, 14, 1, 'Luvas', 'LVA-05', '', '2021-07-23 11:07:32', NULL, 12, 0, '0.00'),
(131, 3, 14, 1, 'Luvas', 'LVA-06', '', '2021-07-23 11:07:56', NULL, 12, 0, '0.00'),
(132, 3, 14, 1, 'Luvas', 'LVA-07', '', '2021-07-23 11:07:56', NULL, 12, 0, '0.00'),
(133, 3, 14, 1, 'Luvas', 'LVA-08', '', '2021-07-23 11:07:56', NULL, 12, 0, '0.00'),
(134, 3, 14, 1, 'Luvas', 'LVA-09', '', '2021-07-23 11:07:56', NULL, 12, 0, '0.00'),
(135, 3, 14, 1, 'Luvas', 'LVA-10', '', '2021-07-23 11:07:56', NULL, 12, 0, '0.00'),
(136, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-01', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(137, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-02', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(138, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-03', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(139, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-04', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(140, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-05', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(141, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-06', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(142, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-07', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(143, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-08', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(144, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-08', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(145, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-10', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(146, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-11', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(147, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-12', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(148, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-13', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(149, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-14', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(150, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-15', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(151, 3, 5, 1, 'Capacete De Proteção BYB  - Branco', 'CAPB-16', 'Capacete de Proteção BYB  - Branco', '2021-07-30 18:08:33', NULL, 12, 0, '50.00'),
(152, 0, 5, 0, 'Capacete De Proteção BYB  - Branco', 'CAPB-17', '', '2021-07-30 19:52:03', NULL, 12, 0, '50.00'),
(155, 0, 1, 1, 'Capacete De Proteção BYB', 'CAP-15', '', '2021-07-30 22:25:33', NULL, 12, 0, '38.90'),
(156, 0, 1, 1, 'Capacete De Proteção BYB', 'CAP-16', '', '2021-07-30 22:25:33', NULL, 12, 0, '38.90'),
(157, 3, 5, 1, 'Testando', 'Testando-1', 'Testando 123', '2021-07-30 22:27:54', NULL, 12, 0, '99.01'),
(158, 3, 5, 1, 'Testando', 'Testando-2', 'Testando 123', '2021-07-30 22:27:54', NULL, 12, 0, '99.01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_categoria`
--

CREATE TABLE `ativo_externo_categoria` (
  `id_ativo_externo_categoria` int(10) NOT NULL,
  `nome` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `ativo_externo_categoria`
--

INSERT INTO `ativo_externo_categoria` (`id_ativo_externo_categoria`, `nome`) VALUES
(1, 'Materiais Elétricos'),
(2, 'Materiais Hidráulicos'),
(3, 'Equipamentos de Proteção Individual (EPI)'),
(1, 'Materiais Elétricos'),
(2, 'Materiais Hidráulicos'),
(3, 'Equipamentos de Proteção Individual (EPI)');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_kit`
--

CREATE TABLE `ativo_externo_kit` (
  `id_ativo_externo_kit` int(11) NOT NULL,
  `id_ativo_externo_item` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `ativo_externo_kit`
--

INSERT INTO `ativo_externo_kit` (`id_ativo_externo_kit`, `id_ativo_externo_item`) VALUES
(104, 74),
(104, 75);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_obra`
--

CREATE TABLE `ativo_externo_obra` (
  `id_ativo_externo_obra` int(10) NOT NULL COMMENT 'Auto',
  `id_ativo_externo` int(10) NOT NULL DEFAULT '0' COMMENT 'Id do Item Externo',
  `id_usuario_aceite` int(10) NOT NULL DEFAULT '0' COMMENT 'ID Usuário que aceitou',
  `id_obra` int(10) NOT NULL DEFAULT '0' COMMENT 'Obra Atual',
  `observacoes` varchar(255) NOT NULL COMMENT 'Obs. de aceite',
  `status` int(10) NOT NULL DEFAULT '0' COMMENT 'Status do Item'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_requisicao`
--

CREATE TABLE `ativo_externo_requisicao` (
  `id_requisicao` int(10) NOT NULL,
  `id_origem` int(10) DEFAULT NULL,
  `id_destino` int(11) NOT NULL,
  `id_solicitante` int(11) NOT NULL,
  `id_despachante` int(11) DEFAULT NULL,
  `data_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` int(10) NOT NULL COMMENT '1: Pendente , 2: Liberado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `ativo_externo_requisicao`
--

INSERT INTO `ativo_externo_requisicao` (`id_requisicao`, `id_origem`, `id_destino`, `id_solicitante`, `id_despachante`, `data_inclusao`, `data_atualizacao`, `status`) VALUES
(1, 1, 1, 2, 1, '2021-07-30 06:35:02', '2021-07-30 09:35:22', 2),
(2, 1, 1, 2, 1, '2021-07-30 06:36:00', '2021-07-30 09:36:39', 2),
(4, 1, 1, 2, 1, '2021-07-30 06:45:26', '2021-07-30 09:59:32', 6),
(5, NULL, 1, 2, NULL, '2021-07-30 06:46:12', '2021-07-30 06:46:12', 1),
(6, 1, 1, 2, 1, '2021-07-30 07:03:25', '2021-07-30 10:03:53', 6),
(7, 1, 1, 2, 1, '2021-07-30 15:52:31', '2021-07-30 18:52:52', 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_requisicao_ativo`
--

CREATE TABLE `ativo_externo_requisicao_ativo` (
  `id_requisicao_ativo` int(11) NOT NULL,
  `id_requisicao` int(11) NOT NULL,
  `id_requisicao_item` int(11) NOT NULL,
  `id_ativo_externo` int(11) NOT NULL,
  `data_liberacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_entrega` datetime DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `ativo_externo_requisicao_ativo`
--

INSERT INTO `ativo_externo_requisicao_ativo` (`id_requisicao_ativo`, `id_requisicao`, `id_requisicao_item`, `id_ativo_externo`, `data_liberacao`, `data_entrega`, `status`) VALUES
(8, 1, 3, 11, '2021-07-30 06:35:22', NULL, 2),
(9, 2, 4, 12, '2021-07-30 06:36:39', NULL, 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_requisicao_item`
--

CREATE TABLE `ativo_externo_requisicao_item` (
  `id_requisicao_item` int(10) NOT NULL,
  `id_requisicao` int(10) NOT NULL DEFAULT '0',
  `id_ativo_externo_grupo` int(10) NOT NULL DEFAULT '0',
  `quantidade` int(10) NOT NULL DEFAULT '0',
  `quantidade_liberada` int(10) NOT NULL,
  `data_liberacao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `data_entrega` datetime DEFAULT NULL,
  `status` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `ativo_externo_requisicao_item`
--

INSERT INTO `ativo_externo_requisicao_item` (`id_requisicao_item`, `id_requisicao`, `id_ativo_externo_grupo`, `quantidade`, `quantidade_liberada`, `data_liberacao`, `data_entrega`, `status`) VALUES
(3, 1, 3, 1, 1, '2021-07-30 09:35:22', NULL, 2),
(4, 2, 3, 1, 1, '2021-07-30 09:36:39', NULL, 2),
(7, 4, 3, 100, 0, NULL, NULL, 1),
(8, 5, 1, 2, 0, NULL, NULL, 1),
(9, 6, 3, 9, 0, NULL, NULL, 1),
(10, 6, 0, 4, 0, NULL, NULL, 1),
(11, 7, 3, 1, 0, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_requisicao_status`
--

CREATE TABLE `ativo_externo_requisicao_status` (
  `id_requisicao_status` int(10) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `texto` varchar(255) NOT NULL,
  `classe` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `ativo_externo_requisicao_status`
--

INSERT INTO `ativo_externo_requisicao_status` (`id_requisicao_status`, `slug`, `texto`, `classe`) VALUES
(1, 'pendente', 'Pendente', 'danger'),
(2, 'liberado', 'Liberado', 'primary'),
(3, 'emtransito', 'Em Trânsito', 'warning'),
(4, 'recebido', 'Recebido', 'success'),
(5, 'emoperacao', 'Em Operação', 'light'),
(6, 'semestoque', 'Sem Estoque', 'info'),
(7, 'transferido', 'Transferido', 'danger'),
(8, 'comdefeito', 'Com Defeito', 'danger'),
(9, 'devolvido', 'Devolvido', 'secondary'),
(10, 'foradeoperacao', 'Fora de Operação', 'dark'),
(11, 'liberadoparcialmente', 'Liberado Parcialmente', 'primary2'),
(12, 'estoque', 'Estoque', 'success'),
(1, 'pendente', 'Pendente', 'danger'),
(2, 'liberado', 'Liberado', 'primary'),
(3, 'emtransito', 'Em Trânsito', 'warning'),
(4, 'recebido', 'Recebido', 'success'),
(5, 'emoperacao', 'Em Operação', 'light'),
(6, 'semestoque', 'Sem Estoque', 'info'),
(7, 'transferido', 'Transferido', 'danger'),
(8, 'comdefeito', 'Com Defeito', 'danger'),
(9, 'devolvido', 'Devolvido', 'secondary'),
(10, 'foradeoperacao', 'Fora de Operação', 'dark'),
(11, 'liberadoparcialmente', 'Liberado Parcialmente', 'primary2'),
(12, 'estoque', 'Estoque', 'success'),
(13, 'recebidoparcialmente', 'Recebido Parcialmente', 'success'),
(14, 'aguardandoautorizacao', 'Aguardando Autorizacao', 'warning');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_retirada`
--

CREATE TABLE `ativo_externo_retirada` (
  `id_retirada` int(11) NOT NULL,
  `id_obra` int(11) NOT NULL,
  `id_funcionario` int(11) NOT NULL,
  `data_inclusao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) DEFAULT '0' COMMENT '1:Pendente, 9:Devolvido',
  `observacoes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `ativo_externo_retirada`
--

INSERT INTO `ativo_externo_retirada` (`id_retirada`, `id_obra`, `id_funcionario`, `data_inclusao`, `status`, `observacoes`) VALUES
(1, 1, 2, '2021-07-31 13:24:42', 9, '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_retirada_ativo`
--

CREATE TABLE `ativo_externo_retirada_ativo` (
  `id_retirada_ativo` int(11) NOT NULL,
  `id_retirada` int(11) NOT NULL,
  `id_retirada_item` int(11) NOT NULL,
  `id_ativo_externo` int(11) NOT NULL,
  `data_retirada` datetime DEFAULT NULL,
  `data_devolucao` datetime DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `ativo_externo_retirada_ativo`
--

INSERT INTO `ativo_externo_retirada_ativo` (`id_retirada_ativo`, `id_retirada`, `id_retirada_item`, `id_ativo_externo`, `data_retirada`, `data_devolucao`, `status`) VALUES
(1, 1, 1, 67, '2021-07-31 16:27:31', '2021-07-31 16:29:15', 9),
(2, 1, 1, 68, '2021-07-31 16:27:31', '2021-07-31 16:29:15', 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_retirada_item`
--

CREATE TABLE `ativo_externo_retirada_item` (
  `id_retirada_item` int(11) NOT NULL,
  `id_retirada` int(11) NOT NULL,
  `id_ativo_externo_grupo` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT '0',
  `data_retirada` datetime DEFAULT NULL,
  `data_devolucao` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '4'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `ativo_externo_retirada_item`
--

INSERT INTO `ativo_externo_retirada_item` (`id_retirada_item`, `id_retirada`, `id_ativo_externo_grupo`, `quantidade`, `data_retirada`, `data_devolucao`, `status`) VALUES
(1, 1, 4, 2, '2021-07-31 16:27:31', '2021-07-31 16:29:15', 9);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_transferencia`
--

CREATE TABLE `ativo_externo_transferencia` (
  `id_transferencia` int(11) NOT NULL,
  `id_veiculo` datetime DEFAULT NULL,
  `data_agendamento` datetime DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `data_fim` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_externo_transferencia_item`
--

CREATE TABLE `ativo_externo_transferencia_item` (
  `id_transferencia_item` int(11) NOT NULL,
  `id_transferencia` int(11) NOT NULL,
  `id_requisicao` int(11) NOT NULL,
  `data_inicio` int(11) DEFAULT NULL,
  `data_fim` int(11) DEFAULT NULL,
  `ordem_entrega` int(11) DEFAULT NULL,
  `prioridade` int(11) DEFAULT '0' COMMENT '0: Sem Prioridade, 1: Baixa, 2: Media, 3: Alta',
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_interno`
--

CREATE TABLE `ativo_interno` (
  `id_ativo_interno` int(10) NOT NULL,
  `id_obra` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `valor` float NOT NULL,
  `quantidade` int(10) NOT NULL DEFAULT '1',
  `observacao` text NOT NULL,
  `data_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_descarte` timestamp NULL DEFAULT NULL,
  `situacao` int(1) NOT NULL DEFAULT '0' COMMENT 'Ativo , 1: Inativo , 2: Descartado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `ativo_interno`
--

INSERT INTO `ativo_interno` (`id_ativo_interno`, `id_obra`, `nome`, `valor`, `quantidade`, `observacao`, `data_inclusao`, `data_descarte`, `situacao`) VALUES
(1, 1, 'Impressora HP 468 V5', 6500.1, 1, 'Ativo conclusivo BY 2015', '2020-11-16 13:41:17', '2021-07-14 18:42:05', 0),
(2, 0, 'Caneta BIC 10 unidades', 12, 12, 'Unidade 1,20', '2020-11-16 13:49:46', NULL, 0),
(3, 2, 'Notebook Dell i5 1TB ', 2350, 1, 'Ativo complementar ', '2020-11-16 14:00:17', '2021-07-14 19:03:17', 2),
(4, 0, 'Mouse Logitech', 500, 50, 'Mouse Lista', '2021-02-05 20:12:06', '2021-07-14 18:50:56', 0),
(1, 1, 'Impressora HP 468 V5', 6500.1, 1, 'Ativo conclusivo BY 2015', '2020-11-16 13:41:17', '2021-07-14 18:42:05', 0),
(2, 0, 'Caneta BIC 10 unidades', 12, 12, 'Unidade 1,20', '2020-11-16 16:49:46', NULL, 0),
(3, 2, 'Notebook Dell i5 1TB ', 2350, 1, 'Ativo complementar ', '2020-11-16 14:00:17', '2021-07-14 19:03:17', 2),
(4, 0, 'Mouse Logitech', 500, 50, 'Mouse Lista', '2021-02-05 20:12:06', '2021-07-14 18:50:56', 0),
(15, 1, 'Monitor Samsunng', 1762, 5, 'Monitor Samsunng 22\"', '2021-07-13 18:21:47', '2021-07-14 18:20:02', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_interno_manutencao`
--

CREATE TABLE `ativo_interno_manutencao` (
  `id_manutencao` int(11) NOT NULL,
  `id_ativo_interno` int(11) NOT NULL,
  `data_saida` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_retorno` timestamp NULL DEFAULT NULL,
  `situacao` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '0: Em manutencão , 1: Retorno OK, 2:Retorno com pendência',
  `valor` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `ativo_interno_manutencao`
--

INSERT INTO `ativo_interno_manutencao` (`id_manutencao`, `id_ativo_interno`, `data_saida`, `data_retorno`, `situacao`, `valor`) VALUES
(8, 4, '0000-00-00 00:00:00', '2021-07-13 03:00:00', '', 50),
(10, 15, '2021-07-13 03:00:00', '2021-07-13 03:00:00', '2', 19.86),
(11, 15, '2021-04-06 03:00:00', '2021-07-11 03:00:00', '1', 40.9),
(12, 15, '2021-07-12 03:00:00', NULL, '', 0),
(13, 4, '2021-07-14 03:00:00', NULL, '', 0),
(14, 1, '2021-07-04 03:00:00', NULL, '', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_interno_manutencao_obs`
--

CREATE TABLE `ativo_interno_manutencao_obs` (
  `id_obs` int(11) NOT NULL,
  `id_manutencao` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `texto` text NOT NULL,
  `data_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_edicao` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Despejando dados para a tabela `ativo_interno_manutencao_obs`
--

INSERT INTO `ativo_interno_manutencao_obs` (`id_obs`, `id_manutencao`, `id_usuario`, `texto`, `data_inclusao`, `data_edicao`) VALUES
(1, 1, 1, 'A expressão Lorem ipsum em design gráfico e editoração é um texto padrão em latim utilizado na produção gráfica para preencher os espaços de texto em publicações para testar e ajustar aspectos visuais antes de utilizar conteúdo real.', '2021-07-13 13:54:10', NULL),
(2, 1, 2, 'A expressão Lorem ipsum em design gráfico e editoração é um texto padrão em latim utilizado na produção gráfica para preencher os espaços de texto em publicações para testar e ajustar aspectos visuais antes de utilizar conteúdo real.', '2021-07-13 13:54:10', NULL),
(3, 1, 3, 'A expressão Lorem ipsum em design gráfico e editoração é um texto padrão em latim utilizado na produção gráfica para preencher os espaços de texto em publicações para testar e ajustar aspectos visuais antes de utilizar conteúdo real.', '2021-07-01 14:04:44', '2021-07-13 14:05:35'),
(4, 2, 1, 'A expressão Lorem ipsum em design gráfico e editoração é um texto padrão em latim utilizado na produção gráfica para preencher os espaços de texto em publicações para testar e ajustar aspectos visuais antes de utilizar conteúdo real.', '2021-07-13 14:51:54', NULL),
(23, 10, 1, 'qqpowiepoqwiopeqweq', '2021-07-14 01:41:40', NULL),
(24, 10, 1, 'messias   wagner', '2021-07-14 01:41:53', '2021-07-14 01:43:05'),
(25, 13, 1, 'Test ok', '2021-07-14 18:16:53', NULL),
(27, 14, 1, 'asdsdasdasdd', '2021-07-14 18:35:28', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_veiculo`
--

CREATE TABLE `ativo_veiculo` (
  `id_ativo_veiculo` int(10) NOT NULL,
  `tipo_veiculo` enum('carro','moto','caminhao') NOT NULL DEFAULT 'carro',
  `id_marca` int(10) NOT NULL DEFAULT '0',
  `id_modelo` varchar(10) NOT NULL DEFAULT '0',
  `ano` varchar(10) NOT NULL,
  `veiculo` varchar(255) NOT NULL,
  `valor_fipe` float NOT NULL,
  `codigo_fipe` varchar(50) NOT NULL,
  `fipe_mes_referencia` varchar(100) NOT NULL,
  `veiculo_placa` varchar(20) NOT NULL,
  `veiculo_renavam` varchar(255) NOT NULL,
  `veiculo_km` varchar(50) NOT NULL,
  `veiculo_km_data` date NOT NULL,
  `valor_funcionario` float NOT NULL,
  `valor_adicional` float NOT NULL,
  `veiculo_observacoes` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `situacao` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=ATIVO,1=INATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `ativo_veiculo`
--

INSERT INTO `ativo_veiculo` (`id_ativo_veiculo`, `tipo_veiculo`, `id_marca`, `id_modelo`, `ano`, `veiculo`, `valor_fipe`, `codigo_fipe`, `fipe_mes_referencia`, `veiculo_placa`, `veiculo_renavam`, `veiculo_km`, `veiculo_km_data`, `valor_funcionario`, `valor_adicional`, `veiculo_observacoes`, `data`, `situacao`) VALUES
(0, 'moto', 101, '4744', '2011-1', 'YBR 125 FACTOR K/ FACTOR K1', 4268, '827072-4', 'julho de 2021 ', 'PFM-2984', '', '', '0000-00-00', 0, 0, '', '2021-07-05 20:11:28', '0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_veiculo_depreciacao`
--

CREATE TABLE `ativo_veiculo_depreciacao` (
  `id_ativo_veiculo_depreciacao` int(10) NOT NULL,
  `id_ativo_veiculo` int(10) NOT NULL,
  `valor_fipe` float NOT NULL,
  `fipe_mes_referencia` varchar(255) NOT NULL,
  `veiculo_km` varchar(20) NOT NULL,
  `veiculo_observacoes` text NOT NULL,
  `veiculo_data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_veiculo_ipva`
--

CREATE TABLE `ativo_veiculo_ipva` (
  `id_ativo_veiculo_ipva` int(10) NOT NULL,
  `id_ativo_veiculo` int(10) NOT NULL,
  `ipva_ano` int(4) NOT NULL,
  `ipva_custo` float NOT NULL,
  `ipva_data_vencimento` date NOT NULL,
  `ipva_data_pagamento` date NOT NULL,
  `comprovante_ipva` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_veiculo_manutencao`
--

CREATE TABLE `ativo_veiculo_manutencao` (
  `id_ativo_veiculo_manutencao` int(10) NOT NULL,
  `id_fornecedor` int(10) NOT NULL DEFAULT '0',
  `id_ativo_configuracao` int(10) NOT NULL DEFAULT '0',
  `id_ativo_veiculo` int(10) NOT NULL,
  `veiculo_km_atual` int(10) NOT NULL,
  `veiculo_custo` float NOT NULL,
  `veiculo_km_data` date NOT NULL,
  `ordem_de_servico` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura para tabela `ativo_veiculo_quilometragem`
--

CREATE TABLE `ativo_veiculo_quilometragem` (
  `id_ativo_veiculo_quilometragem` int(10) NOT NULL,
  `id_ativo_veiculo` int(10) NOT NULL,
  `veiculo_km_inicial` int(10) NOT NULL,
  `veiculo_km_final` int(10) NOT NULL,
  `veiculo_litros` float NOT NULL,
  `veiculo_custo` float NOT NULL,
  `veiculo_km_data` date NOT NULL,
  `comprovante_fiscal` varchar(255) NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `ativo_veiculo_quilometragem`
--

INSERT INTO `ativo_veiculo_quilometragem` (`id_ativo_veiculo_quilometragem`, `id_ativo_veiculo`, `veiculo_km_inicial`, `veiculo_km_final`, `veiculo_litros`, `veiculo_custo`, `veiculo_km_data`, `comprovante_fiscal`, `data`) VALUES
(0, 0, 5897, 6789, 4.5, 5.58, '2021-07-05', '', '2021-07-05 20:39:47'),
(0, 0, 0, 0, 0, 0, '2021-07-22', '', '2021-07-22 16:58:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracao`
--

CREATE TABLE `configuracao` (
  `id_configuracao` int(10) NOT NULL,
  `id_categoria` int(10) NOT NULL DEFAULT '0',
  `categoria` varchar(255) NOT NULL,
  `data_inclusao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

CREATE TABLE `empresa` (
  `id_empresa` int(10) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `cnpj` varchar(100) NOT NULL,
  `inscricao_estadual` varchar(30) NOT NULL,
  `inscricao_municipal` varchar(30) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `endereco_numero` varchar(30) NOT NULL,
  `endereco_complemento` varchar(255) NOT NULL,
  `endereco_bairro` varchar(255) NOT NULL,
  `endereco_cep` varchar(15) NOT NULL,
  `endereco_cidade` varchar(255) NOT NULL,
  `endereco_estado` int(10) NOT NULL DEFAULT '0',
  `responsavel` varchar(255) NOT NULL,
  `responsavel_telefone` varchar(50) NOT NULL,
  `responsavel_celular` varchar(50) NOT NULL,
  `responsavel_email` varchar(255) NOT NULL,
  `observacao` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `situacao` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=ATIVO, 1=INATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `empresa`
--

INSERT INTO `empresa` (`id_empresa`, `razao_social`, `nome_fantasia`, `cnpj`, `inscricao_estadual`, `inscricao_municipal`, `endereco`, `endereco_numero`, `endereco_complemento`, `endereco_bairro`, `endereco_cep`, `endereco_cidade`, `endereco_estado`, `responsavel`, `responsavel_telefone`, `responsavel_celular`, `responsavel_email`, `observacao`, `data_criacao`, `data_atualizacao`, `situacao`) VALUES
(1, 'ENGETECNICA ENGENHARIA E CONSTRUÇÃO LTDA', 'ENGETÉCNICA', '76.624.584/0001-38', '0', '0', 'RUA JOÃO BETTEGA', '1160', '', 'PORTÃO', '81070-001', 'CURITIBA', 16, 'MURILO LUIS', '(41) 4040-4676', '', 'murilo@engetecnica.com.br', '', '2020-08-13 14:10:57', NULL, '0'),
(1, 'ENGETECNICA ENGENHARIA E CONSTRUÇÃO LTDA', 'ENGETÉCNICA', '76.624.584/0001-38', '0', '0', 'RUA JOÃO BETTEGA', '1160', '', 'PORTÃO', '81070-001', 'CURITIBA', 16, 'MURILO LUIS', '(41) 4040-4676', '', 'murilo@engetecnica.com.br', '', '2020-08-13 17:10:57', NULL, '0'),
(9, 'LOCAL ENGENHARIA E CONSTRUÇÃO LTDA', '', '26.731.618/0001-15', '', '', '', '', '', '', '', '', 0, 'José Luiz Mendonça', '', '', 'jmend@test.com', '', '2021-07-08 19:35:59', '2021-07-14 14:50:27', '0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `codigo_uf` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `uf` char(2) NOT NULL,
  `regiao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `estado`
--

INSERT INTO `estado` (`id_estado`, `codigo_uf`, `estado`, `uf`, `regiao`) VALUES
(1, 12, 'Acre', 'AC', 1),
(2, 27, 'Alagoas', 'AL', 2),
(3, 16, 'Amapá', 'AP', 1),
(4, 13, 'Amazonas', 'AM', 1),
(5, 29, 'Bahia', 'BA', 2),
(6, 23, 'Ceará', 'CE', 2),
(7, 53, 'Distrito Federal', 'DF', 5),
(8, 32, 'Espírito Santo', 'ES', 3),
(9, 52, 'Goiás', 'GO', 5),
(10, 21, 'Maranhão', 'MA', 2),
(11, 51, 'Mato Grosso', 'MT', 5),
(12, 50, 'Mato Grosso do Sul', 'MS', 5),
(13, 31, 'Minas Gerais', 'MG', 3),
(14, 15, 'Pará', 'PA', 1),
(15, 25, 'Paraíba', 'PB', 2),
(16, 41, 'Paraná', 'PR', 4),
(17, 26, 'Pernambuco', 'PE', 2),
(18, 22, 'Piauí', 'PI', 2),
(19, 33, 'Rio de Janeiro', 'RJ', 3),
(20, 24, 'Rio Grande do Norte', 'RN', 2),
(21, 43, 'Rio Grande do Sul', 'RS', 4),
(22, 11, 'Rondônia', 'RO', 1),
(23, 14, 'Roraima', 'RR', 1),
(24, 42, 'Santa Catarina', 'SC', 4),
(25, 35, 'São Paulo', 'SP', 3),
(26, 28, 'Sergipe', 'SE', 2),
(27, 17, 'Tocantins', 'TO', 1),
(1, 12, 'Acre', 'AC', 1),
(2, 27, 'Alagoas', 'AL', 2),
(3, 16, 'Amapá', 'AP', 1),
(4, 13, 'Amazonas', 'AM', 1),
(5, 29, 'Bahia', 'BA', 2),
(6, 23, 'Ceará', 'CE', 2),
(7, 53, 'Distrito Federal', 'DF', 5),
(8, 32, 'Espírito Santo', 'ES', 3),
(9, 52, 'Goiás', 'GO', 5),
(10, 21, 'Maranhão', 'MA', 2),
(11, 51, 'Mato Grosso', 'MT', 5),
(12, 50, 'Mato Grosso do Sul', 'MS', 5),
(13, 31, 'Minas Gerais', 'MG', 3),
(14, 15, 'Pará', 'PA', 1),
(15, 25, 'Paraíba', 'PB', 2),
(16, 41, 'Paraná', 'PR', 4),
(17, 26, 'Pernambuco', 'PE', 2),
(18, 22, 'Piauí', 'PI', 2),
(19, 33, 'Rio de Janeiro', 'RJ', 3),
(20, 24, 'Rio Grande do Norte', 'RN', 2),
(21, 43, 'Rio Grande do Sul', 'RS', 4),
(22, 11, 'Rondônia', 'RO', 1),
(23, 14, 'Roraima', 'RR', 1),
(24, 42, 'Santa Catarina', 'SC', 4),
(25, 35, 'São Paulo', 'SP', 3),
(26, 28, 'Sergipe', 'SE', 2),
(27, 17, 'Tocantins', 'TO', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id_fornecedor` int(10) NOT NULL,
  `razao_social` varchar(255) NOT NULL,
  `nome_fantasia` varchar(255) NOT NULL,
  `cnpj` varchar(100) NOT NULL,
  `inscricao_estadual` varchar(30) NOT NULL,
  `inscricao_municipal` varchar(30) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `endereco_numero` varchar(30) NOT NULL,
  `endereco_complemento` varchar(255) NOT NULL,
  `endereco_bairro` varchar(255) NOT NULL,
  `endereco_cep` varchar(15) NOT NULL,
  `endereco_cidade` varchar(255) NOT NULL,
  `endereco_estado` int(10) NOT NULL DEFAULT '0',
  `responsavel` varchar(255) NOT NULL,
  `responsavel_telefone` varchar(50) NOT NULL,
  `responsavel_celular` varchar(50) NOT NULL,
  `responsavel_email` varchar(255) NOT NULL,
  `observacao` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `situacao` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=ATIVO, 1=INATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `fornecedor`
--

INSERT INTO `fornecedor` (`id_fornecedor`, `razao_social`, `nome_fantasia`, `cnpj`, `inscricao_estadual`, `inscricao_municipal`, `endereco`, `endereco_numero`, `endereco_complemento`, `endereco_bairro`, `endereco_cep`, `endereco_cidade`, `endereco_estado`, `responsavel`, `responsavel_telefone`, `responsavel_celular`, `responsavel_email`, `observacao`, `data_criacao`, `data_atualizacao`, `situacao`) VALUES
(1, '4DEV INTERNET LTDA', 'JOKEY INFORMÁTICA', '76.263.215/0001-67', '0', '0', 'RUA JOAO BETTEGA', '600', '', 'PORTAO', '85853440', 'CURITIBA', 16, 'ANDRE BAILL', '45998291100', '459988204100', 'SRANDREBAILL@GMAIL.COM', 'FORNECEDOR TESTE', '2021-02-06 11:47:07', NULL, '0'),
(1, '4DEV INTERNET LTDA', 'JOKEY INFORMÁTICA', '76.263.215/0001-67', '0', '0', 'RUA JOAO BETTEGA', '600', '', 'PORTAO', '85853440', 'CURITIBA', 16, 'ANDRE BAILL', '45998291100', '459988204100', 'SRANDREBAILL@GMAIL.COM', 'FORNECEDOR TESTE', '2021-02-06 14:47:07', NULL, '0'),
(6, 'FORNECEDOR TEST', '', '', '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '2021-07-08 22:33:39', NULL, '1'),
(7, 'Test', '', '', '', '', '', '', '', '', '', '', 0, '', '', '', '', '', '2021-07-18 17:53:33', '2021-07-18 17:53:51', '0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `id_funcionario` int(10) NOT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `id_obra` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `rg` varchar(255) NOT NULL,
  `cpf` varchar(100) NOT NULL,
  `data_nascimento` date NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `endereco_numero` varchar(30) NOT NULL,
  `endereco_complemento` varchar(255) NOT NULL,
  `endereco_bairro` varchar(255) NOT NULL,
  `endereco_cep` varchar(15) NOT NULL,
  `endereco_cidade` varchar(255) NOT NULL,
  `endereco_estado` int(10) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `observacao` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `situacao` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=ATIVO, 1=INATIVO'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `funcionario`
--

INSERT INTO `funcionario` (`id_funcionario`, `id_empresa`, `id_obra`, `nome`, `rg`, `cpf`, `data_nascimento`, `endereco`, `endereco_numero`, `endereco_complemento`, `endereco_bairro`, `endereco_cep`, `endereco_cidade`, `endereco_estado`, `email`, `telefone`, `celular`, `observacao`, `data_criacao`, `data_atualizacao`, `situacao`) VALUES
(2, 1, 1, 'José Fernando de Lima', '7689045', '990.928.738-79', '1970-12-19', '', '', '', '', '', '', 0, 'jfernando@test.com', '', '8599309090', '', '2021-07-07 14:22:21', '2021-07-25 11:59:29', '0'),
(4, 1, 1, 'Arnaldo Antunes', '23434424', '234.234.234-23', '1991-08-19', '', '', '', '', '', '', 0, 'test@test.com', '', '4353454555435345435', '', '2021-07-07 17:37:32', '2021-07-25 11:58:54', '0'),
(5, 1, 1, 'Jose Luiz Silva', '213132312', '987.129.739-81', '1987-12-19', '', '', '', '', '', '', 0, 'test2@test.com', '', '23423442342', '', '2021-07-07 17:42:06', '2021-07-25 11:59:11', '0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `modulo`
--

CREATE TABLE `modulo` (
  `id_modulo` int(10) NOT NULL,
  `id_vinculo` int(10) NOT NULL DEFAULT '0',
  `titulo` varchar(255) NOT NULL,
  `rota` varchar(255) NOT NULL,
  `icone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `modulo`
--

INSERT INTO `modulo` (`id_modulo`, `id_vinculo`, `titulo`, `rota`, `icone`) VALUES
(1, 0, 'Cadastros', '#', 'fas fa-hashtag'),
(2, 1, 'Usuários', 'usuario', 'fas fa-smile'),
(3, 1, 'Funcionários', 'funcionario', 'fas fa-users'),
(4, 1, 'Empresas', 'empresa', 'fas fa-coffee'),
(5, 1, 'Fornecedores', 'fornecedor', 'fas fa-minus'),
(6, 1, 'Obras', 'obra', 'fas fa-check'),
(7, 0, 'Ativos', '#', 'fas fa-tasks'),
(8, 7, 'Configurações', 'ativo_configuracao', 'fas fa-cog'),
(9, 7, 'Veículos', 'ativo_veiculo', 'fas fa-truck'),
(10, 7, 'Internos', 'ativo_interno', 'fas fa-folder'),
(11, 12, 'Externos', 'ativo_externo', 'fas fa-wrench'),
(12, 0, 'Ferramental', '#', 'fas fa-bars'),
(13, 12, 'Estoque', 'ferramental_estoque', 'fas fa-cubes'),
(14, 12, 'Requisição', 'ferramental_requisicao', 'fas fa-dolly-flatbed'),
(1, 0, 'Cadastros', '#', 'fas fa-hashtag'),
(2, 1, 'Usuários', 'usuario', 'fas fa-smile'),
(3, 1, 'Funcionários', 'funcionario', 'fas fa-users'),
(4, 1, 'Empresas', 'empresa', 'fas fa-coffee'),
(5, 1, 'Fornecedores', 'fornecedor', 'fas fa-minus'),
(6, 1, 'Obras', 'obra', 'fas fa-check'),
(7, 0, 'Ativos', '#', 'fas fa-tasks'),
(8, 7, 'Configurações', 'ativo_configuracao', 'fas fa-cog'),
(9, 7, 'Veículos', 'ativo_veiculo', 'fas fa-truck'),
(10, 7, 'Internos', 'ativo_interno', 'fas fa-folder'),
(11, 12, 'Externos', 'ativo_externo', 'fas fa-wrench'),
(12, 0, 'Ferramental', '#', 'fas fa-bars'),
(13, 12, 'Estoque', 'ferramental_estoque', 'fas fa-cubes'),
(14, 12, 'Requisição', 'ferramental_requisicao', 'fas fa-dolly-flatbed');

-- --------------------------------------------------------

--
-- Estrutura para tabela `obra`
--

CREATE TABLE `obra` (
  `id_obra` int(10) NOT NULL,
  `id_empresa` int(10) DEFAULT NULL,
  `id_responsavel` int(11) DEFAULT NULL,
  `codigo_obra` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `endereco_numero` varchar(30) NOT NULL,
  `endereco_complemento` varchar(255) NOT NULL,
  `endereco_bairro` varchar(255) NOT NULL,
  `endereco_cep` varchar(15) NOT NULL,
  `endereco_cidade` varchar(255) NOT NULL,
  `endereco_estado` int(10) NOT NULL DEFAULT '0',
  `responsavel` varchar(255) NOT NULL,
  `responsavel_telefone` varchar(50) NOT NULL,
  `responsavel_celular` varchar(50) NOT NULL,
  `responsavel_email` varchar(255) NOT NULL,
  `observacao` text NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `situacao` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=ATIVO, 1=INATIVO',
  `obra_base` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `obra`
--

INSERT INTO `obra` (`id_obra`, `id_empresa`, `id_responsavel`, `codigo_obra`, `endereco`, `endereco_numero`, `endereco_complemento`, `endereco_bairro`, `endereco_cep`, `endereco_cidade`, `endereco_estado`, `responsavel`, `responsavel_telefone`, `responsavel_celular`, `responsavel_email`, `observacao`, `data_criacao`, `data_atualizacao`, `situacao`, `obra_base`) VALUES
(1, 1, NULL, 'Estoque - Curitiba', 'João Bettega, Portão', '2366', '', '', '', '', 0, '', '', '', '', '', '2021-05-27 21:31:50', '2021-07-08 21:02:21', '0', 1),
(2, 1, NULL, 'Obra 1200 - Campo dos Goytacazes', 'Quadra 28 Centro', '665', '', '', '', '', 0, '', '', '', '', '', '2021-05-27 21:31:50', '2021-07-08 21:10:37', '0', NULL),
(1, 1, NULL, 'Estoque - Curitiba', 'João Bettega, Portão', '2366', '', '', '', '', 0, '', '', '', '', '', '2021-05-27 21:31:50', '2021-07-08 21:02:21', '0', 1),
(2, 1, NULL, 'Obra 1200 - Campo dos Goytacazes', 'Quadra 28 Centro', '665', '', '', '', '', 0, '', '', '', '', '', '2021-05-27 21:31:50', '2021-07-08 21:10:37', '0', NULL),
(3, 9, NULL, 'Estoque - Curitiba2', '', '', '', '', '', '', 0, '', '', '', '', '', '2021-07-08 20:26:03', '2021-07-08 20:34:35', '0', NULL),
(4, 9, NULL, 'Estoque - Curitiba3', '', '', '', '', '', '', 0, '', '', '', '', '', '2021-07-08 20:44:44', '2021-07-25 11:58:22', '0', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(10) NOT NULL,
  `id_empresa` int(10) NOT NULL,
  `id_obra` int(10) NOT NULL DEFAULT '0',
  `usuario` varchar(200) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nivel` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `id_empresa`, `id_obra`, `usuario`, `senha`, `data_criacao`, `nivel`) VALUES
(1, 1, 1, 'engetecnica', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2020-08-13 15:58:49', 1),
(2, 1, 1, 'obra1200', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2021-05-27 15:58:49', 2),
(1, 1, 1, 'engetecnica', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2020-08-13 18:58:49', 1),
(2, 1, 1, 'obra1200', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2021-05-27 18:58:49', 2),
(3, 0, 1, 'adm', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2021-07-06 19:39:25', 1),
(10, 1, 1, 'obra1201', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2021-07-06 21:45:42', 2),
(11, 1, 3, 'obra1202', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2021-07-07 12:59:11', 2),
(12, 1, 1, 'obra1203', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2021-07-07 16:04:04', 2),
(13, 1, 1, 'obra1204', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2021-07-07 16:06:10', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_modulo`
--

CREATE TABLE `usuario_modulo` (
  `id_usuario_nivel` int(10) NOT NULL DEFAULT '0',
  `id_modulo` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `usuario_modulo`
--

INSERT INTO `usuario_modulo` (`id_usuario_nivel`, `id_modulo`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 14),
(2, 12),
(2, 14),
(1, 13),
(2, 13);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_nivel`
--

CREATE TABLE `usuario_nivel` (
  `id_usuario_nivel` int(10) NOT NULL,
  `nivel` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Despejando dados para a tabela `usuario_nivel`
--

INSERT INTO `usuario_nivel` (`id_usuario_nivel`, `nivel`) VALUES
(1, 'Administrador'),
(2, 'Almoxarifado');

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_ativo_externo`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_ativo_externo` (
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_itens`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_itens` (
`id_ativo_externo` int(10)
,`nome` varchar(255)
,`id_obra` int(10)
,`obra` varchar(255)
,`categoria` varchar(255)
,`total_itens` bigint(21)
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_requisicao`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_requisicao` (
);

-- --------------------------------------------------------

--
-- Estrutura stand-in para view `v_requisicao_detalhes`
-- (Veja abaixo para a visão atual)
--
CREATE TABLE `v_requisicao_detalhes` (
);

-- --------------------------------------------------------

--
-- Estrutura para view `v_ativo_externo`
--
DROP TABLE IF EXISTS `v_ativo_externo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`codigosd_engetec`@`localhost` SQL SECURITY DEFINER VIEW `v_ativo_externo`  AS  select `c1`.`nome` AS `nome`,`c1`.`id_ativo_externo` AS `id_ativo_externo`,`c1`.`codigo` AS `codigo`,`c1`.`data_inclusao` AS `data_inclusao`,`c1`.`data_liberacao` AS `data_liberacao`,`c1`.`situacao` AS `situacao`,`c2`.`codigo_obra` AS `codigo_obra`,`c2`.`endereco` AS `endereco` from (`ativo_externo` `c1` join `obra` `c2` on((`c2`.`id_obra` = `c1`.`id_obra`))) order by `c1`.`id_ativo_externo` desc ;

-- --------------------------------------------------------

--
-- Estrutura para view `v_itens`
--
DROP TABLE IF EXISTS `v_itens`;

CREATE ALGORITHM=UNDEFINED DEFINER=`codigosd_engetec`@`localhost` SQL SECURITY DEFINER VIEW `v_itens`  AS  select `c1`.`id_ativo_externo` AS `id_ativo_externo`,`c1`.`nome` AS `nome`,`c1`.`id_obra` AS `id_obra`,`c3`.`codigo_obra` AS `obra`,`c2`.`nome` AS `categoria`,count(`c1`.`nome`) AS `total_itens` from ((`ativo_externo` `c1` left join `ativo_externo_categoria` `c2` on((`c2`.`id_ativo_externo_categoria` = `c1`.`id_ativo_externo_categoria`))) left join `obra` `c3` on((`c3`.`id_obra` = `c1`.`id_obra`))) group by `c1`.`nome` ;

-- --------------------------------------------------------

--
-- Estrutura para view `v_requisicao`
--
DROP TABLE IF EXISTS `v_requisicao`;

CREATE ALGORITHM=UNDEFINED DEFINER=`codigosd_engetec`@`localhost` SQL SECURITY DEFINER VIEW `v_requisicao`  AS  select `c1`.`id_requisicao` AS `id_requisicao`,date_format(`c1`.`data_inclusao`,'%d/%m/%Y às %H:%i') AS `data_inclusao`,`c2`.`id_obra` AS `id_obra`,`c2`.`codigo_obra` AS `codigo_obra`,`c2`.`endereco` AS `endereco`,`c2`.`endereco_cidade` AS `endereco_cidade`,`c3`.`usuario` AS `usuario`,`c4`.`texto` AS `status`,`c4`.`classe` AS `classe` from (((`ativo_externo_requisicao` `c1` join `obra` `c2` on((`c2`.`id_obra` = `c1`.`id_obra`))) join `usuario` `c3` on((`c3`.`id_usuario` = `c1`.`id_usuario`))) join `ativo_externo_requisicao_status` `c4` on((`c4`.`id_requisicao_status` = `c1`.`status`))) ;

-- --------------------------------------------------------

--
-- Estrutura para view `v_requisicao_detalhes`
--
DROP TABLE IF EXISTS `v_requisicao_detalhes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`codigosd_engetec`@`localhost` SQL SECURITY DEFINER VIEW `v_requisicao_detalhes`  AS  select `c1`.`id_requisicao` AS `id_requisicao`,`c1`.`data_inclusao` AS `data_inclusao`,`c1`.`id_obra` AS `id_obra`,`c1`.`codigo_obra` AS `codigo_obra`,`c1`.`endereco` AS `endereco`,`c1`.`endereco_cidade` AS `endereco_cidade`,`c1`.`usuario` AS `usuario`,`c1`.`status` AS `status`,`c1`.`classe` AS `classe`,`c2`.`quantidade` AS `quantidade`,`c2`.`quantidade_liberada` AS `quantidade_liberada`,`c3`.`nome` AS `item`,`c4`.`texto` AS `status_item`,`c2`.`id_requisicao_item` AS `id_requisicao_item`,`c2`.`id_ativo_externo` AS `id_ativo_externo` from (((`v_requisicao` `c1` join `ativo_externo_requisicao_item` `c2` on((`c2`.`id_requisicao` = `c1`.`id_requisicao`))) join `ativo_externo` `c3` on((`c3`.`id_ativo_externo` = `c2`.`id_ativo_externo`))) join `ativo_externo_requisicao_status` `c4` on((`c4`.`id_requisicao_status` = `c2`.`status`))) ;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `ativo_externo`
--
ALTER TABLE `ativo_externo`
  ADD KEY `id_ativo_externo` (`id_ativo_externo`);

--
-- Índices de tabela `ativo_externo_requisicao`
--
ALTER TABLE `ativo_externo_requisicao`
  ADD KEY `id_requisicao` (`id_requisicao`);

--
-- Índices de tabela `ativo_externo_requisicao_ativo`
--
ALTER TABLE `ativo_externo_requisicao_ativo`
  ADD PRIMARY KEY (`id_requisicao_ativo`);

--
-- Índices de tabela `ativo_externo_requisicao_item`
--
ALTER TABLE `ativo_externo_requisicao_item`
  ADD KEY `id_requisicao_item` (`id_requisicao_item`);

--
-- Índices de tabela `ativo_externo_retirada`
--
ALTER TABLE `ativo_externo_retirada`
  ADD PRIMARY KEY (`id_retirada`);

--
-- Índices de tabela `ativo_externo_retirada_ativo`
--
ALTER TABLE `ativo_externo_retirada_ativo`
  ADD PRIMARY KEY (`id_retirada_ativo`);

--
-- Índices de tabela `ativo_externo_retirada_item`
--
ALTER TABLE `ativo_externo_retirada_item`
  ADD PRIMARY KEY (`id_retirada_item`);

--
-- Índices de tabela `ativo_externo_transferencia`
--
ALTER TABLE `ativo_externo_transferencia`
  ADD PRIMARY KEY (`id_transferencia`);

--
-- Índices de tabela `ativo_externo_transferencia_item`
--
ALTER TABLE `ativo_externo_transferencia_item`
  ADD PRIMARY KEY (`id_transferencia_item`);

--
-- Índices de tabela `ativo_interno`
--
ALTER TABLE `ativo_interno`
  ADD KEY `id_ativo_interno` (`id_ativo_interno`);

--
-- Índices de tabela `ativo_interno_manutencao`
--
ALTER TABLE `ativo_interno_manutencao`
  ADD PRIMARY KEY (`id_manutencao`);

--
-- Índices de tabela `ativo_interno_manutencao_obs`
--
ALTER TABLE `ativo_interno_manutencao_obs`
  ADD PRIMARY KEY (`id_obs`);

--
-- Índices de tabela `empresa`
--
ALTER TABLE `empresa`
  ADD KEY `id_empresa` (`id_empresa`);

--
-- Índices de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD KEY `id_fornecedor` (`id_fornecedor`);

--
-- Índices de tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD KEY `id_funcionario` (`id_funcionario`);

--
-- Índices de tabela `obra`
--
ALTER TABLE `obra`
  ADD KEY `id_obra` (`id_obra`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `ativo_externo`
--
ALTER TABLE `ativo_externo`
  MODIFY `id_ativo_externo` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_externo_requisicao`
--
ALTER TABLE `ativo_externo_requisicao`
  MODIFY `id_requisicao` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_externo_requisicao_ativo`
--
ALTER TABLE `ativo_externo_requisicao_ativo`
  MODIFY `id_requisicao_ativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_externo_requisicao_item`
--
ALTER TABLE `ativo_externo_requisicao_item`
  MODIFY `id_requisicao_item` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_externo_retirada`
--
ALTER TABLE `ativo_externo_retirada`
  MODIFY `id_retirada` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_externo_retirada_ativo`
--
ALTER TABLE `ativo_externo_retirada_ativo`
  MODIFY `id_retirada_ativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_externo_retirada_item`
--
ALTER TABLE `ativo_externo_retirada_item`
  MODIFY `id_retirada_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_externo_transferencia`
--
ALTER TABLE `ativo_externo_transferencia`
  MODIFY `id_transferencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_externo_transferencia_item`
--
ALTER TABLE `ativo_externo_transferencia_item`
  MODIFY `id_transferencia_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_interno`
--
ALTER TABLE `ativo_interno`
  MODIFY `id_ativo_interno` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_interno_manutencao`
--
ALTER TABLE `ativo_interno_manutencao`
  MODIFY `id_manutencao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `ativo_interno_manutencao_obs`
--
ALTER TABLE `ativo_interno_manutencao_obs`
  MODIFY `id_obs` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id_empresa` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  MODIFY `id_fornecedor` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `id_funcionario` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `obra`
--
ALTER TABLE `obra`
  MODIFY `id_obra` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000000000;
