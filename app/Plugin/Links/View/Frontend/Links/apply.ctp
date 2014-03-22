{{ tinymce.simple }}

{{ setTitle('Submit Link') }}

{{ addCrumb('Links', null) }}
{{ addCrumb('Submit Link', null) }}

{{ form.create('Link', array('class' => 'well admin-validate')) }}
    <h2>Submit Link</h2>

    {{ form.input('title', array('class' => 'required')) }}
    {{ form.input('url', array(
        'class' => 'required url',
        'label' => 'Website Address',
        'placeholder' => 'http://'
    )) }}
    {{ form.input('link_title') }}
    {{ form.hidden('link_target', array('value' => '_blank')) }}

    {{ form.input('image_url', array(
        'label' => 'Image URL (optional)',
        'class' => 'url',
        'placeholder' => 'http://'
    )) }}

    {% if not empty(captcha) %}
        <div id="captcha">
            {{ captcha.form() }}
        </div>
	{% endif %}

{{ form.end(array(
    'label' => 'Submit',
    'class' => 'btn btn-primary'
)) }}