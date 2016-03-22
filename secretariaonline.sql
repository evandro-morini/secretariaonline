-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 23-Mar-2016 às 00:15
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `secretariaonline`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_perfil`
--

CREATE TABLE IF NOT EXISTS `tb_perfil` (
  `cod` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `tb_perfil`
--

INSERT INTO `tb_perfil` (`cod`, `descricao`, `status`) VALUES
(1, 'Aluno', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cpf` varchar(11) NOT NULL,
  `matricula` varchar(45) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `dta_nasc` date DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `fk_perfil` int(11) NOT NULL,
  `adm` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_usuario_fk_perfil_idx` (`fk_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`id`, `cpf`, `matricula`, `nome`, `dta_nasc`, `email`, `pwd`, `fk_perfil`, `adm`) VALUES
(1, '04763305999', 'GRR20121401', 'Evandro Morini Silva', '1985-08-19', 'evandro.morini@gmail.com', 'aaa2f054b103184b6280569d311e0a219d5436ea', 1, 0),
(2, '27634702030', 'GRR20162016', 'Usuário Teste', '1980-01-01', 'teste@teste.com', '97c1844c2188aa77c91e9c97a9de62dbcd2afe40', 1, 0);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD CONSTRAINT `tb_usuario_fk_perfil` FOREIGN KEY (`fk_perfil`) REFERENCES `tb_perfil` (`cod`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
