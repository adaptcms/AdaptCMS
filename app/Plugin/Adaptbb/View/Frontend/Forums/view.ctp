{{ setTitle('Forums - ' . $forum['title']) }}

{{ paginator.options(array('url' => array(
	'controller' => 'forums',
	'action' => 'view',
	'slug' => $forum['slug']
))) }}

{{ addCrumb('Forums', array('action' => 'index')) }}
{{ addCrumb($forum['title'] . ' Forum', null) }}

<h2>
	{{ forum['title'] }} Forum
</h2>

<!--nocache-->
{% if $this->Admin->hasPermission($permissions['related']['forum_topics']['add']) %}
	<a href="{{ url('adaptbb_add_topic', $forum['slug']) }}" class="btn btn-info pull-right">
		New Topic <i class="fa fa-plus"></i>
	</a>
{% endif %}

{% if not empty(announcements) && $this->Admin->hasPermission($permissions['related']['forum_topics']['view']) %}
	<div class="table-responsive">
	    <table class="table table-striped">
	        <thead>
	        <tr>
	            <th></th>
	            <th>Announcements</th>
	            <th>Replies</th>
	            <th>Views</th>
	            <th>Last Post</th>
	        </tr>
	        </thead>

	        <tbody>
	            {% loop topic in announcements %}
	                <tr>
	                    <td style="width: 50px;">
	                        {% if $topic['ForumTopic']['status'] == 0 %}
	                            <i class="fa fa-exclamation-triangle fa-lg" title="Closed Announcement" alt="Closed Announcement"></i>
	                        {% elseif $topic['ForumTopic']['num_posts'] >= Configure::read('Adaptbb.num_posts_hot_topic') %}
	                            <i class="fa fa-fire fa-lg" title="Hot Announcement" alt="Hot Announcement"></i>
		                    {% else %}
	                            <i class="fa fa-asterisk fa-lg" title="Announcement" alt="Announcement"></i>
		                    {% endif %}
	                    </td>
	                    <td>
		                    <a href="{{ url('adaptbb_view_topic', $topic) }}">
			                    {{ topic['ForumTopic']['subject'] }}
		                    </a><br />

	                        by <a href="{{ url('user_profile', $topic) }}">{{ topic['User']['username'] }}</a>

	                        <em>
	                            {{ time(topic,'words', 'created') }}
	                        </em>
	                    </td>
	                    <td>
	                        {{ topic['ForumTopic']['num_posts'] }}
	                    </td>
	                    <td>
	                        {{ topic['ForumTopic']['num_views'] }}
	                    </td>
	                    <td>
	                        {% if empty(topic['NewestPost']) %}
	                            No Replies Made
		                    {% else %}
		                        <a href="{{ url('user_profile', $topic['NewestPost']) }}">{{ topic['NewestPost']['User']['username'] }}</a>
		                        <a href="{{ url('adaptbb_view_topic', $topic) }}#post{{ topic['NewestPost']['ForumPost']['id'] }}">
			                        <i class="fa fa-external-link"></i>
		                        </a><br />
	                            <em>{{ time(topic['NewestPost']['ForumPost']['created'], 'words') }}</em>
	                        {% endif %}
	                    </td>
	                </tr>
	            {% endloop %}
	        </tbody>
	    </table>
	</div>
{% endif %}

{% if empty(topics) || !$this->Admin->hasPermission($permissions['related']['forum_topics']['view']) %}
	<p>No Topics Found</p>
{% else %}
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th></th>
					<th>{{ paginator.sort('subject') }}</th>
					<th>{{ paginator.sort('replies') }}</th>
					<th>{{ paginator.sort('views') }}</th>
					<th>Last Post</th>
				</tr>
			</thead>

			<tbody>
				{% loop topic in topics %}
					<tr>
						<td style="width: 50px;">
							{% if $topic['ForumTopic']['status'] == 0 %}
								<i class="fa fa-exclamation-triangle fa-lg" title="Closed Topic" alt="Closed Topic"></i>
							{% elseif $topic['ForumTopic']['num_posts'] >= Configure::read('Adaptbb.num_posts_hot_topic') %}
								<i class="fa fa-fire fa-lg" title="Hot Topic" alt="Hot Topic"></i>
	                        {% elseif $topic['ForumTopic']['topic_type'] == 'sticky' %}
	                            <i class="fa fa-info fa-lg" title="Sticky Topic" alt="Sticky Topic"></i>
	                        {% else %}
								<i class="fa fa-list fa-lg" title="Topic" alt="Topic"></i>
							{% endif %}
						</td>
						<td>
	                        {% if $topic['ForumTopic']['topic_type'] == 'sticky' %}
	                            <strong>Sticky:</strong>
	                        {% endif %}

							<a href="{{ url('adaptbb_view_topic', $topic) }}">
								{{ topic['ForumTopic']['subject'] }}
							</a><br />

							by <a href="{{ url('user_profile', $topic) }}">{{ topic['User']['username'] }}</a>

							<em>
								{{ time(topic, 'words', 'created') }}
							</em>
						</td>
						<td>
							{{ topic['ForumTopic']['num_posts'] }}
						</td>
						<td>
							{{ topic['ForumTopic']['num_views'] }}
						</td>
						<td>
							{% if empty(topic['NewestPost']) %}
								No Replies Made
							{% else %}
								<a href="{{ url('user_profile', $topic['NewestPost']) }}">{{ topic['NewestPost']['User']['username'] }}</a>
								<a href="{{ url('adaptbb_view_topic', $topic) }}#post{{ topic['NewestPost']['ForumPost']['id'] }}">
									<i class="fa fa-external-link"></i>
								</a><br />
								<em>{{ time(topic['NewestPost']['ForumPost']['created'], 'words') }}</em>
							{% endif %}
						</td>
					</tr>
				{% endloop %}
			</tbody>
		</table>
	</div>
{% endif %}
<!--/nocache-->

{{ partial('pagination') }}