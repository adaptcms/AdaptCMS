{% loop link in data %}
	<li>
		{% if not empty(link['Link']['file_id']) %}
			{{ set title = $this->Html->image('/' . $link['File']['dir'].$link['File']['filename'], array(
				'class' => 'span6',
				'style' => 'max-width: 300px;'
			)) }}
		{% elseif not empty(link['Link']['image_url']) %}
			{{ set title = $this->Html->image('/' . $link['Link']['image_url'], array(
				'class' => 'span6',
				'style' => 'max-width: 300px;'
			)) }}
		{% else %}
			{{ set title = $link['Link']['link_title'] }}
		{% endif %}

		<a href="{{ link['Link']['url'] }}" target="{{ link['Link']['link_target'] }}" class="track clearfix" id="{{ link['Link']['id'] }}">
			{{ title }}
		</a>
	</li>
{% endloop %}

<div class="clearfix"></div>

<a href="{{ url('links_apply') }}" class="btn btn-info">
	Submit Link <i class="fa fa-plus"></i>
</a>