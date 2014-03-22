<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>AdaptCMS <?= ADAPTCMS_VERSION ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<?= $this->Html->script('html5.min.js') ?>
	<![endif]-->

	<?= $this->Html->meta('favicon.ico', '/img/favicon.ico', array('type' => 'icon')) ?>
	<link rel="apple-touch-icon" href="<?= $this->webroot ?>img/apple-touch-icon.png" />

	<?php echo $this->Html->script("jquery.min") ?>
	<?php echo $this->Html->script("jquery.validate.min") ?>
	<?php echo $this->Html->script("bootstrap.min") ?>
	<?= $this->Html->script("vendor/noty/jquery.noty.packaged.min.js") ?>
	<?= $this->Html->script("vendor/noty/themes/default.js") ?>
	<?php echo $this->Html->script("global") ?>

	<?php echo $this->fetch("meta") ?>
	<?php echo $this->fetch("css") ?>
	<?php echo $this->fetch("script") ?>

	<?php echo $this->AutoLoadJS->getJs() ?>
	<?php echo $this->AutoLoadJS->getCss() ?>

	<?= $this->Html->css("bootstrap-admin.min") ?>
	<?= $this->Html->css("bootstrap-responsive.min") ?>
	<?= $this->Html->css("font-awesome.min") ?>

	<div class="hidden">{{ headers }}</div>
</head>

<body>

<div class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">AdaptCMS</a>
		</div>
		<div class="navbar-collapse collapse navbar-responsive-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="<?= $this->webroot ?>">Home</a></li>
				<li><a href="<?= $this->webroot ?>admin">Admin</a></li>
				<li>
					<?=
					$this->Html->link('Documentation', Configure::read('Component.Api.adaptcms_docs_url'), array(
						'target' => '_blank'
					)) ?>
				</li>
				<li>
					<?=
					$this->Html->link('Help', Configure::read('Component.Api.adaptcms_url') . 'support', array(
							'target' => '_blank'
						)) ?>
				</li>
			</ul>
			<div class="nav navbar-nav btn-group pull-right">
				<?php echo $this->fetch('admin_header_top_right') ?>
				<a class="btn btn-primary navbar-btn dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-user"></i> <?= $username ?>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<?=
						$this->Html->link('<i class="fa fa-user"></i> Profile',
							array(
								'admin' => false,
								'plugin' => false,
								'controller' => 'users',
								'action' => 'profile',
								$username
							), array('escape' => false)
						) ?>
					</li>
					<li class="divider"></li>
					<li><?=
						$this->Html->link('<i class="fa fa-sign-out"></i> Sign Out', array(
								'plugin' => false,
								'admin' => false,
								'controller' => 'users',
								'action' => 'logout'
							), array('escape' => false)
						) ?></li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</div>
</div>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-3 left-menu">
			<div class="well sidebar-nav" style="padding:0">
				<div class="panel-group" id="admin-sidebar-accordion">
					<div class="panel">
						<div class="panel-heading">
							<a class="accordion-toggle" data-toggle="collapse"
							   data-parent="#admin-sidebar-accordion" href="#content">
								<h3 class="success panel-title">
									<i class="fa fa-pencil fa-lg"></i> Content
								</h3>
							</a>
						</div>
						<?php $options = array('articles', 'categories', 'fields', 'pages', 'field_types', 'comments') ?>
						<div id="content" class="panel-collapse collapse<?= (in_array($this->params->controller, $options) ? ' in' : '') ?>">
							<div class="panel-body">
								<ul class="nav nav-list">
									<li><?= $this->Html->link('Articles', array('admin' => true, 'plugin' => false, 'controller' => 'articles', 'action' => 'index')) ?></li>
									<li><?= $this->Html->link('Comments', array('admin' => true, 'plugin' => false, 'controller' => 'comments', 'action' => 'index')) ?></li>
									<li><?= $this->Html->link('Categories', array('admin' => true, 'plugin' => false, 'controller' => 'categories', 'action' => 'index')) ?></li>
									<li><?= $this->Html->link('Fields', array('admin' => true, 'plugin' => false, 'controller' => 'fields', 'action' => 'index')) ?></li>
									<!-- <li><?= $this->Html->link('Field Types', array('admin' => true, 'plugin' => false, 'controller' => 'field_types', 'action' => 'index')) ?></li> -->
									<li><?= $this->Html->link('Pages', array('admin' => true, 'plugin' => false, 'controller' => 'pages', 'action' => 'index')) ?></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="panel">
						<div class="panel-heading">
							<a class="accordion-toggle" data-toggle="collapse"
							   data-parent="#admin-sidebar-accordion" href="#users">
								<h3 class="primary">
									<i class="fa fa-group fa-lg"></i> Users
								</h3>
							</a>
						</div>
						<?php $options = array('users', 'roles') ?>
						<div id="users" class="panel-collapse collapse<?= (in_array($this->params->controller, $options) ? ' in' : '') ?>">
							<ul class="nav nav-list panel-body">
								<li><?= $this->Html->link('Users', array('admin' => true, 'plugin' => false, 'controller' => 'users', 'action' => 'index')) ?></li>
								<li><?= $this->Html->link('Roles', array('admin' => true, 'plugin' => false, 'controller' => 'roles', 'action' => 'index')) ?></li>
							</ul>
						</div>
					</div>
					<div class="panel">
						<div class="panel-heading">
							<a class="accordion-toggle" data-toggle="collapse"
							   data-parent="#admin-sidebar-accordion" href="#media">
								<h3 class="warning">
									<i class="fa fa-picture-o fa-lg"></i> Media
								</h3>
							</a>
						</div>
						<?php $options = array('files', 'media') ?>
						<div id="media" class="panel-collapse collapse<?= (in_array($this->params->controller, $options) ? ' in' : '') ?>">
							<ul class="nav nav-list panel-body">
								<li><?= $this->Html->link('Files', array('admin' => true, 'plugin' => false, 'controller' => 'files', 'action' => 'index')) ?></li>
								<li><?= $this->Html->link('Media Libraries', array('admin' => true, 'plugin' => false, 'controller' => 'media', 'action' => 'index')) ?></li>
							</ul>
						</div>
					</div>
					<div class="panel">
						<div class="panel-heading">
							<a class="accordion-toggle" data-toggle="collapse"
							   data-parent="#admin-sidebar-accordion" href="#system">
								<h3 class="inverse">
									<i class="fa fa-cogs fa-lg"></i> System
								</h3>
							</a>
						</div>
						<?php $options = array('themes', 'templates', 'blocks', 'settings', 'tools', 'menus', 'cron') ?>
						<div id="system" class="panel-collapse collapse<?= (in_array($this->params->controller, $options) ? ' in' : '') ?>">
							<ul class="nav nav-list panel-body">
								<li><?= $this->Html->link('Appearance', array('admin' => true, 'plugin' => false, 'controller' => 'templates', 'action' => 'index')) ?></li>
								<li><?= $this->Html->link('Create a Theme', array('admin' => true, 'plugin' => false, 'controller' => 'tools', 'action' => 'create_theme')) ?></li>
								<li><?= $this->Html->link('Global Template Tags', array('admin' => true, 'plugin' => false, 'controller' => 'templates', 'action' => 'global_tags')) ?></li>
								<li><?= $this->Html->link('Blocks', array('admin' => true, 'plugin' => false, 'controller' => 'blocks', 'action' => 'index')) ?></li>
								<li><?= $this->Html->link('Menus', array('admin' => true, 'plugin' => false, 'controller' => 'menus', 'action' => 'index')) ?></li>
								<li><?= $this->Html->link('Settings', array('admin' => true, 'plugin' => false, 'controller' => 'settings', 'action' => 'index')) ?></li>
								<li><?= $this->Html->link('Tools', array('admin' => true, 'plugin' => false, 'controller' => 'tools', 'action' => 'index')) ?></li>
							</ul>
						</div>
					</div>
					<div class="panel">
						<div class="panel-heading">
							<a class="accordion-toggle" data-toggle="collapse"
							   data-parent="#admin-sidebar-accordion" href="#plugins">
								<h3 class="danger">
									<i class="fa fa-cloud fa-lg"></i> Plugins
								</h3>
							</a>
						</div>
						<div id="plugins" class="panel-collapse collapse<?= ($this->params->controller == 'plugins' || !empty($this->params->plugin) ? ' in' : '') ?>">
							<ul class="nav nav-list panel-body">
								<li><?= $this->Html->link('Manage Plugins', array('admin' => true, 'plugin' => false, 'controller' => 'plugins', 'action' => 'index')) ?></li>
								<li><?= $this->Html->link('Create a Plugin', array('admin' => true, 'plugin' => false, 'controller' => 'tools', 'action' => 'create_plugin')) ?></li>
								<?php foreach (Configure::read('Plugins.list') as $plugin): ?>
									<?php if (Configure::read($plugin . '.admin_menu')): ?>
										<li><?=
											$this->Html->link(
												(Configure::read($plugin . '.admin_menu_label') ? Configure::read($plugin . '.admin_menu_label') : $plugin),
												Configure::read($plugin . '.admin_menu')) ?>
										</li>
									<?php endif ?>
								<?php endforeach ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!--/.well -->
		</div>
		<!--/span-->
		<div class="col-lg-9 content-area">
			<?=
			$this->Html->getCrumbList(array(
				'class' => 'breadcrumb',
				'escape' => false,
				'lastClass' => 'active'
			), 'Home') ?>

			<!--nocache-->
			<?= $this->Session->flash() ?>
			<!--/nocache-->

			<?= $this->fetch('content') ?>

		</div>
		<!--/span-->
	</div>
	<!--/row-->

	<footer>
		<p class="pull-left">
			Powered by
			<?= $this->Html->link('AdaptCMS ', 'http://www.adaptcms.com', array('target' => '_blank')) ?>
			<?= $this->Api->version_check() ?>
		</p>

		<p class="pull-right">&copy; <a href="http://www.adaptcms.com" target="_blank">AdaptCMS</a> 2006-<?= date('Y') ?>
			<span class="hidden-xs">
				Cosmo theme
				by <?= $this->Html->link('Bootswatch', 'http://bootswatch.com/cosmo/', array('target' => '_blank')) ?>
			</span>
		</p>
	</footer>

</div>
<!--/.fluid-container-->

<div id="webroot" style="display:none"><?= $this->request->webroot ?></div></body>
</html>