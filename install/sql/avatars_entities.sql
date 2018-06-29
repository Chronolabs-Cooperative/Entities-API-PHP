
CREATE TABLE `" . $GLOBALS['APIDB']->prefix('avatars_entities') . "` (
  `avatar-id` varchar(32) NOT NULL DEFAULT '',
  `entity-id` varchar(32) NOT NULL DEFAULT '',
  `when` int(12) NOT NULL,
  PRIMARY KEY (`avatar-id`,`entity-id`,`when`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
