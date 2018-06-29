
CREATE TABLE `" . $GLOBALS['APIDB']->prefix('addresses') . "` (
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
  `" . $GLOBALS['APIDB']->prefix('entities') . "` int(8) DEFAULT '0',
  `created` int(12) DEFAULT '0',
  `updated` int(12) DEFAULT '0',
  `offlined` int(12) DEFAULT '0',
  PRIMARY KEY (`address-id`),
  KEY `SEARCH` (`type`,`street-one`,`street-two`,`province`,`city`,`state`,`country`,`postcode`,`country-id`,`place-id`,`longitude`,`latitude`,`serial-postal`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
