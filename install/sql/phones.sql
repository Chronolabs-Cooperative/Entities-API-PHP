
CREATE TABLE `phones` (
  `phone-id` varchar(32) NOT NULL DEFAULT '',
  `type` enum('Business','Home','Callback','Fax','Car','Switch','ISDN','Mobile','Other','Pager','Radio','TTY/TDD','Primary','Unknown') NOT NULL DEFAULT 'Unknown',
  `number` varchar(30) DEFAULT '',
  `entities` int(8) DEFAULT '0',
  `created` int(12) DEFAULT '0',
  `updated` int(12) DEFAULT '0',
  `offlined` int(12) DEFAULT '0',
  PRIMARY KEY (`phone-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
