<!DOCTYPE html>
<html lang="en">
  <head>
    <?php echo $this->Html->charset(); ?>
    <title>
      <?php echo $title_for_layout; ?>
    </title>
    <?php
      echo $this->Html->meta('icon');

      echo $this->Html->css('cake.generic');

      echo $this->fetch('meta');
      echo $this->fetch('css');
      echo $this->fetch('script');
    ?>
    <?php if (1 == 1): ?>
      <?= $this->Html->script('jquery.min.js') ?>
    <?php else: ?>
      <?= $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js') ?>
    <?php endif; ?>

    <?php if (1 == 1): ?>
      <?= $this->Html->script('jquery.validate.min.js') ?>
    <?php else: ?>
      <?= $this->Html->script('//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js') ?>
    <?php endif; ?>

    <?= $this->Html->script('global.js') ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?= $this->webroot ?>installer/webroot/css/bootstrap-install.css" rel="stylesheet">
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
          <a class="brand" href="<?= $this->webroot ?>">AdaptCMS <?= ADAPTCMS_VERSION ?></a>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <ul class="nav nav-pills nav-stacked">
              <?php if ($this->params->action == 'install_plugin'): ?>
                <li class="nav-header">Plugin Install Progress</li>

                <li<?= (empty($this->request->data) ? " class='active'" : "") ?>>
                  <?= $this->Html->link('1. Confirm Install', '#', array('class' => 'no-link')) ?>
                </li>
                <li<?= (!empty($this->request->data) ? " class='active'" : " class='disabled'") ?>>
                  <?= $this->Html->link('2. Finish', '#', array('class' => 'no-link')) ?>
                </li>
              <?php elseif ($this->params->action == 'uninstall_plugin'): ?>
                <li class="nav-header">Plugin Uninstall Progress</li>

                <li<?= (empty($this->request->data) ? " class='active'" : "") ?>>
                  <?= $this->Html->link('1. Confirm Un-install', '#', array('class' => 'no-link')) ?>
                </li>
                <li<?= (!empty($this->request->data) ? " class='active'" : " class='disabled'") ?>>
                  <?= $this->Html->link('2. Finish', '#', array('class' => 'no-link')) ?>
                </li>
              <?php elseif ($this->params->action == 'upgrade_plugin'): ?>
                <li class="nav-header">Plugin Upgrade Progress</li>

                <li<?= (empty($this->request->data) ? " class='active'" : "") ?>>
                  <?= $this->Html->link('1. Confirm Upgrade', '#', array('class' => 'no-link')) ?>
                </li>
                <li<?= (!empty($this->request->data) ? " class='active'" : " class='disabled'") ?>>
                  <?= $this->Html->link('2. Finish', '#', array('class' => 'no-link')) ?>
                </li>
              <?php elseif ($this->params->action == 'install_theme'): ?>
                <li class="nav-header">Theme Install Progress</li>

                <li<?= (empty($this->request->data) ? " class='active'" : "") ?>>
                  <?= $this->Html->link('1. Confirm Install', '#', array('class' => 'no-link')) ?>
                </li>
                <li<?= (!empty($this->request->data) ? " class='active'" : " class='disabled'") ?>>
                  <?= $this->Html->link('2. Finish', '#', array('class' => 'no-link')) ?>
                </li>
              <?php elseif ($this->params->action == 'uninstall_theme'): ?>
                <li class="nav-header">Theme Uninstall Progress</li>

                <li<?= (empty($this->request->data) ? " class='active'" : "") ?>>
                  <?= $this->Html->link('1. Confirm Un-install', '#', array('class' => 'no-link')) ?>
                </li>
                <li<?= (!empty($this->request->data) ? " class='active'" : " class='disabled'") ?>>
                  <?= $this->Html->link('2. Finish', '#', array('class' => 'no-link')) ?>
                </li>
              <?php elseif ($this->params->action == 'upgrade_theme'): ?>
                <li class="nav-header">Theme Upgrade Progress</li>

                <li<?= (empty($this->request->data) ? " class='active'" : "") ?>>
                  <?= $this->Html->link('1. Confirm Upgrade', '#', array('class' => 'no-link')) ?>
                </li>
                <li<?= (!empty($this->request->data) ? " class='active'" : " class='disabled'") ?>>
                  <?= $this->Html->link('2. Finish', '#', array('class' => 'no-link')) ?>
                </li>
              <?php elseif ($this->params->action == 'upgrade'): ?>
                <li class="nav-header">Upgrade Progress</li>

                <li<?= (empty($this->request->data) ? " class='active'" : "") ?>>
                  <?= $this->Html->link('1. Confirm Upgrade', '#', array('class' => 'no-link')) ?>
                </li>
                <li<?= (!empty($this->request->data) ? " class='active'" : " class='disabled'") ?>>
                  <?= $this->Html->link('2. Finish', '#', array('class' => 'no-link')) ?>
                </li>
              <?php else: ?>
                <li class="nav-header">Install Progress</li>

                <li<?= ($this->params->action == 'index' ? " class='active'" : "") ?>>
                  <?= $this->Html->link('1. Requirements', array('action' => 'index')) ?>
                </li>
                <?php if (strstr($this->params->action, "upgrade")): ?>

                <?php else: ?>
                  <li<?= ($this->params->action == 'database' ? " class='active'" : "") ?>>
                    <?= $this->Html->link('2. Database Configuration', array('action' => 'database')) ?>
                  </li>
                  <li<?= ($this->params->action == 'sql' ? " class='active'" : "") ?>>
                    <?= $this->Html->link('3. Setup SQL', array('action' => 'sql')) ?>
                  </li>
                  <li<?= ($this->params->action == 'account' ? " class='active'" : "") ?>>
                    <?= $this->Html->link('4. Create Admin Account', array('action' => 'account')) ?>
                  </li>
                  <li<?= ($this->params->action == 'finish' ? " class='active'" : " class='disabled'") ?>>
                    <?= $this->Html->link('5. Finish', array('action' => 'finish')) ?>
                  </li>
                <?php endif ?>
              <?php endif ?>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <?php echo $this->Session->flash(); ?>

          <?php echo $this->fetch('content'); ?>
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>
          <?php echo $this->Html->link(
              $this->Html->image('cake.power.gif', array('style' => 'float: left', 'border' => '0')),
              'http://www.cakephp.org/',
              array('target' => '_blank', 'escape' => false)
            );
          ?>
          <span style="float: right">
          	&copy; 2006-13 <?= $this->Html->link('AdaptCMS ' . ADAPTCMS_VERSION, 'http://www.adaptcms.com', array('target' => '_blank')) ?>
          </span>
        </p>
      </footer>

    </div><!--/.fluid-container-->
  </body>
</html>
