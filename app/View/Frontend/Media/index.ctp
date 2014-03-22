{{ addCrumb('Media Index', null) }}

<h1>Media Libraries</h1>

{% if empty(media) %}
    <div class="well">
        No Libraries found
    </div>
{% else %}
	<ul class="thumbnails no-pad-left">
		{% loop library in media %}
			<li class="col-lg-3 list-unstyled panel panel-inverse">
				<div class="panel-heading">
					<h3 class="panel-title">
						<a href="{{ url('media_view', $library) }}">{{ library['Media']['title'] }}</a>
						<small>
							{{ library['File']['count'] }} Images
						</small>
					</h3>
				</div>

				{% if not empty(library['File']['id']) %}
					<a href="{{ url('media_view', $library) }}">
						<img src="{{ webroot }}{{ library['File']['dir'].'thumb/' . $library['File']['filename'] }}" style="width: 267px; height: 200px;">
					</a>
				{% endif %}
				<div class="caption">
					<em>
						Posted {{ time(library['Media']['created'], 'words') }}
					</em>
				</div>
			</li>
		{% endloop %}
	</ul>

	<div class="clearfix"></div>

	{{ partial('pagination') }}
{% endif %}