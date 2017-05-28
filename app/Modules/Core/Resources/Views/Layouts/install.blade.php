<!DOCTYPE html>
<html>
	<head>
	  <!-- Standard Meta -->
	  <meta charset="utf-8" />
	  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	  <meta name="csrf-token" content="{{ csrf_token() }}">

	  <!-- Site Properties -->
	  <title>Install AdaptCMS</title>
	  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.css">
	  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	  <link rel="stylesheet" type="text/css" href="/css/jquery.tagsinput.min.css">
	  <link rel="stylesheet" type="text/css" href="/css/semantic-ui-calendar.min.css">

	  <link rel="stylesheet" type="text/css" href="/css/main.compiled.min.css">

	  @stack('css')
	</head>
	<body class="install {{ Core::getRequestActionName() }}">
		<div class="ui grid">
				<div class="ui container">
					<h1 class="ui center aligned header">Install AdaptCMS {{ Core::getVersion() }}</h1>

					@if(!empty($success))
			    	<div class="ui success message">
					    <div class="header">Good news everyone!</div>
					    <p>{{ $success }}</p>
					  </div>
			    @endif

			    @if(session('success'))
			    	<div class="ui success message">
					    <div class="header">Good news everyone!</div>
					    <p>{{ session('success') }}</p>
					  </div>
			    @endif

			    @if(!empty($error))
			    	<div class="ui error message">
					    <div class="header">Whoops, we got a problem!</div>
					    <p>{{ $error }}</p>
					  </div>
			    @endif

			    @if(session('error'))
			    	<div class="ui error message">
					    <div class="header">Whoops, we got a problem!</div>
					    <p>{{ session('error') }}</p>
					  </div>
			    @endif

					<div class="ui five top attached steps">
						<a href="{{ route('install.index') }}" class="step {{ Route::currentRouteName() == 'install.index' ? 'active' : '' }}">
							<i class="server icon"></i>
							<div class="content">
								<div class="title">Server</div>
								<div class="description">Let's checkout your server and see if it's up to snuff.</div>
							</div>
						</a>
						<a
							href="{{ route('install.database') }}"
							class="step {{ Route::currentRouteName() == 'install.database' ? 'active' : (!Cache::get('install_database') ? 'disabled' : '') }}"
						>
							<i class="database icon"></i>
							<div class="content">
								<div class="title">Database</div>
								<div class="description">You'll be entering your database and website information in here.</div>
							</div>
						</a>
						<a
							href="{{ route('install.me') }}"
							class="step {{ Route::currentRouteName() == 'install.me' ? 'active' : (!Cache::get('install_me') ? 'disabled' : '') }}"
						>
							<i class="child icon"></i>
							<div class="content">
								<div class="title">Me</div>
								<div class="description">We want to know about you! Only if you feel like it.</div>
							</div>
						</a>
						<a
							href="{{ route('install.account') }}"
							class="step {{ Route::currentRouteName() == 'install.account' ? 'active' : (!Cache::get('install_account') ? 'disabled' : '') }}"
						>
							<i class="user icon"></i>
							<div class="content">
								<div class="title">Account</div>
								<div class="description">Fill out your admin account credentials.</div>
							</div>
						</a>
						<a
							href="{{ route('install.finished') }}"
							class="step {{ Route::currentRouteName() == 'install.finished' ? 'active' : 'disabled' }}"
						>
							<i class="child icon"></i>
							<div class="content">
								<div class="title">Finished!</div>
								<div class="description">Tada, you're all done! We have some useful tips before you go onto the admin.</div>
							</div>
						</a>
						</div>
						<div class="ui red very padded attached segment">
								@yield('content')
						</div>

				</div>
		</div>

	  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	  <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
	  <script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.js"></script>
	  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

	  <script src="/js/vendor/jquery.tagsinput.min.js"></script>
	  <script src="/js/vendor/semantic-ui-calendar.min.js"></script>

	  <script src="/js/vendor/vue.min.js"></script>
	  <script src="/js/vendor/vue-router.min.js"></script>

	  @stack('js')

	  <script src="/js/main.compiled.min.js"></script>

	  <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-99970500-1', 'auto');
		  ga('send', 'pageview');

		</script>
	</body>
</html>
