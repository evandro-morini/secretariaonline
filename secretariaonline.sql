-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 08-Jun-2016 às 02:50
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
-- Estrutura da tabela `tb_atribuido`
--

CREATE TABLE IF NOT EXISTS `tb_atribuido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_solicitacao` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `dta_inc` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_atribuido_solic_idx` (`fk_solicitacao`),
  KEY `fk_atribuido_usuario_idx` (`fk_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_curso`
--

CREATE TABLE IF NOT EXISTS `tb_curso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod` varchar(45) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `tb_curso`
--

INSERT INTO `tb_curso` (`id`, `cod`, `descricao`, `status`) VALUES
(1, '48A', 'Tecnologia em Análise e Desenvolvimento de Sistemas - Noite', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_disciplina`
--

CREATE TABLE IF NOT EXISTS `tb_disciplina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_curso` int(11) NOT NULL,
  `fk_professor` int(11) NOT NULL,
  `cod` varchar(45) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_disciplina_curso_idx` (`fk_curso`),
  KEY `fk_disciplina_professor_idx` (`fk_professor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `tb_disciplina`
--

INSERT INTO `tb_disciplina` (`id`, `fk_curso`, `fk_professor`, `cod`, `descricao`, `status`) VALUES
(1, 1, 1, 'TI143', 'Gerência de Projeto de Software', 1),
(2, 1, 2, 'TI027', 'Prática de Algoritmos', 1),
(3, 1, 3, 'TI159', 'Tópicos Especiais', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_encaminhado`
--

CREATE TABLE IF NOT EXISTS `tb_encaminhado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_solicitacao` int(11) NOT NULL,
  `fk_usuario_old` int(11) NOT NULL,
  `fk_usuario_new` int(11) NOT NULL,
  `justificativa` text NOT NULL,
  `dta_inc` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_encaminhado_solicitacao_idx` (`fk_solicitacao`),
  KEY `fk_encaminhado_usuario_old_idx` (`fk_usuario_old`),
  KEY `fk_encaminhado_usuario_new_idx` (`fk_usuario_new`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_encerrado`
--

CREATE TABLE IF NOT EXISTS `tb_encerrado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_solicitacao` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `justificativa` text NOT NULL,
  `dta_inc` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_encerrado_solicitacao_idx` (`fk_solicitacao`),
  KEY `fk_encerrado_usuario_idx` (`fk_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_perfil`
--

CREATE TABLE IF NOT EXISTS `tb_perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_curso` int(11) DEFAULT NULL,
  `descricao` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_perfil_curso_idx` (`fk_curso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `tb_perfil`
--

INSERT INTO `tb_perfil` (`id`, `fk_curso`, `descricao`, `status`) VALUES
(1, NULL, 'Aluno', 1),
(2, 1, 'Secretario(a)', 1),
(3, 1, 'Coordenação', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_professor`
--

CREATE TABLE IF NOT EXISTS `tb_professor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `tb_professor`
--

INSERT INTO `tb_professor` (`id`, `nome`, `email`, `status`) VALUES
(1, 'Rafaela Mantovani Fontana', 'rafaela.m.fontana@gmail.com', 1),
(2, 'Luiz Antonio Pereira Neves', 'lapneves@gmail.com', 1),
(3, 'Alessandro Brawerman', 'brawerman@gmail.com', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_solicitacao`
--

CREATE TABLE IF NOT EXISTS `tb_solicitacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_usuario` int(11) NOT NULL,
  `fk_curso` int(11) NOT NULL,
  `protocolo` varchar(45) DEFAULT NULL,
  `fk_tipo_solicitacao` int(11) NOT NULL,
  `observacao` text,
  `arquivo` varchar(255) DEFAULT NULL,
  `fk_status` int(11) NOT NULL,
  `dta_abertura` datetime NOT NULL,
  `dta_alteracao` datetime DEFAULT NULL,
  `dta_fechamento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tb_solicitacao_usuario_idx` (`fk_usuario`),
  KEY `fk_tb_solicitacao_curso_idx` (`fk_curso`),
  KEY `fk_tb_solicitacao_status_idx` (`fk_status`),
  KEY `fk_tb_solicitacao_tipo_idx` (`fk_tipo_solicitacao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_solicitacao_disciplina`
--

CREATE TABLE IF NOT EXISTS `tb_solicitacao_disciplina` (
  `fk_solicitacao` int(11) NOT NULL,
  `fk_disciplina` int(11) NOT NULL,
  `fk_tipo_solic_disciplina` int(11) DEFAULT NULL,
  KEY `fk_solicitacao_disciplina_idx` (`fk_solicitacao`),
  KEY `fk_disciplina_solicitacao_idx` (`fk_disciplina`),
  KEY `fk_solicitacao_disciplina_tipo_idx` (`fk_tipo_solic_disciplina`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_status`
--

CREATE TABLE IF NOT EXISTS `tb_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `tb_status`
--

INSERT INTO `tb_status` (`id`, `descricao`, `status`) VALUES
(1, 'Aberto', 1),
(2, 'Em atendimento', 1),
(3, 'Encaminhado', 1),
(4, 'Encerrado', 1),
(5, 'Cancelado', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_tipo_solicitacao`
--

CREATE TABLE IF NOT EXISTS `tb_tipo_solicitacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `tb_tipo_solicitacao`
--

INSERT INTO `tb_tipo_solicitacao` (`id`, `descricao`, `status`) VALUES
(1, 'Adiantamento de disciplina', 1),
(2, 'Aproveitamento de conhecimento', 1),
(3, 'Cancelamento de matrícula', 1),
(4, 'Correção de matrícula', 1),
(5, 'Outras solicitações', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_tipo_solic_disciplina`
--

CREATE TABLE IF NOT EXISTS `tb_tipo_solic_disciplina` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `tb_tipo_solic_disciplina`
--

INSERT INTO `tb_tipo_solic_disciplina` (`id`, `descricao`, `status`) VALUES
(1, 'Não se aplica', '1'),
(2, 'Incluir', '1'),
(3, 'Excluir', '1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario`
--

CREATE TABLE IF NOT EXISTS `tb_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cpf` varchar(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(14) NOT NULL,
  `email` varchar(45) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `fk_perfil` int(11) NOT NULL,
  `adm` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_usuario_fk_perfil_idx` (`fk_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`id`, `cpf`, `nome`, `telefone`, `email`, `pwd`, `fk_perfil`, `adm`, `status`, `hash`) VALUES
(11, '04763305999', 'Evandro Morini Silva', '(41) 8810-1513', 'evandrogtr@hotmail.com', 'aaa2f054b103184b6280569d311e0a219d5436ea', 1, 0, 1, '7c45f07749a7f06a98cff559931f6cb3'),
(13, '06499454923', 'Patrícia Pacheco dos Santos', '(41) 3045-5425', 'pati.p.santos@hotmail.com', '94cbcdee6d2771acf9a2f73a0373994b1919c3bc', 1, 0, 1, '4e732e654d47b5f91586f99e3cad2770\r\n'),
(15, '55499508470', 'Adão', '(41) 9727-6020', 'testeservidor@teste.com', 'e852ca3a736cf9f50237cab54e3ed84b7aeb9144', 2, 0, 1, 'ab63eb803423a8b09d08d3805c7b9c10\r\n'),
(16, '20659217309', 'Luiz Antonio Pereira Neves', '(41) 9985-9711', 'testeadm@teste.com', '8eb456f281c9b4ae8e3877ffdfa7d784631e5a30', 3, 1, 1, '16f98d0182e0b50e28473763238c57a9\r\n'),
(17, '12345678911', 'Otávio Ledur', '(41) 8110-8765', 'otavio.ledur@gmail.com', 'c920fc2b4a1d62023ecc7627df456442cebff6d2', 1, 0, 1, '1f50ca4e8299c9e4ae601f179fbc440c\r\n');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario_curso`
--

CREATE TABLE IF NOT EXISTS `tb_usuario_curso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_usuario` int(11) NOT NULL,
  `fk_curso` int(11) NOT NULL,
  `matricula` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_usuario_curso_usuario_idx` (`fk_usuario`),
  KEY `fk_usuario_curso_curso_idx` (`fk_curso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Extraindo dados da tabela `tb_usuario_curso`
--

INSERT INTO `tb_usuario_curso` (`id`, `fk_usuario`, `fk_curso`, `matricula`) VALUES
(8, 11, 1, 'GRR20121401'),
(10, 13, 1, 'GRR20121511'),
(11, 17, 1, 'GRR20121364');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tb_atribuido`
--
ALTER TABLE `tb_atribuido`
  ADD CONSTRAINT `fk_atribuido_solic` FOREIGN KEY (`fk_solicitacao`) REFERENCES `tb_solicitacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_atribuido_usuario` FOREIGN KEY (`fk_usuario`) REFERENCES `tb_usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_disciplina`
--
ALTER TABLE `tb_disciplina`
  ADD CONSTRAINT `fk_disciplina_curso` FOREIGN KEY (`fk_curso`) REFERENCES `tb_curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_disciplina_professor` FOREIGN KEY (`fk_professor`) REFERENCES `tb_professor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_encaminhado`
--
ALTER TABLE `tb_encaminhado`
  ADD CONSTRAINT `fk_encaminhado_solicitacao` FOREIGN KEY (`fk_solicitacao`) REFERENCES `tb_solicitacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_encaminhado_usuario_new` FOREIGN KEY (`fk_usuario_new`) REFERENCES `tb_usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_encaminhado_usuario_old` FOREIGN KEY (`fk_usuario_old`) REFERENCES `tb_usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_encerrado`
--
ALTER TABLE `tb_encerrado`
  ADD CONSTRAINT `fk_encerrado_solicitacao` FOREIGN KEY (`fk_solicitacao`) REFERENCES `tb_solicitacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_encerrado_usuario` FOREIGN KEY (`fk_usuario`) REFERENCES `tb_usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_perfil`
--
ALTER TABLE `tb_perfil`
  ADD CONSTRAINT `fk_perfil_curso` FOREIGN KEY (`fk_curso`) REFERENCES `tb_curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_solicitacao`
--
ALTER TABLE `tb_solicitacao`
  ADD CONSTRAINT `fk_tb_solicitacao_curso` FOREIGN KEY (`fk_curso`) REFERENCES `tb_curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_solicitacao_status` FOREIGN KEY (`fk_status`) REFERENCES `tb_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_solicitacao_tipo` FOREIGN KEY (`fk_tipo_solicitacao`) REFERENCES `tb_tipo_solicitacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tb_solicitacao_usuario` FOREIGN KEY (`fk_usuario`) REFERENCES `tb_usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_solicitacao_disciplina`
--
ALTER TABLE `tb_solicitacao_disciplina`
  ADD CONSTRAINT `fk_disciplina_solicitacao` FOREIGN KEY (`fk_disciplina`) REFERENCES `tb_disciplina` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_solicitacao_disciplina` FOREIGN KEY (`fk_solicitacao`) REFERENCES `tb_solicitacao` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_solicitacao_disciplina_tipo` FOREIGN KEY (`fk_tipo_solic_disciplina`) REFERENCES `tb_tipo_solic_disciplina` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_usuario`
--
ALTER TABLE `tb_usuario`
  ADD CONSTRAINT `tb_usuario_fk_perfil` FOREIGN KEY (`fk_perfil`) REFERENCES `tb_perfil` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `tb_usuario_curso`
--
ALTER TABLE `tb_usuario_curso`
  ADD CONSTRAINT `fk_usuario_curso_curso` FOREIGN KEY (`fk_curso`) REFERENCES `tb_curso` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_curso_usuario` FOREIGN KEY (`fk_usuario`) REFERENCES `tb_usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
