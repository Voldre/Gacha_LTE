-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Dim 04 Juillet 2021 à 21:21
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `gacha_bdd`
--

-- --------------------------------------------------------

--
-- Structure de la table `cartes_des_joueurs`
--

CREATE TABLE IF NOT EXISTS `cartes_des_joueurs` (
  `idJoueur` int(11) unsigned NOT NULL,
  `Nom_P` varchar(50) NOT NULL,
  `PVM_P` smallint(3) NOT NULL,
  `ATK_P` smallint(3) NOT NULL,
  `DEF_P` smallint(3) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `cartes_des_joueurs`
--

INSERT INTO `cartes_des_joueurs` (`idJoueur`, `Nom_P`, `PVM_P`, `ATK_P`, `DEF_P`) VALUES
(2, 'Velrod', 14, 11, 11),
(2, 'Ketsu', 18, 10, 6),
(2, 'Jessica', 14, 10, 7),
(2, 'Eroste', 14, 12, 5),
(2, 'Zerito', 17, 9, 6),
(2, 'Zerito', 17, 9, 6),
(2, 'Silarius', 15, 11, 6),
(2, 'Kzina', 15, 11, 7),
(2, 'Hearth', 17, 11, 7),
(3, 'Chrome', 18, 11, 9),
(3, 'Velrod_Adulte', 18, 14, 11),
(3, 'Ether', 18, 7, 11),
(3, 'Aria', 17, 9, 5),
(3, 'Kzina', 18, 9, 7),
(3, 'Eroste', 15, 10, 5),
(3, 'Zerito', 16, 9, 7),
(3, 'Darok', 16, 9, 9),
(3, 'Hearth', 15, 10, 7),
(3, 'Raykas', 18, 11, 13),
(3, 'Mentor', 14, 9, 10),
(3, 'Mentor', 14, 9, 10),
(3, 'Hearth', 15, 11, 6),
(8, 'Chrome', 19, 13, 9),
(8, 'Baga', 15, 13, 5),
(8, 'Kimmy', 15, 6, 11),
(8, 'Darok', 14, 9, 7),
(8, 'Zavell', 15, 9, 7),
(8, 'NavarÃ¨s', 18, 17, 14),
(5, 'Vayl', 17, 12, 8),
(5, 'Aria', 17, 9, 7),
(5, 'Zerito', 16, 10, 7),
(5, 'Felmos', 16, 12, 10),
(5, 'Darok', 18, 10, 8),
(5, 'Silarius', 16, 10, 6),
(5, 'Chrome', 18, 13, 11),
(5, 'Kzina', 18, 9, 8),
(5, 'Velrod_Adulte', 22, 14, 13),
(5, 'Aria', 17, 11, 7),
(5, 'Summerill', 20, 12, 13),
(5, 'Zelcia_Adulte', 19, 12, 13),
(5, 'Prunella', 19, 13, 9),
(5, 'Felmos', 19, 13, 10),
(5, 'Dark_Silarius', 20, 14, 8),
(5, 'Velrod', 19, 13, 10),
(5, 'Zavell_Jeune', 16, 11, 11),
(5, 'Raykas', 20, 9, 12),
(5, 'Chrome', 18, 12, 10),
(5, 'Brahms', 19, 11, 12),
(5, 'Xoress', 20, 14, 10),
(5, 'Nerio', 19, 12, 10);

-- --------------------------------------------------------

--
-- Structure de la table `cartes_personnages`
--

CREATE TABLE IF NOT EXISTS `cartes_personnages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(50) NOT NULL,
  `PVM` smallint(3) NOT NULL,
  `ATK` smallint(3) NOT NULL,
  `DEF` smallint(3) NOT NULL,
  `ELMT` varchar(50) NOT NULL,
  `STARS` smallint(3) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Contenu de la table `cartes_personnages`
--

INSERT INTO `cartes_personnages` (`ID`, `NOM`, `PVM`, `ATK`, `DEF`, `ELMT`, `STARS`) VALUES
(1, 'Aria', 16, 10, 6, 'terre', 3),
(2, 'Ether', 16, 7, 10, 'terre', 3),
(3, 'Hearth', 16, 10, 7, 'glace', 3),
(4, 'Kuro', 16, 7, 10, 'foudre', 3),
(5, 'Kzina', 16, 10, 7, 'tenebres', 3),
(6, 'Silarius', 16, 11, 6, 'tenebres', 3),
(41, 'Yune', 16, 10, 8, 'lumiere', 3),
(8, 'Zelcia', 16, 11, 6, 'feu', 3),
(9, 'Zerito', 16, 10, 7, 'vent', 3),
(10, 'Eroste', 16, 11, 6, 'glace', 3),
(11, 'Jessica', 16, 10, 6, 'vent', 3),
(12, 'Kendrick', 16, 9, 9, 'physique', 3),
(13, 'Ketsu', 16, 9, 7, 'glace', 3),
(14, 'Kimmy', 16, 7, 10, 'physique', 3),
(15, 'Mentor', 16, 9, 9, 'lumiere', 3),
(16, 'Velrod', 18, 12, 10, 'foudre', 4),
(17, 'Nerio', 18, 11, 10, 'tenebres', 4),
(18, 'Brahms', 18, 10, 12, 'lumiere', 4),
(19, 'Chrome', 18, 12, 10, 'lumiere', 4),
(20, 'Dark_Silarius', 18, 14, 8, 'tenebres', 4),
(21, 'Felmos', 18, 13, 9, 'feu', 4),
(25, 'Zavell', 16, 10, 8, 'vent', 3),
(26, 'Raykas', 18, 10, 12, 'glace', 4),
(27, 'Summerill', 18, 12, 12, 'physique', 4),
(28, 'Varox', 20, 14, 12, 'physique', 5),
(29, 'Baga', 16, 12, 6, 'terre', 3),
(32, 'Zavell_Jeune', 18, 12, 10, 'vent', 4),
(33, 'Xoress', 20, 14, 10, 'terre', 5),
(34, 'Prunella', 18, 14, 8, 'physique', 4),
(35, 'NavarÃ¨s', 20, 16, 14, 'tenebres', 5),
(36, 'Zelcia_Adulte', 20, 12, 14, 'feu', 5),
(37, 'Darok', 16, 10, 8, 'foudre', 3),
(38, 'Maishi', 16, 6, 10, 'terre', 3),
(39, 'Velrod_Adulte', 20, 14, 12, 'foudre', 5),
(40, 'Vayl', 18, 12, 8, 'tenebres', 4);

-- --------------------------------------------------------

--
-- Structure de la table `joueurs`
--

CREATE TABLE IF NOT EXISTS `joueurs` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `argent` int(25) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `joueurs`
--

INSERT INTO `joueurs` (`ID`, `login`, `mdp`, `argent`) VALUES
(1, 'test5', '$2y$10$2xZJ6jBfWAC3hCuYgECH6.1.FeVek6.3MWtzJTQSCj5VKifovCA8y', -100),
(2, 'test6', '$2y$10$qGCMauKlZUTBuCloAd09IOzGvepyrhXMvqcpT9KodHXNUlYPR4BWa', 3),
(3, 'voldre', '$2y$10$/PCsd6wvCzyniVdH72VfvOj5Uz2IPz4zEHGYU0/hszkXmFDIJABt6', 3),
(4, 'zelcia', '$2y$10$PlpbklV280R/iF5qXWfDbexW97yUZL7EZw7zNbInA5ffrFcfAVmbi', -100),
(5, 'zelcia1', '$2y$10$8HKI3FNBbPz0wI/Pfo.n3uVoMkiWrXt1EpkyIeKtenjMhSEF3buIO', 10),
(6, 'ahahah', '$2y$10$F7rxjCWDdujX.rSw3ZUNt.cnCXP1zZs/rte3pqBQctUsqnvu0m8AS', -100),
(7, 'blabla', '$2y$10$iI2RYGJS3hLgCMCL0YAQuemqCTqlN1SGwpRyzVytc958ytfM5loWW', -100),
(8, 'test9', '$2y$10$XLQLVsfqLN.efYlgwI9R0urjXaLOx0XJlKe1chs81b4F6d7vXHnfW', 2),
(9, 'luceau', '$2y$10$1Gee/TKs7ptjatsDoWG7muH0Q.0dLvyZJcmmQbe0k.J7RC6MfIQu.', -100);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
