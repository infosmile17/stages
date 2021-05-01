-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 26 avr. 2021 à 10:53
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `g_stages`
--

-- --------------------------------------------------------

--
-- Structure de la table `actualite`
--

DROP TABLE IF EXISTS `actualite`;
CREATE TABLE IF NOT EXISTS `actualite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `actualite`
--

INSERT INTO `actualite` (`id`, `titre`, `description`, `date`) VALUES
(1, 'test actualité', 'je sais pas mais cvv', '2021-04-25');

-- --------------------------------------------------------

--
-- Structure de la table `demande_nomination`
--

DROP TABLE IF EXISTS `demande_nomination`;
CREATE TABLE IF NOT EXISTS `demande_nomination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_etudiant` int(11) NOT NULL,
  `nom_pdf` varchar(250) NOT NULL,
  `date_demande` date NOT NULL,
  `date_reponse` date NOT NULL,
  `etat` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `demande_nomination_ibfk_1` (`id_etudiant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `demande_stage`
--

DROP TABLE IF EXISTS `demande_stage`;
CREATE TABLE IF NOT EXISTS `demande_stage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_etudiant` int(11) NOT NULL,
  `nom_pdf` varchar(250) NOT NULL,
  `date_demande` date NOT NULL,
  `date_reponse` date NOT NULL,
  `etat` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `demande_stage_ibfk_1` (`id_etudiant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `enseignant_soutenance`
--

DROP TABLE IF EXISTS `enseignant_soutenance`;
CREATE TABLE IF NOT EXISTS `enseignant_soutenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_enseignant` int(11) NOT NULL,
  `date` date NOT NULL,
  `temps` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `enseignant_soutenance_ibfk_1` (`id_enseignant`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `enseignant_soutenance`
--

INSERT INTO `enseignant_soutenance` (`id`, `id_enseignant`, `date`, `temps`) VALUES
(1, 2, '2021-04-27', '15:30:00');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

DROP TABLE IF EXISTS `etudiant`;
CREATE TABLE IF NOT EXISTS `etudiant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_etudiant` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `id_etudiant`, `niveau`) VALUES
(1, 2, 3),
(2, 2, 3),
(3, 4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant_soutenance`
--

DROP TABLE IF EXISTS `etudiant_soutenance`;
CREATE TABLE IF NOT EXISTS `etudiant_soutenance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_etudiant` int(11) NOT NULL,
  `date` date NOT NULL,
  `temps` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `etudiant_soutenance_ibfk_1` (`id_etudiant`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rapport`
--

DROP TABLE IF EXISTS `rapport`;
CREATE TABLE IF NOT EXISTS `rapport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_etudiant` int(11) NOT NULL,
  `titre` varchar(250) NOT NULL,
  `nom_pdf` varchar(250) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rapport_ibfk_1` (`id_etudiant`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `rapport`
--

INSERT INTO `rapport` (`id`, `id_etudiant`, `titre`, `nom_pdf`, `date`) VALUES
(1, 2, 'rapport pfe', 'rapport', '2021-04-26');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `tel` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cin` varchar(10) NOT NULL,
  `login` varchar(30) NOT NULL,
  `pwd` varchar(30) NOT NULL,
  `approuver` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `tel`, `email`, `adresse`, `cin`, `login`, `pwd`, `approuver`, `type`) VALUES
(5, 'sam', 'sam', 9877665, 'sam@gmail.com', 'sam', '0987777', 'sam', 'sam', 0, 1),
(2, 'sam', 'sam', 98776666, 'sam@gmail.com', 'sam afi', '09876543', 'sam', 'sam', 1, 1),
(4, 'test', 'test', 888888, 'test@gmail.com', 'test', '09876543', 'test', 'test', 0, 1),
(6, 'sam', 'sam', 9877665, 'sam@gmail.com', 'sam', '0987777', 'sam', 'sam', 0, 1),
(7, 'sam', 'sam', 9877665, 'sam@gmail.com', 'sam', '0987777', 'sam', 'sam', 0, 1),
(8, 'sam', 'sam', 9877665, 'sam@gmail.com', 'sam', '0987777', 'sam', 'sam', 0, 1),
(9, 'sam', 'sam', 9877665, 'sam@gmail.com', 'sam', '0987777', 'sam', 'sam', 0, 1),
(10, 'salemm', 'salemm', 98798798, 'salemm@salemm.salemm', 'salemm', '89987689', 'salemm', 'salemm', 0, 1),
(11, 'salemm', 'salemm', 98798798, 'salemm@salemm.salemm', 'salemm', '89987689', 'salemm', 'salemm', 0, 1),
(12, 'salemm', 'salemm', 98798798, 'salemm@salemm.salemm', 'salemm', '89987689', 'salemm', 'salemm', 0, 1),
(13, 'salemm', 'salemm', 98798798, 'salemm@salemm.salemm', 'salemm', '89987689', 'salemm', 'salemm', 0, 1),
(14, 'salemm', 'salemm', 98798798, 'salemm@salemm.salemm', 'salemm', '89987689', 'salemm', 'salemm', 0, 1),
(16, 'nawress', 'afi', 78968976, 'nawress@nawress.nawress', 'nawress', '98767655', 'nawress', 'nawress', 1, 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
