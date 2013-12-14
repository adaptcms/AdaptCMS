ALTER TABLE  `{prefix}plugin_polls` ADD  `start_date` DATE NULL AFTER  `user_id` ,
ADD  `end_date` DATE NULL AFTER  `start_date`
-- --------------------------------------------------------