--
-- Table structure for table `ibc_league`
--

CREATE TABLE IF NOT EXISTS `ibc_league` (
  `league_id` int(11) NOT NULL,
  `league_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sport_type` int(11) NOT NULL,
  PRIMARY KEY (`league_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `ibc_team`
--

CREATE TABLE IF NOT EXISTS `ibc_team` (
  `team_id` int(11) NOT NULL,
  `team_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sport_type` int(11) NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `ibc_match`
--

CREATE TABLE IF NOT EXISTS `ibc_match` (
  `match_id` int(11) NOT NULL,
  `league_id` int(11) NOT NULL,
  `home_id` int(11) NOT NULL,
  `away_id` int(11) NOT NULL,
  `event_date` date NOT NULL,
  `match_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sport_type` int(11) NOT NULL,
  PRIMARY KEY (`match_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `ost_actor`
--
CREATE TABLE IF NOT EXISTS `ost_actor` (
  `actor_id` int(11) NOT NULL AUTO_INCREMENT,
  `actor_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_info` text COLLATE utf8_unicode_ci,
  `type` tinyint(4) NOT NULL COMMENT '1: trader, 2:suspender, 3:CSD',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:active, 0:inactive',
  `updated` datetime NOT NULL,
  PRIMARY KEY (`actor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;

--
-- Table structure for table `ost_attachment`
--

CREATE TABLE IF NOT EXISTS `ost_attachment` (
  `attachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `attachment_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`attachment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;

--
-- Table structure for table `ost_league`
--

CREATE TABLE IF NOT EXISTS `ost_league` (
  `league_id` int(11) NOT NULL AUTO_INCREMENT,
  `league_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sport_type` int(11) NOT NULL,
  `ibc_league_id` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:active, 0:inactive',
  `updated` datetime NOT NULL,
  PRIMARY KEY (`league_id`),
  KEY `sport_type` (`sport_type`,`ibc_league_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;

--
-- Table structure for table `ost_match`
--

CREATE TABLE IF NOT EXISTS `ost_match` (
  `match_id` int(11) NOT NULL AUTO_INCREMENT,
  `league_id` int(11) NOT NULL,
  `home_id` int(11) NOT NULL,
  `away_id` int(11) NOT NULL,
  `ibc_match_id` int(11) DEFAULT NULL,
  `event_date` date NOT NULL,
  `match_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sport_type` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1:active, 0:inactive',
  `updated` datetime NOT NULL,
  PRIMARY KEY (`match_id`),
  KEY `league_id` (`league_id`),
  KEY `league_id_2` (`league_id`,`home_id`,`away_id`,`ibc_match_id`,`sport_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;

--
-- Table structure for table `ost_sport`
--

CREATE TABLE IF NOT EXISTS `ost_sport` (
  `sport_type` int(11) NOT NULL AUTO_INCREMENT,
  `sport_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL,
  PRIMARY KEY (`sport_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;

--
-- Table structure for table `ost_team`
--

CREATE TABLE IF NOT EXISTS `ost_team` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sport_type` int(11) NOT NULL,
  `ibc_team_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `updated` datetime NOT NULL,
  PRIMARY KEY (`team_id`),
  KEY `sport_type` (`sport_type`,`ibc_team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;

--
-- Table structure for table `ost_ticket`
--

ALTER TABLE `ost_ticket`
  ADD `sport_type` INT NOT NULL AFTER `subject`, 
  ADD `league` INT(64) NOT NULL AFTER `sport_type`,
  ADD `match` INT(64) NOT NULL AFTER `league`,
  ADD `trader` INT(64) NULL AFTER `match`,
  ADD `suspender` INT(64) NULL AFTER `trader`,
  ADD `csd` INT(64) NULL AFTER `suspender`,
  ADD INDEX (`sport_type`, `league`, `match`, `trader`, `suspender`, `csd`) 




