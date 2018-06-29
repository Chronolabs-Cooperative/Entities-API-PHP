
CREATE TABLE `netbios` (
  `netbios-id`varchar(32) NOT NULL,
  `netbios` varchar(250) NOT NULL DEFAULT '',
  `ipv4-id` varchar(32) NOT NULL DEFAULT '',
  `ipv6-id` varchar(32) NOT NULL DEFAULT '',
  `realm-id` varchar(32) NOT NULL DEFAULT '',
  `hits` int(8) unsigned NOT NULL DEFAULT '0',
  `created` int(13) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`netbios-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
