<!DOCTYPE html>
<html>
	<head>
	  <!-- Standard Meta -->
	  <meta charset="utf-8" />
	  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	  <meta name="csrf-token" content="{{ csrf_token() }}">

	  @if(Theme::get('meta_description'))
	    <meta name="description" content="{{ Theme::get('meta_description') }}">
	  @endif
	  
	  @if(Theme::get('meta_keywords'))
	    <meta name="keywords" content="{{ Theme::get('meta_keywords') }}">
	  @endif

	  <meta name="author" content="{{ Settings::get('sitename') }}">

	  <!-- Site Properties -->
	  <title>
	        @if(Theme::hasTitle())
	            {{ Theme::getTitle() }} |
	        @endif
	        {{ Settings::get('sitename') }}
	  </title>

	  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.css">
	  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	  <link rel="stylesheet" type="text/css" href="/css/jquery.tagsinput.min.css">
	  <link rel="stylesheet" type="text/css" href="/css/semantic-ui-calendar.min.css">

	  <link rel="stylesheet" type="text/css" href="/css/main.compiled.min.css">

	  @stack('css')
	</head>
	<body>
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

	    <div class="ui container">
	        <div class="ui stackable centered middle aligned grid">
	            {!! Theme::content() !!}
	        </div>
	    </div>

		<div class="ui fixed footer segment">
			<div class="seven wide column clear">
				<p>
				  &copy; Copyright {{ date('Y') }} {{ Settings::get('sitename') }}
				</p>
				<p>
				  Powered by <a href="https://www.adaptcms.com" target="_blank">
					AdaptCMS {{ ucfirst(Core::getVersion()) }}
				  </a>
				</p>
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
