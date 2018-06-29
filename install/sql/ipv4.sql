
CREATE TABLE `ipv4` (
  `ipv4-id` varchar(32) NOT NULL DEFAULT '',
  `ipv4` varchar(32) NOT NULL DEFAULT '127.0.0.1',
  `whois-id` varchar(32) NOT NULL DEFAULT '',
  `hits` int(8) unsigned NOT NULL DEFAULT '0',
  `created` int(13) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ipv4-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
