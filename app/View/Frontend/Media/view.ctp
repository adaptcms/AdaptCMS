{{ js('../css/fancybox/jquery.fancybox.js') ?>
{{ css('fancybox/jquery.fancybox') ?>

{{ js('../css/fancybox/helpers/jquery.fancybox-thumbs.js') ?>
{{ css('fancybox/helpers/jquery.fancybox-thumbs') ?>

{{ addCrumb('Media Index', array('action' => 'index')) }}
{{ addCrumb('View Media Library', null) }}

<h1 class="pull-left">
	{{ media['Media']['title'] }}
</h1>

<a href="{{ url('media_index') }}" class="btn btn-primary pull-right">
	<i class="fa fa-arrow-left"></i> Library List
</a>
<div class="clearfix"></div>

{% if empty(files) %}
    <div class="well">
        No Images found
    </div>
{% else %}
	<ul class="thumbnails unstyled no-pad-left">
		{% loop file in files %}
			<li class="col-lg-4 list-unstyled{% if $index % 3 === 0 %} no-marg-left{% endif %}">
				<a href="{{ webroot }}{{ file['File']['dir'] . $file['File']['filename'] }}" class="fancybox thumbnail" rel="fancybox" title="{% if not empty(file['File']['caption']) %}{{ file['File']['caption'] }}{% else %}{{ file['File']['filename'] }}{% endif %}">
					<img src="{{ webroot }}{{ file['File']['dir'] . $file['File']['filename'] }}">
				</a>
			</li>
		{% endloop %}
	</ul>

	<div class="clearfix"></div>

	{{ partial('admin_pagination') }}
{% endif %}