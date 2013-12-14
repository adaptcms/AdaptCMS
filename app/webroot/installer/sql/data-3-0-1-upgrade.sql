ALTER TABLE `{prefix}blocks` DROP COLUMN `location`;
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET action = 'admin_ajax_related_update' WHERE `action` = 'admin_ajax_related_add'
-- --------------------------------------------------------
ALTER TABLE  `{prefix}categories` ADD  `settings` LONGTEXT NULL AFTER  `slug`
-- --------------------------------------------------------
INSERT INTO `{prefix}permissions` (`id`,`label`, `module_id`, `role_id`, `action_id`, `plugin`, `controller`, `action`, `status`, `related`, `own`, `any`) VALUES
(null, NULL, 6, 1, 0, '', 'templates', 'admin_global_tags', 1, '', 2, 2),
(null, NULL, 6, 4, 0, '', 'templates', 'admin_global_tags', 0, '', 2, 2),
(null, null, 0, 1, 0, '', 'tools', 'admin_convert_onecms', 1, '', 2, 2),
(null, null, 0, 4, 0, '', 'tools', 'admin_convert_onecms', 0, '', 2, 2),
(null, null, 20, 4, 0, '', 'comments', 'admin_delete', 1, NULL, 1, 1),
(null, null, 20, 4, 0, '', 'comments', 'admin_restore', 1, NULL, 1, 1),
(null, null, 20, 1, 0, '', 'comments', 'admin_delete', 1, NULL, 1, 1),
(null, null, 20, 1, 0, '', 'comments', 'admin_restore', 1, NULL, 1, 1);
-- --------------------------------------------------------