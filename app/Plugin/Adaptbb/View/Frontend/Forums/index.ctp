{{ setTitle('Forums') }}

{{ addCrumb('Forums', null) }}

<h1>Forum Index</h1>

<div class="col-lg-12" id="forum-index">
	{% loop category in categories %}
		{% if not empty(category['Forum']) %}
			<div class="table-responsive">
				<table class="table well category">
					<thead>
						<tr class="btn-success">
							<th>
								<h3>{{ category['ForumCategory']['title'] }}</h3>
							</th>
							<th>
								Topics
							</th>
							<th>
								Posts
							</th>
							<th>
								Newest Post
							</th>
						</tr>
					</thead>
					<tbody>
						{% if not empty(category['Forum']) %}
							{% loop forum in category['Forum'] %}
								<tr>
									<td>
										<a href="{{ url('adaptbb_view_forum', $forum['slug']) }}">
											{{ forum['title'] }}
										</a>

										{% if not empty(forum['description']) %}
											{{ forum['description'] }}
										{% endif %}
									</td>
									<td>
										{{ forum['num_topics'] }}
									</td>
									<td>
										{{ forum['num_posts'] }}
									</td>
									<td>
										{% if empty(forum['NewestPost']) %}
											No Posts Made
										{% else %}
											by <a href="{{ url('user_profile', $forum['NewestPost']) }}">{{ forum['NewestPost']['User']['username'] }}</a>
											<a href="{{ url('adaptbb_view_topic', $forum['NewestPost']) }}#post{{ forum['NewestPost']['ForumPost']['id'] }}">
												<i class="fa fa-external-link"></i>
											</a>
											<em>{{ time(forum, 'words', 'created') }}</em>
										{% endif %}
									</td>
								</tr>
							{% endloop %}
						{% endif %}
					</tbody>
				</table>
			</div>
			<div class="clearfix"></div>
		{% endif %}
	{% endloop %}
</div>
<div class="clearfix"></div>

<div class="header btn-primary" id="forum-index-stats-header">
	<h3>Stats</h3>
</div>
<div class="well" id="forum-stats">
	<dl class="pull-left dl-horizontal">
		<dt>Total Topics</dt>
		<dd>{{ categories['Stats']['topics'] }}</dd>

		<dt>Total Posts</dt>
		<dd>{{ categories['Stats']['posts'] }}</dd>
	</dl>
	<dl class="pull-left dl-horizontal">
		<dt>Total Users</dt>
		<dd>{{ categories['Stats']['users'] }}</dd>

		<dt>Newest User</dt>
		<dd>
            <!--nocache-->
			{% if $this->Admin->hasPermission($permissions['related']['users']['profile']) %}
				<a href="{{ url('user_profile', $categories['Stats']['newest_user']) }}">
					{{ categories['Stats']['newest_user']['User']['username'] }}
				</a>
			{% else %}
				{{ categories['Stats']['newest_user']['User']['username'] }}
			{% endif %}
            <!--/nocache-->
			<br />
			<em>{{ time(categories['Stats']['newest_user']['User']['created'], 'words') }}</em>
		</dd>
	</dl>
	<div class="clearfix"></div>
</div>
<div class="clearfix"></div>