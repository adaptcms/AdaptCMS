{{ addCrumb($tag, null) }}

<h1>{{ tag }}</h1>

{% if empty(articles) %}
	<p>No Articles Found</p>
{% else %}
	<ul>
		{% loop article in articles %}
			<li>
				<a href="{{ url('article_view', $article) }}">
					{{ article['Article']['title'] }}
				</a> @
				{{ time(article['Article']['created']) }}
			</li>
		{% endloop %}
	</ul>
{% endif %}

{{ partial('pagination') }}