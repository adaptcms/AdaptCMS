CREATE TABLE IF NOT EXISTS `{prefix}plugin_google_maps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `map_type` varchar(255) NOT NULL,
  `locations` longtext DEFAULT NULL,
  `options` longtext DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------