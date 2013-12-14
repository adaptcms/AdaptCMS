<!DOCTYPE html>
<?php if (!empty($this->Facebook)): ?>
  <?= $this->Facebook->html() ?>
<?php else: ?>
<html lang="en">
<?php endif ?>
<head>
  <?= $this->Html->charset() ?>
  <title>
    Your Website | Sample Theme | <?= $title_for_layout ?>
  </title>

  <?= $this->Html->meta('favicon.ico', $this->webroot . 'img/favicon.ico', array('type' => 'icon')) ?>

  <?php
  echo $this->fetch('meta');
  echo $this->fetch('css');
  echo $this->fetch('script');
  ?>
  <?= $this->Html->script('jquery.min') ?>
  <?= $this->Html->script('jquery.validate.min') ?>
  <?= $this->Html->script('bootstrap.min') ?>

  <?= $this->Html->script('global') ?>

  <?= $this->AutoLoadJS->getJs() ?>
  <?= $this->AutoLoadJS->getCss() ?>

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Le styles -->
  <?= $this->Html->css("bootstrap-default.min") ?>
  <style type="text/css">
    body {
      padding-top: 60px;
      padding-bottom: 40px;
    }

    .sidebar-nav {
      padding: 9px 0;
    }
  </style>

  <?= $this->Html->css("font-awesome.min") ?>

  <!--[if lt IE 9]>
  <?= $this->Html->script('html5.min') ?>
  <![endif]-->
  <!--[if IE 7]>
  <?= $this->Html->css("font-awesome-ie7.min") ?>
  <![endif]-->
</head>

<body>

  <div class="navbar navbar-inverse navbar-fixed-top">
    <button class="navbar-toggle collapsed" data-toggle="collapse" type="button" data-target=".navbar-responsive-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?= $this->webroot ?>">Your Website</a>

    <div class="navbar-responsive-collapse nav-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="<?= $this->webroot ?>">Home</a></li>
        <?php if (!$this->Session->read('Auth.User.Role.defaults') || $this->Session->read('Auth.User.Role.defaults') == 'default-admin'): ?>
          <li><a href="<?= $this->webroot ?>admin">Admin</a></li>
        <?php endif ?>
        <?php if ($this->View->pluginExists('Adaptbb')): ?>
          <li>
            <?=
            $this->Html->link('Forums', array(
              'plugin' => 'adaptbb',
              'controller' => 'forums',
              'action' => 'index'
            )) ?>
          </li>
        <?php endif ?>
      </ul>
      <?= $this->Element('Search/search_basic') ?>
      <p class="navbar-text pull-right">
        <!--nocache-->
        <?php if ($this->Session->check('Auth.User.username')): ?>
          Logged in as
          <?=
          $this->Html->link($this->Session->read('Auth.User.username'),
            array(
              'controller' => 'users',
              'action' => 'profile',
              'plugin' => null,
              $username
            ),
            array('class' => 'navbar-link')
          ) ?>
          <?php if ($this->Session->check('Auth.User.login_type') && $this->Session->read('Auth.User.login_type') == "facebook"): ?>
            <?=
            $this->Facebook->logout(array(
              'redirect' => array(
                'action' => 'logout',
                'controller' => 'users'
              ), 'img' => 'facebook-logout.png'
            )) ?>
          <?php else: ?>
            <?=
            $this->Html->link(' (logout)',
              array(
                'controller' => 'users',
                'action' => 'logout',
                'plugin' => null
              ),
              array('class' => 'logout')
            ) ?>
          <?php endif ?>
        <?php else: ?>
          Please
          <?=
          $this->Html->link('login',
            array(
              'plugin' => null,
              'controller' => 'users',
              'action' => 'login'
            ),
            array('class' => 'navbar-link')
          ) ?> or
          <?=
          $this->Html->link('register',
            array(
              'plugin' => null,
              'controller' => 'users',
              'action' => 'register'
            ),
            array('class' => 'navbar-link')
          ) ?>
        <?php endif ?>
        <!--/nocache-->
      </p>
    </div>
    <!--/.nav-collapse -->
  </div>

  <div class="container-fluid">
    <div class="row-fluid">
      <div class="col-lg-3 left-menu">
        <div class="well sidebar-nav">
          <ul class="nav nav-list">
            <li class="nav-header">Links</li>
            <li>
              <?=
              $this->Html->link('Media', array(
                'plugin' => null,
                'controller' => 'media',
                'action' => 'index'
              )) ?>
            </li>
            <li>
              <?=
              $this->Html->link('RSS Feed', array(
                'plugin' => null,
                'rss' => true,
                'controller' => 'articles',
                'action' => 'index'
              )) ?>
            </li>
            <li>
              <?=
              $this->Html->link('Contact Us', array(
                'plugin' => null,
                'controller' => 'pages',
                'action' => 'display',
                'contact-us'
              )) ?>
            </li>
            <?php if ($this->View->pluginExists('Polls')): ?>
              <li>
                <?=
                $this->Html->link('Polls List', array(
                  'plugin' => 'polls',
                  'controller' => 'polls',
                  'action' => 'all'
                )) ?>
              </li>
            <?php endif ?>
            <li class="nav-header">Categories</li>
            <li>
              <?=
              $this->Html->link('News', array(
                'plugin' => null,
                'controller' => 'categories',
                'action' => 'view',
                'news'
              )) ?>
            </li>

            <?php if (!empty($block_data['show-poll'])): ?>
              <li class="nav-header">Poll</li>

              <!--nocache-->
              <div class="span11 clearfix">
                <?= $this->element('Polls.show_poll', array('data' => $block_data['show-poll'])) ?>
              </div>
              <!--/nocache-->
            <?php endif ?>

            <?php if (!empty($block_data['latest-links'])): ?>
              <li class="nav-header clear">Links</li>

              <div class="span11 clearfix">
                <?= $this->element('Links.links_list', array('data' => $block_data['latest-links'])) ?>
              </div>
            <?php endif ?>
          </ul>
        </div>
        <!--/.well -->
      </div>
      <!--/span-->
      <div class="col-lg-9 content">
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

      <hr>

      <div class="col-lg-12 footer">
        <p>
            <span class="pull-left">
                Powered by
              <?= $this->Html->link('AdaptCMS ' . ADAPTCMS_VERSION, 'http://www.adaptcms.com', array('target' => '_blank')) ?>
            </span>
            <span class="pull-right">
              &copy;
              2006-13 <?= $this->Html->link('AdaptCMS', 'http://www.adaptcms.com', array('target' => '_blank')) ?>
              <br/>
              Cosmo theme by <?= $this->Html->link('Bootswatch', 'http://bootswatch.com/cosmo/', array('target' => '_blank')) ?>
            </span>
        </p>
      </div>
    </div>
    <!--/row-->
  </div>
  <!--/.fluid-container-->

</body>
<?php if (!empty($this->Facebook)): ?>
  <?= $this->Facebook->init() ?>
<?php endif ?>
</html>