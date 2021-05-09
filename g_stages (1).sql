-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 09 mai 2021 à 13:56
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `actualite`
--

INSERT INTO `actualite` (`id`, `titre`, `description`, `date`) VALUES
(5, 'test', 'font-size font-size', '2021-05-03');

-- --------------------------------------------------------

--
-- Structure de la table `demande_nomination`
--

DROP TABLE IF EXISTS `demande_nomination`;
CREATE TABLE IF NOT EXISTS `demande_nomination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_etudiant` int(11) NOT NULL,
  `nom_pdf` varchar(250) NOT NULL,
  `date_demande` varchar(15) NOT NULL,
  `date_reponse` varchar(15) NOT NULL,
  `etat` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `demande_nomination_ibfk_1` (`id_etudiant`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `demande_nomination`
--

INSERT INTO `demande_nomination` (`id`, `id_etudiant`, `nom_pdf`, `date_demande`, `date_reponse`, `etat`) VALUES
(1, 27, '179.pdf', '2021-05-09', '2021-05-12', 1),
(2, 27, '48.pdf', '2021-05-10', '2021-05-09', 1);

-- --------------------------------------------------------

--
-- Structure de la table `demande_stage`
--

DROP TABLE IF EXISTS `demande_stage`;
CREATE TABLE IF NOT EXISTS `demande_stage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_etudiant` int(11) NOT NULL,
  `nom_pdf` varchar(250) NOT NULL,
  `date_demande` varchar(15) NOT NULL,
  `date_reponse` varchar(15) NOT NULL,
  `etat` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `demande_stage_ibfk_1` (`id_etudiant`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `demande_stage`
--

INSERT INTO `demande_stage` (`id`, `id_etudiant`, `nom_pdf`, `date_demande`, `date_reponse`, `etat`) VALUES
(2, 18, '06.pdf', '2021-05-08', '2021-05-07', 1),
(3, 27, 'Agence Technique des Transports Terrestres.pdf', '2021-05-07', '2021-05-07', 1),
(4, 27, 'RNE3.pdf', '2021-05-21', '2021-05-28', 1),
(5, 27, '', '2021-05-11', '', 0);

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `enseignant_soutenance`
--

INSERT INTO `enseignant_soutenance` (`id`, `id_enseignant`, `date`, `temps`) VALUES
(2, 16, '2021-05-09', '12:09:00');

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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `id_etudiant`, `niveau`) VALUES
(9, 27, 5);

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `etudiant_soutenance`
--

INSERT INTO `etudiant_soutenance` (`id`, `id_etudiant`, `date`, `temps`) VALUES
(1, 27, '2021-05-09', '15:00:00'),
(2, 28, '2021-05-09', '19:54:00');

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
  `public` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rapport_ibfk_1` (`id_etudiant`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `rapport`
--

INSERT INTO `rapport` (`id`, `id_etudiant`, `titre`, `nom_pdf`, `date`, `public`) VALUES
(1, 2, 'rapport pfe', 'rapport', '2021-04-26', 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `tel` int(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cin` varchar(10) NOT NULL,
  `login` varchar(30) NOT NULL,
  `pwd` varchar(30) NOT NULL,
  `approuver` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `tel`, `email`, `adresse`, `cin`, `login`, `pwd`, `approuver`, `type`) VALUES
(27, 'sam', 'sam', 9877665, 'sam@gmail.com', 'sam', '0987777', 'sam', 'sam', 1, 1),
(18, 'afi', 'afi', 6587587, 'afi@afi.afi', 'afi', '8768768', 'afi', 'afi', 1, 3),
(16, 'nawress', 'afi', 78968976, 'nawress@nawress.nawress', 'nawress', '98767655', 'nawress', 'nawress', 1, 2),
(28, 'bti', 'bti', 7587876, 'bti@bti.bti', 'bti', '8977879', 'bti', 'bti', 1, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
