@extends('core::Layouts/install')

@section('content')
    <h2>Finished!</h2>

    <div class="ui red very padded segment">
      <p>
        We're so happy to see that you've finished installing AdaptCMS!
      </p>
      <p>
        We want to make the best CMS that's possible so we're going to highlight a few of the best
        things you should be aware of. Please feel free to reach out to us below.
      </p>

      <div class="ui stackable center aligned grid">
        <div class="six column row">
          <i class="huge icons">
              <a href="https://www.facebook.com/AdaptCMS-104913829614704/" target="_blank"><i class="facebook icon"></i></a>
              <a href="https://plus.google.com/b/103405380776422939951/103405380776422939951" target="_blank"><i class="google plus icon"></i></a>
              <a href="https://twitter.com/adaptcms" target="_blank"><i class="twitter icon"></i></a>
              <a href="https://github.com/adaptcms/AdaptCMS" target="_blank"><i class="github icon"></i></a>
              <a href="https://www.youtube.com/channel/UCsX9sKDo07DBr9hclDw3PMw" target="_blank"><i class="youtube icon"></i></a>
              <a href="mailto:charliepage88@gmail.com?subject=Add me to AdaptCMS Slack" target="_blank"><i class="slack icon"></i></a>
          </i>
        </div>
      </div>
    </div>

    <div class="ui center aligned grid">
      <div class="ui two column doubling stackable grid container">
          <div class="column">
            <a
              href="{{ route('admin.dashboard') }}"
              class="ui right labeled icon primary massive button"
            >
                Admin
                <i class="dashboard icon"></i>
            </a>
          </div>
          <div class="column">
            <a href="{{ route('home') }}" class="ui right labeled icon green massive button">
                View Your Site
                <i class="home icon"></i>
            </a>
          </div>
      </div>
    </div>
  </div>

    <div class="ui horizontal segments">
      <div class="ui red very padded segment">
        <h2 class="header">
          Plugins and Themes
        </h2>

        <p>
          With our custom built <a href="https://marketplace.adaptcms.com">Marketplace</a> and installation/update
          system, you can get going with addons straight away.
        </p>
      </div>
      <div class="ui violet very padded segment">
        <h2 class="header">
          Full Search
        </h2>

        <p>
          From posts to pages, we index all content so that admins can search when manging content
          and the public can as well. We feel one of the most important aspects of a modern website
          with content is search capabilities.
        </p>
      </div>
      <div class="ui blue very padded segment">
        <h2 class="header">
          SEO
        </h2>

        <p>
          It seems so simple, but it's not often done right. All posts and pages feature slugs for
          SEO attractive URL's. Meta keywords and description fields can be set too.
        </p>
        <p>
          Starting with the Alpha release, we also include a Sitemap plugin for better SEO results.
        </p>
      </div>
    </div
@stop
