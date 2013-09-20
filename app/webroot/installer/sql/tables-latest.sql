CREATE TABLE IF NOT EXISTS `{prefix}articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `tags` longtext,
  `related_articles` longtext NOT NULL,
  `user_id` int(11) DEFAULT '0',
  `category_id` int(11) DEFAULT '0',
  `status` int(3) DEFAULT '0',
  `publish_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `settings` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`category_id`,`status`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}article_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT '0',
  `field_id` int(11) DEFAULT '0',
  `file_id` int(11) DEFAULT '0',
  `data` text,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`,`field_id`,`file_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `module_id` int(11),
  `user_id` int(11),
  `location` longtext NOT NULL,
  `limit` int(11),
  `settings` longtext DEFAULT NULL,
  `data` longtext NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `deleted_time` (`deleted_time`,`module_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}captcha_codes` (
  `id` varchar(40) NOT NULL,
  `namespace` varchar(32) NOT NULL,
  `code` varchar(32) NOT NULL,
  `code_display` varchar(32) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`,`namespace`),
  KEY `created` (`created`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `user_id` int(11),
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rght` int(11) NOT NULL DEFAULT '0',
  `article_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `comment_text` text,
  `author_name` varchar(255) DEFAULT NULL,
  `author_email` varchar(255) DEFAULT NULL,
  `author_website` varchar(255) DEFAULT NULL,
  `author_ip` varchar(255) DEFAULT NULL,
  `active` int(1) DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`,`user_id`,`deleted_time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL,
  `function` varchar(255) NOT NULL,
  `run_time` datetime NOT NULL,
  `last_run` datetime NOT NULL,
  `period_amount` int(11) NOT NULL,
  `period_type` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `run_time` (`run_time`,`deleted_time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `field_order` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) DEFAULT '0',
  `module_id` int(11) DEFAULT '0',
  `field_type_id` int(11) NOT NULL,
  `field_type_slug` varchar(255) DEFAULT NULL,
  `description` text,
  `field_options` longtext,
  `field_limit_min` int(11) NOT NULL DEFAULT '0',
  `field_limit_max` int(11) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `user_id` int(11),
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`,`field_type_id`,`user_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}field_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `limit` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `ord` int(11) NOT NULL DEFAULT '0',
  `active` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`,`deleted_time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `filesize` varchar(255) NOT NULL,
  `mimetype` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `watermark` int(1) DEFAULT '0',
  `user_id` int(11),
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` longtext,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(30) NOT NULL,
  `plugin` varchar(55) DEFAULT NULL,
  `controller` varchar(55) NOT NULL,
  `action` varchar(55) NOT NULL,
  `action_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `user_id` int(11),
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`deleted_time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}media_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `file_id` (`file_id`,`media_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `menu_items` longtext NOT NULL,
  `settings` longtext,
  `user_id` varchar(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deleted_time` (`deleted_time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `message` text,
  `sender_user_id` int(11) DEFAULT '0',
  `receiver_user_id` int(11) DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `last_reply_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sender_archived_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `receiver_archived_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_read` int(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_user_id` (`sender_user_id`,`receiver_user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `model_title` varchar(255) DEFAULT NULL,
  `block_active` int(1) NOT NULL DEFAULT '0',
  `is_plugin` int(1) NOT NULL DEFAULT '0',
  `is_searchable` int(1) NOT NULL DEFAULT '0',
  `is_fields` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `model_title` (`model_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}module_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT '0',
  `field_id` int(11) DEFAULT '0',
  `file_id` int(11) DEFAULT '0',
  `data` text,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`,`field_id`,`file_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `user_id` int(11),
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`,`user_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) DEFAULT NULL,
  `module_id` int(11) DEFAULT '0',
  `role_id` int(11) NOT NULL DEFAULT '0',
  `action_id` int(11) NOT NULL DEFAULT '0',
  `plugin` varchar(50) NOT NULL,
  `controller` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `related` longtext,
  `own` int(1) NOT NULL DEFAULT '0',
  `any` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`,`action_id`,`plugin`,`controller`,`action`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_adaptbb_forums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `description` longtext,
  `status` int(11) NOT NULL,
  `num_posts` int(1) NOT NULL DEFAULT '0',
  `num_topics` int(11) NOT NULL DEFAULT '0',
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_adaptbb_forum_topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `num_views` int(11) NOT NULL,
  `num_posts` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `user_id` int(11) NOT NULL,
  `forum_id` int(11) NOT NULL,
  `topic_type` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`forum_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_google_maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `map_type` varchar(255) NOT NULL,
  `locations` longtext,
  `options` longtext,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `link_title` varchar(255) DEFAULT NULL,
  `link_target` varchar(255) DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11),
  `active` int(1),
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `file_id` (`file_id`,`user_id`,`deleted_time`,`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `poll_type` varchar(255) DEFAULT NULL,
  `user_id` int(11),
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`,`user_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_poll_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `poll_id` int(11) NOT NULL,
  `votes` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}plugin_poll_voting_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) DEFAULT '0',
  `value_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `user_ip` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`,`value_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `defaults` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `defaults` (`defaults`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}setting_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `data` text,
  `data_options` longtext,
  `setting_type` varchar(255) DEFAULT NULL,
  `setting_id` int(11) DEFAULT '0',
  `model` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `title` (`title`,`setting_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `theme_id` int(11) DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` varchar(255) DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `theme_id` (`theme_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `deleted_time` (`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT '0',
  `login_time` datetime DEFAULT NULL,
  `security_answers` longtext NOT NULL,
  `settings` longtext NOT NULL,
  `status` int(3) DEFAULT '0',
  `theme_id` int(11) DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_reset_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`,`deleted_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;