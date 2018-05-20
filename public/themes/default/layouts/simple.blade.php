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

      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha256-eZrrJcwDc/3uDhsdt61sL2oOBY362qM3lon1gyExkL0=" crossorigin="anonymous" />
      <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
      <!-- Bulma Version 0.6.0 -->
      <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bulma@0.7.1/css/bulma.min.css" />
      <link rel="stylesheet" type="text/css" href="/themes/default/assets/css/main.compiled.min.css">

      @stack('css')
    </head>
    <body>
        @include('partials/flash_bulma')
        {!! Theme::content() !!}

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

      <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.0/underscore-min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
      <script src="/js/vendor/jquery.tagsinput.min.js"></script>

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
