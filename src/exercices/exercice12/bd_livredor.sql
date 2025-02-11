-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 04 Janvier 2018 à 22:36
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `bd_livredor`
--
CREATE DATABASE IF NOT EXISTS `bd_livredor` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `bd_livredor`;

-- --------------------------------------------------------

--
-- Structure de la table `t_message`
--

CREATE TABLE IF NOT EXISTS `t_message` (
  `pk_message` int(11) NOT NULL AUTO_INCREMENT,
  `auteur` varchar(25) COLLATE utf8_bin NOT NULL,
  `message` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`pk_message`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Contenu de la table `t_message`
--

INSERT INTO `t_message` (`pk_message`, `auteur`, `message`) VALUES
(1, 'Louis', 'Super site, bravo! Quel talent!'),
(2, 'Marc', 'Jamais vu un site aussi bien conçu');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
