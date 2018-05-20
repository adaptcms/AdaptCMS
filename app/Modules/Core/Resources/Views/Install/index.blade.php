@extends('core::Layouts/install')

@section('content')
    <h2>Server</h2>

    @if(!$checks['status'])
        <div class="ui icon error huge message">
            <i class="server icon"></i>
            <div class="content">
                <div class="header">
                    Uh-Oh!
                </div>

                <p>
                  We've found some problems with your server. But it's okay! Let's talk about how to fix this.
                </p>

                @if(!$checks['permissions_status'])
                    <a name="permissions"></a>

                    <h4>Permissions</h4>

                    <p>
                      See below for what commands to run on your server for proper permissions.
                      Or, checkout the docs.
                    </p>

<pre class="command-line" data-user="root" data-host="localhost"><code class="language-bash">
chown -R www-data:www-data .
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;</code>
</pre>

                    <a href="https://adaptcms.gitbooks.io/adaptcms/content/Getting-Started/installation.html" target="_blank" class="ui right labeled icon primary button">
                        Docs
                        <i class="file text icon"></i>
                    </a>
                @endif

                @if(!$checks['php']['status'])
                    <a name="php"></a>

                    <h4>PHP Version</h4>

                    <p>
                        This is the tougher one unfortunately. But we have help on the way, checkout the link below:
                    </p>

                    <a href="https://adaptcms.gitbooks.io/adaptcms/content/Getting-Started/installation.html" target="_blank" class="ui right labeled icon primary button">
                        Docs
                        <i class="file text icon"></i>
                    </a>
                @endif

                <i class="thumbs outline down big icon pull-right"></i>
            </div>
        </div>
    @endif

    <div class="ui icon {{ $checks['php']['status'] ? 'success' : 'error' }} huge message">
        <i class="server icon"></i>
        <div class="content">
            <div class="header">
                PHP Version
            </div>

            <div class="ui-list">
                <div class="item">
                    <div class="header">
                        Minimum Required
                    </div>

                    {{ $checks['php']['min'] }}
                </div>
                <div class="item">
                    <div class="header">
                      Currently Installed
                    </div>

                    {{ $checks['php']['current'] }}
                </div>

                @if(!$checks['php']['status'])
                    <div class="item">
                        <div class="header">
                          Error
                        </div>

                        <a href="#php">Please update permissions as described above</a>
                    </div>
                @endif
            </div>

            <i class="{{ $checks['php']['status'] ? 'thumbs outline up' : 'thumbs outline down' }} big icon pull-right"></i>
        </div>
    </div>

    @foreach($checks['permissions'] as $permission)
        <div class="ui icon {{ $permission['status'] ? 'success' : 'error' }} huge message">
            <i class="server icon"></i>
            <div class="content">
                <div class="header">
                    {{ $permission['is_dir'] ? 'Folder' : 'File' }} Permission
                </div>

                <div class="ui list">
                    <div class="item">
                        <div class="header">
                          Path
                        </div>

                        {{ $permission['full_path'] }}
                    </div>

                    @if(!$permission['status'])
                        <div class="item">
                            <div class="header">
                              Error
                            </div>

                            <a href="#permissions">Please update permissions as described above</a>
                        </div>
                    @endif
                </div>

                <i class="{{ $permission['status'] ? 'thumbs outline up' : 'thumbs outline down' }} big icon pull-right"></i>
            </div>
        </div>
    @endforeach

    <a href="{{ route('install.database') }}" class="ui right labeled icon huge green button pull-right {{ !$checks['status'] ? 'disabled' : '' }}">
        Next: Database
        <i class="database icon"></i>
    </a>
@stop

@push('css')
    <link href="/css/vendor/prism.min.css" rel="stylesheet" />
@endpush

@push('js')
    <script src="/js/vendor/prism.min.js"></script>
@endpush
