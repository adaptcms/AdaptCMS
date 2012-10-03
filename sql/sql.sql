-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 05, 2012 at 11:18 PM
-- Server version: 5.1.63-0ubuntu0.11.10.1
-- PHP Version: 5.3.6-13ubuntu3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cake2`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT '0',
  `category_id` int(11) DEFAULT '0',
  `status` int(3) DEFAULT '0',
  `publish_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `slug`, `user_id`, `category_id`, `status`, `publish_time`, `created`, `modified`, `deleted_time`) VALUES
(1, 'halo 4 bud', 'halo_4_bud', 7, 3, 0, '0000-00-00 00:00:00', '2012-06-09 23:39:08', '2012-08-05 22:23:47', '0000-00-00 00:00:00'),
(2, 'tadoo', 'tadoo', 3, 6, 0, '0000-00-00 00:00:00', '2012-06-25 20:47:14', '2012-07-07 23:41:45', '0000-00-00 00:00:00'),
(3, 'The Amazing Spiderman', 'the-amazing-spiderman', 3, 6, 0, '0000-00-00 00:00:00', '2012-07-15 13:55:01', '2012-07-15 14:18:49', '0000-00-00 00:00:00'),
(4, 'The Dark Knight Rises', 'the_dark_knight_rises', 7, 6, 0, '0000-00-00 00:00:00', '2012-07-15 16:54:58', '2012-08-05 22:56:20', '0000-00-00 00:00:00'),
(5, 'The Matrix', 'the_matrix', 7, 6, 0, '0000-00-00 00:00:00', '2012-08-05 22:57:10', '2012-08-05 22:58:10', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `article_values`
--

CREATE TABLE IF NOT EXISTS `article_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT '0',
  `field_id` int(11) DEFAULT '0',
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `article_values`
--

INSERT INTO `article_values` (`id`, `article_id`, `field_id`, `data`) VALUES
(1, 1, 12, 'there'),
(12, 1, 11, 'new_one'),
(3, 1, 13, 'Yes'),
(13, 1, 16, 'image.jpg'),
(6, 1, 17, 'http://www.website.com'),
(7, 1, 18, '1111'),
(8, 1, 19, 'abc@google.com'),
(9, 1, 20, '02/05/2005'),
(38, 4, 24, '["Action"]'),
(34, 1, 14, '["Male"]'),
(23, 2, 22, 'PC'),
(24, 3, 23, '2012'),
(25, 4, 23, '2012'),
(39, 5, 23, '2001'),
(40, 5, 24, '["Drama","Action","Sci-Fi"]');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `title`, `slug`, `created`, `modified`, `deleted_time`) VALUES
(2, 'Reviews', 'reviews', '2012-06-03 22:18:57', '2012-06-05 23:41:40', '0000-00-00 00:00:00'),
(3, 'News', 'news', '2012-06-03 22:19:02', '2012-06-05 23:41:47', '0000-00-00 00:00:00'),
(6, 'Movies', 'movies', '2012-06-05 23:41:52', '2012-06-05 23:41:56', '0000-00-00 00:00:00'),
(7, 'Games', 'games', '2012-07-15 13:54:36', '2012-07-15 13:54:39', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `comment_text` text,
  `author_name` varchar(255) DEFAULT NULL,
  `author_email` varchar(255) DEFAULT NULL,
  `author_website` varchar(255) DEFAULT NULL,
  `author_ip` varchar(255) DEFAULT NULL,
  `status` int(3) DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE IF NOT EXISTS `fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `label` varchar(255) NOT NULL,
  `field_order` int(11) NOT NULL DEFAULT '0',
  `category_id` int(11) DEFAULT '0',
  `field_type` varchar(255) DEFAULT NULL,
  `description` text,
  `field_options` varchar(255) DEFAULT NULL,
  `field_limit_min` int(11) NOT NULL DEFAULT '0',
  `field_limit_max` int(11) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `rules` text,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `title`, `label`, `field_order`, `category_id`, `field_type`, `description`, `field_options`, `field_limit_min`, `field_limit_max`, `required`, `rules`, `created`, `modified`, `deleted_time`) VALUES
(1, 'summary', 'summary', 0, 2, 'textarea', 'You ought to enter a short summary of the news article.', '', 0, 0, 1, NULL, '2012-06-03 23:24:24', '2012-06-10 16:14:47', '0000-00-00 00:00:00'),
(12, 'basd', 'basd', 0, 3, 'dropdown', '', 'this,that,there', 0, 0, 0, NULL, '2012-06-09 21:36:18', '2012-06-10 16:15:14', '0000-00-00 00:00:00'),
(11, 'test', 'test', 0, 3, 'text', '', '', 2, 10, 1, '["required: true,","minlength: 2,","maxlength: 10,"]', '2012-06-09 21:35:49', '2012-07-01 15:09:26', '0000-00-00 00:00:00'),
(13, 'radio_guy', 'radio_guy', 0, 3, 'radio', '', 'Yes,No', 0, 0, 0, NULL, '2012-06-09 21:36:32', '2012-06-10 16:14:54', '0000-00-00 00:00:00'),
(14, 'gender', 'gender', 0, 3, 'check', '', 'Male,Female', 0, 0, 0, '["required: false,"]', '2012-06-09 21:37:02', '2012-08-05 21:40:19', '0000-00-00 00:00:00'),
(15, 'here_s_a_file', 'here_s_a_file', 0, 3, 'file', '', '', 0, 0, 0, NULL, '2012-06-09 21:37:41', '2012-06-10 16:14:56', '0000-00-00 00:00:00'),
(16, 'image', 'image', 0, 3, 'img', '', '', 0, 0, 0, NULL, '2012-06-09 21:38:31', '2012-06-10 16:15:06', '0000-00-00 00:00:00'),
(17, 'link', 'link', 0, 3, 'url', '', '', 0, 0, 0, 'required: false,url: true,', '2012-06-09 21:39:16', '2012-06-10 17:45:51', '0000-00-00 00:00:00'),
(18, 'year', 'year', 0, 3, 'num', '', '', 0, 0, 0, 'required: false,number: true,', '2012-06-09 21:39:24', '2012-06-10 17:46:07', '0000-00-00 00:00:00'),
(19, 'email', 'Email', 0, 3, 'email', '', '', 0, 0, 0, 'required: false,email: true,', '2012-06-09 21:39:34', '2012-06-10 17:45:59', '0000-00-00 00:00:00'),
(20, 'date_selector', 'date_selector', 0, 3, 'date', '', '', 0, 0, 0, NULL, '2012-06-09 21:39:44', '2012-06-10 16:14:38', '0000-00-00 00:00:00'),
(21, 'this_is_a_new_field', 'This is a field', 0, 3, 'textarea', '', '', 0, 0, 0, '["required: false,"]', '2012-06-10 16:10:14', '2012-07-01 14:45:16', '0000-00-00 00:00:00'),
(22, 'system', 'system', 0, 7, 'dropdown', '', 'PC,360,PS3,PSP,Wii', 0, 0, 1, '["required: true,"]', '2012-06-30 23:34:32', '2012-07-15 13:54:56', '0000-00-00 00:00:00'),
(23, 'release-year', 'Release Year', 0, 6, 'num', '', '', 4, 4, 1, '["required: true,","minlength: 4,","maxlength: 4,","number: true,"]', '2012-07-15 14:15:19', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(24, 'genres', 'Genres', 0, 6, 'multi-dropdown', '', 'Drama,Action,Comedy,Sci-Fi', 0, 0, 0, '["required: false,"]', '2012-08-05 22:36:49', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `filesize` varchar(255) NOT NULL,
  `mimetype` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `media_id` int(11) DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `filename`, `dir`, `filesize`, `mimetype`, `caption`, `media_id`, `created`, `modified`, `deleted_time`) VALUES
(14, 'Pond.png', 'uploads/', '337778', 'image/png', 'Pond', 0, '2012-08-03 23:11:09', '2012-08-03 23:11:09', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` longtext,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(30) NOT NULL,
  `plugin` varchar(55) DEFAULT NULL,
  `controller` varchar(55) NOT NULL,
  `action` varchar(55) NOT NULL,
  `action_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=747 ;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `data`, `user_id`, `ip_address`, `plugin`, `controller`, `action`, `action_id`, `date`) VALUES
(1, '{"params":{"plugin":null,"controller":"settings","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/settings","base":"","webroot":"\\/","here":"\\/admin\\/settings","plugin":""}', 3, '127.0.0.1', '', 'settings', 'admin_index', 0, '2012-07-15 11:41:48'),
(2, '{"params":{"plugin":null,"controller":"pages","action":"display","named":[],"pass":["admin"]},"data":[],"query":[],"url":"admin","base":"","webroot":"\\/","here":"\\/admin","plugin":""}', 3, '127.0.0.1', '', 'pages', 'display', 0, '2012-07-15 12:22:49'),
(3, '{"params":{"plugin":null,"controller":"articles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles","base":"","webroot":"\\/","here":"\\/admin\\/articles","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 12:41:07'),
(4, '{"params":{"plugin":null,"controller":"files","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/files","base":"","webroot":"\\/","here":"\\/admin\\/files","plugin":""}', 3, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-07-15 12:41:12'),
(5, '{"params":{"plugin":null,"controller":"pages","action":"display","named":[],"pass":["admin"]},"data":[],"query":[],"url":"admin","base":"","webroot":"\\/","here":"\\/admin","plugin":""}', 3, '127.0.0.1', '', 'pages', 'display', 0, '2012-07-15 13:15:33'),
(6, '{"params":{"plugin":null,"controller":"categories","action":"admin_edit","named":[],"pass":["6"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/categories\\/edit\\/6","base":"","webroot":"\\/","here":"\\/admin\\/categories\\/edit\\/6","plugin":""}', 3, '127.0.0.1', '', 'categories', 'admin_edit', 6, '2012-07-15 13:31:54'),
(7, '{"params":{"plugin":null,"controller":"categories","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/categories","base":"","webroot":"\\/","here":"\\/admin\\/categories","plugin":""}', 3, '127.0.0.1', '', 'categories', 'admin_index', 0, '2012-07-15 13:31:58'),
(8, '{"params":{"plugin":null,"controller":"categories","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/categories","base":"","webroot":"\\/","here":"\\/admin\\/categories","plugin":""}', 3, '127.0.0.1', '', 'categories', 'admin_index', 0, '2012-07-15 13:41:21'),
(9, '{"params":{"plugin":null,"controller":"categories","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/categories","base":"","webroot":"\\/","here":"\\/admin\\/categories","plugin":""}', 3, '127.0.0.1', '', 'categories', 'admin_index', 0, '2012-07-15 13:54:11'),
(10, '{"params":{"plugin":null,"controller":"articles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles","base":"","webroot":"\\/","here":"\\/admin\\/articles","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 13:54:23'),
(11, '{"params":{"plugin":null,"controller":"Articles","action":"admin_add","named":[],"pass":["6"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Articles\\/add\\/6","base":"","webroot":"\\/","here":"\\/admin\\/Articles\\/add\\/6","plugin":""}', 3, '127.0.0.1', '', 'Articles', 'admin_add', 6, '2012-07-15 13:54:26'),
(12, '{"params":{"plugin":null,"controller":"categories","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/categories","base":"","webroot":"\\/","here":"\\/admin\\/categories","plugin":""}', 3, '127.0.0.1', '', 'categories', 'admin_index', 0, '2012-07-15 13:54:35'),
(13, '{"params":{"plugin":null,"controller":"Categories","action":"admin_add","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Categories\\/add","base":"","webroot":"\\/","here":"\\/admin\\/Categories\\/add","plugin":""}', 3, '127.0.0.1', '', 'Categories', 'admin_add', 0, '2012-07-15 13:54:36'),
(14, '{"params":{"plugin":null,"controller":"Categories","action":"admin_add","named":[],"pass":[],"prefix":"admin","admin":true},"data":{"_Token":{"key":"201dce3df7733dee86f9db858a39b554b375093e","fields":"6132597b70baf27cd2ab0bbfca45bc2eab2c9804%3ACategory.created","unlocked":""},"Category":{"title":"Games","created":"2012-07-15 13:54:36"}},"query":[],"url":"admin\\/Categories\\/add","base":"","webroot":"\\/","here":"\\/admin\\/Categories\\/add","plugin":""}', 3, '127.0.0.1', '', 'Categories', 'admin_add', 0, '2012-07-15 13:54:39'),
(15, '{"params":{"plugin":null,"controller":"Categories","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Categories","base":"","webroot":"\\/","here":"\\/admin\\/Categories","plugin":""}', 3, '127.0.0.1', '', 'Categories', 'admin_index', 0, '2012-07-15 13:54:39'),
(16, '{"params":{"plugin":null,"controller":"articles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles","base":"","webroot":"\\/","here":"\\/admin\\/articles","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 13:54:41'),
(17, '{"params":{"plugin":null,"controller":"fields","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/fields","base":"","webroot":"\\/","here":"\\/admin\\/fields","plugin":""}', 3, '127.0.0.1', '', 'fields', 'admin_index', 0, '2012-07-15 13:54:43'),
(18, '{"params":{"plugin":null,"controller":"fields","action":"admin_edit","named":[],"pass":["22"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/fields\\/edit\\/22","base":"","webroot":"\\/","here":"\\/admin\\/fields\\/edit\\/22","plugin":""}', 3, '127.0.0.1', '', 'fields', 'admin_edit', 22, '2012-07-15 13:54:51'),
(19, '{"params":{"plugin":null,"controller":"fields","action":"admin_edit","named":[],"pass":["22"],"prefix":"admin","admin":true},"data":{"_Token":{"key":"660c441110eb3d2b524bfe2cd0d0aeccc06ee82b","fields":"5924e7e6cf25bd2b36e861ecd414ac10c515761b%3AField.id","unlocked":""},"Field":{"title":"system","label":"system","category_id":["7"],"field_type":"dropdown","field_options":"PC,360,PS3,PSP,Wii","description":"","field_limit_min":"0","field_limit_max":"0","field_order":"0","required":"1","id":"22"}},"query":[],"url":"admin\\/fields\\/edit\\/22","base":"","webroot":"\\/","here":"\\/admin\\/fields\\/edit\\/22","plugin":""}', 3, '127.0.0.1', '', 'fields', 'admin_edit', 22, '2012-07-15 13:54:56'),
(20, '{"params":{"plugin":null,"controller":"fields","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/fields","base":"","webroot":"\\/","here":"\\/admin\\/fields","plugin":""}', 3, '127.0.0.1', '', 'fields', 'admin_index', 0, '2012-07-15 13:54:56'),
(21, '{"params":{"plugin":null,"controller":"articles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles","base":"","webroot":"\\/","here":"\\/admin\\/articles","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 13:54:58'),
(22, '{"params":{"plugin":null,"controller":"Articles","action":"admin_add","named":[],"pass":["6"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Articles\\/add\\/6","base":"","webroot":"\\/","here":"\\/admin\\/Articles\\/add\\/6","plugin":""}', 3, '127.0.0.1', '', 'Articles', 'admin_add', 6, '2012-07-15 13:55:01'),
(23, '{"params":{"plugin":null,"controller":"Articles","action":"admin_add","named":[],"pass":["6"],"prefix":"admin","admin":true},"data":{"_Token":{"key":"e712b1d28d691152eabd6d6460f055c68811e60e","fields":"e12d72b615921449029f511799ff4b12ef8ea0c4%3AArticle.category_id%7CArticle.created","unlocked":""},"Article":{"title":"The Amazing Spiderman","category_id":"6","created":"2012-07-15 13:55:01"}},"query":[],"url":"admin\\/Articles\\/add\\/6","base":"","webroot":"\\/","here":"\\/admin\\/Articles\\/add\\/6","plugin":""}', 3, '127.0.0.1', '', 'Articles', 'admin_add', 6, '2012-07-15 13:55:10'),
(24, '{"params":{"plugin":null,"controller":"Articles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Articles","base":"","webroot":"\\/","here":"\\/admin\\/Articles","plugin":""}', 3, '127.0.0.1', '', 'Articles', 'admin_index', 0, '2012-07-15 13:55:10'),
(25, '{"params":{"plugin":null,"controller":"Articles","action":"view","named":[],"pass":["the-amazing-spiderman"]},"data":[],"query":[],"url":"Articles\\/view\\/the-amazing-spiderman","base":"","webroot":"\\/","here":"\\/Articles\\/view\\/the-amazing-spiderman","plugin":""}', 3, '127.0.0.1', '', 'Articles', 'view', 0, '2012-07-15 13:55:14'),
(26, '{"params":{"plugin":null,"controller":"fields","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/fields","base":"","webroot":"\\/","here":"\\/admin\\/fields","plugin":""}', 3, '127.0.0.1', '', 'fields', 'admin_index', 0, '2012-07-15 14:15:17'),
(27, '{"params":{"plugin":null,"controller":"Fields","action":"admin_add","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Fields\\/add","base":"","webroot":"\\/","here":"\\/admin\\/Fields\\/add","plugin":""}', 3, '127.0.0.1', '', 'Fields', 'admin_add', 0, '2012-07-15 14:15:19'),
(28, '{"params":{"plugin":null,"controller":"Fields","action":"admin_add","named":[],"pass":[],"prefix":"admin","admin":true},"data":{"_Token":{"key":"f0246949e353f0c1d4a442044513836791758149","fields":"639f7092a78c059d173b4d4dfb0aba1aa7da4714%3AField.created","unlocked":""},"Field":{"title":"Release Year","label":"","category_id":["6"],"field_type":"num","field_options":"","description":"","field_limit_min":"4","field_limit_max":"4","field_order":"0","required":"1","created":"2012-07-15 14:15:19"}},"query":[],"url":"admin\\/Fields\\/add","base":"","webroot":"\\/","here":"\\/admin\\/Fields\\/add","plugin":""}', 3, '127.0.0.1', '', 'Fields', 'admin_add', 0, '2012-07-15 14:15:45'),
(29, '{"params":{"plugin":null,"controller":"Fields","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Fields","base":"","webroot":"\\/","here":"\\/admin\\/Fields","plugin":""}', 3, '127.0.0.1', '', 'Fields', 'admin_index', 0, '2012-07-15 14:15:45'),
(30, '{"params":{"plugin":null,"controller":"articles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles","base":"","webroot":"\\/","here":"\\/admin\\/articles","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 14:15:47'),
(31, '{"params":{"plugin":null,"controller":"articles","action":"admin_edit","named":[],"pass":["3"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles\\/edit\\/3","base":"","webroot":"\\/","here":"\\/admin\\/articles\\/edit\\/3","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_edit', 3, '2012-07-15 14:15:49'),
(32, '{"params":{"plugin":null,"controller":"articles","action":"admin_edit","named":[],"pass":["3"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles\\/edit\\/3","base":"","webroot":"\\/","here":"\\/admin\\/articles\\/edit\\/3","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_edit', 3, '2012-07-15 14:17:42'),
(33, '{"params":{"plugin":null,"controller":"articles","action":"admin_edit","named":[],"pass":["3"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles\\/edit\\/3","base":"","webroot":"\\/","here":"\\/admin\\/articles\\/edit\\/3","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_edit', 3, '2012-07-15 14:18:02'),
(34, '{"params":{"plugin":null,"controller":"articles","action":"admin_edit","named":[],"pass":["3"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles\\/edit\\/3","base":"","webroot":"\\/","here":"\\/admin\\/articles\\/edit\\/3","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_edit', 3, '2012-07-15 14:18:22'),
(35, '{"params":{"plugin":null,"controller":"articles","action":"admin_edit","named":[],"pass":["3"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles\\/edit\\/3","base":"","webroot":"\\/","here":"\\/admin\\/articles\\/edit\\/3","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_edit', 3, '2012-07-15 14:18:39'),
(36, '{"params":{"plugin":null,"controller":"articles","action":"admin_edit","named":[],"pass":["3"],"prefix":"admin","admin":true},"data":{"_Token":{"key":"7a83519d91f598276398ff12eb85ee43f633661e","fields":"797ffa02299d230ee22ef5f7fe21acfcdc391b4a%3AArticle.category_id%7CArticle.created%7CArticle.id%7CArticleValue.23.field_id","unlocked":""},"Article":{"title":"The Amazing Spiderman","category_id":"6","created":"2012-07-15 13:55:01","id":"3"},"ArticleValue":{"23":{"field_id":"23","data":"2012"}}},"query":[],"url":"admin\\/articles\\/edit\\/3","base":"","webroot":"\\/","here":"\\/admin\\/articles\\/edit\\/3","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_edit', 3, '2012-07-15 14:18:48'),
(37, '{"params":{"plugin":null,"controller":"articles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles","base":"","webroot":"\\/","here":"\\/admin\\/articles","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 14:18:49'),
(38, '{"params":{"plugin":null,"controller":"Articles","action":"admin_add","named":[],"pass":["6"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Articles\\/add\\/6","base":"","webroot":"\\/","here":"\\/admin\\/Articles\\/add\\/6","plugin":""}', 3, '127.0.0.1', '', 'Articles', 'admin_add', 6, '2012-07-15 14:18:52'),
(39, '{"params":{"plugin":null,"controller":"articles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles","base":"","webroot":"\\/","here":"\\/admin\\/articles","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 14:18:56'),
(40, '{"params":{"plugin":null,"controller":"articles","action":"admin_edit","named":[],"pass":["3"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles\\/edit\\/3","base":"","webroot":"\\/","here":"\\/admin\\/articles\\/edit\\/3","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_edit', 3, '2012-07-15 14:18:58'),
(41, '{"params":{"plugin":null,"controller":"articles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles","base":"","webroot":"\\/","here":"\\/admin\\/articles","plugin":""}', 3, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 14:19:00'),
(42, '{"params":{"plugin":null,"controller":"pages","action":"display","named":[],"pass":["admin"]},"data":[],"query":[],"url":"admin","base":"","webroot":"\\/","here":"\\/admin","plugin":""}', 3, '127.0.0.1', '', 'pages', 'display', 0, '2012-07-15 15:11:39'),
(43, '{"params":{"plugin":null,"controller":"users","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/users","base":"","webroot":"\\/","here":"\\/admin\\/users","plugin":""}', 3, '127.0.0.1', '', 'users', 'admin_index', 0, '2012-07-15 15:11:42'),
(44, '{"params":{"plugin":null,"controller":"Users","action":"admin_add","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Users\\/add","base":"","webroot":"\\/","here":"\\/admin\\/Users\\/add","plugin":""}', 3, '127.0.0.1', '', 'Users', 'admin_add', 0, '2012-07-15 15:11:44'),
(45, '{"params":{"plugin":null,"controller":"Users","action":"admin_add","named":[],"pass":[],"prefix":"admin","admin":true},"data":{"_Token":{"key":"a3cbb0d36b8043cefbec06f5f25d116eba893af3","fields":"099c651347bbe983c21928015afde0071df924f7%3AUser.created%7CUser.status","unlocked":""},"User":{"username":"admin","password":"fusion","password_confirm":"fusion","email":"charliepage88@gmail.com","role_id":"1","status":"1","created":"2012-07-15 15:11:45"}},"query":[],"url":"admin\\/Users\\/add","base":"","webroot":"\\/","here":"\\/admin\\/Users\\/add","plugin":""}', 3, '127.0.0.1', '', 'Users', 'admin_add', 0, '2012-07-15 15:12:18'),
(46, '{"params":{"plugin":null,"controller":"Users","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Users","base":"","webroot":"\\/","here":"\\/admin\\/Users","plugin":""}', 3, '127.0.0.1', '', 'Users', 'admin_index', 0, '2012-07-15 15:12:18'),
(47, '{"params":{"plugin":null,"controller":"Users","action":"admin_add","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Users\\/add","base":"","webroot":"\\/","here":"\\/admin\\/Users\\/add","plugin":""}', 3, '127.0.0.1', '', 'Users', 'admin_add', 0, '2012-07-15 15:12:21'),
(48, '{"params":{"plugin":null,"controller":"users","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/users","base":"","webroot":"\\/","here":"\\/admin\\/users","plugin":""}', 3, '127.0.0.1', '', 'users', 'admin_index', 0, '2012-07-15 15:12:23'),
(49, '{"params":{"plugin":null,"controller":"Users","action":"admin_add","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Users\\/add","base":"","webroot":"\\/","here":"\\/admin\\/Users\\/add","plugin":""}', 3, '127.0.0.1', '', 'Users', 'admin_add', 0, '2012-07-15 15:12:25'),
(50, '{"params":{"plugin":null,"controller":"Users","action":"admin_add","named":[],"pass":[],"prefix":"admin","admin":true},"data":{"_Token":{"key":"a66aafed26d6d06b6b62eb22fc8c81175ee65199","fields":"69f380d7f4275509de86b1739d26f2c0d647ed47%3AUser.created%7CUser.status","unlocked":""},"User":{"username":"admin","password":"fusion","password_confirm":"fusion","email":"charliepage88@gmail.com","role_id":"1","status":"1","created":"2012-07-15 15:12:25"}},"query":[],"url":"admin\\/Users\\/add","base":"","webroot":"\\/","here":"\\/admin\\/Users\\/add","plugin":""}', 3, '127.0.0.1', '', 'Users', 'admin_add', 0, '2012-07-15 15:12:32'),
(51, '{"params":{"plugin":null,"controller":"Users","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/Users","base":"","webroot":"\\/","here":"\\/admin\\/Users","plugin":""}', 3, '127.0.0.1', '', 'Users', 'admin_index', 0, '2012-07-15 15:12:37'),
(52, '{"params":{"plugin":null,"controller":"roles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/roles","base":"","webroot":"\\/","here":"\\/admin\\/roles","plugin":""}', 3, '127.0.0.1', '', 'roles', 'admin_index', 0, '2012-07-15 15:12:55'),
(53, '{"params":{"plugin":null,"controller":"roles","action":"admin_edit","named":[],"pass":["1"],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/roles\\/edit\\/1","base":"","webroot":"\\/","here":"\\/admin\\/roles\\/edit\\/1","plugin":""}', 3, '127.0.0.1', '', 'roles', 'admin_edit', 1, '2012-07-15 15:12:58'),
(54, '{"params":{"plugin":null,"controller":"roles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/roles","base":"","webroot":"\\/","here":"\\/admin\\/roles","plugin":""}', 3, '127.0.0.1', '', 'roles', 'admin_index', 0, '2012-07-15 15:13:09'),
(55, '{"params":{"plugin":null,"controller":"users","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/users","base":"","webroot":"\\/","here":"\\/admin\\/users","plugin":""}', 3, '127.0.0.1', '', 'users', 'admin_index', 0, '2012-07-15 15:13:14'),
(56, '{"params":{"plugin":null,"controller":"users","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/users","base":"","webroot":"\\/","here":"\\/admin\\/users","plugin":""}', 3, '127.0.0.1', '', 'users', 'admin_index', 0, '2012-07-15 15:13:34'),
(57, '{"params":{"plugin":null,"controller":"pages","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/pages","base":"","webroot":"\\/","here":"\\/admin\\/pages","plugin":""}', 3, '127.0.0.1', '', 'pages', 'admin_index', 0, '2012-07-15 15:15:02'),
(58, '{"params":{"plugin":null,"controller":"pages","action":"display","named":[],"pass":["home"]},"data":[],"query":[],"url":false,"base":"","webroot":"\\/","here":"\\/"}', 3, '127.0.0.1', NULL, 'pages', 'display', 0, '2012-07-15 15:16:07'),
(59, '{"params":{"plugin":null,"controller":"pages","action":"display","named":[],"pass":["admin"]},"data":[],"query":[],"url":"admin","base":"","webroot":"\\/","here":"\\/admin","plugin":""}', 3, '127.0.0.1', '', 'pages', 'display', 0, '2012-07-15 15:16:11'),
(60, '{"params":{"plugin":null,"controller":"Users","action":"logout","named":[],"pass":[]},"data":[],"query":[],"url":"Users\\/logout","base":"","webroot":"\\/","here":"\\/Users\\/logout"}', 3, '127.0.0.1', NULL, 'Users', 'logout', 0, '2012-07-15 15:16:28'),
(61, '{"params":{"plugin":null,"controller":"pages","action":"display","named":[],"pass":["admin"]},"data":[],"query":[],"url":"admin","base":"","webroot":"\\/","here":"\\/admin","plugin":""}', 7, '127.0.0.1', '', 'pages', 'display', 0, '2012-07-15 15:16:39'),
(62, '{"params":{"plugin":null,"controller":"pages","action":"display","named":[],"pass":["home"]},"data":[],"query":[],"url":false,"base":"","webroot":"\\/","here":"\\/"}', 7, '127.0.0.1', NULL, 'pages', 'display', 0, '2012-07-15 15:17:04'),
(63, '{"params":{"plugin":null,"controller":"pages","action":"display","named":[],"pass":["admin"]},"data":[],"query":[],"url":"admin","base":"","webroot":"\\/","here":"\\/admin","plugin":""}', 7, '127.0.0.1', '', 'pages', 'display', 0, '2012-07-15 15:17:06'),
(64, '{"params":{"plugin":null,"controller":"articles","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/articles","base":"","webroot":"\\/","here":"\\/admin\\/articles","plugin":""}', 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 15:17:08'),
(65, '{"params":{"plugin":null,"controller":"users","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/users","base":"","webroot":"\\/","here":"\\/admin\\/users","plugin":""}', 7, '127.0.0.1', '', 'users', 'admin_index', 0, '2012-07-15 15:17:10'),
(66, '{"params":{"plugin":null,"controller":"users","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/users","base":"","webroot":"\\/","here":"\\/admin\\/users","plugin":""}', 7, '127.0.0.1', '', 'users', 'admin_index', 0, '2012-07-15 15:18:31'),
(67, '{"params":{"plugin":null,"controller":"users","action":"admin_index","named":[],"pass":[],"prefix":"admin","admin":true},"data":[],"query":[],"url":"admin\\/users","base":"","webroot":"\\/","here":"\\/admin\\/users","plugin":""}', 7, '127.0.0.1', '', 'users', 'admin_index', 0, '2012-07-15 15:18:44'),
(68, NULL, 7, '127.0.0.1', '', 'users', 'admin_index', 0, '2012-07-15 15:19:01'),
(69, NULL, 7, '127.0.0.1', '', 'settings', 'admin_index', 0, '2012-07-15 15:19:08'),
(70, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 15:36:02'),
(71, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 15:38:17'),
(72, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 15:40:39'),
(73, NULL, 7, '127.0.0.1', '', 'roles', 'admin_index', 0, '2012-07-15 15:40:41'),
(74, NULL, 7, '127.0.0.1', '', 'roles', 'admin_edit', 1, '2012-07-15 15:40:43'),
(75, NULL, 7, '127.0.0.1', '', 'permissions', 'admin_add', 0, '2012-07-15 15:41:03'),
(76, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-07-15 15:41:03'),
(77, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-07-15 15:41:15'),
(78, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-07-15 15:41:15'),
(79, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-07-15 15:41:27'),
(80, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-07-15 15:41:27'),
(81, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-07-15 15:41:43'),
(82, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-07-15 15:41:43'),
(83, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-07-15 15:41:54'),
(84, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-07-15 15:41:54'),
(85, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:43:09'),
(86, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:43:20'),
(87, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:44:50'),
(88, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:45:05'),
(89, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:45:46'),
(90, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:45:58'),
(91, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:47:52'),
(92, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:48:12'),
(93, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:48:22'),
(94, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:48:43'),
(95, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:49:00'),
(96, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:50:36'),
(97, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:50:53'),
(98, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:51:28'),
(99, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:51:47'),
(100, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:52:02'),
(101, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:53:45'),
(102, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:54:11'),
(103, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 15:54:37'),
(104, NULL, 7, '127.0.0.1', '', 'roles', 'admin_index', 0, '2012-07-15 15:54:49'),
(105, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_add', 0, '2012-07-15 15:54:51'),
(106, NULL, 7, '127.0.0.1', '', 'roles', 'admin_edit', 1, '2012-07-15 15:54:54'),
(107, NULL, 7, '127.0.0.1', '', 'permissions', 'admin_add', 0, '2012-07-15 15:55:05'),
(108, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-07-15 15:55:05'),
(109, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-07-15 15:55:48'),
(110, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-07-15 15:55:48'),
(111, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-07-15 15:56:00'),
(112, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-07-15 15:56:00'),
(113, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-07-15 16:14:47'),
(114, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-07-15 16:14:47'),
(115, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 16:15:03'),
(116, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 16:16:37'),
(117, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 16:17:11'),
(118, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 16:17:18'),
(119, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 16:17:26'),
(120, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 16:20:04'),
(121, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-15 16:54:54'),
(122, NULL, 7, '127.0.0.1', '', 'Articles', 'admin_add', 6, '2012-07-15 16:54:58'),
(123, NULL, 7, '127.0.0.1', '', 'Articles', 'admin_add', 6, '2012-07-15 16:55:05'),
(124, NULL, 7, '127.0.0.1', '', 'Articles', 'admin_index', 0, '2012-07-15 16:55:05'),
(125, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 16:55:07'),
(126, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:18:39'),
(127, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:23:06'),
(128, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:23:50'),
(129, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:29:53'),
(130, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:30:59'),
(131, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:31:30'),
(132, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:31:53'),
(133, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:32:24'),
(134, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:32:44'),
(135, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:33:10'),
(136, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:46:56'),
(137, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:47:08'),
(138, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:49:59'),
(139, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:52:24'),
(140, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:52:37'),
(141, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:53:04'),
(142, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:53:37'),
(143, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:53:53'),
(144, NULL, 7, '127.0.0.1', NULL, 'users', 'ajax_check_user', 0, '2012-07-15 17:56:54'),
(145, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 17:57:03'),
(146, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 17:57:42'),
(147, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 17:57:45'),
(148, NULL, 7, '127.0.0.1', '', 'settings', 'admin_index', 0, '2012-07-15 18:03:02'),
(149, NULL, 7, '127.0.0.1', '', 'Settings', 'admin_add', 0, '2012-07-15 18:03:05'),
(150, NULL, 7, '127.0.0.1', '', 'Settings', 'admin_add', 0, '2012-07-15 18:03:09'),
(151, NULL, 7, '127.0.0.1', '', 'Settings', 'admin_index', 0, '2012-07-15 18:03:09'),
(152, NULL, 7, '127.0.0.1', '', 'Settings', 'admin_edit', 2, '2012-07-15 18:03:11'),
(153, NULL, 7, '127.0.0.1', '', 'setting_values', 'admin_add', 0, '2012-07-15 18:03:36'),
(154, NULL, 7, '127.0.0.1', '', 'Settings', 'admin_edit', 2, '2012-07-15 18:03:36'),
(155, NULL, 7, '127.0.0.1', '', 'settings', 'admin_index', 0, '2012-07-15 18:03:40'),
(156, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:03:44'),
(157, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:05:50'),
(158, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:06:14'),
(159, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:06:43'),
(160, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:06:57'),
(161, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:07:07'),
(162, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:13:06'),
(163, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:13:16'),
(164, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:14:09'),
(165, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:14:18'),
(166, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:14:46'),
(167, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:15:35'),
(168, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:15:36'),
(169, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:15:49'),
(170, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:15:51'),
(171, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:15:58'),
(172, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:15:59'),
(173, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:16:11'),
(174, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:16:13'),
(175, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:16:21'),
(176, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:16:22'),
(177, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:17:11'),
(178, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:17:13'),
(179, NULL, 7, '127.0.0.1', '', 'pages', 'admin_index', 0, '2012-07-15 18:20:38'),
(180, NULL, 7, '127.0.0.1', '', 'pages', 'admin_edit', 4, '2012-07-15 18:20:40'),
(181, NULL, 7, '127.0.0.1', '', 'pages', 'admin_edit', 4, '2012-07-15 18:20:42'),
(182, NULL, 7, '127.0.0.1', '', 'pages', 'admin_index', 0, '2012-07-15 18:20:42'),
(183, NULL, 7, '127.0.0.1', '', 'pages', 'admin_index', 0, '2012-07-15 18:23:01'),
(184, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:23:02'),
(185, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:23:06'),
(186, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:23:40'),
(187, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:23:53'),
(188, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:24:05'),
(189, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:24:08'),
(190, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:24:20'),
(191, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:25:13'),
(192, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:25:15'),
(193, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:25:26'),
(194, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:25:39'),
(195, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:25:41'),
(196, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:25:44'),
(197, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:25:47'),
(198, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:25:57'),
(199, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:25:59'),
(200, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:26:02'),
(201, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:26:04'),
(202, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:26:21'),
(203, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:27:12'),
(204, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:27:16'),
(205, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:27:28'),
(206, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:27:30'),
(207, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:28:20'),
(208, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:28:23'),
(209, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:28:32'),
(210, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:28:40'),
(211, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:28:43'),
(212, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:28:51'),
(213, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:28:53'),
(214, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:28:54'),
(215, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:28:59'),
(216, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:30:14'),
(217, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:30:41'),
(218, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:30:50'),
(219, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:31:07'),
(220, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:31:40'),
(221, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:31:44'),
(222, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:31:53'),
(223, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:31:56'),
(224, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:32:07'),
(225, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:32:09'),
(226, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:32:12'),
(227, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:32:15'),
(228, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:32:17'),
(229, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:32:19'),
(230, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:32:23'),
(231, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:32:25'),
(232, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:33:08'),
(233, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:33:11'),
(234, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:33:13'),
(235, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:33:16'),
(236, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:33:17'),
(237, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:33:19'),
(238, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:33:22'),
(239, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:33:27'),
(240, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:33:47'),
(241, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:33:50'),
(242, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:33:52'),
(243, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:34:02'),
(244, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:34:05'),
(245, NULL, 7, '127.0.0.1', '', 'settings', 'admin_index', 0, '2012-07-15 18:41:55'),
(246, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:41:59'),
(247, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_update', 0, '2012-07-15 18:42:37'),
(248, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:42:43'),
(249, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 18:47:00'),
(250, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 18:52:29'),
(251, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 18:55:34'),
(252, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 18:55:50'),
(253, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 18:56:03'),
(254, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 18:57:44'),
(255, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 18:58:02'),
(256, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 18:58:16'),
(257, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 18:58:22'),
(258, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 18:58:29'),
(259, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 18:59:55'),
(260, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:00:21'),
(261, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:01:29'),
(262, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 19:03:12'),
(263, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 19:04:35'),
(264, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 19:04:58'),
(265, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 19:05:10'),
(266, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 19:06:02'),
(267, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:06:04'),
(268, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:20:35'),
(269, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:21:03'),
(270, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:47:20'),
(271, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:47:40'),
(272, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:47:53'),
(273, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:47:58'),
(274, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:48:38'),
(275, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:48:49'),
(276, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:50:17'),
(277, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 19:50:45'),
(278, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:00:31'),
(279, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:04:08'),
(280, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:04:20'),
(281, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:04:33'),
(282, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:05:06'),
(283, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:05:16'),
(284, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:15:40'),
(285, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:17:32'),
(286, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:27:01'),
(287, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:29:02'),
(288, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:29:23'),
(289, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:29:42'),
(290, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:30:01'),
(291, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:38:40'),
(292, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:38:48'),
(293, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:38:57'),
(294, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:39:16'),
(295, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:39:34'),
(296, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:39:49'),
(297, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:45:01'),
(298, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:45:13'),
(299, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:45:41'),
(300, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:47:41'),
(301, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:48:45'),
(302, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 20:49:24'),
(303, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 21:15:20'),
(304, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 21:17:23'),
(305, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 21:17:52'),
(306, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 21:21:40'),
(307, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 21:21:41'),
(308, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 2, '2012-07-15 21:23:29'),
(309, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 2, '2012-07-15 21:24:34'),
(310, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 2, '2012-07-15 21:29:02'),
(311, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 2, '2012-07-15 21:30:15'),
(312, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 2, '2012-07-15 21:30:29'),
(313, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 2, '2012-07-15 21:30:41'),
(314, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 2, '2012-07-15 21:31:14'),
(315, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 2, '2012-07-15 21:31:26'),
(316, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 21:32:13'),
(317, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 21:32:36'),
(318, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 21:32:37'),
(319, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 3, '2012-07-15 21:32:41'),
(320, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 3, '2012-07-15 21:40:15'),
(321, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 3, '2012-07-15 21:40:39'),
(322, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 2, '2012-07-15 21:40:54'),
(323, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 3, '2012-07-15 21:41:02'),
(324, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 3, '2012-07-15 21:45:08'),
(325, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 3, '2012-07-15 21:51:33'),
(326, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 21:51:33'),
(327, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 3, '2012-07-15 21:51:47'),
(328, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 3, '2012-07-15 21:52:00'),
(329, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 21:52:00'),
(330, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 3, '2012-07-15 21:52:03'),
(331, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 21:52:08'),
(332, NULL, 7, '127.0.0.1', '', 'templates', 'admin_delete', 3, '2012-07-15 21:52:41'),
(333, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 21:52:41'),
(334, NULL, 7, '127.0.0.1', '', 'templates', 'admin_delete', 2, '2012-07-15 21:53:16'),
(335, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 21:53:17'),
(336, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 21:53:44'),
(337, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 21:53:55'),
(338, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 21:53:55'),
(339, NULL, 7, '127.0.0.1', '', 'templates', 'admin_delete', 4, '2012-07-15 21:54:00'),
(340, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 21:54:01'),
(341, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 21:54:12'),
(342, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 21:54:22'),
(343, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 21:54:22'),
(344, NULL, 7, '127.0.0.1', '', 'templates', 'admin_delete', 5, '2012-07-15 21:56:53'),
(345, NULL, 7, '127.0.0.1', '', 'templates', 'admin_delete', 5, '2012-07-15 21:58:48'),
(346, NULL, 7, '127.0.0.1', '', 'templates', 'admin_delete', 5, '2012-07-15 21:58:53'),
(347, NULL, 7, '127.0.0.1', '', 'templates', 'admin_delete', 5, '2012-07-15 21:59:44'),
(348, NULL, 7, '127.0.0.1', '', 'templates', 'admin_delete', 5, '2012-07-15 22:00:16'),
(349, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 22:00:17'),
(350, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 22:21:44'),
(351, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 22:21:49'),
(352, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 22:35:16'),
(353, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 22:59:44'),
(354, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:02:00'),
(355, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:02:14'),
(356, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:03:05'),
(357, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:03:07'),
(358, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:07:28'),
(359, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:07:37'),
(360, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:08:28'),
(361, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:09:54'),
(362, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:13:13'),
(363, NULL, 7, '127.0.0.1', '', 'pages', 'display', 0, '2012-07-15 23:13:45'),
(364, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:13:47'),
(365, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:13:59'),
(366, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:14:04'),
(367, NULL, 7, '127.0.0.1', '', 'pages', 'display', 0, '2012-07-15 23:14:38'),
(368, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:14:40'),
(369, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 23:14:44'),
(370, NULL, 7, '127.0.0.1', '', 'templates', 'admin_add', 0, '2012-07-15 23:15:00'),
(371, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:15:00'),
(372, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 22, '2012-07-15 23:15:51'),
(373, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:15:51'),
(374, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:15:53'),
(375, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:16:34'),
(376, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:16:35'),
(377, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 23, '2012-07-15 23:16:40'),
(378, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:16:40'),
(379, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:16:54'),
(380, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:16:56'),
(381, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:16:56');
INSERT INTO `logs` (`id`, `data`, `user_id`, `ip_address`, `plugin`, `controller`, `action`, `action_id`, `date`) VALUES
(382, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 24, '2012-07-15 23:17:10'),
(383, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:17:10'),
(384, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:17:17'),
(385, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:17:20'),
(386, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:17:20'),
(387, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:17:32'),
(388, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:17:49'),
(389, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:19:31'),
(390, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:19:39'),
(391, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:19:51'),
(392, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:19:56'),
(393, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:20:01'),
(394, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:20:15'),
(395, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:20:34'),
(396, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:20:42'),
(397, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:20:47'),
(398, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 25, '2012-07-15 23:20:54'),
(399, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:20:55'),
(400, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:22:10'),
(401, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:22:13'),
(402, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:22:13'),
(403, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:23:06'),
(404, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:23:09'),
(405, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:23:14'),
(406, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:23:44'),
(407, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:23:44'),
(408, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:23:54'),
(409, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:23:56'),
(410, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:23:56'),
(411, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 27, '2012-07-15 23:24:36'),
(412, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:24:36'),
(413, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 26, '2012-07-15 23:24:39'),
(414, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:24:39'),
(415, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 28, '2012-07-15 23:24:41'),
(416, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:24:41'),
(417, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:25:50'),
(418, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:25:55'),
(419, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:25:55'),
(420, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:31:09'),
(421, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 29, '2012-07-15 23:31:11'),
(422, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 29, '2012-07-15 23:31:21'),
(423, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 29, '2012-07-15 23:31:27'),
(424, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:31:27'),
(425, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 29, '2012-07-15 23:31:38'),
(426, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 29, '2012-07-15 23:32:11'),
(427, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:32:11'),
(428, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 29, '2012-07-15 23:32:51'),
(429, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 29, '2012-07-15 23:32:55'),
(430, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 29, '2012-07-15 23:33:19'),
(431, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 29, '2012-07-15 23:33:23'),
(432, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:33:23'),
(433, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 29, '2012-07-15 23:33:32'),
(434, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:33:32'),
(435, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:33:39'),
(436, NULL, 7, '127.0.0.1', '', 'themes', 'admin_add', 0, '2012-07-15 23:33:42'),
(437, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-07-15 23:33:42'),
(438, NULL, 7, '127.0.0.1', '', 'pages', 'display', 0, '2012-07-16 19:20:49'),
(439, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-07-16 19:20:51'),
(440, NULL, 7, '127.0.0.1', '', 'pages', 'display', 0, '2012-08-01 21:15:43'),
(441, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-08-01 21:15:45'),
(442, NULL, 7, '127.0.0.1', '', 'settings', 'admin_index', 0, '2012-08-01 21:41:21'),
(443, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-08-01 21:51:49'),
(444, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-08-01 21:56:13'),
(445, NULL, 7, '127.0.0.1', '', 'articles', 'admin_edit', 4, '2012-08-01 22:00:07'),
(446, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-08-01 22:25:33'),
(447, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-01 22:32:11'),
(448, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 2, '2012-08-01 22:32:13'),
(449, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 6, '2012-08-01 22:32:16'),
(450, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-01 22:35:06'),
(451, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 1, '2012-08-01 22:35:15'),
(452, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-01 22:45:09'),
(453, NULL, 7, '127.0.0.1', '', 'themes', 'admin_edit', 30, '2012-08-01 22:50:50'),
(454, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-01 23:10:51'),
(455, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-01 23:10:53'),
(456, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-01 23:11:20'),
(457, NULL, 7, '127.0.0.1', NULL, 'pages', 'display', 0, '2012-08-01 23:14:08'),
(458, NULL, 7, '127.0.0.1', NULL, 'pages', 'display', 0, '2012-08-01 23:16:42'),
(459, NULL, 7, '127.0.0.1', '', 'pages', 'display', 0, '2012-08-01 23:16:47'),
(460, NULL, 7, '127.0.0.1', '', 'pages', 'display', 0, '2012-08-03 20:13:53'),
(461, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:14:00'),
(462, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:14:03'),
(463, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:14:06'),
(464, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:14:08'),
(465, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:14:10'),
(466, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:47:57'),
(467, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:48:20'),
(468, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:48:22'),
(469, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:48:24'),
(470, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:50:21'),
(471, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:50:23'),
(472, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:50:26'),
(473, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:50:29'),
(474, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:51:00'),
(475, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:51:02'),
(476, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:51:19'),
(477, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:52:10'),
(478, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:52:13'),
(479, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:52:14'),
(480, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:52:16'),
(481, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:52:30'),
(482, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:52:47'),
(483, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:52:49'),
(484, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:53:16'),
(485, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:55:05'),
(486, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:55:07'),
(487, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:55:28'),
(488, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:55:31'),
(489, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:55:33'),
(490, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:55:42'),
(491, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:58:42'),
(492, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:58:44'),
(493, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:58:45'),
(494, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:58:56'),
(495, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 20:59:12'),
(496, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:59:14'),
(497, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:59:16'),
(498, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 20:59:18'),
(499, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:00:25'),
(500, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:00:49'),
(501, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:01:00'),
(502, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:01:21'),
(503, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:01:23'),
(504, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:01:24'),
(505, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:01:32'),
(506, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:01:33'),
(507, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:02:08'),
(508, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:02:10'),
(509, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:02:11'),
(510, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:02:12'),
(511, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:02:20'),
(512, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:02:22'),
(513, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:02:24'),
(514, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:03:30'),
(515, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:03:32'),
(516, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:03:34'),
(517, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:03:36'),
(518, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:03:37'),
(519, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:03:39'),
(520, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:03:41'),
(521, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:03:51'),
(522, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:03:55'),
(523, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:03:57'),
(524, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:04:00'),
(525, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:06:33'),
(526, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:06:35'),
(527, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:06:55'),
(528, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:06:57'),
(529, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:06:58'),
(530, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:06:59'),
(531, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:07:49'),
(532, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:08:14'),
(533, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:08:16'),
(534, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:08:17'),
(535, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:08:17'),
(536, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:08:20'),
(537, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:08:28'),
(538, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:09:21'),
(539, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:09:25'),
(540, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:09:28'),
(541, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:09:29'),
(542, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:09:30'),
(543, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:09:33'),
(544, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:13:52'),
(545, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:13:53'),
(546, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:13:55'),
(547, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:13:56'),
(548, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:14:41'),
(549, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:14:43'),
(550, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:14:55'),
(551, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:22:39'),
(552, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:22:46'),
(553, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:22:48'),
(554, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:23:47'),
(555, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:23:48'),
(556, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:24:30'),
(557, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:24:32'),
(558, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:24:42'),
(559, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:24:43'),
(560, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:25:09'),
(561, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:25:11'),
(562, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:25:23'),
(563, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:25:25'),
(564, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:25:29'),
(565, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:25:56'),
(566, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:25:58'),
(567, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:26:37'),
(568, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:26:39'),
(569, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:27:52'),
(570, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:27:54'),
(571, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:29:12'),
(572, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:29:13'),
(573, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:29:24'),
(574, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:29:26'),
(575, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:29:33'),
(576, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:29:34'),
(577, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:29:45'),
(578, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:32:41'),
(579, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:33:09'),
(580, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:33:12'),
(581, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:34:11'),
(582, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:34:18'),
(583, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:36:22'),
(584, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:36:26'),
(585, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:36:41'),
(586, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:36:43'),
(587, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:38:00'),
(588, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:38:02'),
(589, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:38:25'),
(590, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:38:28'),
(591, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:38:43'),
(592, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:39:25'),
(593, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:39:27'),
(594, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:39:29'),
(595, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:39:45'),
(596, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:40:00'),
(597, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:40:20'),
(598, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:40:24'),
(599, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:40:33'),
(600, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:40:35'),
(601, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:41:01'),
(602, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:41:07'),
(603, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:41:15'),
(604, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:41:18'),
(605, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:41:21'),
(606, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:41:25'),
(607, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:41:55'),
(608, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:43:20'),
(609, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:43:25'),
(610, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:43:30'),
(611, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:43:38'),
(612, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:43:39'),
(613, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:43:40'),
(614, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:43:45'),
(615, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:43:47'),
(616, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:44:00'),
(617, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:44:02'),
(618, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:44:04'),
(619, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:44:09'),
(620, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:44:12'),
(621, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:44:18'),
(622, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:44:20'),
(623, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:44:22'),
(624, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:44:23'),
(625, NULL, 7, '127.0.0.1', 'support_ticket', 'tickets', 'index', 0, '2012-08-03 21:44:28'),
(626, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:44:32'),
(627, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:49:45'),
(628, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:49:50'),
(629, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:49:58'),
(630, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:50:15'),
(631, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:50:19'),
(632, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:50:25'),
(633, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 21:50:30'),
(634, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:50:39'),
(635, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:52:22'),
(636, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:52:45'),
(637, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:53:23'),
(638, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:53:34'),
(639, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 21:53:42'),
(640, NULL, 7, '127.0.0.1', '', 'templates', 'admin_edit', 28, '2012-08-03 21:53:55'),
(641, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 22:00:27'),
(642, NULL, 7, '127.0.0.1', NULL, 'templates', 'ajax_theme_refresh', 0, '2012-08-03 22:06:57'),
(643, NULL, 7, '127.0.0.1', '', 'themes', 'admin_delete', 30, '2012-08-03 22:07:06'),
(644, NULL, 7, '127.0.0.1', '', 'templates', 'admin_index', 0, '2012-08-03 22:07:06'),
(645, NULL, 7, '127.0.0.1', '', 'roles', 'admin_index', 0, '2012-08-03 22:07:29'),
(646, NULL, 7, '127.0.0.1', '', 'settings', 'admin_index', 0, '2012-08-03 22:07:31'),
(647, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-08-03 22:07:33'),
(648, NULL, 7, '127.0.0.1', '', 'articles', 'admin_edit', 4, '2012-08-03 22:07:36'),
(649, NULL, 7, '127.0.0.1', '', 'Articles', 'view', 0, '2012-08-03 22:07:41'),
(650, NULL, 7, '127.0.0.1', '', 'articles', 'admin_index', 0, '2012-08-03 22:27:06'),
(651, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 22:54:55'),
(652, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 22:54:57'),
(653, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 22:55:17'),
(654, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 22:56:19'),
(655, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 22:56:36'),
(656, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 9, '2012-08-03 22:56:38'),
(657, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 22:56:38'),
(658, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 22:56:40'),
(659, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 22:56:48'),
(660, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 22:56:59'),
(661, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 22:57:00'),
(662, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 22:57:01'),
(663, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 22:57:02'),
(664, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 22:57:09'),
(665, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 22:57:09'),
(666, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 22:58:55'),
(667, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 22:59:48'),
(668, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:00:09'),
(669, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:01:29'),
(670, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:02:29'),
(671, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:02:40'),
(672, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:03:11'),
(673, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:03:56'),
(674, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:04:29'),
(675, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:04:39'),
(676, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:07:44'),
(677, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:07:54'),
(678, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 10, '2012-08-03 23:08:35'),
(679, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:08:36'),
(680, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 23:08:37'),
(681, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 23:08:43'),
(682, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:08:43'),
(683, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 11, '2012-08-03 23:09:31'),
(684, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:09:31'),
(685, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 23:09:32'),
(686, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 23:09:42'),
(687, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:09:43'),
(688, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 23:10:01'),
(689, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 23:10:16'),
(690, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:10:16'),
(691, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:10:38'),
(692, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 13, '2012-08-03 23:10:41'),
(693, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:10:41'),
(694, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 23:10:42'),
(695, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:10:44'),
(696, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 12, '2012-08-03 23:10:51'),
(697, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:10:51'),
(698, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:10:58'),
(699, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 23:11:02'),
(700, NULL, 7, '127.0.0.1', '', 'files', 'admin_add', 0, '2012-08-03 23:11:08'),
(701, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:11:09'),
(702, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:12:28'),
(703, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 14, '2012-08-03 23:12:32'),
(704, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 14, '2012-08-03 23:12:55'),
(705, NULL, 7, '127.0.0.1', '', 'files', 'admin_edit', 8, '2012-08-03 23:14:40'),
(706, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 14, '2012-08-03 23:14:59'),
(707, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:17:06'),
(708, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:17:09'),
(709, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:17:57'),
(710, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:18:14'),
(711, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:18:23'),
(712, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:18:33'),
(713, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:18:42'),
(714, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:18:48'),
(715, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:19:03'),
(716, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:19:11'),
(717, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:19:25'),
(718, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:20:09'),
(719, NULL, 7, '127.0.0.1', '', 'files', 'admin_delete', 8, '2012-08-03 23:20:16'),
(720, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:20:16'),
(721, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:20:35'),
(722, NULL, 7, '127.0.0.1', '', 'files', 'admin_edit', 14, '2012-08-03 23:20:40'),
(723, NULL, 7, '127.0.0.1', '', 'files', 'admin_edit', 14, '2012-08-03 23:21:17'),
(724, NULL, 7, '127.0.0.1', '', 'files', 'admin_edit', 14, '2012-08-03 23:22:00'),
(725, NULL, 7, '127.0.0.1', '', 'files', 'admin_edit', 14, '2012-08-03 23:22:29'),
(726, NULL, 7, '127.0.0.1', '', 'files', 'admin_edit', 14, '2012-08-03 23:22:44'),
(727, NULL, 7, '127.0.0.1', '', 'files', 'admin_edit', 14, '2012-08-03 23:22:57'),
(728, NULL, 7, '127.0.0.1', '', 'files', 'admin_edit', 14, '2012-08-03 23:25:22'),
(729, NULL, 7, '127.0.0.1', '', 'files', 'admin_edit', 14, '2012-08-03 23:25:32'),
(730, NULL, 7, '127.0.0.1', '', 'files', 'admin_index', 0, '2012-08-03 23:25:33'),
(731, NULL, 7, '127.0.0.1', '', 'pages', 'display', 0, '2012-08-04 15:50:04'),
(732, NULL, 7, '127.0.0.1', '', 'roles', 'admin_index', 0, '2012-08-04 15:50:11'),
(733, NULL, 7, '127.0.0.1', '', 'roles', 'admin_edit', 1, '2012-08-04 15:50:12'),
(734, NULL, 7, '127.0.0.1', '', 'permissions', 'admin_add', 0, '2012-08-04 15:50:25'),
(735, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-08-04 15:50:25'),
(736, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-08-04 15:50:39'),
(737, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-08-04 15:50:39'),
(738, NULL, 7, '127.0.0.1', '', 'modules', 'admin_index', 0, '2012-08-04 15:50:42'),
(739, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-08-04 15:51:06'),
(740, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-08-04 15:51:06'),
(741, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-08-04 15:51:22'),
(742, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-08-04 15:51:22'),
(743, NULL, 7, '127.0.0.1', '', 'permission_values', 'admin_add', 0, '2012-08-04 15:51:32'),
(744, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-08-04 15:51:32'),
(745, NULL, 7, '127.0.0.1', '', 'Roles', 'admin_edit', 1, '2012-08-04 15:55:50'),
(746, NULL, 7, '127.0.0.1', '', 'modules', 'admin_index', 0, '2012-08-04 15:55:54');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `box_slug` varchar(100) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text,
  `sender_user_id` int(11) DEFAULT '0',
  `receiver_user_id` int(11) DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `templates` longtext NOT NULL,
  `settings` longtext NOT NULL,
  `view` longtext NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `content` text,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `created`, `modified`, `deleted_time`) VALUES
(1, 'just a pageas', 'just_a_pageas', '<p>ok here is content!</p>', '2012-06-05 23:28:03', '2012-06-10 18:36:17', '0000-00-00 00:00:00'),
(2, 'test', 'test', 'adsasd', '2012-06-05 23:38:20', '2012-06-05 23:38:23', '0000-00-00 00:00:00'),
(3, 'what about ! this?', 'what_about_this', '', '2012-06-05 23:38:29', '2012-06-05 23:38:37', '0000-00-00 00:00:00'),
(4, 'Contact Us', 'contact-us', '<p>hey send me an email yo</p>', '2012-07-12 22:36:28', '2012-07-15 18:20:42', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `plugin` varchar(50) DEFAULT NULL,
  `controller` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `title`, `role_id`, `plugin`, `controller`) VALUES
(6, 'Admin Roles', 1, NULL, 'roles'),
(8, 'Admin Users', 1, NULL, 'users'),
(17, 'Admin Articles', 1, NULL, ''),
(18, 'Support Ticket', 1, NULL, ''),
(19, 'Admin Categories', 1, NULL, ''),
(20, 'Admin Fields', 1, NULL, ''),
(21, 'Admin Pages', 1, NULL, ''),
(22, 'Admin Settings', 1, NULL, ''),
(23, 'Admin Polls', 1, NULL, ''),
(24, 'Admin Roles', 3, '', 'roles'),
(25, 'Admin Users', 3, '', 'users'),
(26, 'Admin Articles', 3, '', ''),
(27, 'Support Ticket', 3, '', ''),
(28, 'Admin Categories', 3, '', ''),
(29, 'Admin Fields', 3, '', ''),
(30, 'Admin Pages', 3, '', ''),
(31, 'Admin Settings', 3, '', ''),
(32, 'Admin Polls', 3, '', ''),
(33, 'Admin Roles', 4, '', 'roles'),
(34, 'Admin Users', 4, '', 'users'),
(35, 'Admin Articles', 4, '', ''),
(36, 'Support Ticket', 4, '', ''),
(37, 'Admin Categories', 4, '', ''),
(38, 'Admin Fields', 4, '', ''),
(39, 'Admin Pages', 4, '', ''),
(40, 'Admin Settings', 4, '', ''),
(41, 'Admin Polls', 4, '', ''),
(42, 'Polls Plugin', 1, NULL, ''),
(43, 'Admin Files', 1, NULL, ''),
(44, 'Templates', 1, NULL, ''),
(45, 'Themes', 1, NULL, ''),
(46, 'Modules', 1, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `permission_values`
--

CREATE TABLE IF NOT EXISTS `permission_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `action_id` int(11) NOT NULL DEFAULT '0',
  `plugin` varchar(50) NOT NULL,
  `controller` varchar(50) NOT NULL,
  `pageAction` varchar(50) NOT NULL,
  `action` int(3) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;

--
-- Dumping data for table `permission_values`
--

INSERT INTO `permission_values` (`id`, `title`, `permission_id`, `role_id`, `action_id`, `plugin`, `controller`, `pageAction`, `action`, `type`) VALUES
(1, 'admin', 8, 1, 0, '', 'users', 'admin_index', 1, 'default'),
(2, 'Index', 6, 1, 0, '', 'roles', 'admin_index', 1, 'default'),
(3, 'Add', 6, 1, 0, '', 'roles', 'admin_add', 1, 'default'),
(4, 'Edit', 6, 1, 0, '', 'roles', 'admin_edit', 1, 'default'),
(5, 'Delete', 6, 1, 0, '', 'roles', 'admin_delete', 1, 'default'),
(10, 'add', 8, 1, 0, '', 'permissions', 'admin_add', 1, 'default'),
(11, 'add', 8, 1, 0, '', 'permission_values', 'admin_add', 1, 'default'),
(12, 'index', 17, 1, 0, '', 'articles', 'admin_index', 1, 'default'),
(13, 'index', 18, 1, 0, 'support_ticket', 'tickets', 'index', 1, 'default'),
(14, 'add', 17, 1, 0, '', 'articles', 'admin_add', 1, 'default'),
(15, 'edit', 17, 1, 0, '', 'articles', 'admin_edit', 1, 'default'),
(16, 'index', 19, 1, 0, '', 'categories', 'admin_index', 1, 'default'),
(17, 'index', 20, 1, 0, '', 'fields', 'admin_index', 1, 'default'),
(18, 'index', 21, 1, 0, '', 'pages', 'admin_index', 1, 'default'),
(19, 'index', 22, 1, 0, '', 'settings', 'admin_index', 1, 'default'),
(20, 'index', 23, 1, 0, '', 'polls', 'admin_index', 1, 'default'),
(21, 'edit', 21, 1, 0, '', 'pages', 'admin_edit', 1, 'default'),
(22, 'add', 21, 1, 0, '', 'pages', 'admin_add', 1, 'default'),
(23, 'delete', 21, 1, 0, '', 'pages', 'admin_delete', 1, 'default'),
(24, 'add', 22, 1, 0, '', 'settings', 'admin_add', 1, 'default'),
(25, 'edit', 22, 1, 0, '', 'settings', 'admin_edit', 1, 'default'),
(26, 'delete', 22, 1, 0, '', 'settings', 'admin_delete', 1, 'default'),
(27, 'add val', 22, 1, 0, '', 'setting_values', 'admin_add', 1, 'default'),
(28, 'edit val', 22, 1, 0, '', 'setting_values', 'admin_edit', 1, 'default'),
(29, 'add', 20, 1, 0, '', 'fields', 'admin_add', 1, 'default'),
(30, 'edit', 20, 1, 0, '', 'fields', 'admin_edit', 1, 'default'),
(31, '', 18, 1, 0, 'support_ticket', 'tickets', 'add', 1, 'default'),
(32, 'view', 17, 1, 0, '', 'articles', 'view', 1, 'default'),
(33, '', 8, 1, 0, '', 'users', 'admin_add', 1, 'default'),
(34, '', 8, 1, 0, '', 'users', 'admin_edit', 1, 'default'),
(35, 'index', 42, 1, 0, 'polls', 'polls', 'admin_index', 1, 'default'),
(36, 'add', 42, 1, 0, 'polls', 'polls', 'admin_add', 1, 'default'),
(37, 'delete', 42, 1, 0, 'polls', 'polls', 'admin_delete', 1, 'default'),
(38, 'edit', 42, 1, 0, 'polls', 'polls', 'admin_edit', 1, 'default'),
(39, 'add', 19, 1, 0, '', 'categories', 'admin_add', 1, 'default'),
(40, 'edit', 19, 1, 0, '', 'categories', 'admin_edit', 1, 'default'),
(41, 'view', 19, 1, 0, '', 'categories', 'view', 1, 'default'),
(42, 'display', 21, 1, 0, '', 'pages', 'display', 1, 'default'),
(43, 'index', 43, 1, 0, '', 'files', 'admin_index', 1, 'default'),
(44, 'add', 43, 1, 0, '', 'files', 'admin_add', 1, 'default'),
(45, 'edit', 43, 1, 0, '', 'files', 'admin_edit', 1, 'default'),
(46, 'delete', 43, 1, 0, '', 'files', 'admin_delete', 1, 'default'),
(47, 'index', 44, 1, 0, '', 'templates', 'admin_index', 1, 'default'),
(48, 'add', 44, 1, 0, '', 'templates', 'admin_add', 1, 'default'),
(49, 'edit', 44, 1, 0, '', 'templates', 'admin_edit', 1, 'default'),
(50, 'delete', 44, 1, 0, '', 'templates', 'admin_delete', 1, 'default'),
(51, 'add', 45, 1, 0, '', 'themes', 'admin_add', 1, 'default'),
(52, 'edit', 45, 1, 0, '', 'themes', 'admin_edit', 1, 'default'),
(53, 'delete', 45, 1, 0, '', 'themes', 'admin_delete', 1, 'default'),
(54, 'index', 46, 1, 0, '', 'modules', 'admin_index', 1, 'default'),
(55, 'edit', 46, 1, 0, '', 'modules', 'admin_edit', 1, 'default'),
(56, 'add', 46, 1, 0, '', 'modules', 'admin_add', 1, 'default'),
(57, 'delete', 46, 1, 0, '', 'modules', 'admin_delete', 1, 'default'),
(58, 'Step Two', 46, 1, 0, '', 'modules', 'admin_step_two', 1, 'default'),
(59, 'Step Three', 46, 1, 0, '', 'modules', 'admin_step_three', 1, 'default');

-- --------------------------------------------------------

--
-- Table structure for table `plugins`
--

CREATE TABLE IF NOT EXISTS `plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `description` text,
  `version` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `author_url` varchar(255) DEFAULT NULL,
  `plugin_url` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_support_resources`
--

CREATE TABLE IF NOT EXISTS `plugin_support_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `plugin_support_tickets`
--

CREATE TABLE IF NOT EXISTS `plugin_support_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `send_user_id` int(11) NOT NULL,
  `reply_user_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `priority` varchar(50) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE IF NOT EXISTS `polls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `poll_type` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`id`, `article_id`, `title`, `poll_type`, `created`, `modified`, `deleted_time`) VALUES
(5, NULL, 'Your favorite sport?', NULL, '2012-07-07 21:51:38', '2012-07-08 20:57:15', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `poll_values`
--

CREATE TABLE IF NOT EXISTS `poll_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `poll_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `poll_values`
--

INSERT INTO `poll_values` (`id`, `title`, `poll_id`) VALUES
(7, 'NBA', 5),
(8, 'NHL', 5),
(9, 'PGA', 5),
(11, 'NFL', 5);

-- --------------------------------------------------------

--
-- Table structure for table `poll_voting_values`
--

CREATE TABLE IF NOT EXISTS `poll_voting_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) DEFAULT '0',
  `option_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `related_articles`
--

CREATE TABLE IF NOT EXISTS `related_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id_1` int(11) NOT NULL DEFAULT '0',
  `article_id_2` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `created`, `modified`, `deleted_time`) VALUES
(1, 'admin', '0000-00-00 00:00:00', '2012-06-24 22:50:05', '0000-00-00 00:00:00'),
(3, 'member', '2012-06-30 15:42:06', '2012-06-30 15:42:09', '0000-00-00 00:00:00'),
(4, 'guest', '2012-06-30 21:42:36', '2012-06-30 21:42:39', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `deleted_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `title`, `created`, `deleted_time`) VALUES
(1, 'Site Info', '2012-06-27 22:38:24', '0000-00-00 00:00:00'),
(2, 'Appearance', '2012-07-15 18:03:05', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `setting_values`
--

CREATE TABLE IF NOT EXISTS `setting_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `data` text,
  `setting_type` varchar(255) DEFAULT NULL,
  `setting_id` int(11) DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `setting_values`
--

INSERT INTO `setting_values` (`id`, `title`, `description`, `data`, `setting_type`, `setting_id`, `created`, `modified`, `deleted_time`) VALUES
(1, 'sitename', '<p>What do you think? A site...name</p>', 'Alpha', NULL, 1, '2012-06-27 22:55:11', '2012-06-27 23:30:01', '0000-00-00 00:00:00'),
(2, 'Webmaster Email', '<p>email DUMMY - okay, sorry</p>', 'charliepage88@gmail.com', NULL, 1, '2012-06-27 23:07:12', '2012-06-27 23:30:01', '0000-00-00 00:00:00'),
(5, 'default-theme', '', '1', NULL, 2, '2012-07-15 18:03:11', '2012-07-15 18:42:37', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE IF NOT EXISTS `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `theme_id` int(11) DEFAULT '0',
  `template` text,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=70 ;

--
-- Dumping data for table `templates`
--

INSERT INTO `templates` (`id`, `title`, `location`, `theme_id`, `template`, `created`, `modified`, `deleted_time`) VALUES
(14, 'Home Pages', 'Themed/Movie/Pages/home.ctp', 2, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(13, 'Display Pages', 'Themed/Movie/Pages/display.ctp', 2, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(12, 'Default Layouts', 'Themed/Movie/Layouts/default.ctp', 2, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(11, 'View Articles', 'Themed/Movie/Articles/view.ctp', 2, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(15, 'Admin Add Articles', 'Articles/admin_add.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(16, 'Admin Edit Articles', 'Articles/admin_edit.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(17, 'Admin Index Articles', 'Articles/admin_index.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(18, 'View Articles', 'Articles/view.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(19, 'Admin Add Categories', 'Categories/admin_add.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(20, 'Admin Edit Categories', 'Categories/admin_edit.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(21, 'Admin Index Categories', 'Categories/admin_index.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(22, 'View Categories', 'Categories/view.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(23, 'Plugins Sidebar Elements', 'Elements/plugins_sidebar.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(24, 'Sidebar Elements', 'Elements/sidebar.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(25, 'Default html', 'Emails/html/default.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(26, 'Default text', 'Emails/text/default.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(27, 'Error400 Errors', 'Errors/error400.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(28, 'Error500 Errors', 'Errors/error500.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(29, 'Admin Add Fields', 'Fields/admin_add.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(30, 'Admin Edit Fields', 'Fields/admin_edit.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(31, 'Admin Index Fields', 'Fields/admin_index.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(32, 'Admin Add Files', 'Files/admin_add.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(33, 'Admin Edit Files', 'Files/admin_edit.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(34, 'Admin Index Files', 'Files/admin_index.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(35, 'Html Emails', 'Layouts/Emails/html', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(36, 'Text Emails', 'Layouts/Emails/text', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(37, 'Admin Layouts', 'Layouts/admin.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(38, 'Ajax Layouts', 'Layouts/ajax.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(39, 'Default Layouts', 'Layouts/default.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(40, 'Error Layouts', 'Layouts/error.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(41, 'Flash Layouts', 'Layouts/flash.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(42, 'Default js', 'Layouts/js/default.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(43, 'Default rss', 'Layouts/rss/default.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(44, 'Default xml', 'Layouts/xml/default.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(45, 'Admin Index Modules', 'Modules/admin_index.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(46, 'Admin Pages', 'Pages/admin.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(47, 'Admin Add Pages', 'Pages/admin_add.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(48, 'Admin Edit Pages', 'Pages/admin_edit.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(49, 'Admin Index Pages', 'Pages/admin_index.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(50, 'Denied Pages', 'Pages/denied.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(51, 'Display Pages', 'Pages/display.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(52, 'Home Pages', 'Pages/home.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(53, 'Admin Add Roles', 'Roles/admin_add.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(54, 'Admin Edit Roles', 'Roles/admin_edit.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(55, 'Admin Index Roles', 'Roles/admin_index.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(56, 'Admin Add Settings', 'Settings/admin_add.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(57, 'Admin Edit Settings', 'Settings/admin_edit.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(58, 'Admin Index Settings', 'Settings/admin_index.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(59, 'Admin Add Templates', 'Templates/admin_add.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(60, 'Admin Edit Templates', 'Templates/admin_edit.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(61, 'Admin Index Templates', 'Templates/admin_index.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(62, 'Admin Add Themes', 'Themes/admin_add.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(63, 'Admin Edit Themes', 'Themes/admin_edit.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(64, 'Admin Add Users', 'Users/admin_add.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(65, 'Admin Edit Users', 'Users/admin_edit.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(66, 'Admin Index Users', 'Users/admin_index.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(67, 'Ajax Check User Users', 'Users/ajax_check_user.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(68, 'Login Users', 'Users/login.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(69, 'Register Users', 'Users/register.ctp', 1, NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `title`, `created`, `modified`, `deleted_time`) VALUES
(2, 'Movie', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(1, 'Default', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role_id` int(11) DEFAULT '0',
  `login_time` varchar(255) DEFAULT NULL,
  `status` int(3) DEFAULT '0',
  `theme_id` int(11) DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role_id`, `login_time`, `status`, `theme_id`, `created`, `modified`, `deleted_time`) VALUES
(4, 'guest', 'f6fda68a7e08e3b9c3fccf24ed666097a8c2676d', 'guest@google.com', 4, NULL, 0, 0, '2012-07-01 15:30:08', '2012-07-07 16:52:44', '0000-00-00 00:00:00'),
(3, 'test', '768daef57eff3aad40216ab7330200fe58dc428a', NULL, 1, '2012-07-15 15:11:39', 1, 0, '2012-06-13 21:30:21', '2012-07-15 15:11:39', '0000-00-00 00:00:00'),
(7, 'admin', 'c823d0a95cdf74ccfc6cc083ecfc3196c462ab16', 'charliepage88@gmail.com', 1, '2012-08-05 20:08:56', 1, 0, '2012-07-15 15:11:45', '2012-08-05 20:08:56', '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
