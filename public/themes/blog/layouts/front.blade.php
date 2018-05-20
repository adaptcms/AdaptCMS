
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
      @if(Theme::get('meta_description'))
        <meta name="description" content="{{ Theme::get('meta_description') }}">
      @endif
      @if(Theme::get('meta_keywords'))
        <meta name="keywords" content="{{ Theme::get('meta_keywords') }}">
      @endif
    
      <meta name="author" content="{{ Settings::get('sitename') }}">

    <title>
          @if(Theme::hasTitle())
              {{ Theme::getTitle() }} |
          @endif
          {{ Settings::get('sitename') }}
    </title>

    <!-- Bootstrap core CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    
      <link rel="stylesheet" type="text/css" href="/themes/blog/assets/css/main.compiled.min.css">
    
      @stack('css')
  </head>

  <body>

    <div class="blog-masthead">
      <div class="container">
        <nav class="nav">
          <a class="nav-link {{ Request::url() == route('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
          
          @foreach(Core::getData('categories', 'all', [], [ 'ord', 'asc' ], 5) as $category)
            	<a href="{{ route('categories.view', [ 'slug' => $category->slug ]) }}" class="nav-link {{ Request::url() == route('categories.view', [ 'slug' => $category->slug ]) ? 'active' : '' }}">
          	    	{{ $category->name }}
            	</a>
            @endforeach
            
            @foreach(Core::getData('pages', 'all', [], [], 5) as $page)
              	@if($page->slug != 'home')
              		<a href="{{ route('pages.view', [ 'slug' => $page->slug ]) }}" class="nav-link {{ Request::url() == route('pages.view', [ 'slug' => $page->slug ]) ? 'active' : '' }}">
            	    	{{ $page->name }}
            	    </a>
              	@endif
            @endforeach
        </nav>
      </div>
    </div>

    <div class="blog-header">
      <div class="container">
        <h1 class="blog-title">{{ Settings::get('sitename') }}</h1>
        <p class="lead blog-description">
        	{{ Settings::get('tagline') }}
        </p>
      </div>
    </div>

    <div class="container">
    	<div class="row">
    		<div class="col-sm-8 blog-main">
				@include('partials/flash_bulma')
				@yield('content')
				{!! Theme::content() !!}
			</div>

        <div class="col-sm-3 offset-sm-1 blog-sidebar">
          <div class="sidebar-module sidebar-module-inset">
            <h4>About</h4>
            <p>
            	{{ Settings::get('description') }}
            </p>
          </div>
          <div class="sidebar-module">
            <h4>Archives</h4>
            <ol class="list-unstyled">
            	@foreach(\App\Modules\Posts\Models\Post::getArchivePeriods() as $period)
            		<?php $time = strtotime($period->year . '-' . $period->month . '-30') ?>
            		<li>
            			<a href="{{ route('posts.archive', [ 'year' => $period->year, 'month' => date('m', $time) ]) }}">
            				{{ date('F Y', $time) }}
            			</a>
            		</li>
            	@endforeach
            </ol>
          </div>
          <div class="sidebar-module">
            <h4>Elsewhere</h4>
            <ol class="list-unstyled">
              <li>
              	<a href="https://github.com/adaptcms/AdaptCMS" target="_blank">GitHub</a>
              </li>
              <li>
              	<a href="https://twitter.com/adaptcms" target="_blank">Twitter</a>
              </li>
              <li>
              	<a href="https://www.facebook.com/AdaptCMS-104913829614704/" target="_blank">Facebook</a>
              </li>
            </ol>
          </div>
        </div><!-- /.blog-sidebar -->

      </div><!-- /.row -->

    </div><!-- /.container -->

    <footer class="blog-footer">
      <p>Blog template built by <a href="https://twitter.com/mdo">@mdo</a>.</p>
      <p>
      	Powered by <a href="https://www.adaptcms.com" target="_blank">
      	  AdaptCMS {{ ucfirst(Core::getVersion()) }}
      	</a>
      </p>
      <p>
        <a href="#">Back to top</a>
      </p>
    </footer>


    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.0/semantic.min.js"></script>
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