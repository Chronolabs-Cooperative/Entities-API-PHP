
CREATE TABLE `" . $GLOBALS['APIDB']->prefix('emails') . "` (
  `email-id` varchar(32) NOT NULL DEFAULT '',
  `type` enum('Email','Forwarder','Mailing List','Ticketer','Uploader','Unknown') NOT NULL DEFAULT 'Unknown',
  `state` enum('Unvalidated','Validated','None Delivery Notification') NOT NULL DEFAULT 'Unvalidated',
  `email` varchar(250) DEFAULT '',
  `display-name` varchar(150) DEFAULT '',
  `" . $GLOBALS['APIDB']->prefix('entities') . "` int(8) DEFAULT '0',
  `created` int(12) DEFAULT '0',
  `updated` int(12) DEFAULT '0',
  `offlined` int(12) DEFAULT '0',
  `verified` int(12) DEFAULT '0',
  `contacted` int(12) DEFAULT '0',
  `referee` varchar(44) DEFAULT '',
  `actkey` varchar(10) DEFAULT '',
  `data` mediumtext,
  PRIMARY KEY (`email-id`),
  KEY `SEARCH` (`type`,`display-name`,`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
