<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Alpha</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <?= $this->Html->css("bootstrap.css") ?>
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <?= $this->Html->css("bootstrap-responsive.css") ?>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <?= $this->Html->script('html5.min.js') ?>

    <![endif]-->
    
    <!-- http://html5shim.googlecode.com/svn/trunk/html5.js -->

  <?php
    echo $this->Html->meta('icon');

    // echo $this->Html->css('cake.generic');

    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    // echo $scripts_for_layout;
  ?>
    <?php if (1 == 1): ?>
    <?= $this->Html->script('jquery.min.js') ?>
    <?php else: ?>
    <?= $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js') ?>
    <?php endif; ?>

    <?= $this->Html->script('bootstrap.min.js') ?>

    <?php if (1 == 1): ?>
    <?= $this->Html->script('jquery.validate.min.js') ?>
    <?php else: ?>
    <?= $this->Html->script('//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js') ?>
    <?php endif; ?>

    <?= $this->Html->script('global.js') ?>
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
          <a class="brand" href="#">Alpha</a>
          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> <?= $username ?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#">Profile</a></li>
              <li class="divider"></li>
              <li><?= $this->Html->link("Sign Out", array(
                  'controller' => 'Users', 
                  'action' => 'logout', 
                  'plugin' => false,
                  'admin' => false
                )) ?></li>
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
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Sidebar</li>
              <?= $this->element('sidebar') ?>
              <li class="nav-header">Plugins</li>
              <?= $this->element('plugins_sidebar') ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          
        <?php echo $this->Session->flash(); ?>

        <ul class="breadcrumb">
          <li>
            <a href="<?= $this->webroot.$prefix ?>"><?= ucwords(str_replace("_", " ", $prefix)) ?></a> <span class="divider">/</span>
          </li>
          <?php if (isset($this->params->controller)): ?>
          <li>
            <a href="<?= $this->webroot.$prefix."/".$this->params->controller ?>"><?= ucwords($this->params->controller) ?></a> <span class="divider">/</span>
          </li>
          <?php endif; ?>
          <li class="active"><?= ucwords(str_replace($prefix."_", "", $this->params->action)) ?></li>
        </ul>

        <?php echo $this->fetch('content'); ?>

        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p style="float:right">&copy; <a href="http://www.insanevisions.com" target="_blank">InsaneVisions</a> 2007-2012  <?php echo $this->Html->link(
          $this->Html->image('cake.power.gif', array('border' => '0')),
          'http://www.cakephp.org/',
          array('target' => '_blank', 'escape' => false)
        );
      ?></p><div style="clear:both"></div>
      <p><?php echo $this->element('sql_dump'); ?></p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
  </body>
</html>