Fields you can use in this template are also dependant on what custom fields you have, first we'll do the basics.

* Article Name `{{ article['Article']['title'] }}`
* Article Slug `{{ article['Article']['slug'] }}`
* Article Created Datetime `{{ article['Article']['created'] }}`
* Article Published Datetime `{{ article['Article']['publish_time'] }}`
* Category Name `{{ article['Category']['title'] }}`
* Category Slug `{{ article['Category']['slug'] }}`
* Article Author Username `{{ article['User']['username'] }}`
* Article Author Email `{{ article['User']['email'] }}`
* Number of Comments `{{ comments_count }}`

Custom Fields
-------------

For reference, check the custom fields section in the admin panel to find the specific list of fields for this category.

To use a custom field, simply do this:

`{{ article['Data']['field-slug'] }}`

Replace 'field-slug' with the slug of the field. (so System becomes system and Test This becomes test-this) To return the contents of a custom field if it is not a required field, do this:

    {% if not empty(article['Data']['field-slug']) %}
        {{ article['Data']['field-slug'] }}
    {% endif %}

This will render the custom field data if it is not empty. If you have a multi-option custom field, then the data will be in an array format. To loop through you can do this:

    {% if not empty(article['Data']['field-slug']) %}
        {% loop field in article['Data']['field-slug'] %}
            {{ field }}
        {% endloop %}
    {% endif %}

This will check to see if the data is not empty and if so, loop through it. If you are not familiar with the foreach loop, you may want to read up on it at [php.net](http://www.php.net/manual/en/control-structures.foreach.php).

Tags
----

Similar to the above code block, you can easily go through article tags like so:

    {% if not empty(article['Article']['tags']) %}
        {% loop tag in article['Article']['tags'] %}
            {{ tag }}
        {% endloop %}
    {% endif %}

To build a list that links to these tags (which is a category list type view, all articles showing with the specified tag), you can use this:

    {% if not empty(article['Article']['tags']) %}
        {% loop tag in article['Article']['tags'] %}
            <a href="{{ url('article_tag', $tag) }}" class="tags">
                <span class="btn btn-success">{{ tag }}</span>
            </a>
        {% endloop %}
    {% endif %}

Comments
--------

To show the element which will show a post comment form (based on permissions)

`{{ partial('post_comment', array('cached' => false)) }}`

And to show the element which will loop through the comments

`{{ partial('view_all_comments', array('comments' => $article['Comments'])) }}`

Related Articles
----------------

Lastly, there are scenarios where you want to show related article data. For example, when viewing a game page you would want to show the system name for example.
If you have the two linked together, you can try showing the name like so if you expect only one item linked to it:

    {% if not empty(related_articles['category']['platforms'][0]) %}
        {{ related_articles['category']['platforms'][0]['Article']['title'] }}
    {% endif %}

You can see where the syntax follows the above with the `['Article']['title']` bit. The same you see above is accessible from a related article. If you believe there are multiple
relationships, you can list them out:

    {% if not empty(related_articles['category']['platforms']) %}
        {% loop item in related_articles['category']['platforms'] %}
            {{ item['Article']['title'] }}
        {% endloop %}
    {% endif %}

For the above two examples, the platforms is the slug of the category, replace that with whatever category of related items you wish to show or if you want to list all related items -
replace `['category']['platforms']` with `['all']`.

Media Libraries <span class="label label-info">NEW IN 3.0.3</span>
---------------

With 3.0.3, attaching media libraries to articles has been introduced. When adding or editing an article, you can add/delete libraries from being linked to the article. When editing this template, you
can then link to media libraries or show the first image in a library.

There are three ways you can access the library data. If you want to loop through all the libraries, you can do so like this:

    {% if not empty(media['all']) %}
        {% loop library in media['all'] %}
            {{ library['title'] }}
        {% endloop %}
    {% endif %}

Or if you just one image you can do the below:

    {% if not empty(media['first']) %}
        {{ media['first']['file'] }}
    {% endif %}

This would output the web path to the file. Lastly, you can get a list of all the files from the attached libraries in that array as well:

    {% if not empty(media['first']['files']) %}
        {% loop file in media['first']['files'] %}
            {{ file['file'] }}
        {% endloop %}
    {% endif %}

For a full list of data you can access (whether you are looping through media items and accessing through there, through the whole files list or just the first file) see below:

* Media ID `{{ item['id'] }}`
* Media Slug `{{ item['slug'] }}`
* Media Title `{{ item['title'] }}`
* Media Count of Files `{{ item['count'] }}`
* Media File ID `{{ item['file_id'] }}`
* Media File Label `{{ item['file_label'] }}`
* Media File Web Path `{{ item['file'] }}`
* Media File - true/false on whether it exists `{{ item['file_exists'] }}`
* Media File Caption `{{ item['file_caption'] }}`
* Media File Size `{{ item['file_size'] }}`
* Media File Created `{{ item['file_created'] }}`