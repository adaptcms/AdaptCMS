CREATE TABLE IF NOT EXISTS `{prefix}plugin_adaptbb_forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` longtext,
  `status` int(11) NOT NULL,
  `num_posts` int(11) NOT NULL,
  `num_topics` int(11) NOT NULL,
  `ord` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `icon_url` varchar(250) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`,`user_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_adaptbb_forum_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `ord` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_adaptbb_forum_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext NOT NULL,
  `topic_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_id` (`topic_id`,`user_id`,`deleted_time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_adaptbb_forum_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `num_views` int(11) NOT NULL,
  `num_posts` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `topic_type` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`forum_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
INSERT INTO `{prefix}fields` (`id`, `title`, `label`, `field_order`, `category_id`, `module_id`, `field_type_id`, `field_type_slug`, `description`, `field_options`, `field_limit_min`, `field_limit_max`, `required`, `user_id`, `created`, `modified`, `deleted_time`) VALUES
(null, 'signature', 'Signature', 4, 0, 9, 13, 'textarea', '<p>You may enter in your signature that will appear under your posts in the Forums.</p>', '', 0, 0, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00');
-- --------------------------------------------------------
INSERT INTO `{prefix}plugin_adaptbb_forums` (`id`, `title`, `slug`, `category_id`, `description`, `status`, `num_posts`, `num_topics`, `ord`, `user_id`, `icon_url`, `created`, `modified`, `deleted_time`) VALUES
(1, 'Off Topic', 'off-topic', 1, '<p>All discussions go here.</p>', 1, 0, 0, 0, 1, '', '{date}', '{date}', '0000-00-00 00:00:00');
-- --------------------------------------------------------
INSERT INTO `{prefix}plugin_adaptbb_forum_categories` (`id`, `title`, `slug`, `ord`, `user_id`, `created`, `modified`, `deleted_time`) VALUES
(1, 'General', 'general', 0, 1, '{date}', '{date}', '0000-00-00 00:00:00');