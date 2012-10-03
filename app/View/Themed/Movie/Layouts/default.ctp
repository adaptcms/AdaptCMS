<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?= $this->webroot ?>themes/Movie/css/style.css" type="text/css" media="all" />
	<!--[if IE 6]>
		<link rel="stylesheet" href="<?= $this->webroot ?>themes/Movie/css/ie6.css" type="text/css" media="all" />
	<![endif]-->
	<?php
		echo $this->fetch('meta');
    	echo $this->fetch('css');
    	echo $this->fetch('script');
	?>
	<!-- <link href="<?= $this->webroot ?>css/bootstrap.css" rel="stylesheet"> -->
	<!-- <link href="<?= $this->webroot ?>css/bootstrap-responsive.css" rel="stylesheet"> -->
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

    <script type="text/javascript" src="<?= $this->webroot ?>themes/Movie/js/jquery-func.js"></script>
</head>
<body>
<!-- Shell -->
<div id="shell">
	<!-- Header -->
	<div id="header">
		<h1 id="logo"><a href="#">Movie Hunter</a></h1>
		<div class="social">
			<span>FOLLOW US ON:</span>
			<ul>
			    <li><a class="twitter" href="#">twitter</a></li>
			    <li><a class="facebook" href="#">facebook</a></li>
			    <li><a class="vimeo" href="#">vimeo</a></li>
			    <li><a class="rss" href="#">rss</a></li>
			</ul>
		</div>
		
		<!-- Navigation -->
		<div id="navigation">
			<ul>
			    <li><a class="active" href="/">HOME</a></li>
			    <li><a href="/category/news/">NEWS</a></li>
			    <li><a href="#">IN THEATERS</a></li>
			    <li><a href="#">COMING SOON</a></li>
			    <li><a href="/pages/contact-us/">CONTACT</a></li>
			    <li><a href="/pages/advertise/">ADVERTISE</a></li>
			</ul>
		</div>
		<!-- end Navigation -->
		
		<!-- Sub-menu -->
		<div id="sub-navigation">
			<ul>
			    <li><a href="#">SHOW ALL</a></li>
			    <li><a href="#">LATEST TRAILERS</a></li>
			    <li><a href="#">TOP RATED</a></li>
			    <li><a href="#">MOST COMMENTED</a></li>
			</ul>
			<div id="search">
				<form action="home_submit" method="get" accept-charset="utf-8">
					<label for="search-field">SEARCH</label>					
					<input type="text" name="search field" value="Enter search here" id="search-field" title="Enter search here" class="blink search-field"  />
					<input type="submit" value="GO!" class="search-button" />
				</form>
			</div>
		</div>
		<!-- end Sub-Menu -->
		
	</div>
	<!-- end Header -->
	
	<!-- Main -->
	<div id="main">
		<!-- Content -->
		<div id="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<!-- end Content -->
		<?php
			// echo $this->element('sql_dump')
		?>
		<div class="cl">&nbsp;</div>
	</div>
	<!-- end Main -->

	<!-- Footer -->
	<div id="footer">
		<p>
			<a href="/">HOME</a> <span>|</span>
			<a href="/category/news">NEWS</a> <span>|</span>
			<a href="#">IN THEATERS</a> <span>|</span>
			<a href="#">COMING SOON </a> <span>|</span>
			<a href="#">LATERS TRAILERS</a> <span>|</span>
			<a href="#">TOP RATED TRAILERS</a> <span>|</span>
			<a href="#">MOST COMMENTED TRAILERS</a> <span>|</span>
			<a href="/pages/advertise/">ADVERTISE</a> <span>|</span>
			<a href="/pages/contact-us/">CONTACT </a>
		</p>
		<p> &copy; <a href="http://www.insanevisions.com" target="_blank">InsaneVisions</a> 2007-2012  <?php echo $this->Html->link(
          $this->Html->image('cake.power.gif', array('border' => '0')),
          'http://www.cakephp.org/',
          array('target' => '_blank', 'escape' => false)
        );
      ?>  Designed by <a href="http://chocotemplates.com" target="_blank" title="The Sweetest CSS Templates WorldWide">ChocoTemplates.com</a></p>
	</div>
	<!-- end Footer -->
</div>
<!-- end Shell -->
</body>
</html>