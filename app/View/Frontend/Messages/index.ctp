{{ addCrumb('Profile', url('user_profile', current_user('username'))) }}
{{ addCrumb('Messages', null) }}

<div class="pull-left">
    <h1>Messages - {{ current_box }}</h1>
</div>
<div class="btn-group pull-right">
	<a href="{{ url('messages_send') }}" class="btn btn-primary">
		Send Message
	</a>
    <a class="btn dropdown-toggle btn-success" data-toggle="dropdown">
    View <i class="fa fa-picture-o"></i>
    <span class="caret"></span>
    </a>
    <ul class="dropdown-menu" style="min-width: 0px">
	    <li{% if $box == 'inbox' %} class="active"{% endif %}>
		    <a href="{{ url('messages_index_box', 'inbox') }}">Inbox ({{ box_count['inbox'] }})</a>
	    </li>
	    <li{% if $box == 'outbox' %} class="active"{% endif %}>
		    <a href="{{ url('messages_index_box', 'outbox') }}">Outbox ({{ box_count['outbox'] }})</a>
	    </li>
	    <li{% if $box == 'sentbox' %} class="active"{% endif %}>
		    <a href="{{ url('messages_index_box', 'sentbox') }}">Sentbox ({{ box_count['sentbox'] }})</a>
	    </li>
	    <li{% if $box == 'archive' %} class="active"{% endif %}>
		    <a href="{{ url('messages_index_box', 'archive') }}">Archive ({{ box_count['archive'] }})</a>
	    </li>
    </ul>
</div>
<div class="clearfix"></div>

{% if not empty(messages) %}
    <table class="table table-hover">
        <thead>
            <tr>
                <th>{{ paginator.sort('is_read', 'Read') }}</th>
                {% if $box == 'archive' %}
                    <th>Box</th>
	            {% endif %}
                <th>{{ paginator.sort('title', 'Subject') }}</th>
                <th>{{ paginator.sort('Sender.username', 'From') }}
                <th>{{ paginator.sort('Receiver.username', 'To') }}
                <th class="hidden-xs">{{ paginator.sort('created') }}</th>
                <th class="hidden-xs">{{ paginator.sort('last_reply_time', 'Last Reply') }}</th>
                {% if $box != 'outbox' %}
                    <th></th>
	            {% endif %}
            </tr>
        </thead>

        <tbody>
            {% loop message in messages %}
            <tr>
                <td>
                    {% if $message['Message']['is_read'] == 1 %}
                        <i class="fa fa-check"></i>
                    {% else %}
                        <i class="fa fa-minus"></i>
                    {% endif %}
                </td>
                {% if $box == 'archive' %}
                    <td>
                        {% if $message['Sender']['username'] == $username && $message['Message']['is_read'] == 0 %}
	                        <a href="{{ url('messages_index_box', 'outbox') }}">Outbox</a>
                        {% elseif $message['Sender']['username'] == $username %}
	                        <a href="{{ url('messages_index_box', 'sentbox') }}">Sentbox</a>
                        {% else %}
                            <a href="{{ url('messages_index_box', 'inbox') }}">Inbox</a>
	                    {% endif %}
                    </td>
                {% endif %}
                <td>
                    {% if $message['Message']['parent_id'] == 0 %}
                        <a href="{{ url('messages_view', $message) }}">{{ message['Message']['title'] }}</a>
                    {% else %}
	                    <a href="{{ url('messages_view', $message) }}#message-{{ message['Message']['id'] }}">{{ message['Message']['title'] }}</a>
                    {% endif %}
                </td>
                <td>
				    <a href="{{ url('user_profile', $message['Sender']['username']) }}">
					    {{ message['Sender']['username'] }}
				    </a>
                </td>
                <td>
	                <a href="{{ url('user_profile', $message['Receiver']['username']) }}">
		                {{ message['Receiver']['username'] }}
	                </a>
                </td>
                <td class="hidden-xs">
                    {{ time(message['Message']['created'], 'words') }}
                </td>
                <td class="hidden-xs">
                    {% if $message['Message']['last_reply_time'] == '0000-00-00 00:00:00' %}
                        No Replies
                    {% else %}
                        {{ time(message['Message']['last_reply_time'], 'words') }}
					{% endif %}
                </td>
                {% if $box != 'outbox' %}
                    <td>
                        {% if $box != 'archive' && $message['Message']['is_read'] == 0 && $message['Message']['receiver_user_id'] == $this->Session->read('Auth.User.id') %}
                            <a href="{{ url('messages_move', array('mark_read', $message['Message']['id'])) }}" class="btn btn-primary">
	                            <i class="fa fa-check"></i> Mark Read
                            </a>
                        {% endif %}
                        {% if $box != 'archive' %}
		                    <a href="{{ url('messages_move', array('archive', $message['Message']['id'])) }}" class="btn btn-info">
			                    <i class="fa fa-check"></i> Archive Message
		                    </a>
                        {% elseif $box == 'archive' %}
		                    <a href="{{ url('messages_move', array('inbox', $message['Message']['id'])) }}" class="btn btn-success">
			                    <i class="fa fa-check"></i> Move to Inbox
		                    </a>
                        {% endif %}
                    </td>
                {% endif %}
            </tr>
            {% endloop %}
        </tbody>
    </table>

    {{ partial('pagination') }}
{% else %}
    <div class="well span12 no-marg-left">
        <p>
            No Messages in {{ current_box }}
        </p>
    </div>
{% endif %}