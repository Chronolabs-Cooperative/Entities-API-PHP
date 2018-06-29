
CREATE TABLE `whois_ipv6` (
  `id` mediumint(255) unsigned NOT NULL AUTO_INCREMENT,
  `state` enum('current','historical') NOT NULL DEFAULT 'current',
  `mode` enum('realm','netbios') NOT NULL DEFAULT 'realm',
  `mode-id` mediumint(20) unsigned NOT NULL DEFAULT '0',
  `ipv6-id` varchar(32) NOT NULL DEFAULT '',
  `whois-id` varchar(32) NOT NULL DEFAULT '',
  `created` int(13) unsigned NOT NULL DEFAULT '0',
  `history` int(13) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `whois_ipv6`
--

LOCK TABLES `whois_ipv6` WRITE;
/*!40000 ALTER TABLE `whois_ipv6` DISABLE KEYS */;
/*!40000 ALTER TABLE `whois_ipv6` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-02-10  4:37:03
