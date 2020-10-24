-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 25 Avril 2020 à 20:56
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `Gacha_BDD`
--
CREATE DATABASE IF NOT EXISTS `Gacha_BDD` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Gacha_BDD`;

-- --------------------------------------------------------

--
-- Structure de la table `Cartes_Personnages`
--

CREATE TABLE IF NOT EXISTS `Cartes_Personnages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOM` varchar(50) NOT NULL,
  `PVM` smallint(3)  NOT NULL,
  `ATK` smallint(3)  NOT NULL,
  `DEF` smallint(3)  NOT NULL,
  `ELMT` varchar(50) NOT NULL,
  `STARS` smallint(3)  NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Joueurs`
--


CREATE TABLE IF NOT EXISTS `Joueurs` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `mdp` varchar(255)  NOT NULL,
  `argent` int(25)  NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Cartes_des_Joueurs`
--


CREATE TABLE IF NOT EXISTS `Cartes_des_Joueurs` (
  `idJoueur` int(11) unsigned NOT NULL,
  `Nom_P` varchar(50) NOT NULL,
  `PVM_P` smallint(3)  NOT NULL,
  `ATK_P` smallint(3)  NOT NULL,
  `DEF_P` smallint(3)  NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
