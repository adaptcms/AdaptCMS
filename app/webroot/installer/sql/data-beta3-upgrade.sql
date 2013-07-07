INSERT INTO `{prefix}permissions` (`id`, `module_id`, `role_id`, `action_id`, `plugin`, `controller`, `action`, `status`, `related`, `own`, `any`) VALUES
(null, NULL, 1, 0, '', 'install', 'install_plugin', 1, '', 2, 2),
(null, NULL, 1, 0, '', 'install', 'uninstall_plugin', 1, '', 2, 2),
(null, NULL, 1, 0, '', 'install', 'upgrade_plugin', 1, '', 2, 2),
(null, NULL, 1, 0, '', 'install', 'install_theme', 1, '', 2, 2),
(null, NULL, 1, 0, '', 'install', 'uninstall_theme', 1, '', 2, 2),
(null, NULL, 1, 0, '', 'install', 'upgrade_theme', 1, '', 2, 2),
(null, NULL, 1, 0, '', 'install', 'upgrade', 1, '', 2, 2),
(null, NULL, 4, 0, '', 'install', 'install_plugin', 0, '', 2, 2),
(null, NULL, 4, 0, '', 'install', 'uninstall_plugin', 0, '', 2, 2),
(null, NULL, 4, 0, '', 'install', 'upgrade_plugin', 0, '', 2, 2),
(null, NULL, 4, 0, '', 'install', 'install_theme', 0, '', 2, 2),
(null, NULL, 4, 0, '', 'install', 'uninstall_theme', 0, '', 2, 2),
(null, NULL, 4, 0, '', 'install', 'upgrade_theme', 0, '', 2, 2),
(null, NULL, 4, 0, '', 'install', 'upgrade', 0, '', 2, 2);
-- --------------------------------------------------------
ALTER TABLE  `{prefix}cron` ADD INDEX (  `run_time` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}permissions` ADD INDEX (  `role_id` ,  `action_id` ,  `plugin` ,  `controller` ,  `action` ,  `status` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}blocks` ADD INDEX (  `deleted_time`, `module_id` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}articles` ADD INDEX (  `user_id` ,  `category_id` ,  `status` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}article_values` ADD INDEX (  `article_id` ,  `field_id` ,  `file_id` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}categories` ADD INDEX (  `user_id` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}comments` ADD INDEX (  `article_id` ,  `user_id` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}fields` ADD INDEX (  `category_id` ,  `field_type_id` ,  `user_id` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}field_types` ADD INDEX (  `slug` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}files` ADD INDEX (  `user_id` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}media` ADD INDEX (  `user_id` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}media_files` ADD INDEX (  `file_id` ,  `media_id` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}menus` ADD INDEX (  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}messages` ADD INDEX (  `sender_user_id` ,  `receiver_user_id` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}modules` ADD INDEX (  `model_title` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}module_values` ADD INDEX (  `module_id` ,  `field_id` ,  `file_id` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}pages` ADD INDEX (  `slug` ,  `user_id` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}themes` ADD INDEX (  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}templates` ADD INDEX (  `theme_id` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}setting_values` ADD INDEX (  `title` ,  `setting_id` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}roles` ADD INDEX (  `defaults` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}plugin_links` ADD INDEX (  `file_id` ,  `user_id` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}plugin_polls` ADD INDEX (  `article_id` ,  `user_id` ,  `deleted_time` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}plugin_poll_values` ADD INDEX (  `plugin_poll_id` ) ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}permissions` ADD  `label` VARCHAR( 255 ) NULL AFTER `id`
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