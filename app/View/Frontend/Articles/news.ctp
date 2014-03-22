{{ addCrumb($article['Category']['title'], url('category_view', $article['Category']['slug'])) }}
{{ addCrumb($article['Article']['title'], null) }}

{{ setTitle($article['Article']['title']) }}

{% if not empty(wysiwyg) %}
	{{ tinymce.simple }}
{% endif %}

{{ js('jquery.blockui.min.js') }}
{{ js('jquery.smooth-scroll.min.js') }}
{{ js('comments.js') }}

<div class="span8 no-marg-left">
	<h1>{{ article['Article']['title'] }}</h1>

	<p class="lead">
		@ <em>{{ time(article['Article']['created']) }}</em>
	</p>

	{{ getTextAreaData(article) }}

	<div id="post-options">
        <span class="pull-left">
	        <a href="{{ url('category_view', $category['slug']) }}" class="btn btn-primary">
		        {{ category['title'] }}
	        </a>
            <span style="margin-left: 10px">
                <i class="fa fa-search fa fa-user"></i>&nbsp;
                Posted by <a href="{{ url('user_profile', $user['username']) }}">{{ user['username'] }}</a>
            </span>
        </span>
        <span class="pull-right">
	        {% if not empty(tags) %}
	            {% loop tag in tags %}
	                <a href="{{ url('article_tag', $tag) }}" class="tags">
		                <span class="btn btn-success">{{ tag }}</span>
	                </a>
	            {% endloop %}
        	{% endif %}
        </span>
    </div>
</div>

<div class="clearfix"></div>

<h2>Comments</h2>

<!--nocache-->
{{ partial('post_comment', array('cached' => false)) }}
<!--/nocache-->

{{ partial('view_all_comments', array('comments' => $comments)) }}