# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.7.14)
# Database: s266004
# Generation Time: 2019-06-18 09:39:52 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table PRENOTAZIONI
# ------------------------------------------------------------

DROP TABLE IF EXISTS `PRENOTAZIONI`;

CREATE TABLE `PRENOTAZIONI` (
  `id` varchar(5) NOT NULL DEFAULT '',
  `stato` varchar(15) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `PRENOTAZIONI` DISABLE KEYS */;

INSERT INTO `PRENOTAZIONI` (`id`, `stato`, `username`)
VALUES
	('A4','booked','u1@p.it'),
	('B2','busy','u2@p.it'),
	('B3','busy','u2@p.it'),
	('B4','busy','u2@p.it'),
	('D4','booked','u1@p.it'),
	('F4','booked','u2@p.it');

/*!40000 ALTER TABLE `PRENOTAZIONI` ENABLE KEYS */;


# Dump of table UTENTI
# ------------------------------------------------------------

DROP TABLE IF EXISTS `UTENTI`;

CREATE TABLE `UTENTI` (
  `username` varchar(50) NOT NULL DEFAULT '',
  `nome` varchar(30) NOT NULL DEFAULT '',
  `cognome` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40000 ALTER TABLE `UTENTI` DISABLE KEYS */;

INSERT INTO `UTENTI` (`username`, `nome`, `cognome`, `password`)
VALUES
	('u1@p.it','u1','u1','ec6ef230f1828039ee794566b9c58adc'),
	('u2@p.it','u2','u2','1d665b9b1467944c128a5575119d1cfd');

/*!40000 ALTER TABLE `UTENTI` ENABLE KEYS */;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
