INSERT INTO `{prefix}setting_values` (`id`, `title`, `description`, `data`, `data_options`, `setting_type`, `setting_id`, `model`, `created`, `modified`, `deleted_time`) VALUES
(null, 'Comment Allowed HTML', '<p>Allowed HTML tags when posting a comment. Entering in nothing will result in all HTML being stripped.</p>', '<strong>,<a>,<p>,<br>', NULL, 'text', 6, NULL, '{date}', '{date}', '0000-00-00 00:00:00');
-- --------------------------------------------------------
INSERT INTO `{prefix}permissions` (`id`, `module_id`, `role_id`, `action_id`, `plugin`, `controller`, `action`, `status`, `related`, `own`, `any`) VALUES
(null, 12, 1, 0, '', 'settings', 'admin_restore', 1, NULL, 1, 1),
(null, 12, 4, 0, '', 'settings', 'admin_restore', 0, NULL, 0, 0),
(null, 0, 4, 0, '', 'plugins', 'admin_settings', 0, NULL, 2, 2),
(null, 0, 1, 0, '', 'plugins', 'admin_settings', 1, NULL, 2, 2),
(null, 0, 1, 0, '', 'tools', 'admin_convert_wordpress', 1, '', 2, 2),
(null, 0, 4, 0, '', 'tools', 'admin_convert_wordpress', 0, '', 2, 2),
(null, 7, 1, 0, '', 'themes', 'admin_asset_delete', 1, '', 0, 1),
(null, 7, 1, 0, '', 'themes', 'admin_asset_add', 1, '', 0, 1),
(null, 7, 1, 0, '', 'themes', 'admin_asset_edit', 1, '', 0, 1),
(null, 7, 4, 0, '', 'themes', 'admin_asset_delete', 0, '', 0, 0),
(null, 7, 4, 0, '', 'themes', 'admin_asset_add', 0, '', 0, 0),
(null, 7, 4, 0, '', 'themes', 'admin_asset_edit', 0, '', 0, 0),
(null, NULL, 1, 0, '', 'plugins', 'admin_assets', 1, '', 2, 2),
(null, NULL, 4, 0, '', 'plugins', 'admin_assets', 0, '', 2, 2),
(null, NULL, 1, 0, '', 'plugins', 'admin_permissions', 1, '', 2, 2),
(null, NULL, 4, 0, '', 'plugins', 'admin_permissions', 0, '', 2, 2);
-- --------------------------------------------------------