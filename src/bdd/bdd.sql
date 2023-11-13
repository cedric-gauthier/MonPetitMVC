-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: clicommvc
-- ------------------------------------------------------
-- Server version	5.7.19

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

create database if not exists clicomMVC;
--
-- Table structure for table `client`
--
Use clicommvc;
DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titreCli` char(12) NOT NULL DEFAULT 'Monsieur',
  `nomCli` varchar(30) NOT NULL,
  `prenomCli` varchar(30) NOT NULL,
  `adresseRue1Cli` varchar(40) NOT NULL,
  `adresseRue2Cli` varchar(40) DEFAULT NULL,
  `cpCli` char(6) NOT NULL,
  `villeCli` varchar(30) NOT NULL,
  `telCli` char(14) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
INSERT INTO `client` VALUES (1,'Monsieur','Tienun','Jean','112, rue du Départ',NULL,'13000','Marseille','0404040404'),(2,'Madame','Terrature','Julie','Résidence Mermoz','1234 Boulevard des Aviateurs','14000','Caen','0202020202'),(3,'Madame','Cerf','Paulette','343 Avenue Henri Barbusse',NULL,'33000','Bordeaux','0550505050'),(4,'Mademoiselle','Morizet','Patricia','Hameau de Pau','23 Boulevard du Lycée','33000','Bordeaux','0250505052'),(5,'Monsieur','Nolapin','Jean','12 quai des Brumes',NULL,'83000','Toulon','0404505050'),(6,'Monsieur','Entete','Martel','Résidence du Faron','30 rue du téléphérique','83000','Toulon','0250505050'),(7,'Monsieur','Entete','Martel','12 Avenue de Lille',NULL,'59140','Dunkerque','0250905057'),(8,'Madame','DUMANS','Henriette','Corniche des Bolides','Villa Ferrari','49000','Angers','0250765357');
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commande`
--

DROP TABLE IF EXISTS `commande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idClient` int(11) NOT NULL,
  `dateCde` date NOT NULL,
  `noFacture` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_COMMANDE_PASSER_CLIENT` (`idClient`),
  CONSTRAINT `FK_COMMANDE_CLIENT` FOREIGN KEY (`idClient`) REFERENCES `client` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98769 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commande`
--

LOCK TABLES `commande` WRITE;
/*!40000 ALTER TABLE `commande` DISABLE KEYS */;
INSERT INTO `commande` VALUES (98762,1,'2014-09-07',123454),(98763,2,'2014-09-08',123455),(98764,4,'2014-09-10',123487),(98765,2,'2014-10-01',123475),(98766,4,'2014-10-01',NULL),(98767,5,'2014-10-01',123489),(98768,6,'2014-10-01',NULL);
/*!40000 ALTER TABLE `commande` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` char(20) NOT NULL,
  `motDePasse` varchar(255) NOT NULL,
  `dateCreation` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES (1,'Dupont','$2y$10$vO1JzBB01SF9LMN89z.Hj.uLR.hWiwnKQfSie.KN.sQmDfm0am/Zy','2019-02-27 11:13:57'),
(2,'Tanguy','$2y$10$Dxe7zZRN1uaG423WVYzarutB6A08clI7O1rBnZI5CkZSs549qEaOK','2019-02-27 11:13:57');
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-27 13:15:10
 