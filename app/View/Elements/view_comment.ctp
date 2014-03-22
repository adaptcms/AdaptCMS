<a name="comment_{{ data['Comment']['id'] }}"></a>

<div class="span5 well comment level_{{ level }}" id="comment-{{ data['Comment']['id'] }}">
	<div class="header pull-left">
		<h5>
			<span class="pull-left">
				{% if not empty(data['User']['username']) %}
					<a href="{{ url('user_profile', $data['User']['username']) }}">
						{{ data['User']['username'] }}
					</a>
				{% elseif not empty(data['Comment']['author_name']) && !empty($data['Comment']['author_website']) %}
					{{ html.link($data['Comment']['author_name'], $data['Comment']['author_website'], array('target' => '_blank')) }}
				{% elseif not empty(data['Comment']['author_name']) && empty($data['Comment']['author_website']) %}
					{{ data['Comment']['author_name'] }}
				{% else %}
					Guest
				{% endif %}
				 @
				{{ time(data['Comment']['created'], 'words') }}
			</span>
		</h5>
	</div>
	<div class="btn-group pull-right">
		{% if not empty(permissions['related']['comments']['admin_edit']) && $this->Admin->hasPermission($permissions['related']['comments']['admin_edit'], $data['User']['id']) %}
			<a href="{{ url('comment_edit', $data) }}">
				edit <i class="fa fa-pencil"></i>
			</a>
		{% endif %}
		{% if not empty(permissions['related']['comments']['admin_delete']) && $this->Admin->hasPermission($permissions['related']['comments']['admin_delete'], $data['User']['id']) %}
			<a href="{{ url('comment_delete', $data) }}" class="btn-confirm">
				delete <i class="fa fa-trash-o"></i>
			</a>
		{% endif %}
	</div>
	<div class="clearfix"></div>

	<div class="body">
		{{ data['Comment']['comment_text'] }}

		{% if not empty(data['Data']) %}
			{% loop value in data['Data'] %}
				<dt>
					{{ humanize(index) }}
				</dt>
				<dd>
					{{ value }}
				</dd>
			{% endloop %}
		{% endif %}
	</div>

	{% if $level != 3 %}
		<div class="footer">
			{{ html.link('reply <i class="fa fa-reply"></i>', '#reply', array('class' => 'pull-right', 'escape' => false)) }}
		</div>
		<div class="clearfix"></div>
	{% endif %}
</div>

<div class="clearfix"></div>