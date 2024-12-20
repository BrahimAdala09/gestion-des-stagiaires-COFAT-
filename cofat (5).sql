-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 26 juil. 2024 à 08:47
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cofat`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf16;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `username`, `nom`, `prenom`, `password`) VALUES
(1, 'admin1', 'admin', 'admin', '0000'),
(2, 'brahim', 'brahim', 'adala', '$2y$10$Ghdl8Xetsy1iaJLqIuo4CugYx5/qsWMLI9NFRKKoE6NcKmr/OJLg6');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_de_stage`
--

DROP TABLE IF EXISTS `categorie_de_stage`;
CREATE TABLE IF NOT EXISTS `categorie_de_stage` (
  `categorie_id` int NOT NULL AUTO_INCREMENT,
  `nom_categorie` varchar(50) NOT NULL,
  PRIMARY KEY (`categorie_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf16;

--
-- Déchargement des données de la table `categorie_de_stage`
--

INSERT INTO `categorie_de_stage` (`categorie_id`, `nom_categorie`) VALUES
(28, 'IT'),
(29, 'COMPTABILITEE'),
(30, 'PROD');

-- --------------------------------------------------------

--
-- Structure de la table `diplome`
--

DROP TABLE IF EXISTS `diplome`;
CREATE TABLE IF NOT EXISTS `diplome` (
  `diplome_id` int NOT NULL AUTO_INCREMENT,
  `diplome_type` varchar(255) NOT NULL,
  PRIMARY KEY (`diplome_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf16;

--
-- Déchargement des données de la table `diplome`
--

INSERT INTO `diplome` (`diplome_id`, `diplome_type`) VALUES
(8, 'BTP'),
(7, 'BTS'),
(6, 'CAP'),
(9, 'LMD'),
(10, 'ING');

-- --------------------------------------------------------

--
-- Structure de la table `stage`
--

DROP TABLE IF EXISTS `stage`;
CREATE TABLE IF NOT EXISTS `stage` (
  `stage_id` int NOT NULL AUTO_INCREMENT,
  `stage_type` varchar(255) NOT NULL,
  PRIMARY KEY (`stage_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf16;

--
-- Déchargement des données de la table `stage`
--

INSERT INTO `stage` (`stage_id`, `stage_type`) VALUES
(1, 'PFA'),
(2, 'PFE'),
(3, 'Initiation'),
(4, 'Perfectionnement'),
(6, 'Optionnel');

-- --------------------------------------------------------

--
-- Structure de la table `stagiaire`
--

DROP TABLE IF EXISTS `stagiaire`;
CREATE TABLE IF NOT EXISTS `stagiaire` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `cin` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `telephone` int(8) NOT NULL,
  `date_de_naissance` date NOT NULL,
  `adress` varchar(255) NOT NULL,
  `categorie_id` int NOT NULL,
  `etablissement` varchar(100) DEFAULT NULL,
  `date_de_debut` date DEFAULT NULL,
  `date_de_fin` date DEFAULT NULL,
  `Etat` varchar(255) NOT NULL,
  `diplome_id` int NOT NULL,
  `stage_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cin` (`cin`),
  UNIQUE KEY `email` (`email`),
  KEY `categorie_id` (`categorie_id`),
  KEY `diplome_id` (`diplome_id`),
  KEY `stage_id` (`stage_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf16;

--
-- Déchargement des données de la table `stagiaire`
--

INSERT INTO `stagiaire` (`id`, `cin`, `nom`, `prenom`, `email`, `telephone`, `date_de_naissance`, `adress`, `categorie_id`, `etablissement`, `date_de_debut`, `date_de_fin`, `Etat`, `diplome_id`, `stage_id`) VALUES
(1, 15019008, 'Adala', 'Brahim', 'brahimadala09@gmail.com', 50231614, '2002-09-25', 'kram', 28, 'ISLAI beja', '2024-07-01', '2024-07-31', 'en cours', 9, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
