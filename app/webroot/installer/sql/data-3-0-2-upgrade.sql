INSERT INTO `{prefix}permissions` (`id`,`label`, `module_id`, `role_id`, `action_id`, `plugin`, `controller`, `action`, `status`, `related`, `own`, `any`) VALUES
(null, NULL, 4, 1, 0, '', 'files', 'admin_json_list', 1, '', 1, 1),
(null, NULL, 4, 4, 0, '', 'files', 'admin_json_list', 1, '', 1, 1),
(null, NULL, 1, 1, 0, '', 'articles', 'admin_preview', 1, '', 1, 1),
(null, NULL, 1, 4, 0, '', 'articles', 'admin_preview', 1, '', 1, 1),
(null, NULL, NULL, 1, 0, '', 'tools', 'admin_feeds', 1, '', 1, 1),
(null, NULL, NULL, 4, 0, '', 'tools', 'admin_feeds', 1, '', 1, 1),
(null, NULL, NULL, 1, 0, '', 'tools', 'admin_create_plugin', 1, '', 1, 1),
(null, NULL, NULL, 4, 0, '', 'tools', 'admin_create_plugin', 1, '', 1, 1),
(null, NULL, NULL, 1, 0, '', 'tools', 'admin_create_theme', 1, '', 1, 1),
(null, NULL, NULL, 4, 0, '', 'tools', 'admin_create_theme', 1, '', 1, 1),
(null, NULL, NULL, 1, 0, '', 'tools', 'admin_routes_list', 1, '', 1, 1),
(null, NULL, NULL, 4, 0, '', 'tools', 'admin_routes_list', 1, '', 1, 1);
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}article_revisions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `data` longtext NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}media_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `media_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `article_id` (`article_id`,`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------