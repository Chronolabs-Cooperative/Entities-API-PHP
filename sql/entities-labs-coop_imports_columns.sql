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
-- Table structure for table `imports_columns`
--

DROP TABLE IF EXISTS `imports_columns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imports_columns` (
  `column-id` int(24) NOT NULL AUTO_INCREMENT,
  `maps-id` varchar(32) NOT NULL DEFAULT '',
  `position` int(12) NOT NULL DEFAULT '0',
  `title` varchar(150) NOT NULL DEFAULT '',
  `field` enum('Category','Title','Full Name','First Name','Middle Name','Last Name','Suffix','Company','Department','Job Title','Instant Messaging One','Instant Messaging Two','Business Street One','Business Street Two','Business Street Three','Business Province','Business City','Business State','Business Country','Business Postcode','Business Longitude','Business Latitude','Business Serial Number Postage','Home Street One','Home Street Two','Home Street Three','Home Province','Home City','Home State','Home Country','Home Postcode','Home Longitude','Home Latitude','Home Serial Number Postage','Other Street One','Other Street Two','Other Street Three','Other Province','Other City','Other State','Other Country','Other Postcode','Other Longitude','Other Latitude','Other Serial Number Postage','Assistant Phone Number','Business Fax Number','Business Phone Number One','Business Phone Number Two','Callback Phone Number','Car Phone Number','Business Switch Phone Number','Home Fax Number','Home Phone Number One','Home Phone Number Two','ISDN Phone Number','Mobile Phone Number','Other Phone Number','Other Fax Number','Pager Phone Number','Primary Phone Number','Radio Phone Number','TTY/TDD Phone Number','Telex','Account','Anniversary','Assistants Name','Billing Information','Birthday','Children','Email One Address','Email One Display Name','Email Two Address','Email Two Display Name','Email Three Address','Email Three Display Name','Gender','Government ID Number','Hobby','Intials','Keywords','Languages','Country','Location Place','Milage','Notes','Office Country','Office Location Place','Registered Business Number','Profession','Refereed By','Spouse','Web Page Primary','Web Page Blog','Web Page Facebook','Web Page Twitter','Web Page Linked-in','Web Page Google+','Web Page Other','Skip (Unknown)') NOT NULL DEFAULT 'Skip (Unknown)' COMMENT 'ENUM(''Title'', ''Full Name'', ''First Name'', ''Middle Name'', ''Suffix'', ''Company'', ''Department'', ''Job Title'', ''Business Street One'', ''Business Street Two'', ''Business Street Three'', ''Business Province'', ''Business City'', ''Business State'', ''Business Country'', ''Business Postcode'', ''Business Longitude'', ''Business Latitude'', ''Business Serial Number Postage'',  ''Home Street One'', ''Home Street Two'', ''Home Street Three'', ''Home Province'', ''Home City'', ''Home State'', ''Home Country'', ''Home Postcode'', ''Home Longitude'', ''Home Latitude'', ''Home Serial Number Postage'',  ''Other Street One'', ''Other Street Two'', ''Other Street Three'', ''Other Province'', ''Other City'', ''Other State'', ''Other Country'', ''Other Postcode'', ''Other Longitude'', ''Other Latitude'', ''Other Serial Number Postage'', ''Assistant Phone Number'', ''Business Fax Number'', ''Business Phone Number One'', ''Business Phone Number Two'', ''Callback Phone Number'', ''Car Phone Number'', ''Business Switch Phone Number'', ''Home Fax Number'', ''Home Phone Number One'', ''Home Phone Number Two'', ''ISDN P',
  `type` enum('String','Integer','Unix Time Stamp','URL','Email','Phone Number','Date','Time','Date/time','Unknown') NOT NULL DEFAULT 'Unknown',
  `data` tinytext,
  PRIMARY KEY (`column-id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8;
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
