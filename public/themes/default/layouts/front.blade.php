<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 
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

  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <!-- Bulma Version 0.6.0 -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bulma@0.6.1/css/bulma.min.css" />
  <link rel="stylesheet" type="text/css" href="/themes/default/assets/css/main.compiled.min.css">

  @stack('css')
  {!! Theme::asset()->styles() !!}

  @routes()
</head>
<body>
  @include('users::Users/admin-check')
  
  <section class="hero is-info is-medium is-bold">
    <div class="hero-head">
      <nav class="navbar">
        <div class="container">
          <div class="navbar-brand">
            <a class="navbar-item title is-4" href="/">
              {{ Settings::get('sitename') }}
            </a>
            <span class="navbar-burger burger" data-target="navbarMenu">
              <span></span>
              <span></span>
              <span></span>
            </span>
          </div>
          <div id="navbarMenu" class="navbar-menu">
            <div class="navbar-end">
              {!! Theme::partial('nav') !!}
            </div>
          </div>
        </div>
      </nav>
    </div>
    <div class="hero-body">
      <div class="container has-text-centered">
        <h1 class="title">
          {{ Settings::get('tagline') }}
        </h1>
        <h2 class="subtitle">
          {{ Settings::get('description') }}
        </h2>
      </div>
    </div>

  </section>

  <div class="box cta">
    <p class="has-text-centered">
      <span class="tag is-primary">New</span> 
      @foreach(Core::getData('posts', 'all', [], [ 'created_at', 'desc' ], 1) as $post)
        <a href="{{ route('posts.view', [ 'slug' => $post->slug ]) }}">{{ $post->name }}</a>
      @endforeach
    </p>
  </div>

  <section class="container">
      @include('partials/flash')
      @yield('content')
      {!! Theme::content() !!}
  </section>

  <footer class="footer">
    <div class="container">
      <div class="content has-text-centered">
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
  </footer>
  
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