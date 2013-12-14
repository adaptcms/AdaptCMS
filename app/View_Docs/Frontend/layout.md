With the template system starting in 3.0.2, there are only a few things you must have. First, you need to enter in the following tag in your header:

`{{ headers }}`

This will include JS, CSS and other things that are used by the CMS. If you don't include this tag, the script will just look for a `<head>` tag and add it in after that.

The only other must have tag is where content will be included.

`{{ content }}`

Whether you are viewing the home page or an article, this is where the content is loaded in through. Lastly, make sure you have an ending `</body>` tag and that's it!

What you really should have
---------------------------

These aren't must haves, but we highly recommend you do to retain certain non-essential functionality, usually asethetic.

This tag takes titles set in the templates and puts them in your title tag, great for SEO:

`{{ title_for_layout }}`

Another useful thing to do is to use the "nocache" feature for things that should be loaded on every page load. This is for things that should be dynamic every time, such as showing a poll
(since the user may or may not of voted on it), flash message (see below), user login/register or welcome message, etc.

`<!--nocache-->
    {{ flash }}
<!--/nocache-->`