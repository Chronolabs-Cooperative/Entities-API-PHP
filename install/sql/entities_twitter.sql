CREATE TABLE `entities_twitter` (
  `tid` mediumint(32) unsigned NOT NULL AUTO_INCREMENT,
  `entity-id` varchar(32) NOT NULL DEFAULT '',
  `access_token` varchar(128) NOT NULL DEFAULT '',
  `token_type` varchar(128) NOT NULL DEFAULT '',
  `expires_in` int(12) unsigned NOT NULL DEFAULT '0',
  `refresh_token` varchar(128) NOT NULL DEFAULT '',
  `scope` tinytext,
  `username` varchar(128) NOT NULL DEFAULT '',
  `userid` varchar(128) NOT NULL DEFAULT '',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(12) unsigned NOT NULL DEFAULT '0'
  `refresh` int(12) unsigned NOT NULL DEFAULT '0'
  PRIMARY KEY (`tid`),
  KEY `tidentityidusername` (`tid`,`entity-id`,`username`),
  KEY `search` (`refresh`,`created`,`last_login`,`expires_in`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
