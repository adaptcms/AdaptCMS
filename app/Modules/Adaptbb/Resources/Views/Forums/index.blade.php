<h1 class="ui center aligned icon header">
    <i class="circular wpforms icon"></i>
    Community Forums
</h1>

@foreach($forums as $category)
    <!-- DESKTOP AND TABLET ONLY -->
    <table class="ui red stackable very padded large forums table computer only tablet only">
        <thead>
            <th>{{ $category['category']->name }}</th>
            <th>Topics</th>
            <th class="right aligned">Replies</th>
            <th class="right aligned">Latest Activity</th>
        </thead>
        <tbody>
            @foreach($category['forums'] as $forum)
                <tr>
                    <td>
                        <a href="{{ route('plugin.adaptbb.forums.view', [ 'slug' => $forum->slug ]) }}">
                            <strong>{{ $forum->name }}</strong>
                        </a>

                        <p>{!! $forum->description !!}</p>
                    </td>
                    <td>
                        {{ number_format($forum->topics_count) }}
                    </td>
                    <td class="right aligned">
                        {{ number_format($forum->replies_count) }}
                    </td>
                    <td class="right aligned">
                        <?php $last_post = $forum->getLatestPost(); ?>

                        @if(empty($last_post))
                            No posts
                        @else
                            <div class="ui feed pull-right">
                                <div class="event">
                                    <div class="label">
                                        <img src="{{ $last_post->user->getProfileImage() }}">
                                    </div>
                                    <div class="content">
                                        <div class="date">
                                            {{ Core::getDateShort($last_post->created_at) }}
                                        </div>
                                        <div class="summary">
                                            {{ $last_post->user->getName() }} posted <a href="{{ $last_post->getUrl() }}">{{ $last_post->name }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- MOBILE ONLY -->
    <table class="ui red stackable very padded large forums table mobile only">
        <thead>
            <th>{{ $category['category']->name }}</th>
        </thead>
        <tbody>
            @foreach($category['forums'] as $forum)
                <tr>
                    <td>
                        <a href="{{ route('plugin.adaptbb.forums.view', [ 'slug' => $forum->slug ]) }}">
                            <strong>{{ $forum->name }}</strong>
                        </a>

                        <p>{!! $forum->description !!}</p>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endforeach

{!! Theme::partial('adaptbb.user_box') !!}
