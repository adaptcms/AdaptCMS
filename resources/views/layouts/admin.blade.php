<!DOCTYPE html>
<html>
    <head>
        <!-- Standard Meta -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Site Properties -->
        <title>AdaptCMS {{ Core::getVersion() }} Admin</title>

        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.0/semantic.min.css">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

        <link rel="stylesheet" type="text/css" href="/css/jquery.tagsinput.min.css">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/semantic-ui-calendar/0.0.8/calendar.min.css">

        <!-- code mirror -->
        <link href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/codemirror.min.css" rel="stylesheet" />
        <link href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/theme/monokai.min.css" rel="stylesheet" />

        <link rel="stylesheet" type="text/css" href="/css/main.compiled.min.css">

        @stack('css')

        @routes()
    </head>
    <body class="pushable">
        <div class="ui large top pointing menu inverted main-menu">
            <div class="logo item">
                <a href="{{ route('admin.dashboard') }}">
                    <img src="/img/AdaptCMSLogoPNG_2.png" class="ui image">
                </a>
            </div>
            @include('partials/admin-menu-top')
        </div>

        <div class="ui vertical inverted sidebar menu">
            @include('partials/admin-menu-left-mobile')

            <div class="divider item"></div>

            @include('partials/admin-menu-top')
        </div>

        <div class="pusher">
                <div class="ui stackable grid">
                    <div class="row">
                        <div class="four wide column left-container">

                            <div class="ui blue large vertical inverted left pointing menu computer only tablet only">
                                <a class="toc item">
                                    <i class="sidebar icon"></i>
                                    <img src="/img/AdaptCMSLogoPNG_2.png" class="ui image">
                                </a>
                                @include('partials/admin-menu-left')
                            </div>

                            <div class="ui black large vertical inverted left pointing menu mobile only">
                                <a class="toc item">
                                    <i class="sidebar icon"></i>
                                    <img src="/img/AdaptCMSLogoPNG_2.png" class="ui image">
                                </a>
                                @include('partials/admin-menu-left')
                            </div>

                        </div>
                        <div id="vue-app" class="twelve wide column">
                            @include('partials/flash')

                            <div id="main-container" class="ui fluid red left aligned raised padded text container segment pull-left">
                                <a href="{{ request()->headers->get('referer') }}" class="ui left labeled icon button large violet margin-bottom-10 right floated">
                                    Back
                                    <i class="reply icon"></i>
                                </a>
                                <div class="clear"></div>

                                @yield('content')
                            </div>

                        </div>
                    </div>
                </div>
        </div>

        <div class="ui inverted vertical footer segment">
            <div class="ui center aligned container">
                <div class="pull-left">
                    &copy; Copyright 2006-{{ date('Y') }}
                    <a href="https://www.adaptcms.com" target="_blank">
                        AdaptCMS
                    </a>
                </div>
                <div class="pull-right">
                    Powered by
                    <a href="https://www.adaptcms.com" target="_blank">
                        AdaptCMS {{ Core::getVersion() }}
                    </a>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.0/semantic.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/semantic-ui-calendar/0.0.8/calendar.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/string.js/3.3.3/string.min.js"></script>

        <!-- wysiwyg editors -->
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/codemirror.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/mode/php/php.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/mode/javascript/javascript.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/mode/sass/sass.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/mode/scheme/scheme.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.35.0/mode/css/css.min.js"></script>

        <script src="//cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>

        <script src="/js/vendor/jquery.tagsinput.min.js"></script>
        <script src="/js/vendor/jquery-sortable.min.js"></script>

        @if (empty($ignore_vuejs))
            <script src="/js/vendor/vue.min.js"></script>
            <script src="/js/vendor/vue-router.min.js"></script>
        @endif

        @stack('js')

        <script src="/js/main.compiled.min.js"></script>
        <script src="/js/app.js"></script>

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
