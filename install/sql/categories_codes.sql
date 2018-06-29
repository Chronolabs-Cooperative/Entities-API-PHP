
CREATE TABLE `categories_codes` (
  `category-code-id` int(24) NOT NULL AUTO_INCREMENT,
  `category-id` varchar(32) NOT NULL DEFAULT '',
  `maps-id` varchar(32) NOT NULL DEFAULT '',
  `code` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`category-code-id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
