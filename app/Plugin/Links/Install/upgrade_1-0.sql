ALTER TABLE  `{prefix}plugin_links` ADD  `active` INT( 1 ) NOT NULL DEFAULT  '1' AFTER  `views`
-- --------------------------------------------------------
INSERT INTO `{prefix}permissions` (`id`, `label`, `module_id`, `role_id`, `action_id`, `plugin`, `controller`, `action`, `status`, `related`, `own`, `any`) VALUES
(null, NULL, 17, 1, 0, 'links', 'links', 'apply', 1, '', 2, 2),
(null, NULL, 17, 2, 0, 'links', 'links', 'apply', 1, '', 2, 2),
(null, NULL, 17, 3, 0, 'links', 'links', 'apply', 1, '', 2, 2),
(null, NULL, 17, 4, 0, 'links', 'links', 'apply', 1, '', 2, 2);