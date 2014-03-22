{{ tinymce.simple }}

{{ js('jquery.smooth-scroll.min.js') }}
{{ js('jquery.blockui.min.js') }}

{{ setTitle('Forums - ' . $forum['title'] . ' :: ' . $topic['subject']) }}

{{ addCrumb('Forums', url('adaptbb_forums')) }}
{{ addCrumb($forum['title'] . ' Forum', url('adaptbb_view_forum', $forum['slug'])) }}
{{ addCrumb('View Topic', null) }}

<h2>
	Viewing {{ topic['topic_type'] }}
	<small>
		{{ topic['subject'] }}
	</small>
</h2>

<!--nocache-->
{% if $this->Admin->hasPermission($permissions['related']['forum_topics']['change_status']) %}
	<a href="{{ url('adaptbb_topic_change_status', $topic['id']) }}" class="btn btn-info pull-right marg-btm">
		{% if $topic['status'] == 0 %}
			Open Topic <i class="fa fa-unlock"></i>
		{% else %}
			Close Topic <i class="fa fa-lock"></i>
		{% endif %}
	</a>
{% endif %}
<!--/nocache-->
<div class="clearfix"></div>

{{ partial('error', array('message' => 'Your post could not be made.', 'hidden' => true)) }}
{{ partial('success', array('message' => 'Your post is live.', 'hidden' => true)) }}

<div id="posts_container">
    <div id="posts" class="col-lg-12">
	    {% loop post in posts %}
            <a name="post{{ post['ForumPost']['id'] }}"></a>
            <div class="post well col-lg-12" id="post-{{ post['ForumPost']['id'] }}">
                <div class="post-info col-lg-2 pull-left">
                    {% if not empty(post['User']['username']) %}
	                    <a href="{{ url('user_profile', $post) }}">
		                    <h4 class="user">{{ post['User']['username'] }}</h4>
	                    </a>
	                {% else %}
                        <h4 class="user">Guest</h4>
	                {% endif %}

                    {% if not empty(post['User']['settings']['avatar']) %}
                        {{ image(
                            $this->webroot . 'uploads/avatars/' . $post['User']['settings']['avatar'],
                            array('style' => 'max-width: 140px;margin-bottom: 15px', 'class' => 'img-polaroid')
                        ) }}
                    {% endif %}

                    <div class="info">
                        <strong>Joined</strong>
                        {{ time(post['User']['created'], 'F d, Y') }}
                    </div>
                    <div class="clearfix"></div>

                    <div class="actions">
                        <!--nocache-->
	                    <a href="{{ url('messages_send') }}/{{ post['User']['username'] }}" class="btn btn-primary">
		                    Send Message <i class="fa fa-envelope"></i>
	                    </a>

                        {% if $topic['status'] == 1 && $this->Admin->hasPermission($permissions['related']['forum_posts']['ajax_post']) %}
                            <div class="btn-group">
	                            <a href="#" class="btn btn-warning reply">
		                            Reply <i class="fa fa-reply"></i>
	                            </a>
	                            <a href="#" class="btn btn-success quote">
		                            Quote <i class="fa fa-quote-right"></i>
	                            </a>
                            </div>
                        {% endif %}

                        <div class="btn-group">
                            {% if $post['type'] == 'post' %}
                                {% if $this->Admin->hasPermission($permissions['related']['forum_posts']['ajax_edit']) %}
			                        <a href="{{ url('adaptbb_post_edit', $post) }}" data-id="edit_post_{{ post['ForumPost']['id'] }}" class="btn btn-primary edit-post">
				                        Edit <i class="fa fa-pencil"></i>
			                        </a>
                                {% endif %}
                            {% else %}
                                {% if $this->Admin->hasPermission($permissions['related']['forum_topics']['edit']) %}
	                                <a href="{{ url('adaptbb_topic_edit', $post['ForumPost']['id']) }}" class="btn btn-primary">
		                                Edit <i class="fa fa-pencil"></i>
	                                </a>
                                {% endif %}
                            {% endif %}

                            {% if $this->Admin->hasPermission($permissions['related']['forum_' . $post['type'] . 's']['delete']) %}
	                            {% if $post['type'] == 'post' %}
			                        <a href="{{ url('adaptbb_post_delete', $post) }}" class="btn btn-danger btn-confirm" title="this post">
				                        Delete <i class="fa fa-minus"></i>
			                        </a>
	                            {% else %}
			                        <a href="{{ url('adaptbb_topic_delete', $post) }}" class="btn btn-danger btn-confirm" title="this topic">
				                        Delete <i class="fa fa-minus"></i>
			                        </a>
	                            {% endif %}
                            {% endif %}
                        </div>
                        <!--/nocache-->
                    </div>
                </div>
                <div class="post-body col-lg-10">
                    <h4 class="subject">
                        {% if empty(post['ForumPost']['subject']) %}
                            RE:
                        {% endif %}
                        {{ topic['subject'] }}
                        <small>
                            @ {{ time(post['ForumPost']['created'], 'words') }}
                        </small>
                    </h4>

                    <span class="message">
                        {{ post['ForumPost']['content'] }}
                    </span>

                    {% if not empty(post['User']['Data']['signature']) %}
                        <span class="signature">
                            <hr>

                            {{ post['User']['Data']['signature'] }}
                        </span>
                    {% endif %}

                    <!--nocache-->
                    {% if $post['type'] == 'post' && $this->Admin->hasPermission($permissions['related']['forum_posts']['ajax_edit']) %}
                        {{ partial('post_reply', array(
                            'topic_id' => $topic['id'],
                            'forum_id' => $forum['id'],
                            'post_id' => $post['ForumPost']['id'],
                            'post' => $post['ForumPost']
                        )) }}
                    {% endif %}
                    <!--/nocache-->
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        {% endloop %}

        {{ paginator.options(array('url' => array(
            'controller' => 'forum_topics',
            'action' => 'view',
            'slug' => $topic['slug'],
            'forum_slug' => $forum['slug']
        ))) }}

        {{ partial('pagination') }}
    </div>
</div>
<div class="clearfix"></div>

{% if $topic['status'] == 1 %}
	{{ partial('post_reply', array(
		'topic_id' => $topic['id'],
		'forum_id' => $forum['id']
	)) }}
{% endif %}