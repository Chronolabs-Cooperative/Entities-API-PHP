
CREATE TABLE `realms` (
  `realm-id` varchar(32) NOT NULL,
  `realm` varchar(250) NOT NULL DEFAULT '',
  `ipv4-id` varchar(32) NOT NULL DEFAULT '',
  `ipv6-id` varchar(32) NOT NULL DEFAULT '',
  `realm-whois-id` varchar(32) NOT NULL DEFAULT '',
  `ipv4-whois-id` varchar(32) NOT NULL DEFAULT '',
  `ipv6-whois-id` varchar(32) NOT NULL DEFAULT '',
  `hits` int(8) unsigned NOT NULL DEFAULT '0',
  `created` int(13) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`realm-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
