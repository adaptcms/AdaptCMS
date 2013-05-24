<!DOCTYPE html>
<html lang="en" ng-app>
  <head>
    <meta charset="utf-8">
    <title>AdaptCMS <?= ADAPTCMS_VERSION ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <?= $this->Html->css("bootstrap-responsive.min.css") ?>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <?= $this->Html->script('html5.min.js') ?>
    <![endif]-->
    <!--[if lt IE 8]>
      <?= $this->Html->css("font-awesome-ie7.min.css") ?>
    <![endif]-->

  <?php
    echo $this->Html->meta('icon');

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
  ?>
    <?= $this->Html->script('jquery.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <?= $this->Html->script('jquery.validate.min.js') ?>

    <?php if ($this->params->action != "admin_index"): ?>
      <?= $this->Html->script('angular.min.js') ?>
    <?php endif ?>

    <?= $this->Html->css("bootstrap-admin.min") ?>
    <?= $this->Html->css("font-awesome.min.css") ?>

    <?= $this->Html->script('global.js') ?>

    <?= $this->AutoLoadJS->getJs() ?>
    <?= $this->AutoLoadJS->getCss() ?>
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">AdaptCMS</a>
          <div class="btn-group pull-right">
            <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> <?= $username ?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <?= $this->Html->link('<i class="icon-user"></i> Profile', 
                  array(
                    'admin' => false,
                    'controller' => 'users', 
                    'action' => 'profile', 
                    $username
                  ), array('escape' => false)
                ) ?>
              </li>
              <li class="divider"></li>
              <li><?= $this->Html->link('<i class="icon-signout"></i> Sign Out', array(
                  'controller' => 'Users', 
                  'action' => 'logout', 
                  'plugin' => false,
                  'admin' => false
                ), array('escape' => false)
                ) ?></li>
            </ul>
          </div>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="<?= $this->webroot ?>">Home</a></li>
              <li><a href="<?= $this->webroot ?>admin">Admin</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav" style="padding:0">
            <div class="accordion" id="admin-sidebar-accordion">
              <div class="accordion-group">
                <div class="accordion-heading">
                  <h3 class="success">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#admin-sidebar-accordion" href="#content">
                      <i class="icon-pencil icon-large"></i> Content
                    </a>
                  </h3>
                </div>
                <?php $options = array('articles', 'categories', 'fields', 'pages') ?>
                <div id="content" class="accordion-body collapse<?= (in_array($this->params->controller, $options) ? ' in': '') ?>">
                  <ul class="nav nav-list">
                    <li><?= $this->Html->link('Articles', array('admin' => true, 'plugin' => false, 'controller' => 'articles', 'action' => 'index')) ?></li>
                    <li><?= $this->Html->link('Categories', array('admin' => true, 'plugin' => false, 'controller' => 'categories', 'action' => 'index')) ?></li>
                    <li><?= $this->Html->link('Fields', array('admin' => true, 'plugin' => false, 'controller' => 'fields', 'action' => 'index')) ?></li>
                    <li><?= $this->Html->link('Pages', array('admin' => true, 'plugin' => false, 'controller' => 'pages', 'action' => 'index')) ?></li>
                  </ul>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <h3 class="primary">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#admin-sidebar-accordion" href="#users">
                      <i class="icon-group icon-large"></i> Users
                    </a>
                  </h3>
                </div>
                <?php $options = array('users', 'roles') ?>
                <div id="users" class="accordion-body collapse<?= (in_array($this->params->controller, $options) ? ' in': '') ?>">
                  <ul class="nav nav-list">
                    <li><?= $this->Html->link('Users', array('admin' => true, 'plugin' => false, 'controller' => 'users', 'action' => 'index')) ?></li>
                    <li><?= $this->Html->link('Roles', array('admin' => true, 'plugin' => false, 'controller' => 'roles', 'action' => 'index')) ?></li>
                  </ul>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <h3 class="warning">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#admin-sidebar-accordion" href="#media">
                      <i class="icon-picture icon-large"></i> Media
                    </a>
                  </h3>
                </div>
                <?php $options = array('files', 'media') ?>
                <div id="media" class="accordion-body collapse<?= (in_array($this->params->controller, $options) ? ' in': '') ?>">
                  <ul class="nav nav-list">
                    <li><?= $this->Html->link('Files', array('admin' => true, 'plugin' => false, 'controller' => 'files', 'action' => 'index')) ?></li>
                    <li><?= $this->Html->link('Media Library', array('admin' => true, 'plugin' => false, 'controller' => 'media', 'action' => 'index')) ?></li>
                  </ul>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <h3 class="inverse">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#admin-sidebar-accordion" href="#system">
                      <i class="icon-cogs icon-large"></i> System
                    </a>
                  </h3>
                </div>
                <?php $options = array('themes', 'templates', 'blocks', 'settings', 'tools') ?>
                <div id="system" class="accordion-body collapse<?= (in_array($this->params->controller, $options) ? ' in': '') ?>">
                  <ul class="nav nav-list">
                    <li><?= $this->Html->link('Appearance', array('admin' => true, 'plugin' => false, 'controller' => 'templates', 'action' => 'index')) ?></li>
                    <li><?= $this->Html->link('Blocks', array('admin' => true, 'plugin' => false, 'controller' => 'blocks', 'action' => 'index')) ?></li>
                    <li><?= $this->Html->link('Menus', array('admin' => true, 'plugin' => false, 'controller' => 'menus', 'action' => 'index')) ?></li>
                    <li><?= $this->Html->link('Settings', array('admin' => true, 'plugin' => false, 'controller' => 'settings', 'action' => 'index')) ?></li>
                    <li><?= $this->Html->link('Tools', array('admin' => true, 'plugin' => false, 'controller' => 'tools', 'action' => 'index')) ?></li>
                  </ul>
                </div>
              </div>
              <div class="accordion-group">
                <div class="accordion-heading">
                  <h3 class="danger">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#admin-sidebar-accordion" href="#plugins">
                      <i class="icon-cloud icon-large"></i> Plugins
                    </a>
                  </h3>
                </div>
                <div id="plugins" class="accordion-body collapse<?= ($this->params->controller == 'plugins' || !empty($this->params->plugin) ? ' in': '') ?>">
                  <ul class="nav nav-list">
                    <li><?= $this->Html->link('Manage Plugins', array('admin' => true, 'plugin' => false, 'controller' => 'plugins', 'action' => 'index')) ?></li>
                    <?php foreach(Configure::read('Plugins.list') as $plugin): ?>
                      <?php if (Configure::read($plugin . '.admin_menu')): ?>
                        <li><?= $this->Html->link(
                            (Configure::read($plugin . '.admin_menu_label') ? Configure::read($plugin . '.admin_menu_label') : $plugin), 
                            Configure::read($plugin . '.admin_menu')) ?>
                        </li>
                      <?php endif ?>
                    <?php endforeach ?>
                  </ul>
                </div>
              </div>
            </div>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
        <?= $this->Html->getCrumbList(array(
          'separator' => '<span class="divider"> / </span>',
          'class' => 'breadcrumb',
          'escape' => false,
          'lastClass' => 'active'
        ), 'Home') ?>

        <?= $this->Session->flash() ?>

        <?= $this->fetch('content') ?>

        </div><!--/span-->
      </div><!--/row-->

      <footer>
        <p class="pull-left">
            Powered by
            <?= $this->Html->link('AdaptCMS ', 'http://www.adaptcms.com', array('target' => '_blank')) ?>
            <?= $this->Api->version_check() ?>
        </p>
        <p class="pull-right">&copy; <a href="http://www.adaptcms.com" target="_blank">AdaptCMS</a> 2006-2013  <?= $this->Html->link(
          $this->Html->image('cake.power.gif'),
          'http://www.cakephp.org/',
          array('target' => '_blank', 'escape' => false)
          )
        ?><br />
        Cosmo theme by <?= $this->Html->link('Bootswatch', 'http://bootswatch.com/cosmo/', array('target' => '_blank')) ?>
        </p>
        <div class="clearfix"></div>
      </footer>

    </div><!--/.fluid-container-->

  </body>
</html>