-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04/10/2023 às 00:07
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `usuarios2`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno`
--

CREATE TABLE `aluno` (
  `idaluno` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `matricula` int(11) DEFAULT NULL,
  `ano_inicio` date DEFAULT NULL,
  `datanascimento` date DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `idturma` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aluno`
--

INSERT INTO `aluno` (`idaluno`, `nome`, `matricula`, `ano_inicio`, `datanascimento`, `foto`, `cidade`, `descricao`, `idusuario`, `idturma`) VALUES
(13, 'Aluno 1', 12345, '2022-09-01', '2000-01-15', 'foto1.jpg', 'Cidade A', 'Descrição do Aluno 1', 17, 1),
(14, 'Aluno 2', 54321, '2022-09-01', '2001-03-20', 'foto2.jpg', 'Cidade B', 'Descrição do Aluno 2', 19, 2),
(15, 'Aluno 3', 98765, '2022-09-01', '2002-05-10', 'foto3.jpg', 'Cidade C', 'Descrição do Aluno 3', 21, 3),
(16, 'Aluno 4', 45454, '2022-09-01', '2002-05-10', 'foto4.jpg', 'Cidade D', 'Descrição do Aluno 4', 22, 1),
(17, 'Aluno 5', 23234, '2022-09-01', '2002-05-10', 'foto5.jpg', 'Cidade E', 'Descrição do Aluno 5', 23, 2),
(18, 'Aluno 6', 78789, '2022-09-01', '2002-05-10', 'foto6.jpg', 'Cidade C', 'Descrição do Aluno 3', 24, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `colaborador`
--

CREATE TABLE `colaborador` (
  `idcolabora` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `datanascimento` date DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `ano_inicio` date DEFAULT NULL,
  `iduser` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `colaborador`
--

INSERT INTO `colaborador` (`idcolabora`, `nome`, `descricao`, `foto`, `datanascimento`, `cidade`, `ano_inicio`, `iduser`) VALUES
(1, 'Colaborador 1', 'Descrição do Colaborador 1', 'foto4.jpg', '1980-08-25', 'Cidade X', '2005-01-01', 19),
(2, 'Colaborador 2', 'Descrição do Colaborador 2', 'foto5.jpg', '1985-12-10', 'Cidade Y', '2010-02-15', 19),
(3, 'Colaborador 3', 'Descrição do Colaborador 3', 'foto6.jpg', '1990-03-05', 'Cidade Z', '2015-03-20', 19);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `idcurso` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `idfacul` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `turno` varchar(255) DEFAULT NULL,
  `idcordenador` int(11) DEFAULT NULL,
  `idlider` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`idcurso`, `nome`, `idfacul`, `foto`, `descricao`, `turno`, `idcordenador`, `idlider`) VALUES
(1, 'Curso 1', 1, 'nome_da_foto.jpg', 'Descrição do Curso 1', 'manha', 1, 15),
(2, 'Curso 2', 1, 'nome_da_foto.jpg', 'Descrição do Curso 2', 'tarde', 2, 14),
(3, 'Curso 3', 2, 'nome_da_foto.jpg', 'Descrição do Curso 3', 'noite', 3, 16),
(4, 'Curso 4', 2, 'nome_da_foto.jpg', 'Descrição do Curso 4', 'manha', 1, 17),
(5, 'Curso 5', 3, 'nome_da_foto.jpg', 'Descrição do Curso 5', 'tarde', 2, 18),
(6, 'Curso 6', 3, 'nome_da_foto.jpg', 'Descrição do Curso 6', 'noite', 3, 13);

-- --------------------------------------------------------

--
-- Estrutura para tabela `faculdade`
--

CREATE TABLE `faculdade` (
  `idfacul` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `faculdade`
--

INSERT INTO `faculdade` (`idfacul`, `nome`, `descricao`, `foto`) VALUES
(1, 'Faculdade A', 'Descrição da Faculdade A', 'idfacul1.png'),
(2, 'Faculdade B', 'Descrição da Faculdade B', '2'),
(3, 'Faculdade C', 'Descrição da Faculdade C', '3'),
(4, 'Faculdade  D', 'Descrição da Faculdade D', '1'),
(5, 'Faculdade E', 'Descrição da Faculdade E', '2'),
(6, 'Faculdade F', 'Descrição da Faculdade F', '3'),
(7, 'Faculdade G', 'Descrição da Faculdade G', '1'),
(8, 'Faculdade H', 'Descrição da Faculdade H', '2'),
(9, 'Faculdade I', 'Descrição da Faculdade I', '3');

-- --------------------------------------------------------

--
-- Estrutura para tabela `formacoes`
--

CREATE TABLE `formacoes` (
  `idformacao` int(11) NOT NULL,
  `iduser` int(11) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `periodo` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `formacoes`
--

INSERT INTO `formacoes` (`idformacao`, `iduser`, `titulo`, `numero`, `periodo`) VALUES
(1, 17, 'Bacharel em Ciência de Dados', 1, '2025-12-31'),
(2, 19, 'Mestrado em Engenharia de Software', 1, '2024-12-31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `turmas`
--

CREATE TABLE `turmas` (
  `idturma` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `idcurso` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `ano_inicio` date DEFAULT NULL,
  `ano_fim` date DEFAULT NULL,
  `turno` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `turmas`
--

INSERT INTO `turmas` (`idturma`, `nome`, `idcurso`, `foto`, `ano_inicio`, `ano_fim`, `turno`) VALUES
(1, 'Turma A1', 1, 'nome_da_foto.jpg', '0000-00-00', '0000-00-00', 'manha'),
(2, 'Turma A2', 1, 'outra_foto.jpg', '0000-00-00', '0000-00-00', 'tarde'),
(3, 'Turma B1', 2, 'nome_da_foto.jpg', '0000-00-00', '0000-00-00', 'noite'),
(4, 'Turma B2', 2, 'outra_foto.jpg', '0000-00-00', '0000-00-00', 'manha'),
(5, 'Turma C1', 3, 'outra_foto.jpg', '0000-00-00', '0000-00-00', 'tarde'),
(6, 'Turma C2', 3, 'nome_da_foto.jpg', '0000-00-00', '0000-00-00', 'noite');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `iduser` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha_criptografada` varchar(255) NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT 1,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`iduser`, `usuario`, `senha_criptografada`, `nivel`, `email`) VALUES
(17, 'root', '$2y$10$ZS8rv2rHVUmzNw2sCR55UuFapOdwB/0yC8W1zWaJDD6Ws6RtI0WY2', 1, NULL),
(19, 'rootpro', '$2y$10$ib39H0fXVFRyOBtDXast3OXJr.7ToJazcCr6TMJ6aY3.SWsPqSCNu', 2, NULL),
(21, 'root1', '$2y$10$DqNLbME1Vpb5TD5zbWu2sOMOKJMjdxKVdNAmqsIcbJL5bNVRaarrO', 1, NULL),
(22, 'root2', '$2y$10$AHuf6ndmQwAcE7MMrbVAy.AFVlw88VMRyp9VwEiwFPG.QLyblS.8i', 1, NULL),
(23, 'root3', '$2y$10$fTOWdX0nqdI6z75yi9B6yOAG03vvJrh6rsuyWOyI6Pp5eaTZcQFdC', 1, NULL),
(24, 'root4', '$2y$10$uIlbAFc5TcvahPgT2/qh1O1UNBac7GvUqBhJcZ9tT2J8ESXaC65Ua', 1, NULL),
(25, 'root5', '$2y$10$iWajtZRLtPGaioBI0Vtqv.A1m1N2X64FP/GsKmN2AhScfegVy9xhC', 1, NULL),
(26, 'root6', '$2y$10$GShyQPTA3OUw3afqJYakWu76ZJd7VVtJlDh6bil9VUM4F9d0BSYcy', 1, NULL),
(27, 'root7', '$2y$10$vPPQKATk3wf0EC8CKNd4WuN6IO7gKZmAB119LzdNyVBhlbxJCGL4a', 1, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`idaluno`),
  ADD KEY `idusuario` (`idusuario`),
  ADD KEY `idturma` (`idturma`);

--
-- Índices de tabela `colaborador`
--
ALTER TABLE `colaborador`
  ADD PRIMARY KEY (`idcolabora`),
  ADD KEY `iduser` (`iduser`);

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`idcurso`),
  ADD KEY `idfacul` (`idfacul`),
  ADD KEY `idcordenador` (`idcordenador`),
  ADD KEY `idlider` (`idlider`);

--
-- Índices de tabela `faculdade`
--
ALTER TABLE `faculdade`
  ADD PRIMARY KEY (`idfacul`);

--
-- Índices de tabela `formacoes`
--
ALTER TABLE `formacoes`
  ADD PRIMARY KEY (`idformacao`),
  ADD KEY `iduser` (`iduser`);

--
-- Índices de tabela `turmas`
--
ALTER TABLE `turmas`
  ADD PRIMARY KEY (`idturma`),
  ADD KEY `idcurso` (`idcurso`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`iduser`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `idaluno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `colaborador`
--
ALTER TABLE `colaborador`
  MODIFY `idcolabora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `idcurso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `faculdade`
--
ALTER TABLE `faculdade`
  MODIFY `idfacul` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `formacoes`
--
ALTER TABLE `formacoes`
  MODIFY `idformacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `turmas`
--
ALTER TABLE `turmas`
  MODIFY `idturma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `aluno`
--
ALTER TABLE `aluno`
  ADD CONSTRAINT `aluno_ibfk_1` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`iduser`),
  ADD CONSTRAINT `aluno_ibfk_3` FOREIGN KEY (`idturma`) REFERENCES `turmas` (`idturma`);

--
-- Restrições para tabelas `colaborador`
--
ALTER TABLE `colaborador`
  ADD CONSTRAINT `colaborador_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `usuarios` (`iduser`);

--
-- Restrições para tabelas `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`idfacul`) REFERENCES `faculdade` (`idfacul`),
  ADD CONSTRAINT `cursos_ibfk_4` FOREIGN KEY (`idcordenador`) REFERENCES `colaborador` (`idcolabora`),
  ADD CONSTRAINT `cursos_ibfk_5` FOREIGN KEY (`idlider`) REFERENCES `aluno` (`idaluno`),
  ADD CONSTRAINT `cursos_ibfk_6` FOREIGN KEY (`idcordenador`) REFERENCES `colaborador` (`idcolabora`),
  ADD CONSTRAINT `cursos_ibfk_7` FOREIGN KEY (`idlider`) REFERENCES `aluno` (`idaluno`);

--
-- Restrições para tabelas `formacoes`
--
ALTER TABLE `formacoes`
  ADD CONSTRAINT `formacoes_ibfk_1` FOREIGN KEY (`iduser`) REFERENCES `usuarios` (`iduser`);

--
-- Restrições para tabelas `turmas`
--
ALTER TABLE `turmas`
  ADD CONSTRAINT `turmas_ibfk_1` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`),
  ADD CONSTRAINT `turmas_ibfk_2` FOREIGN KEY (`idcurso`) REFERENCES `cursos` (`idcurso`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
