
CREATE TABLE `" . $GLOBALS['APIDB']->prefix('categories_entities') . "` (
  `category-id` varchar(32) NOT NULL DEFAULT '',
  `entity-id` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`category-id`,`entity-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
