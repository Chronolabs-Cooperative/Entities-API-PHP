
CREATE TABLE `imports_maps` (
  `maps-id` varchar(32) NOT NULL,
  `state` enum('Defined','Waiting') NOT NULL DEFAULT 'Waiting',
  `title` varchar(150) NOT NULL DEFAULT '',
  `imports` int(12) NOT NULL DEFAULT '0',
  `records` int(12) NOT NULL DEFAULT '0',
  `duplicates` int(12) NOT NULL DEFAULT '0',
  `last` int(12) NOT NULL DEFAULT '0',
  `columns` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`maps-id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
