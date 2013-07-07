<!DOCTYPE html>
  <?php if (!empty($this->Facebook)): ?>
    <?= $this->Facebook->html() ?>
  <?php else: ?>
    <html lang="en">
  <?php endif ?>
  <head>
    <?= $this->Html->charset() ?>
    <title>
      Your Website | <?= $title_for_layout ?>
    </title>

    <?= $this->Html->meta('favicon.ico', '/img/favicon.ico', array('type' => 'icon')) ?>

    <?php
      echo $this->fetch('meta');
      echo $this->fetch('css');
      echo $this->fetch('script');
    ?>
    <?= $this->Html->script('jquery.min.js') ?>
    <?= $this->Html->script('jquery.validate.min.js') ?>
    <?= $this->Html->script('bootstrap.min.js') ?>

    <?= $this->Html->script('global.js') ?>
    
    <?= $this->AutoLoadJS->getJs() ?>
    <?= $this->AutoLoadJS->getCss() ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <?= $this->Html->css("bootstrap-responsive.min") ?>

    <?= $this->Html->css("font-awesome.min.css") ?>

    <!--[if lt IE 9]>
      <?= $this->Html->script('html5.min.js') ?>
    <![endif]-->
    <!--[if IE 7]>
      <?= $this->Html->css("font-awesome-ie7.min.css") ?>
    <![endif]-->
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
          <a class="brand" href="<?= $this->webroot ?>">Your Website</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
                <!--nocache-->
            	<?php if ($this->Session->check('Auth.User.username')): ?>
            		Logged in as 
            			<?= $this->Html->link($this->Session->read('Auth.User.username'), 
            				array(
            					'controller' => 'users', 
            					'action' => 'profile', 
                      'plugin' => null,
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
                      'action' => 'logout',
                      'plugin' => null
                    )
                  ) ?>
                <?php endif ?>
            	<?php else: ?>
            		Please
            		<?= $this->Html->link('login', 
            				array(
                      'plugin' => null,
            					'controller' => 'users', 
            					'action' => 'login'
            				),
            				array('class' => 'navbar-link')
            			) ?> or 
        			<?= $this->Html->link('register', 
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
            <ul class="nav">
              <li class="active"><a href="<?= $this->webroot ?>">Home</a></li>
              <li><a href="<?= $this->webroot ?>admin">Admin</a></li>
              <li>
                  <?= $this->Html->link('Forums', array(
                      'plugin' => 'adaptbb',
                      'controller' => 'forums',
                      'action' => 'index'
                  )) ?>
              </li>
            </ul>
            <?= $this->Element('Search/search_basic') ?>
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
                <?= $this->Html->link('Media', array(
                  'plugin' => null,
                  'controller' => 'media',
                  'action' => 'index'
                )) ?>
              </li>
              <li>
              	<?= $this->Html->link('RSS Feed', array(
                    'plugin' => null,
              			'rss' => true,
              			'controller' => 'articles',
              			'action' => 'index'
              	)) ?>
              </li>
              <li>
              	<?= $this->Html->link('Contact Us', array(
                    'plugin' => null,
              			'controller' => 'pages',
              			'action' => 'display',
              			'contact-us'
              	)) ?>
              </li>
              <li class="nav-header">Categories</li>
                <li>
                  <?= $this->Html->link('News', array(
                      'plugin' => null,
                      'controller' => 'categories',
                      'action' => 'view',
                      'news'
                  )) ?>
                </li>
              <li class="nav-header">Poll</li>

              <!--nocache-->
                <?= $this->element('Polls.show_poll', array('data' => $block_data['show-poll'])) ?>
              <!--/nocache-->

              <li class="nav-header">Links</li>

              <?= $this->element('Links.links_list', array('data' => $block_data['latest-links'])) ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <?= $this->Html->getCrumbList(array(
            'separator' => '<span class="divider"> / </span>',
            'class' => 'breadcrumb',
            'escape' => false,
            'lastClass' => 'active'
          ), 'Home') ?>
          <!--nocache-->
          <?= $this->Session->flash() ?>
          <!--/nocache-->

          <?= $this->fetch('content') ?>
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>
          <span class="pull-left">
              Powered by
              <?= $this->Html->link('AdaptCMS ' . ADAPTCMS_VERSION, 'http://www.adaptcms.com', array('target' => '_blank')) ?>
          </span>
          <span class="pull-right">
          	&copy; 2006-13 <?= $this->Html->link('AdaptCMS', 'http://www.adaptcms.com', array('target' => '_blank')) ?><br />
            Cosmo theme by <?= $this->Html->link('Bootswatch', 'http://bootswatch.com/cosmo/', array('target' => '_blank')) ?>
          </span>
        </p>
      </footer>

    </div><!--/.fluid-container-->

  </body>
  <?php if (!empty($this->Facebook)): ?>
    <?= $this->Facebook->init() ?>
  <?php endif ?>
</html>