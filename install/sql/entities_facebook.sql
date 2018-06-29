CREATE TABLE `entities_facebook` (
  `entity-id` varchar(32) NOT NULL DEFAULT '',
  `accessToken` varchar(128) NOT NULL DEFAULT '',
  `expiresIn` int(12) unsigned NOT NULL DEFAULT '0',
  `signedRequest` tinytext,
  `userID` varchar(128) NOT NULL DEFAULT '',
  `last_login` int(10) unsigned NOT NULL DEFAULT '0',
  `created` int(12) unsigned NOT NULL DEFAULT '0'
  PRIMARY KEY (`entity-id`),
  KEY `userIDentityid` (`userID`,`entity-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
