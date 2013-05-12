ALTER TABLE  `{prefix}plugin_poll_voting_values` CHANGE  `plugin_poll_value_id`  `plugin_value_id` INT( 11 ) NULL DEFAULT  '0'
-- --------------------------------------------------------
ALTER TABLE  `{prefix}fields` ADD  `module_id` INT( 11 ) NOT NULL DEFAULT  '0' AFTER  `category_id`
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
UPDATE  `alpha`.`{prefix}modules` SET  `is_fields` =  '1' WHERE  `{prefix}modules`.`model_title` = 'User';
-- --------------------------------------------------------
INSERT INTO `{prefix}permissions` (`id`, `module_id`, `role_id`, `action_id`, `plugin`, `controller`, `action`, `status`, `related`, `own`, `any`) VALUES
(null, 12, 1, 0, '', 'settings', 'admin_restore', 0, NULL, 2, 2),
(null, 12, 4, 0, '', 'settings', 'admin_restore', 0, NULL, 2, 2);
-- --------------------------------------------------------
ALTER TABLE  `{prefix}blocks` ADD  `data` LONGTEXT NULL AFTER  `settings`
-- --------------------------------------------------------
INSERT INTO `{prefix}pages` (`id`, `title`, `slug`, `content`, `user_id`, `created`, `modified`, `deleted_time`) VALUES
(null, 'home', 'home', '<?php if (!empty($articles)): ?>\r\n	<?php foreach($articles as $article): ?>\r\n		<div class="span8 no-marg-left">\r\n			<?= $this->Html->link(''<h2>'' . $article[''Article''][''title''] . ''</h2>'', array(\r\n				''controller'' => ''articles'', \r\n				''action'' => ''view'', \r\n            	''slug'' => $article[''Article''][''slug''],\r\n            	''id'' => $article[''Article''][''id'']\r\n			), array(''escape'' => false)) ?>\r\n\r\n			<p class="lead">\r\n				@ <em><?= $this->Admin->time($article[''Article''][''created'']) ?></em>\r\n			</p>\r\n\r\n			<blockquote><?= $this->Field->getTextAreaData($article) ?></blockquote>\r\n\r\n			<div id="post-options">\r\n		        <span class="pull-left">\r\n		            <?= $this->Html->link(''Read More'', array(\r\n		            	''controller'' => ''articles'', \r\n		            	''action'' => ''view'', \r\n		            	''slug'' => $article[''Article''][''slug''],\r\n		            	''id'' => $article[''Article''][''id'']\r\n	            	), array(''class'' => ''btn btn-primary'')) ?>\r\n		            <span style="margin-left: 10px">\r\n		                <i class="icon icon-comment"></i>&nbsp;\r\n		                <?= $this->Html->link($article[''Comments''] . '' Comments'', array(\r\n	                		''controller'' => ''articles'', \r\n	                		''action'' => ''view'', \r\n			            	''slug'' => $article[''Article''][''slug''],\r\n			            	''id'' => $article[''Article''][''id'']\r\n                		)) ?>\r\n		            </span>\r\n		            <span style="margin-left: 10px">\r\n		                <i class="icon-user"></i>&nbsp;\r\n		                Posted by <?= $this->Html->link($article[''User''][''username''], array(''controller'' => ''users'', ''action'' => ''profile'', $article[''User''][''username''])) ?>\r\n		            </span>\r\n		        </span>\r\n		        <span class="pull-right">\r\n		        	<?php if (!empty($article[''Article''][''tags''])): ?>\r\n			            <?php foreach($article[''Article''][''tags''] as $tag): ?>\r\n			                <?= $this->Html->link(''<span class="btn btn-success">''.$tag.''</span>'', array(''controller'' => ''articles'', ''action'' => ''tag'', $tag), array(''class'' => ''tags'', ''escape'' => false)) ?>\r\n			            <?php endforeach ?>\r\n		        	<?php endif ?>\r\n		        </span>\r\n		    </div>\r\n		</div>\r\n\r\n		<div class="clearfix"></div>\r\n\r\n		<hr>\r\n	<?php endforeach ?>\r\n<?php endif ?>\r\n\r\n<?= $this->element(''admin_pagination'') ?>', 1, '{date}', '{date}', '0000-00-00 00:00:00');
-- --------------------------------------------------------
ALTER TABLE  `{prefix}templates` ADD  `label` VARCHAR( 255 ) NULL AFTER  `title`
-- --------------------------------------------------------
UPDATE `{prefix}roles` SET defaults = 'default-admin' WHERE title = 'admin'
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET  `related` =  '[{"action":["view"],"controller":["articles"]}]' WHERE  `beta_permissions`.`id` =192;
-- --------------------------------------------------------
UPDATE `{prefix}permissions` SET  `related` =  '[{"action":["view"],"controller":["articles"]}]' WHERE  `beta_permissions`.`id` =172;
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