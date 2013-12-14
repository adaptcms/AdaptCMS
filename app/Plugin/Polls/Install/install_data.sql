CREATE TABLE IF NOT EXISTS `{prefix}plugin_polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `poll_type` varchar(255) DEFAULT NULL,
  `user_id` int(11),
  `start_date` DATE NULL,
  `end_date` DATE NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`,`user_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_poll_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_poll_voting_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) DEFAULT '0',
  `value_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `user_ip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`,`value_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;