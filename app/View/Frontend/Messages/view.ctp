{{ addCrumb('Profile', url('user_profile', current_user('username'))) }}
{{ addCrumb('Messages', url('messages_index')) }}
{{ addCrumb('View Message', null) }}

{{ tinymce.simple }}
{{ js('jquery.blockui.min.js') }}

<h1>
	View Message
	<small>
		{{ subject }}
	</small>
</h1>

<a href="{{ url('messages_index') }}" class="btn btn-primary pull-right">« Back to Messages</a>
<div class="clearfix"></div>

<div class="messages">
	{% loop message in messages %}
		<div class="span10 well message{% if $sender == $message['Sender']['id'] %} no-marg-left{% endif %}" id="message-{{ message['Message']['id'] }}">
			<a name="message-{{ message['Message']['id'] }}"></a>
			<div class="btn-toolbar pull-right">
				{% if $message['Sender']['username'] == $username && $message['Message']['sender_archived_time'] != '0000-00-00 00:00:00' ||
						$message['Receiver']['username'] == $username && $message['Message']['receiver_archived_time'] != '0000-00-00 00:00:00'
				 %}
					<span class="label label-info btn-group">
						Archived
					</span>
				{% endif %}
				{% if time.within('15 minutes', $message['Message']['created']) %}
					<span class="label label-success btn-group">
						New Message!
					</span>
				{% endif %}
				{% if $message['Message']['is_read'] == 0 %}
					<span class="label label-info btn-group">
						Unread
					</span>
				{% endif %}
			</div>

			<div class="header pull-left">
				<dl class="dl-horizontal">
					<dt>
						From
					</dt>
					<dd>
						{% if not empty(message['Sender']['username']) %}
							<a href="{{ url('user_profile', $message['Sender']['username']) }}">
								{{ message['Sender']['username'] }}
							</a>
						{% endif %}
					</dd>
				</dl>

				<dl class="dl-horizontal">
					<dt>To</dt>
					<dd>
						{% if not empty(message['Receiver']['username']) %}
							<a href="{{ url('user_profile', $message['Receiver']['username']) }}">
								{{ message['Receiver']['username'] }}
							</a>
						{% endif %}
					</dd>
				</dl>

				<em>
					@ {{ time(message['Message']['created'], 'words') }}
				</em>
			</div>
			<div class="clearfix"></div>

			<div class="body span8 no-marg-left" style="padding-top:10px">
				{{ message['Message']['message'] }}
			</div>
		</div>

		<div class="clearfix"></div>

		{% if $message['Message']['parent_id'] == 0 %}
			{{ set parent_id = $message['Message']['id'] }}
			{{ set title = 'RE: ' . $message['Message']['title'] }}
		{% else %}
			{{ set title = '' }}
			{{ set parent_id = $message['Message']['parent_id'] }}
		{% endif %}
	{% endloop %}
</div>

{{ partial('flash_error', array('message' => 'Please enter in a message')) }}
{{ partial('success', array('message' => 'Your message has been sent.')) }}

{% if $receiver == current_user('id') && $messages[0]['Message']['receiver_archived_time'] != '0000-00-00 00:00:00' || $sender == current_user('id') && $messages[0]['Message']['sender_archived_time'] != '0000-00-00 00:00:00' %}
	<p>
		This message has been archived, you can no longer reply to it unless you decide to move it back to your inbox.
		<a href="{{ url('messages_move', array('inbox', $messages[0]['Message']['id'])) }}" class="btn btn-success">
			<i class="icon-check icon-white"></i> Move to Inbox
		</a>
	</p>
{% else %}
	{{ form.create('Message', array('class' => 'SendMessage')) }}

		{{ form.input('message', array(
			'class' => 'span7',
			'style' => 'height: 100%',
	        'required' => false,
			'placeholder' => 'Enter in your message...'
		)) }}

		{{ form.hidden('parent_id', array(
			'value' => $parent_id
		)) }}
		{{ form.hidden('title', array(
			'value' => $title
		)) }}
		{{ form.hidden('receiver_user_id', array(
			'value' => ($messages[0]['Receiver']['id'] == $this->Session->read('Auth.User.id') ? $messages[0]['Sender']['id'] : $messages[0]['Receiver']['id'])
		)) }}

		{{ form.button('Send Reply', array(
			'type' => 'submit',
			'class' => 'btn btn-info',
			'style' => 'margin-top: 10px;margin-bottom: 10px'
		)) }}
	{{ form.end() }}
{% endif %}