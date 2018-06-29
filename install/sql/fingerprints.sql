
CREATE TABLE `" . $GLOBALS['APIDB']->prefix('fingerprints') . "` (
  `fingerprint-id` int(30) NOT NULL AUTO_INCREMENT,
  `entity-id` varchar(32) NOT NULL DEFAULT '',
  `import-id` varchar(32) NOT NULL DEFAULT '',
  `peer-id` varchar(32) NOT NULL DEFAULT '',
  `fingerprint` varchar(32) NOT NULL DEFAULT '',
  `type` enum('addresses','avatars','categories','emails','entities','imports','networking','phones','strings','unknown') NOT NULL DEFAULT 'unknown',
  `created` int(12) NOT NULL DEFAULT '0',
  `updated` int(12) NOT NULL DEFAULT '0',
  `offlined` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fingerprint-id`)
) ENGINE=InnoDB AUTO_INCREMENT=6941 DEFAULT CHARSET=utf8;
