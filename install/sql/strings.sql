
CREATE TABLE `" . $GLOBALS['APIDB']->prefix('strings') . "` (
  `string-id` varchar(32) NOT NULL,
  `data` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`string-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
