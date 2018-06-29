
CREATE TABLE `categories` (
  `category-id` varchar(32) NOT NULL DEFAULT '',
  `category` varchar(250) NOT NULL DEFAULT '',
  `entities` int(8) NOT NULL DEFAULT '0',
  `created` int(12) NOT NULL DEFAULT '0',
  `updated` int(12) NOT NULL DEFAULT '0',
  `offlined` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category-id`),
  KEY `SEARCH` (`category`,`entities`,`category-id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
