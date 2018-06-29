
CREATE TABLE `" . $GLOBALS['APIDB']->prefix('emails_networking') . "` (
  `email-id` varchar(32) NOT NULL DEFAULT '',
  `ip-id` varchar(32) NOT NULL DEFAULT '',
  `when` int(12) DEFAULT '0',
  PRIMARY KEY (`email-id`,`ip-id`),
  KEY `fk_emails_networking_1_idx` (`ip-id`),
  CONSTRAINT `fk_emails_networking_1` FOREIGN KEY (`ip-id`) REFERENCES `" . $GLOBALS['APIDB']->prefix('networking') . "` (`ip-id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_emails_networking_2` FOREIGN KEY (`email-id`) REFERENCES `" . $GLOBALS['APIDB']->prefix('emails') . "` (`email-id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
