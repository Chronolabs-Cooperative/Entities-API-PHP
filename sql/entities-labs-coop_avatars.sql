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
-- Table structure for table `avatars`
--

DROP TABLE IF EXISTS `avatars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avatars` (
  `avatar-id` varchar(32) NOT NULL DEFAULT '',
  `source` enum('gravatar','upload','unknown') NOT NULL DEFAULT 'unknown',
  `instances` int(18) NOT NULL DEFAULT '0',
  `original-path` varchar(150) DEFAULT '',
  `original-image` varchar(150) DEFAULT '',
  `original-width` int(5) DEFAULT '0',
  `original-height` int(5) DEFAULT '0',
  `original-format` enum('jpg','gif','png') DEFAULT 'jpg',
  `small-path` varchar(150) DEFAULT '',
  `small-image` varchar(150) DEFAULT '',
  `small-width` int(5) DEFAULT '0',
  `small-height` int(5) DEFAULT '0',
  `small-format` enum('jpg','gif','png') DEFAULT 'jpg',
  `medium-path` varchar(150) DEFAULT '',
  `medium-image` varchar(150) DEFAULT '',
  `medium-width` int(5) DEFAULT '0',
  `medium-height` int(5) DEFAULT '0',
  `medium-format` enum('jpg','gif','png') DEFAULT 'jpg',
  `large-path` varchar(150) DEFAULT '',
  `large-image` varchar(150) DEFAULT '',
  `large-width` int(5) DEFAULT '0',
  `large-height` int(5) DEFAULT '0',
  `large-format` enum('jpg','gif','png') DEFAULT 'jpg',
  `created` int(12) DEFAULT '0',
  `edited` int(12) DEFAULT '0',
  `offlined` int(12) DEFAULT '0',
  PRIMARY KEY (`avatar-id`)
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

-- Dump completed on 2015-11-30 18:15:07
