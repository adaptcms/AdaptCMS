<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', null) ?>

<h1>Tools</h1>

<div class="well">
	<div class="span7">
		<h4>
			<?= $this->Html->link('Create a Plugin', array(
				'action' => 'create_plugin'
			)) ?> <span class="badge badge-important">NEW IN 3.0.3</span>
		</h4>

		<p>
			With this interactive page, you can easily create a plugin and configure it without having to leave the admin panel. Skeleton files are provided to help getting started quick and easy.
		</p>
	</div>
	<div class="clearfix"></div>

	<div class="span7 no-marg-left">
		<h4>
			<?= $this->Html->link('Create a Theme', array(
				'action' => 'create_theme'
			)) ?> <span class="badge badge-important">NEW IN 3.0.3</span>
		</h4>

		<p>
			With this interactive page, you can easily create a theme and configure it without having to leave the admin panel. A base theme is provided by default, to help getting started quick and easy.
		</p>
	</div>
	<div class="clearfix"></div>

	<div class="span7 no-marg-left">
		<h4>
			<?= $this->Html->link('Feeds', array(
				'action' => 'feeds'
			)) ?> <span class="badge badge-important">NEW IN 3.0.3</span>
		</h4>

		<p>
			You can see a list of RSS feeds by category on this page.
		</p>
	</div>
	<div class="clearfix"></div>

	<div class="span7 no-marg-left">
		<h4>
			<?= $this->Html->link('Routes List', array(
				'action' => 'routes_list'
			)) ?>
		</h4>

		<p>
			This page will list all named routes you can use and how to use them in your templates.
		</p>
	</div>
	<div class="clearfix"></div>

	<div class="span7 no-marg-left">
		<h4>
			<?= $this->Html->link('Cron', array(
				'controller' => 'cron'
			)) ?>
		</h4>

		<p>
			This is an advanced tool, with it you can setup 'Cron Jobs'. Essentially running an item a certain amount, for example, clearing the cache once a week, regenerating a sitemap, etc.
		</p>
	</div>
	<div class="clearfix"></div>

	<div class="span7 no-marg-left">
		<h4>
			<?= $this->Html->link('Clear Cache', array(
				'action' => 'clear_cache'
			)) ?>
		</h4>

		<p>
			Run this quick tool to clear all cache - this affects API cache, view files, internal files and templates.
		</p>
	</div>
	<div class="clearfix"></div>

	<div class="span7 no-marg-left">
		<h4>
			<?= $this->Html->link('Optimize Database', array(
				'action' => 'optimize_database'
			)) ?>
		</h4>

		<p>
			This nifty tool will run through all the database tables in the entered database and look for any in need of repair, attempt repair and optimize any that need it.
		</p>
	</div>
	<div class="clearfix"></div>

	<div class="span7 no-marg-left">
		<h4>
			Converters
		</h4>

		<p>
			The below converters will convert data from those CMS's into your AdaptCMS install. Please note that the CMS you are converting from must be in the same database as AdaptCMS.
		</p>

		<ul>
			<li>
				<?= $this->Html->link('Wordpress 3.5', array(
					'action' => 'convert_wordpress'
				)) ?>
			</li>
			<li>
				<?= $this->Html->link('AdaptCMS 2.x', array(
					'action' => 'convert_adaptcms'
				)) ?>
			</li>
			<li>
				<?= $this->Html->link('OneCMS 2.6.4', array(
					'action' => 'convert_onecms'
				)) ?>
			</li>
		</ul>
	</div>
	<div class="clearfix"></div>

</div>