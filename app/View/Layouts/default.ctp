<!DOCTYPE html>
  <?php if (!empty($this->Facebook)): ?>
    <?= $this->Facebook->html() ?>
  <?php else: ?>
    <html lang="en">
  <?php endif ?>
  <head>
    <?= $this->Html->charset() ?>
    <title>
      <?= $title_for_layout ?>
    </title>
    <?php
      echo $this->Html->meta('icon');

      echo $this->fetch('meta');
      echo $this->fetch('css');
      echo $this->fetch('script');
    ?>
    <?= $this->Html->script('jquery.min.js') ?>
    <?= $this->Html->script('jquery.validate.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>
    <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>-->
    <!-- <?= $this->Html->script('//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js') ?> -->
    <?= $this->Html->script('global.js') ?>
    <?= $this->AutoLoadJS->getJs() ?>
    <?= $this->AutoLoadJS->getCss() ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?= $this->webroot ?>css/bootstraps.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="<?= $this->webroot ?>css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="<?= $this->webroot ?>img/favicon.ico">
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?= $this->webroot ?>">AdaptCMS Alpha</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
            	<?php if ($this->Session->check('Auth.User.username')): ?>
            		Logged in as 
            			<?= $this->Html->link($this->Session->read('Auth.User.username'), 
            				array(
            					'controller' => 'users', 
            					'action' => 'profile', 
            					$username
            				),
            				array('class' => 'navbar-link')
            			) ?>
                <?php if ($this->Session->check('Auth.User.login_type') && $this->Session->read('Auth.User.login_type') == "facebook"): ?>
                  <?= $this->Facebook->logout(array(
                        'redirect' => array(
                          'action' => 'logout', 
                          'controller' => 'users'
                        ), 'img' => 'facebook-logout.png'
                  )) ?>
                <?php else: ?>
                  <?= $this->Html->link(' (logout)', 
                    array(
                      'controller' => 'users', 
                      'action' => 'logout'
                    )
                  ) ?>
                <?php endif ?>
            	<?php else: ?>
            		Please
            		<?= $this->Html->link('login', 
            				array(
            					'controller' => 'users', 
            					'action' => 'login'
            				),
            				array('class' => 'navbar-link')
            			) ?> or 
        			<?= $this->Html->link('register', 
        				array(
        					'controller' => 'users', 
        					'action' => 'register'
        				),
        				array('class' => 'navbar-link')
        			) ?>
            	<?php endif ?>
            </p>
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
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Links</li>
              <li>
              	<?= $this->Html->link('RSS Feed', array(
              			'rss' => true,
              			'controller' => 'articles',
              			'action' => 'index'
              	)) ?>
              </li>
              <li>
              	<?= $this->Html->link('Contact Us', array(
              			'controller' => 'pages',
              			'action' => 'display',
              			'contact-us'
              	)) ?>
              </li>
              <li class="nav-header">Categories</li>
              <?php if (!empty($module_data['categories-list'])): ?>
                <?php foreach($module_data['categories-list'] as $cat): ?>
                  <li>
                    <?= $this->Html->link($cat['Category']['title'], array(
                        'controller' => 'categories',
                        'action' => 'view',
                        $cat['Category']['slug']
                    )) ?>
                  </li>
                <?php endforeach ?>
              <?php else: ?>
                <li>
                  <?= $this->Html->link('Movies', array(
                      'controller' => 'categories',
                      'action' => 'view',
                      'movies'
                  )) ?>
                </li>
                <li>
                  <?= $this->Html->link('News', array(
                      'controller' => 'categories',
                      'action' => 'view',
                      'news'
                  )) ?>
                </li>
              <?php endif ?>
              <li class="nav-header">Poll</li>

              <?= $this->element('show_poll', array('data' => $module_data['show-poll'])) ?>

              <li class="nav-header">Links</li>

              <?= $this->element('links_list', array('data' => $module_data['latest-links'])) ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <?= $this->Session->flash() ?>

          <?= $this->fetch('content') ?>
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>
          <?= $this->Html->link(
              $this->Html->image('cake.power.gif', array('style' => 'float: left', 'border' => '0')),
              'http://www.cakephp.org/',
              array('target' => '_blank', 'escape' => false)
            );
          ?>
          <span style="float: right">
          	&copy; 2006-12 <?= $this->Html->link('AdaptCMS Alpha', 'http://www.adaptcms.com') ?>
          </span>
        </p>
      </footer>

    </div><!--/.fluid-container-->

    <?= $this->element('sql_dump') ?>
  </body>
  <?php if (!empty($this->Facebook)): ?>
    <?= $this->Facebook->init() ?>
  <?php endif ?>
</html>