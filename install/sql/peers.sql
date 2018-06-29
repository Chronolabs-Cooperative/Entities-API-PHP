
CREATE TABLE `peers` (
  `peer-id` varchar(32) NOT NULL,
  `api-url` varchar(200) NOT NULL,
  `api-short-url` varchar(200) NOT NULL,
  `polinating` enum('Yes','No') NOT NULL,
  `version` varchar(20) NOT NULL,
  `callback` varchar(200) NOT NULL,
  `bytes-recieved` mediumint(24) NOT NULL DEFAULT '0',
  `bytes-sent` mediumint(24) NOT NULL DEFAULT '0',
  `entities-recieved` mediumint(24) NOT NULL DEFAULT '0',
  `entities-sent` mediumint(24) NOT NULL DEFAULT '0',
  `heard` int(12) NOT NULL DEFAULT '0',
  `down` int(12) NOT NULL DEFAULT '0',
  `created` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`peer-id`,`api-url`,`api-short-url`,`polinating`,`callback`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
