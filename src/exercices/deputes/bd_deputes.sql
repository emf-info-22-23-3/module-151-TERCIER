-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  sam. 18 jan. 2020 à 21:39
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bd_deputes`
--
CREATE DATABASE IF NOT EXISTS `bd_deputes` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `bd_deputes`;

-- --------------------------------------------------------

--
-- Structure de la table `t_depute`
--

DROP TABLE IF EXISTS `t_depute`;
CREATE TABLE IF NOT EXISTS `t_depute` (
  `PK_Depute` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(45) COLLATE utf8_bin NOT NULL,
  `Langue` varchar(5) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`PK_Depute`)
) ENGINE=MyISAM AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `t_depute`
--

INSERT INTO `t_depute` (`PK_Depute`, `Nom`, `Langue`) VALUES
(1, 'Eliane Aebischer', 'D'),
(2, 'Susanne Aebischer', 'D'),
(3, 'Antoinette Badoud', 'F'),
(4, 'Sylvia Baiutti', 'F'),
(5, 'Mirjam Ballmer', 'D'),
(6, 'Bernard Bapst', 'F'),
(7, 'Solange Berset', 'F'),
(8, 'Jean Bertschi', 'F'),
(9, 'Muriel Besson Gumy', 'F'),
(10, 'Simon Bischof', 'F'),
(11, 'David Bonny', 'F'),
(12, 'Sylvie Bonvin-Sansonnens', 'F'),
(13, 'Bruno Boschung', 'D'),
(14, 'Gabrielle Bourguet', 'F'),
(15, 'Claude Brodard', 'F'),
(16, 'Charles Brönnimann', 'F'),
(17, 'Adrian Brügger', 'D'),
(18, 'Daniel Bürdel', 'D'),
(19, 'Nicolas Bürgisser', 'D'),
(20, 'Dominique Butty', 'F'),
(21, 'Jean-Daniel Chardonnens', 'F'),
(22, 'Claude Chassot', 'F'),
(23, 'Michel Chevalley', 'F'),
(24, 'Romain Collaud', 'F'),
(25, 'Eric Collomb', 'F'),
(26, 'Violaine Cotting-Chardonnens', 'F'),
(27, 'Hubert Dafflon', 'F'),
(28, 'Antoinette de Weck', 'F'),
(29, 'Pierre Décrind', 'F'),
(30, 'Francine Defferrard', 'F'),
(31, 'Philippe Demierre', 'F'),
(32, 'Laurent Dietrich', 'F'),
(33, 'Sébastien Dorthe', 'F'),
(34, 'Jean-Pierre Doutaz', 'F'),
(35, 'Christian Ducotterd', 'F'),
(36, 'Gaétan Emonet', 'F'),
(37, 'Martine Fagherazzi', 'F'),
(38, 'Olivier Flechtner', 'D'),
(39, 'Bertrand Gaillard', 'F'),
(40, 'Nicolas Galley', 'F'),
(41, 'Marc-Antoine Gamba', 'F'),
(42, 'Xavier Ganioz', 'F'),
(43, 'Giovanna Garghentini Python', 'F'),
(44, 'François Genoud (Braillard)', 'F'),
(45, 'Paola Ghielmini Krayenbühl', 'F'),
(46, 'Benoît Glasson', 'F'),
(47, 'Fritz Glauser', 'F'),
(48, 'Nadine Gobet', 'F'),
(49, 'Pierre-André Grandgirard', 'F'),
(50, 'Bernadette Hänni-Fischer', 'D'),
(51, 'Madeleine Hayoz', 'F'),
(52, 'Rudolf Herren-Rutschi', 'D'),
(53, 'Paul Herren-Schick', 'D'),
(54, 'Yvan Hunziker', 'F'),
(55, 'Christine Jakob', 'D'),
(56, 'Armand Jaquier', 'F'),
(57, 'Guy-Noël Jelk', 'F'),
(58, 'Ueli Johner-Etter', 'D'),
(59, 'Patrice Jordan', 'F'),
(60, 'Markus Julmy', 'D'),
(61, 'André Kaltenrieder', 'D'),
(62, 'Gabriel Kolly', 'F'),
(63, 'Nicolas Kolly', 'F'),
(64, 'Ursula Krattinger-Jutzet', 'D'),
(65, 'Grégoire Kubski', 'F'),
(66, 'Pascal Lauber', 'F'),
(67, 'Patrice Longchamp', 'F'),
(68, 'Bernadette Mäder-Brülhart', 'D'),
(69, 'Bruno Marmier', 'F'),
(70, 'Pierre Mauron', 'F'),
(71, 'Roland Mesot', 'F'),
(72, 'Anne Meyer Loetscher', 'F'),
(73, 'Jacques Morand', 'F'),
(74, 'Bertrand Morel', 'F'),
(75, 'Elias Moussa', 'F'),
(76, 'Chantal Müller', 'D'),
(77, 'Christa Mutter', 'D'),
(78, 'Nicolas Pasquier', 'F'),
(79, 'Cédric Péclard', 'F'),
(80, 'Stéphane Peiry', 'F'),
(81, 'Urs Perler', 'D'),
(82, 'Benoît Piller', 'F'),
(83, 'Chantal Pythoud-Gaillard', 'F'),
(84, 'Nicolas Repond', 'F'),
(85, 'Benoît Rey', 'F'),
(86, 'Rose-Marie Rodriguez', 'F'),
(87, 'Marie-France Roth Pasquier', 'F'),
(88, 'Nadia Savary-Moser', 'F'),
(89, 'Ruedi Schläfli', 'F'),
(90, 'Ralph Alexander Schmid', 'D'),
(91, 'Achim Schneuwly', 'D'),
(92, 'André Schneuwly', 'D'),
(93, 'Erika Schnyder', 'F'),
(94, 'André Schoenenweid', 'F'),
(95, 'Jean-Daniel Schumacher', 'F'),
(96, 'Roger Schuwey', 'D'),
(97, 'Esther Schwaller-Merkle', 'D'),
(98, 'Susanne Schwander', 'D'),
(99, 'Julia Senti', 'D'),
(100, 'Thierry Steiert', 'D'),
(101, 'Stéphane Sudan', 'F'),
(102, 'Katharina Thalmann-Bolz', 'D'),
(103, 'Rudolf Vonlanthen', 'D'),
(104, 'Andréa Wassmer', 'F'),
(105, 'Jean-Daniel Wicht', 'F'),
(106, 'Kirthana Wickramasingam', 'F'),
(107, 'Peter Wüthrich', 'F'),
(108, 'Michel Zadory', 'F'),
(109, 'Dominique Zamofing', 'F'),
(110, 'Markus Zosso', 'D');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
