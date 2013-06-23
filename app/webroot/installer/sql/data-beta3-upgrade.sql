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
