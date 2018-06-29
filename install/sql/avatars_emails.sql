
CREATE TABLE `avatars_emails` (
  `avatar-id` varchar(32) NOT NULL DEFAULT '',
  `entity-id` varchar(32) NOT NULL DEFAULT '',
  `email-id` varchar(32) NOT NULL DEFAULT '',
  `when` int(12) NOT NULL,
  PRIMARY KEY (`avatar-id`,`entity-id`,`email-id`,`when`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
