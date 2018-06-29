
CREATE TABLE `entities_networking` (
  `entity-id` varchar(32) NOT NULL DEFAULT '',
  `ip-id` varchar(32) NOT NULL DEFAULT '',
  `when` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`entity-id`,`ip-id`,`when`),
  KEY `fk_entities_networking_2_idx` (`ip-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
