ALTER TABLE  `{prefix}cron` ADD  `last_run` DATETIME NULL AFTER  `run_time`
-- --------------------------------------------------------
ALTER TABLE  `{prefix}cron` ADD  `active` TINYINT( 1 ) NOT NULL DEFAULT  '1' AFTER  `period_type`
-- --------------------------------------------------------
INSERT INTO `{prefix}permissions` (`id`,`label`, `module_id`, `role_id`, `action_id`, `plugin`, `controller`, `action`, `status`, `related`, `own`, `any`) VALUES
(null, NULL, 19, 1, 0, '', 'cron', 'admin_test', 1, '', 2, 2),
(null, NULL, 19, 4, 0, '', 'cron', 'admin_test', 0, '', 2, 2),
(null, NULL, 20, 1, 0, '', 'comments', 'admin_index', 1, '[{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["admin_edit"],"controller":["articles"]},{"action":["profile"],"controller":["users"]}]', 1, 1),
(null, NULL, 20, 1, 0, '', 'comments', 'admin_index', 4, '[{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["admin_edit"],"controller":["articles"]},{"action":["profile"],"controller":["users"]}]', 1, 1),
(null, NULL, 4, 1, 0, '', 'files', 'admin_add_folder', 1, '[{"action":["admin_index"]}]', 2, 2),
(null, NULL, 4, 4, 0, '', 'files', 'admin_add_folder', 1, '[{"action":["admin_index"]}]', 2, 2),
(null, NULL, 9, 1, 0, '', 'users', 'ajax_quick_search', 1, null, 2, 2),
(null, NULL, 9, 2, 0, '', 'users', 'ajax_quick_search', 1, null, 2, 2),
(null, NULL, 9, 4, 0, '', 'users', 'ajax_quick_search', 1, null, 2, 2);
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET `related` = '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["admin_test"]}]' WHERE `controller` = 'cron' AND `action` = 'admin_index'
-- --------------------------------------------------------
ALTER TABLE  `{prefix}categories` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}articles` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}blocks` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}comments` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}fields` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}files` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}media` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}messages` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}pages` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}roles` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}settings` CHANGE  `created`  `created` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}setting_values` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}templates` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}themes` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}users` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}users` CHANGE  `login_time`  `login_time` DATETIME NULL DEFAULT NULL
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET `related` = '[{"action":["admin_edit"],"controller":["articles"]},{"action":["profile"],"controller":["users"]}]' WHERE `controller` = 'comments' AND `action` = 'admin_edit'
-- --------------------------------------------------------
UPDATE {prefix}permissions SET related = '[{"action":["admin_add"]},{"action":["admin_add_folder"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["view"]}]' WHERE controller = 'files' AND action = 'admin_index'
-- --------------------------------------------------------
UPDATE {prefix}permissions SET related = '[{"action":["ajax_post"], "controller":["comments"]},{"action":["admin_edit"], "controller":["comments"]},{"action":["admin_delete"], "controller":["comments"]}]' WHERE controller = 'articles' AND action = 'view' AND own = 1
-- --------------------------------------------------------
UPDATE {prefix}modules SET is_fields = 1 WHERE title = 'Comments'
-- --------------------------------------------------------
DELETE FROM {prefix}permissions WHERE controller = 'fields' AND action = 'admin_ajax_order'
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET action = 'admin_ajax_change_user' WHERE action = 'ajax_change_user'
-- --------------------------------------------------------
UPDATE {prefix}permissions SET related = '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["profile"]},{"action":["admin_ajax_change_user"]}]' WHERE related = '[{"action":["admin_add"]},{"action":["admin_edit"]},{"action":["admin_delete"]},{"action":["admin_restore"]},{"action":["profile"]},{"action":["ajax_change_user"]}]'
-- --------------------------------------------------------
DELETE FROM {prefix}permissions WHERE plugin = 'adaptbb' AND action = 'admin_ajax_order'
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET action = 'admin_ajax_theme_update' WHERE action = 'ajax_theme_update' AND controller = 'templates'
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET action = 'admin_ajax_theme_refresh' WHERE action = 'ajax_theme_refresh' AND controller = 'templates'
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET action = 'admin_ajax_template_locations' WHERE action = 'ajax_template_locations' AND controller = 'templates'
-- --------------------------------------------------------