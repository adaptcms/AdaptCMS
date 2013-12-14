ALTER TABLE  `{prefix}plugin_poll_voting_values` CHANGE  `plugin_poll_id`  `poll_id` INT( 11 ) NULL DEFAULT  '0',
CHANGE  `plugin_value_id`  `value_id` INT( 11 ) NULL DEFAULT  '0'
-- --------------------------------------------------------
ALTER TABLE  `{prefix}plugin_poll_values` CHANGE  `plugin_poll_id`  `poll_id` INT( 11 ) NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}plugin_poll_voting_values` ADD  `created` DATETIME NOT NULL
-- --------------------------------------------------------
ALTER TABLE  `{prefix}plugin_polls` CHANGE  `created`  `created` DATETIME NOT NULL ,
CHANGE  `modified`  `modified` DATETIME NOT NULL
-- --------------------------------------------------------
INSERT INTO `{prefix}permissions` (`id`, `label`, `module_id`, `role_id`, `action_id`, `plugin`, `controller`, `action`, `status`, `related`, `own`, `any`) VALUES
(null, NULL, 8, 1, 0, 'polls', 'polls', 'all', 1, '[{"action":["vote"]}]', 1, 1),
(null, NULL, 8, 2, 0, 'polls', 'polls', 'all', 1, '[{"action":["vote"]}]', 2, 2),
(null, NULL, 8, 3, 0, 'polls', 'polls', 'all', 1, '[{"action":["vote"]}]', 2, 2),
(null, NULL, 8, 4, 0, 'polls', 'polls', 'all', 1, '[{"action":["vote"]}]', 1, 1);