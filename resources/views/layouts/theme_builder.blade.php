<!DOCTYPE html>
<html>
	<head>
	  <!-- Standard Meta -->
	  <meta charset="utf-8" />
	  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	  
	  <meta name="csrf-token" content="{{ csrf_token() }}">
	
	  <!-- Site Properties -->
	  <title>AdaptCMS Admin - Theme Builder</title>
	  
	  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.css">
	  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	  <link rel="stylesheet" type="text/css" href="/css/jquery.tagsinput.min.css">
	  <link rel="stylesheet" type="text/css" href="/css/semantic-ui-calendar.min.css">
	  
	  <link rel="stylesheet" type="text/css" href="/css/main.compiled.min.css">
	  
	  @stack('css')
	</head>
	<body>
      <div class="ui grid centered">
	      <div class="sixteen wide column">
				@yield('content')
	      </div>
	  </div>
	  
	  <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	  <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
	  <script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.10/semantic.min.js"></script>
	  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
	  <script src="//cdn.jsdelivr.net/ace/1.2.6/min/ace.js"></script>
	  <script src="//cdnjs.cloudflare.com/ajax/libs/string.js/3.3.3/string.min.js"></script>
	  
	  <script src="/js/vendor/jquery.tagsinput.min.js"></script>
	  <script src="/js/vendor/semantic-ui-calendar.min.js"></script>
	  <script src="/js/vendor/jquery-sortable.min.js"></script>
	  
	  @if (empty($ignore_vuejs))
		  <script src="/js/vendor/vue.min.js"></script>
		  <script src="/js/vendor/vue-router.min.js"></script>
	  @endif
	  
	  @stack('js')
	  
	  <script src="/js/main.compiled.min.js"></script>
	</body>
</html>