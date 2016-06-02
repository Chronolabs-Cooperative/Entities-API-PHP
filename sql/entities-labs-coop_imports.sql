CREATE DATABASE  IF NOT EXISTS `entities-labs-coop` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `entities-labs-coop`;
-- MySQL dump 10.13  Distrib 5.6.27, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: entities-labs-coop
-- ------------------------------------------------------
-- Server version	5.6.27-0ubuntu1

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

--
-- Table structure for table `imports`
--

DROP TABLE IF EXISTS `imports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imports` (
  `import-id` varchar(32) NOT NULL DEFAULT '',
  `peer-id` varchar(32) NOT NULL DEFAULT '',
  `uploader-email-id` varchar(32) NOT NULL DEFAULT '',
  `maps-id` varchar(32) NOT NULL DEFAULT '',
  `country-ids` tinytext NOT NULL,
  `ip-id` varchar(32) NOT NULL DEFAULT '',
  `referee` varchar(44) NOT NULL DEFAULT '',
  `subject` varchar(200) NOT NULL DEFAULT '',
  `filename` varchar(200) NOT NULL DEFAULT '',
  `path` varchar(200) NOT NULL DEFAULT '',
  `bytes` int(18) NOT NULL DEFAULT '0',
  `columns` int(8) NOT NULL DEFAULT '0',
  `records` int(12) NOT NULL DEFAULT '0',
  `duplicates` int(12) NOT NULL DEFAULT '0',
  `inactivity` int(12) NOT NULL DEFAULT '0',
  `verified` int(12) NOT NULL DEFAULT '0',
  `maintenances` int(12) NOT NULL DEFAULT '0',
  `expiries` int(12) NOT NULL DEFAULT '0',
  `uploaded` int(12) NOT NULL DEFAULT '0',
  `fields-seperated` varchar(6) NOT NULL DEFAULT ',',
  `fields-strings` varchar(6) NOT NULL DEFAULT '"',
  `fields-escapes` varchar(6) NOT NULL DEFAULT '\\',
  `fields-eol` varchar(6) NOT NULL DEFAULT '\n',
  PRIMARY KEY (`import-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-30 18:15:05
