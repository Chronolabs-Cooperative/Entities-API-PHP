
CREATE TABLE `" . $GLOBALS['APIDB']->prefix('keywords') . "` (
  `keyword-id` varchar(32) NOT NULL DEFAULT '',
  `keyword` varchar(100) NOT NULL DEFAULT '',
  `" . $GLOBALS['APIDB']->prefix('entities') . "` int(12) NOT NULL DEFAULT '0',
  `duplicates` int(12) NOT NULL DEFAULT '0',
  `verified` int(12) NOT NULL DEFAULT '0',
  `created` int(12) NOT NULL DEFAULT '0',
  `updated` int(12) NOT NULL DEFAULT '0',
  `offline` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`keyword-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
