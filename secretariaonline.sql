-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 26-Maio-2016 às 04:02
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Extraindo dados da tabela `tb_atribuido`
--

INSERT INTO `tb_atribuido` (`id`, `fk_solicitacao`, `fk_usuario`, `dta_inc`) VALUES
(20, 48, 15, '2016-05-20 16:19:48'),
(21, 49, 15, '2016-05-20 16:19:57'),
(22, 48, 16, '2016-05-20 16:22:05'),
(23, 50, 15, '2016-05-20 23:38:47'),
(24, 50, 16, '2016-05-20 23:40:54');

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
  KEY `fk_disciplina_curso_idx` (`fk_curso`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `tb_disciplina`
--

INSERT INTO `tb_disciplina` (`id`, `fk_curso`, `fk_professor`, `cod`, `descricao`, `status`) VALUES
(1, 1, 1, 'TI143', 'Gerência de Projeto de Software', 1),
(2, 1, 2, 'TI027', 'Prática de Algoritmos', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Extraindo dados da tabela `tb_encaminhado`
--

INSERT INTO `tb_encaminhado` (`id`, `fk_solicitacao`, `fk_usuario_old`, `fk_usuario_new`, `justificativa`, `dta_inc`) VALUES
(12, 48, 15, 16, 'Favor verificar, o prazo de matrícula já foi ultrapassado.', '2016-05-20 16:20:33'),
(13, 48, 16, 15, 'Adão, por favor aceite o requerimento do aluno.', '2016-05-20 16:22:30'),
(14, 50, 15, 16, 'Neves, a solicitação está fora do prazo. Favor verificar.', '2016-05-20 23:39:54');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `tb_encerrado`
--

INSERT INTO `tb_encerrado` (`id`, `fk_solicitacao`, `fk_usuario`, `justificativa`, `dta_inc`) VALUES
(2, 49, 15, 'Deferido. Solicitação realizada com sucesso.', '2016-05-20 16:21:22'),
(3, 50, 16, 'Deferido.', '2016-05-20 23:41:42');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `tb_professor`
--

INSERT INTO `tb_professor` (`id`, `nome`, `email`, `status`) VALUES
(1, 'Rafaela Mantovani Fontana', 'rafaela.m.fontana@gmail.com', 1),
(2, 'Luiz Antonio Pereira Neves', 'lapneves@gmail.com', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- Extraindo dados da tabela `tb_solicitacao`
--

INSERT INTO `tb_solicitacao` (`id`, `fk_usuario`, `fk_curso`, `protocolo`, `fk_tipo_solicitacao`, `observacao`, `arquivo`, `fk_status`, `dta_abertura`, `dta_alteracao`, `dta_fechamento`) VALUES
(48, 11, 1, '20160520CO048', 4, NULL, NULL, 3, '2016-05-20 16:17:35', '2016-05-20 16:22:31', NULL),
(49, 11, 1, '20160520OS049', 5, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer tincidunt venenatis elit, et porta elit consectetur sed. Morbi non quam nec neque viverra pulvinar. Donec tempus mi sit amet turpis tempus, vestibulum porttitor dui ultrices. Quisque non quam sem. Vivamus hendrerit posuere blandit. Curabitur tincidunt nisi magna, quis lobortis enim auctor eu. Cras interdum pulvinar sem, in consequat risus scelerisque ultrices. Maecenas lobortis risus diam, fermentum malesuada mauris dictum ut. Vivamus metus nulla, egestas in auctor eu, auctor et orci. Nulla dapibus augue velit, non iaculis nisl lobortis tincidunt. Morbi odio mauris, euismod eu turpis ac, vehicula elementum turpis. ', 'photo23.jpg', 4, '2016-05-20 16:19:06', '2016-05-20 16:19:57', '2016-05-20 16:21:22'),
(50, 11, 1, '20160520OS050', 5, 'Declaração de matrícula.', '26115.jpg', 4, '2016-05-20 23:37:33', '2016-05-20 23:40:54', '2016-05-20 23:41:42'),
(51, 11, 1, '20160520CO051', 4, NULL, NULL, 5, '2016-05-20 23:46:11', NULL, '2016-05-20 23:46:48');

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

--
-- Extraindo dados da tabela `tb_solicitacao_disciplina`
--

INSERT INTO `tb_solicitacao_disciplina` (`fk_solicitacao`, `fk_disciplina`, `fk_tipo_solic_disciplina`) VALUES
(48, 1, 2),
(48, 2, 3),
(51, 1, 2),
(51, 2, 3);

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
  `dta_nasc` date DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `fk_perfil` int(11) NOT NULL,
  `adm` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tb_usuario_fk_perfil_idx` (`fk_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Extraindo dados da tabela `tb_usuario`
--

INSERT INTO `tb_usuario` (`id`, `cpf`, `nome`, `dta_nasc`, `email`, `pwd`, `fk_perfil`, `adm`, `status`) VALUES
(11, '04763305999', 'Evandro Morini Silva', '1985-08-19', 'evandro.morini@gmail.com', 'aaa2f054b103184b6280569d311e0a219d5436ea', 1, 0, 1),
(13, '06499454923', 'Patrícia Pacheco dos Santos', '1987-11-15', 'pati.p.santos@hotmail.com', '94cbcdee6d2771acf9a2f73a0373994b1919c3bc', 1, 0, 1),
(15, '55499508470', 'Adão', '1985-08-19', 'testeservidor@teste.com', 'e852ca3a736cf9f50237cab54e3ed84b7aeb9144', 2, 0, 1),
(16, '20659217309', 'Luiz Antonio Pereira Neves', '1988-05-31', 'testeadm@teste.com', '8eb456f281c9b4ae8e3877ffdfa7d784631e5a30', 3, 1, 1),
(17, '12345678911', 'Otávio Ledur', '1982-03-19', 'otavio.ledur@gmail.com', '5ad1984a8790720998f0cd804e4e46a82444be56', 1, 0, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

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
  ADD CONSTRAINT `fk_disciplina_professor` FOREIGN KEY (`id`) REFERENCES `tb_professor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
