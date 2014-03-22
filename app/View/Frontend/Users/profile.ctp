{{ addCrumb('Profile', null) }}
{{ setTitle($user['username'] . "'s Profile") }}

<h1>
	{{ user['username'] }}'s Profile
</h1>

<ul id="profile-tabs" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Details</a>
	</li>
	{% if not empty(articles) %}
		<li>
			<a href="#articles" data-toggle="tab">Articles</a>
		</li>
	{% endif %}
	{% if not empty(comments) %}
		<li>
			<a href="#comments" data-toggle="tab">Comments</a>
		</li>
	{% endif %}
	<li>
		<a href="{{ url('messages_index') }}">Messages</a>
	</li>
    <!--nocache-->
	{% if logged_in %}
		<li class="pull-right">
			<a href="{{ url('user_edit') }}">Edit Your Profile</a>
		</li>
	{% endif %}
    <!--/nocache-->
</ul>

<div id="myTabContent" class="tab-content well">
	<div class="tab-pane fade active in" id="main">
		<dl class="dl-horizontal">
			<dt>
				Username
			</dt>
			<dd>
				{{ user['username'] }}
			</dd>

			<dt>
				Email
			</dt>
			<dd>
				<a class="deobfuscate" href="{{ email($user, true) }}" rel="nofollow">{{ email($user) }}</a>
			</dd>

			<dt>
				Status
			</dt>
			<dd>
				{% if not empty(user['status']) %}
					Active
				{% else %}
					Non-Active
				{% endif %}
			</dd>

			<dt>
				Signed Up
			</dt>
			<dd>
				{{ time(user['created']) }}
			</dd>

			<dt>
				Last Login
			</dt>
			<dd>
				{{ time(user['login_time']) }}
			</dd>

			<dt>
				Role
			</dt>
			<dd>
				{{ role['title'] }}
			</dd>

			{% if not empty(field_data) %}
				{% loop value in field_data %}
					<dt>
						{{ humanize(index) }}
					</dt>
					<dd>
						{{ value }}
					</dd>
				{% endloop %}
			{% endif %}
		</dl>
	</div>

	{% if not empty(articles) %}
		<div class="tab-pane" id="articles">
			<h3>Last 10 Articles</h3>

			<ul>
				{% loop article in articles %}
					<li>
						<a href="{{ url('article_view', $article) }}" target="_blank">
							{{ article['title'] }}
						</a> ( <strong>{{ article['Category']['title'] }}</strong> )
					</li>
				{% endloop %}
			</ul>
		</div>
	{% endif %}

	{% if not empty(comments) %}
		<div class="tab-pane" id="comments">
			<h3>Last 10 Comments</h3>

			<ul class="unstyled">
				{% loop comment in comments %}
					<li>
						@ {{ time(comment['created'], 'words') }} -
						<a href="{{ url('article_view', $article) }}#comment_{{ comment['id'] }}" target="_blank">view comment</a>

						<em>
							{{ truncate(comment['comment_text'], 150) }}
						</em>

						<div class="clearfix"></div><br />
					</li>
				{% endloop %}
			</ul>
		</div>
	{% endif %}
</div>