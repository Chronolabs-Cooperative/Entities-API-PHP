
CREATE TABLE `ipv6` (
  `ipv6-id` varchar(32) NOT NULL,
  `ipv6` varchar(128) NOT NULL DEFAULT '::1',
  `whois-id` varchar(32) NOT NULL DEFAULT '',
  `hits` int(8) unsigned NOT NULL DEFAULT '0',
  `created` int(13) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
