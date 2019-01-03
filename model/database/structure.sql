-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.21-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for mitglieder
CREATE DATABASE IF NOT EXISTS `mitglieder` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `mitglieder`;

-- Dumping structure for table mitglieder.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `passwort` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table mitglieder.artikel
CREATE TABLE IF NOT EXISTS `artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nummer` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `kg_price` decimal(5,2) NOT NULL,
  `stueck_gewicht` int(11) DEFAULT NULL,
  `gewicht_1` int(11) DEFAULT NULL,
  `gewicht_2` int(11) DEFAULT NULL,
  `gewicht_3` int(11) DEFAULT NULL,
  `stueckzahl_1` int(11) DEFAULT NULL,
  `stueckzahl_2` int(11) DEFAULT NULL,
  `stueckzahl_3` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.
-- Dumping structure for table mitglieder.bestellung
CREATE TABLE IF NOT EXISTS `bestellung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kunde_id` int(11) NOT NULL,
  `datum` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `ziel_datum` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kunde_id` (`kunde_id`),
  CONSTRAINT `bestellung_ibfk_1` FOREIGN KEY (`kunde_id`) REFERENCES `kunde` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table mitglieder.bestell_artikel
CREATE TABLE IF NOT EXISTS `bestell_artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artikel_id` int(11) NOT NULL,
  `verfuegbar` tinyint(1) NOT NULL DEFAULT '0',
  `gewicht` decimal(6,2) DEFAULT NULL,
  `stueckbestellung` tinyint(1) NOT NULL DEFAULT '0',
  `datum` date NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `artikel_id` (`artikel_id`),
  CONSTRAINT `bestell_artikel_ibfk_1` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1649 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table mitglieder.bestell_position
CREATE TABLE IF NOT EXISTS `bestell_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bestellung_id` int(11) NOT NULL,
  `bestell_artikel_id` int(11) NOT NULL,
  `anzahl_paeckchen` int(11) DEFAULT NULL,
  `gewicht` int(11) DEFAULT NULL,
  `kommentar` varchar(200) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bestellung_id` (`bestellung_id`),
  KEY `bestell_position_ibfk_2` (`bestell_artikel_id`),
  CONSTRAINT `bestell_position_ibfk_1` FOREIGN KEY (`bestellung_id`) REFERENCES `bestellung` (`id`),
  CONSTRAINT `bestell_position_ibfk_2` FOREIGN KEY (`bestell_artikel_id`) REFERENCES `bestell_artikel` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table mitglieder.feedback
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kunde_id` int(11) NOT NULL,
  `feedback` text,
  `zeit` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kunde_id` (`kunde_id`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`kunde_id`) REFERENCES `kunde` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table mitglieder.kunde
CREATE TABLE IF NOT EXISTS `kunde` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `vorname` varchar(50) NOT NULL,
  `tel` varchar(25) DEFAULT NULL,
  `natel` varchar(25) DEFAULT NULL,
  `adresse` varchar(60) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `personen` int(11) DEFAULT NULL,
  `siedfleisch` varchar(10) DEFAULT NULL,
  `besonderes` text,
  `ort_id` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.
-- Dumping structure for table mitglieder.ort
CREATE TABLE IF NOT EXISTS `ort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ort` varchar(50) NOT NULL,
  `PLZ` varchar(10) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table mitglieder.position
CREATE TABLE IF NOT EXISTS `position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gewicht` varchar(20) NOT NULL,
  `preis` decimal(5,2) NOT NULL,
  `rechnung_id` int(11) NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rechnung_id` (`rechnung_id`),
  KEY `artikel_id` (`artikel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1152 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table mitglieder.rechnung
CREATE TABLE IF NOT EXISTS `rechnung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kunde_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `bezahlt` tinyint(4) NOT NULL DEFAULT '0',
  `kommentar` varchar(200) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`kunde_id`),
  CONSTRAINT `rechnung_ibfk_1` FOREIGN KEY (`kunde_id`) REFERENCES `kunde` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=225 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.
-- Dumping structure for table mitglieder.termin
CREATE TABLE IF NOT EXISTS `termin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datum` date NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
