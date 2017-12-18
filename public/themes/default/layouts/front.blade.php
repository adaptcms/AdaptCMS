
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

  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui-calendar/0.0.8/calendar.min.css">
  <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

  <link rel="stylesheet" type="text/css" href="/themes/default/assets/css/main.compiled.min.css">

  @stack('css')
</head>
<body>
	@include('users::Users/admin-check')

	<!-- Following Menu -->
	<div class="ui large top fixed menu main-menu">
	  <div class="ui container">
		  <div class="header item">
			  {{ Settings::get('sitename') }}
		  </div>

	      {!! Theme::partial('nav') !!}
	  </div>
	</div>

	<!-- Sidebar Menu -->
	<div class="ui vertical inverted sidebar menu">
	    {!! Theme::partial('nav') !!}
	</div>

    <div class="pusher">
      <div class="ui inverted vertical masthead center aligned segment hero">
          <div class="ui container mobile only">
              <div class="ui large secondary inverted pointing menu">
                <a class="toc item">
                  <i class="sidebar icon"></i>
                </a>
                {!! Theme::partial('nav') !!}
              </div>
            </div>

            <div class="ui text container">
              <h1 class="ui inverted header">
                {{ Settings::get('sitename') }}
              </h1>
              <h2>Welcome!</h2>
              <a href="{{ route('login') }}" class="ui right labeled icon huge primary button">
                  Get Started
                  <i class="right arrow icon"></i>
              </a>
            </div>

          </div>

          <!-- Page Contents -->
      	<div class="ui grid">
            <div class="twelve wide column centered">
        		    <div class="ui container">
                  @include('partials/flash')
              	  @yield('content')
              	  {!! Theme::content() !!}
                </div>
            </div>
        </div>
      </div>
    </div>

	  <div class="ui inverted vertical footer segment">
	    <div class="ui container">
	      <div class="ui stackable inverted divided equal height stackable grid">
	        <div class="three wide column">
	          <h4 class="ui inverted header">Posts</h4>
	          <div class="ui inverted link list">
              @foreach(Core::getData('categories', 'all', [], [ 'ord', 'asc' ], 5) as $category)
        	    	<a href="{{ route('categories.view', [ 'slug' => $category->slug ]) }}" class="{{ Request::url() == route('categories.view', [ 'slug' => $category->slug ]) ? 'active' : '' }} item">
        		    	{{ $category->name }}
        	    	</a>
        	    @endforeach
	          </div>
	        </div>
	        <div class="three wide column">
	          <h4 class="ui inverted header">Pages</h4>
	          <div class="ui inverted link list">
                @foreach(Core::getData('pages', 'all', [], [], 5) as $page)
          	    	@if($page->slug == 'home')
          	    		<a href="{{ route('home') }}" class="{{ Request::url() == route('home') ? 'active' : '' }} item">
          		    	{{ $page->name }}
          	    	</a>
          	    	@else
          		    	<a href="{{ route('pages.view', [ 'slug' => $page->slug ]) }}" class="{{ Request::url() == route('pages.view', [ 'slug' => $page->slug ]) ? 'active' : '' }} item">
          			    	{{ $page->name }}
          		    	</a>
          	    	@endif
          	    @endforeach
	          </div>
	        </div>
          <div class="three wide column">
	          <h4 class="ui inverted header">Albums</h4>
	          <div class="ui inverted link list">
                @foreach(Core::getData('albums', 'all', [], [], 5) as $album)
          		    	<a href="{{ route('albums.view', [ 'slug' => $album->slug ]) }}" class="{{ Request::url() == route('albums.view', [ 'slug' => $album->slug ]) ? 'active' : '' }} item">
          			    	{{ $album->name }}
          		    	</a>
          	    @endforeach
	          </div>
	        </div>
	        <div class="seven wide column">
	          <h4 class="ui inverted header">Footer Header</h4>
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
	    </div>
	  </div>
	</div>

  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui-calendar/0.0.8/calendar.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/string.js/3.3.3/string.min.js"></script>

  <script src="/js/vendor/jquery.tagsinput.min.js"></script>
  <script src="/js/vendor/jquery-sortable.min.js"></script>

  <script src="/js/vendor/vue.min.js"></script>
  <script src="/js/vendor/vue-router.min.js"></script>

  @stack('js')

  {!! Theme::asset()->styles() !!}
  {!! Theme::asset()->scripts() !!}

  <script src="/themes/default/assets/js/main.compiled.min.js"></script>

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
