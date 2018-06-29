
CREATE TABLE `whois_realms` (
  `id` mediumint(255) unsigned NOT NULL AUTO_INCREMENT,
  `state` enum('current','historical') NOT NULL DEFAULT 'current',
  `realm-id` varchar(32) NOT NULL DEFAULT '',
  `ipv4-id` varchar(32) NOT NULL DEFAULT '',
  `ipv6-id` varchar(32) NOT NULL DEFAULT '',
  `whois-id` varchar(32) NOT NULL DEFAULT '',
  `created` int(13) unsigned NOT NULL DEFAULT '0',
  `history` int(13) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
