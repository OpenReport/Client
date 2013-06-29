delimiter $$

CREATE TABLE `forms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `tags` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `title` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `description` varchar(1024) COLLATE latin1_general_ci DEFAULT NULL,
  `meta` text COLLATE latin1_general_ci,
  `date_created` date DEFAULT NULL,
  `date_modified` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Open Report Form'$$

delimiter $$

CREATE TABLE `records` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `form_id` bigint(20) DEFAULT NULL,
  `meta` text COLLATE latin1_general_ci,
  `record_date` date DEFAULT NULL,
  `record_time` time DEFAULT NULL,
  `user` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `lat` varchar(45) COLLATE latin1_general_ci DEFAULT NULL COMMENT 'Records for task/event data',
  `lon` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `score` mediumint(9) DEFAULT NULL,
  `identity` varchar(128) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci$$

