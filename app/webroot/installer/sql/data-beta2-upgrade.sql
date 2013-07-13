ALTER TABLE  `{prefix}plugin_poll_voting_values` CHANGE  `plugin_poll_value_id`  `plugin_value_id` INT( 11 ) NULL DEFAULT  '0'
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `{prefix}module_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT '0',
  `field_id` int(11) DEFAULT '0',
  `file_id` int(11) DEFAULT '0',
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}modules` ADD  `is_fields` INT( 1 ) NOT NULL DEFAULT  '0' AFTER  `is_searchable`
-- --------------------------------------------------------
UPDATE  `{prefix}modules` SET  `is_fields` =  '1' WHERE  `{prefix}modules`.`model_title` = 'User';
-- --------------------------------------------------------
INSERT INTO `{prefix}permissions` (`id`, `module_id`, `role_id`, `action_id`, `plugin`, `controller`, `action`, `status`, `related`, `own`, `any`) VALUES
(null, 12, 1, 0, '', 'settings', 'admin_restore', 0, NULL, 2, 2),
(null, 12, 4, 0, '', 'settings', 'admin_restore', 0, NULL, 2, 2);
-- --------------------------------------------------------
ALTER TABLE  `{prefix}blocks` ADD  `data` LONGTEXT NULL AFTER  `settings`
-- --------------------------------------------------------
INSERT INTO `{prefix}pages` (`id`, `title`, `slug`, `user_id`, `created`, `modified`, `deleted_time`) VALUES
(null, 'home', 'home', 1, '{date}', '{date}', '0000-00-00 00:00:00');
-- --------------------------------------------------------
ALTER TABLE  `{prefix}templates` ADD  `label` VARCHAR( 255 ) NULL AFTER  `title`
-- --------------------------------------------------------
UPDATE `{prefix}roles` SET defaults = 'default-admin' WHERE title = 'admin'
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET  `related` =  '[{"action":["view"],"controller":["articles"]}]' WHERE  `{prefix}permissions`.`id` =192;
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET  `related` =  '[{"action":["view"],"controller":["articles"]}]' WHERE  `{prefix}permissions`.`id` =172;
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
INSERT INTO `{prefix}permissions` (`id`, `module_id`, `role_id`, `action_id`, `plugin`, `controller`, `action`, `status`, `related`, `own`, `any`) VALUES
(null, NULL, 1, 0, '', 'menus', 'admin_restore', 1, '', 1, 1),
(null, NULL, 1, 0, '', 'menus', 'admin_delete', 1, '', 1, 1),
(null, NULL, 1, 0, '', 'menus', 'admin_edit', 1, '', 1, 1),
(null, NULL, 1, 0, '', 'menus', 'admin_add', 1, '', 0, 0),
(null, NULL, 1, 0, '', 'menus', 'admin_index', 1, '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["profile"],"controller":["users"]}]', 1, 1),
(null, NULL, 4, 0, '', 'menus', 'admin_restore', 1, '', 1, 1),
(null, NULL, 4, 0, '', 'menus', 'admin_delete', 1, '', 1, 1),
(null, NULL, 4, 0, '', 'menus', 'admin_edit', 1, '', 1, 1),
(null, NULL, 4, 0, '', 'menus', 'admin_add', 1, '', 0, 0),
(null, NULL, 4, 0, '', 'menus', 'admin_index', 1, '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["profile"],"controller":["users"]}]', 1, 1),
(null, NULL, 1, 0, '', 'field_types', 'admin_restore', 1, '', 1, 1),
(null, NULL, 1, 0, '', 'field_types', 'admin_delete', 1, '', 1, 1),
(null, NULL, 1, 0, '', 'field_types', 'admin_edit', 1, '', 1, 1),
(null, NULL, 1, 0, '', 'field_types', 'admin_add', 1, '', 0, 0),
(null, NULL, 1, 0, '', 'field_types', 'admin_index', 1, '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["profile"],"controller":["users"]}]', 1, 1),
(null, NULL, 4, 0, '', 'field_types', 'admin_restore', 1, '', 1, 1),
(null, NULL, 4, 0, '', 'field_types', 'admin_delete', 1, '', 1, 1),
(null, NULL, 4, 0, '', 'field_types', 'admin_edit', 1, '', 1, 1),
(null, NULL, 4, 0, '', 'field_types', 'admin_add', 1, '', 0, 0),
(null, NULL, 4, 0, '', 'field_types', 'admin_index', 1, '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["profile"],"controller":["users"]}]', 1, 1);
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
ALTER TABLE  `{prefix}fields` ADD  `module_id` int( 11 ) NULL DEFAULT NULL AFTER  `category_id`
-- --------------------------------------------------------
ALTER TABLE  `{prefix}fields` CHANGE  `field_type`  `field_type_id` INT( 11 ) NULL DEFAULT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}fields` ADD  `field_type_slug` VARCHAR( 255 ) NOT NULL AFTER  `field_type_id`
-- --------------------------------------------------------
INSERT INTO `{prefix}field_types` (`id`, `title`, `label`, `slug`, `limit`, `user_id`, `ord`, `active`, `created`, `modified`, `deleted_time`) VALUES
(null, 'text', 'Text Input', 'text', 1, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'Check', 'Checkbox', 'check', 0, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'Date', '', 'date', 0, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'Dropdown', 'Dropdown Selector', 'dropdown', 0, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'Email', '', 'email', 1, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'File', '', 'file', 0, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'Img', 'Image', 'img', 0, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'Multi Dropdown', 'Dropdown Selector Multiple', 'multi-dropdown', 0, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'Num', 'Number', 'num', 1, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'Radio', '', 'radio', 0, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'Textarea', 'Text Box', 'textarea', 1, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00'),
(null, 'Url', 'Website  URL', 'url', 1, 1, 0, 1, '{date}', '{date}', '0000-00-00 00:00:00');
-- --------------------------------------------------------