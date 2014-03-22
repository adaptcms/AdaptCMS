<!DOCTYPE html>
{% if not empty(this->Facebook) %}
{{ facebook.html() }}
{% else %}
<html lang="en">
{% endif %}
<head>
	{{ html.charset() }}
	<title>
		{{ sitename }} | Gaming Theme | {{ title_for_layout }}
	</title>

	<?= $this->Html->meta('favicon.ico', $this->webroot . 'img/favicon.ico', array('type' => 'icon')) ?>

	{{ headers }}

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="author" content="">

	<link rel="apple-touch-icon" href="{{ webroot }}img/apple-touch-icon.png" />

	<!-- Le styles -->
	{{ css("bootstrap-default.min") }}
	<style type="text/css">
		body {
			padding-top: 60px;
			padding-bottom: 40px;
		}

		.sidebar-nav {
			padding: 9px 0;
		}
	</style>

	{{ css("font-awesome.min") }}

	<!--[if lt IE 9]>
	{{ js('html5.min') }}
	<![endif]-->
	<!--[if IE 7]>
	{{ css("font-awesome-ie7.min") }}
	<![endif]-->
</head>

<body>

<div class="navbar navbar-inverse navbar-fixed-top">
	<button class="navbar-toggle collapsed" data-toggle="collapse" type="button" data-target=".navbar-responsive-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
	<a class="navbar-brand" href="{{ webroot }}">{{ sitename }}</a>

	<div class="navbar-responsive-collapse nav-collapse collapse">
		<ul class="nav navbar-nav">
			<li class="active"><a href="{{ webroot }}">Home</a></li>
			{% if is_admin %}
			<li><a href="{{ webroot }}admin">Admin</a></li>
			{% endif %}
			{% if hasPlugin('Adaptbb') %}
			<li>
				<a href="{{ url('adaptbb_forums') }}">Forums</a>
			</li>
			{% endif %}
		</ul>
		{{ partial('Search/search_basic') }}
		<p class="navbar-text pull-right">
			<!--nocache-->
			{% if logged_in %}
			Logged in as <a href="{{ url('user_profile', current_user('username')) }}" class="navbar-link"> {{ current_user('username') }} </a>

			{% if current_user('login_type') && current_user('login_type') == 'facebook' %}
			{{ facebook_logout }}
			{% else %}
			<a href="{{ url('logout') }}" class="logout"> (logout)</a>
			{% endif %}
			{% else %}
			Please <a href="{{ url('login') }}" class="navbar-link">login</a> or <a href="{{ url('register') }}" class="navbar-link">register</a>
			{% endif %}
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
						<a href="{{ url('media_index') }}">Media</a>
					</li>
					<li>
						<a href="{{ url('article_rss') }}">RSS Feed</a>
					</li>
					<li>
						<a href="{{ url('page_view', 'contact-us') }}">Contact Us</a>
					</li>
					{% if hasPlugin('Polls') %}
					<li>
						<a href="{{ url('polls_list') }}">Polls List</a>
					</li>
					{% endif %}

					<li class="nav-header">Categories</li>
					<li>
						<a href="{{ url('category_view', array('games')) }}">Games</a>
					</li>
					<li>
						<a href="{{ url('category_view', array('reviews')) }}">Reviews</a>
					</li>
					<li>
						<a href="{{ url('category_view', array('platforms')) }}">Platforms</a>
					</li>

					<li class="nav-header">Platforms</li>
					<li>
						<a href="{{ url('article_slug_view', array('playstation-3')) }}">Playstation 3</a>
					</li>
					<li>
						<a href="{{ url('article_slug_view', array('xbox-360')) }}">XBOX 360</a>
					</li>

					{% if not empty(block_data['show-poll']) %}
					<li class="nav-header">Poll</li>

					<!--nocache-->
					<div class="span11 clearfix">
						{{ partial('Polls.show_poll', array('data' => $block_data['show-poll'], 'permissions' => $block_permissions['show-poll'])) }}
					</div>
					<!--/nocache-->
					{% endif %}

					{% if not empty(block_data['latest-links']) %}
					<li class="nav-header clear">Links</li>

					<div class="span11 clearfix">
						{{ partial('Links.links_list', array('data' => $block_data['latest-links'])) }}
					</div>
					{% endif %}
				</ul>
			</div>
			<!--/.well -->
		</div>
		<!--/span-->
		<div class="col-lg-9 content">
			{{ breadcrumbs }}
			<!--nocache-->
			{{ flash }}
			<!--/nocache-->

			{{ content }}
		</div>
		<!--/span-->

		<hr>

		<div class="col-lg-12 footer">
			<p>
			      <span class="pull-left">
			          {{ powered_by }}
			      </span>
			      <span class="pull-right">
			        &copy;
				      {{ copyright }}
				      <br/>
			        Cosmo theme by {{ link('Bootswatch', 'http://bootswatch.com/cosmo/', array('target' => '_blank')) }}
			      </span>
			</p>
		</div>
	</div>
	<!--/row-->
</div>
<!--/.fluid-container-->

</body>
{% if not empty(this->Facebook) %}
{{ facebook.init() }}
{% endif %}
</html>