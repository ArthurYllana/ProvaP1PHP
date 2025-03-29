-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/03/2025 às 18:38
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `provap1`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `pro_cod` int(11) NOT NULL,
  `pro_descricao` varchar(30) NOT NULL,
  `pro_fabricante` varchar(15) NOT NULL,
  `pro_ingredientes` varchar(50) NOT NULL,
  `pro_orientacoes` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`pro_cod`, `pro_descricao`, `pro_fabricante`, `pro_ingredientes`, `pro_orientacoes`) VALUES
(1, 'Molho de pimenta', 'Sra. Pimenta', 'Pimenta do reino', 'Use de molho'),
(2, 'Milho ', 'Sr.Milho ', 'Milho verde', 'Cozinhar'),
(4, 'Banana', 'Sr. Banana', 'Banana', 'Comer '),
(5, 'Torresmo ', 'Sr.Torresmo ', 'Banha de porco ', 'Comer '),
(6, 'Feijao ', 'Sr.Feijao', 'Feijao de corda', 'Cozinhar'),
(8, 'Farinha de Mandioca', 'Lagoa ', 'Mandioca frita ', 'Moída duas veze'),
(10, 'Linguiça ', 'Sr. Linguiça', 'Melhor não saber', 'Frite'),
(11, 'Batata', 'Sr. Batata', 'Batata ', 'Fritar'),
(12, 'Miojo', 'Turma da Monica', 'Segredo ', 'Ferver');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`pro_cod`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `pro_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
