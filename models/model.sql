delimiter $$

CREATE TABLE `accounts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(32) DEFAULT NULL,
  `name` varchar(96) DEFAULT NULL,
  `admin_email` varchar(45) DEFAULT NULL,
  `map_api_key` varchar(64) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `account_limits` varchar(1024) DEFAULT '{"users":0, "records":0, "forms":0, "media":0}',
  `is_active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `KEY` (`api_key`) USING BTREE,
  UNIQUE KEY `EMAIL_UNIQUE` (`admin_email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1$$

delimiter $$

CREATE TABLE `assignments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  `form_id` bigint(20) DEFAULT NULL,
  `user` varchar(128) COLLATE latin1_general_ci DEFAULT NULL,
  `identity` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `schedule` set('daily','weekly','monthly','yearly') COLLATE latin1_general_ci DEFAULT NULL,
  `repeat_schedule` smallint(6) DEFAULT '1',
  `date_assigned` date DEFAULT NULL,
  `date_expires` date DEFAULT NULL,
  `date_last_reported` date DEFAULT NULL,
  `date_next_report` date DEFAULT NULL,
  `report_count` smallint(6) DEFAULT NULL,
  `status` set('open','closed') COLLATE latin1_general_ci DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci$$

delimiter $$

CREATE TABLE `distributions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  `user_role` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `form_tag` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci$$

delimiter $$

CREATE TABLE `forms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `tags` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `title` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `description` varchar(1024) COLLATE latin1_general_ci NOT NULL,
  `identity_name` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `meta` text COLLATE latin1_general_ci,
  `report_version` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `is_public` tinyint(4) DEFAULT '0',
  `is_published` tinyint(4) DEFAULT '0',
  `is_deleted` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Open Report Form'$$

delimiter $$

CREATE TABLE `identity` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  `identity_name` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `identity` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `description` text COLLATE latin1_general_ci,
  `is_active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci$$

delimiter $$

CREATE TABLE `libaray` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(36) COLLATE latin1_general_ci DEFAULT NULL,
  `meta` longtext COLLATE latin1_general_ci,
  `role` set('field','fieldset') COLLATE latin1_general_ci DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Libaray of Standard Controls'$$

delimiter $$

CREATE TABLE `records` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `form_id` bigint(20) DEFAULT NULL,
  `report_version` int(11) DEFAULT NULL,
  `meta` text COLLATE latin1_general_ci,
  `record_date` datetime DEFAULT NULL,
  `record_time_offset` char(5) COLLATE latin1_general_ci DEFAULT NULL,
  `user` varchar(512) COLLATE latin1_general_ci DEFAULT NULL,
  `lat` decimal(10,8) DEFAULT NULL COMMENT 'Records for task/event data',
  `lon` decimal(11,8) DEFAULT NULL,
  `score` mediumint(9) DEFAULT NULL,
  `identity` varchar(128) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci$$

delimiter $$

CREATE TABLE `reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(36) COLLATE latin1_general_ci DEFAULT NULL,
  `form_id` bigint(20) DEFAULT NULL,
  `form_name` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `title` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  `meta` text COLLATE latin1_general_ci,
  `is_active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci$$

delimiter $$

CREATE TABLE `tags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  `scope` set('roles','reports') COLLATE latin1_general_ci DEFAULT NULL,
  `name` varchar(45) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci$$

delimiter $$

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `password` varchar(16) DEFAULT NULL,
  `account_id` bigint(20) DEFAULT NULL,
  `roles` varchar(96) DEFAULT NULL,
  `date_created` date DEFAULT NULL,
  `date_modified` date DEFAULT NULL,
  `date_last_accessed` datetime DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `EMAIL` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci$$
