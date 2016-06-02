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
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `address-id` varchar(32) NOT NULL DEFAULT '',
  `type` enum('Business','Home','Other','Unknown') NOT NULL DEFAULT 'Unknown',
  `street-one` varchar(100) NOT NULL DEFAULT '',
  `street-two` varchar(100) DEFAULT '',
  `street-three` varchar(100) DEFAULT '',
  `province` varchar(100) DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) DEFAULT '',
  `country` varchar(100) DEFAULT '',
  `postcode` varchar(20) DEFAULT '',
  `country-id` varchar(32) DEFAULT '',
  `place-id` varchar(32) DEFAULT '',
  `longitude` float(18,12) DEFAULT '0.000000000000',
  `latitude` float(18,12) DEFAULT '0.000000000000',
  `serial-postal` varchar(90) DEFAULT '',
  `entities` int(8) DEFAULT '0',
  `created` int(12) DEFAULT '0',
  `updated` int(12) DEFAULT '0',
  `offlined` int(12) DEFAULT '0',
  PRIMARY KEY (`address-id`),
  KEY `SEARCH` (`type`,`street-one`,`street-two`,`province`,`city`,`state`,`country`,`postcode`,`country-id`,`place-id`,`longitude`,`latitude`,`serial-postal`) USING BTREE
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
