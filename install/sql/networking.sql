
CREATE TABLE `" . $GLOBALS['APIDB']->prefix('networking') . "` (
  `ip-id` varchar(32) NOT NULL DEFAULT '',
  `type` enum('realm','ipv4','ipv6') NOT NULL DEFAULT 'realm',
  `realm-id` varchar(32) NOT NULL DEFAULT '',
  `ipv4-id` varchar(32) NOT NULL DEFAULT '',
  `ipv6-id` varchar(32) NOT NULL DEFAULT '',
  `netbios-id` varchar(32) NOT NULL DEFAULT '',
  `whois-id`  varchar(32) NOT NULL DEFAULT '',
  `domain` varchar(128) NOT NULL DEFAULT '',
  `country` varchar(3) NOT NULL DEFAULT '',
  `region` varchar(128) NOT NULL DEFAULT '',
  `city` varchar(128) NOT NULL DEFAULT '',
  `postcode` varchar(15) NOT NULL DEFAULT '',
  `timezone` varchar(10) NOT NULL DEFAULT '',
  `longitude` float(12,8) NOT NULL DEFAULT '0.00000000',
  `latitude` float(12,8) NOT NULL DEFAULT '0.00000000',
  `created` int(13) NOT NULL DEFAULT '0',
  `last` int(13) NOT NULL DEFAULT '0',
  `data` mediumtext,
  PRIMARY KEY (`ip-id`,`type`,`ipaddy`(15)),
  KEY `SEARCH` (`type`,`realm-id`(7),`ipv4-id`(7),`ipv6-id`(7),`netbios-id`(7),`domain`(12),`country`(2),`city`(12),`region`(12),`postcode`(6),`longitude`,`latitude`,`created`,`last`,`timezone`(6))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
