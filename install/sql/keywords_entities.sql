
CREATE TABLE `" . $GLOBALS['APIDB']->prefix('keywords_entities') . "` (
  `keyword-id` varchar(32) NOT NULL DEFAULT '',
  `entity-id` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`keyword-id`,`entity-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
