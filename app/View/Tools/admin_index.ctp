<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', null) ?>

<h1>Tools</h1>

<div class="well">
	<div class="span7">
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
			Run this quick tool to clear all cache - this affects API cache, view files and caches queries.
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
		</ul>
	</div>
	<div class="clearfix"></div>

</div>