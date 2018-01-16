@if(isset($post->topic_id))
    <a name="reply_id_{{ $post->id }}"></a>
    <div class="comment">
        <a class="avatar">
            <img src="{{ $post->user->getProfileImage('small') }}">
        </a>
        <div class="content">
            <a class="author"><a href="{{ route('users.profile.view', [ 'username' => $post->user->username ]) }}">{{ $post->user->username }}</a></a>
            <div class="metadata">
                <span class="date">{{ Core::getDateShort($post->created_at) }}</span>
            </div>
            <div class="text">
                {!! nl2br($post->message) !!}
            </div>
            <div class="actions">
                <a href="" class="reply" @click.prevent="triggerReply()">Reply</a>
            </div>
        </div>
    </div>
@else
    <div class="ui segment">
        <img class="ui left floated middle small image" src="{{ $post->user->getProfileImage('medium') }}">

        <h4 class="mobile clear-left">
            By: <a href="{{ route('users.profile.view', [ 'username' => $post->user->username ]) }}">{{ $post->user->username }}</a> @ <small>{{ Core::getDateShort($post->created_at) }}</small>
        </h4>
        <p>{!! nl2br($post->message) !!}</p>
        <a href="" data-href="{{ route('plugin.adaptbb.topics.reply', [ 'id' => $post->id ]) }}" class="ui right labeled icon button green comment" @click.prevent="triggerReply()">
            Reply
            <i class="share icon"></i>
        </a>
    </div>
@endif
