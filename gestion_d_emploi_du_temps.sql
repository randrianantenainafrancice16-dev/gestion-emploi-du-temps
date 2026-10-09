-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 08 oct. 2026 à 13:12
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_d_emploi_du_temps`
--

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

CREATE TABLE `classe` (
  `idclasse` varchar(20) NOT NULL,
  `niveau` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classe`
--

INSERT INTO `classe` (`idclasse`, `niveau`) VALUES
('L2 IG G2', 'L2'),
('L2 IG G3', 'L2'),
('L2-GB-G1', 'Licence 2 Gestion Biologie Groupe 1'),
('L2-IG-G3', 'Licence 2 Informatique de Gestion Groupe 3'),
('L3-IG', 'Licence 3 Informatique de Gestion'),
('M2 ASR G1', 'M2');

-- --------------------------------------------------------

--
-- Structure de la table `edt`
--

CREATE TABLE `edt` (
  `idedt` int(11) NOT NULL,
  `idsalle` int(11) NOT NULL,
  `idprof` varchar(20) NOT NULL,
  `idclasse` varchar(20) NOT NULL,
  `cours` varchar(100) NOT NULL,
  `Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `edt`
--

INSERT INTO `edt` (`idedt`, `idsalle`, `idprof`, `idclasse`, `cours`, `Date`) VALUES
(1, 4, 'P001', 'L2-IG-G3', 'Algorithmique', '2026-06-29 08:00:00'),
(2, 4, 'P004', 'L3-IG', 'Mathématiques', '2026-06-29 10:00:00'),
(3, 5, 'P002', 'L2-IG-G3', 'Mathématiques', '2026-06-29 12:00:00'),
(4, 4, 'P004', 'L3-IG', 'Programmation', '2026-07-01 14:00:00'),
(5, 4, 'P004', 'L3-IG', 'bdr', '2026-07-06 15:00:00'),
(6, 6, 'P003', 'L2-GB-G1', 'MATH', '2026-07-06 08:00:00'),
(7, 1, 'PO8', 'L2 IG G2', 'MICRO', '2026-07-06 10:00:00'),
(8, 6, 'P002', 'L2 IG G2', 'MATH', '2026-07-07 14:00:00'),
(9, 5, 'P004', 'L2 IG G2', 'ASMS', '2026-07-07 16:00:00'),
(10, 3, 'P002', 'L2-GB-G1', 'MATH', '2026-07-11 21:07:00'),
(11, 5, 'P008', 'L2 IG G2', 'math', '2026-07-13 10:00:00'),
(12, 4, 'P008', 'L2-GB-G1', 'analyse', '2026-07-13 14:00:00'),
(13, 6, 'P001', 'L2 IG G2', 'math', '2026-08-07 14:00:00'),
(14, 5, 'P001', 'L2 IG G2', 'analyse', '2026-08-07 16:00:00'),
(15, 6, 'P002', 'L2-GB-G1', 'math', '2026-08-03 14:00:00'),
(16, 4, 'P008', 'L2-IG-G3', 'math', '2026-08-04 16:00:00'),
(17, 6, 'P002', 'L3-IG', 'math', '2026-08-05 14:00:00'),
(18, 6, 'P001', 'L2 IG G2', 'math', '2026-08-26 23:04:00');

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

CREATE TABLE `professeur` (
  `idprof` varchar(20) NOT NULL,
  `Nom` varchar(100) NOT NULL,
  `Prenoms` varchar(100) NOT NULL,
  `Grade` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `professeur`
--

INSERT INTO `professeur` (`idprof`, `Nom`, `Prenoms`, `Grade`, `email`) VALUES
('999', 'rabe', 'rakoto', 'Maître de Conférences', 'RAKOTO88@GMAIL.COM'),
('P001', 'RAKOT', 'Jean', 'Maître de Conférences', 'randrianantenainafrancice16@gmail.com'),
('P002', 'RABE', 'Marie', 'Maître de Conférences', ''),
('P003', 'RANDRIANANTENAINA', 'Francice', 'Professeur Titulaire', 'totomananaambroise@gmail.com'),
('P004', 'ANDRY', 'Sophie', 'Doctorant en Informatique', 'jean.rakoto@gmail.com'),
('P008', 'TOTOMANANA', 'Ambroise', 'Maître de Conférences', 'ambroisetotomanana@gmail.com'),
('PO8', 'Francice', 'bbb', 'Professeur Titulaire', 'nyavoarsonheriniaina@gmail.com'),
('pooo1', 'Francice', 'Jean', 'Professeur Titulaire', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `idsalle` int(11) NOT NULL,
  `design` varchar(100) NOT NULL,
  `occupation` enum('Libre','Occupée') DEFAULT 'Libre'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`idsalle`, `design`, `occupation`) VALUES
(1, 'Amphithéâtre A', 'Occupée'),
(3, 'Salle 102', 'Occupée'),
(4, 'Salle TP Informatique', 'Occupée'),
(5, 'Salle de Conférence', 'Occupée'),
(6, '210', 'Occupée'),
(7, '210', 'Libre'),
(8, 'Amphithéâtre A', 'Occupée');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`idclasse`);

--
-- Index pour la table `edt`
--
ALTER TABLE `edt`
  ADD PRIMARY KEY (`idedt`),
  ADD KEY `edt_ibfk_1` (`idsalle`),
  ADD KEY `edt_ibfk_2` (`idprof`),
  ADD KEY `edt_ibfk_3` (`idclasse`);

--
-- Index pour la table `professeur`
--
ALTER TABLE `professeur`
  ADD PRIMARY KEY (`idprof`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`idsalle`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `edt`
--
ALTER TABLE `edt`
  MODIFY `idedt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `idsalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `edt`
--
ALTER TABLE `edt`
  ADD CONSTRAINT `edt_ibfk_1` FOREIGN KEY (`idsalle`) REFERENCES `salle` (`idsalle`),
  ADD CONSTRAINT `edt_ibfk_2` FOREIGN KEY (`idprof`) REFERENCES `professeur` (`idprof`),
  ADD CONSTRAINT `edt_ibfk_3` FOREIGN KEY (`idclasse`) REFERENCES `classe` (`idclasse`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
