{% if empty(articles) %}
	<p>No Articles Found</p>
{% else %}
	{% loop article in articles %}
		<div class="span8 no-marg-left clearfix">
			<a href="{{ url('article_view', $article) }}"><h2>{{ article['Article']['title'] }}</h2></a>
			<p class="lead">
				@ <em>{{ time(article, '', 'created') }}</em>
			</p>

			<blockquote>
				{{ getTextAreaData(article) }}
			</blockquote>

			<div id="post-options">
		        <span class="pull-left">
			        <a href="{{ url('article_view', $article) }}" class="btn btn-primary">Read More</a>
			        <span style="margin-left: 10px">
		                <i class="fa fa-comment"></i>&nbsp;
				        <a href="{{ url('article_view', $article) }}#comments">{{ article['CommentsCount'] }} Comments</a>
		            </span>
		            <span style="margin-left: 10px">
		                <i class="fa fa-user"></i>&nbsp;
		                Posted by <a href="{{ url('user_profile', $article) }}">{{ article['User']['username'] }}</a>
		            </span>
		        </span>
		        <span class="pull-right">
			        {% if not empty(article['Article']['tags']) %}
						{% loop tag in article['Article']['tags'] %}
			                <a href="{{ url('article_tag', $tag) }}" class="tags">
				                <span class="btn btn-success">{{ tag }}</span>
			                </a>
		                {% endloop %}
			        {% endif %}
		        </span>
			</div>
		</div>
		<hr>
	{% endloop %}
{% endif %}

{{ partial('pagination') }}