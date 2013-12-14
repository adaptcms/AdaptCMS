# Article Ratings

With the Article Ratings plugin, you can now give the ability to users of your website the ability to rate your articles. With a simple clean interface, anyone that you allow access to can click on a rating and it is instantly updated for your website. You can also create blocks, sorting the best and worst rated articles to display on your website.

## Installation

To install, first download the zip and unpack the folder inside into your 'app/Old_Plugins' folder - be sure to chmod this new folder to 755. Then login into your admin panel and click on 'Manage Plugins'. You should see this plugin listed and an option to install on the right side. Simply click that and click continue on the install and your done!
To use it, see below:

###Include Element

To render the rating script as is and not adjust it, simply call this in your template (remember that this needs to either be in a loop or on the article view page - if a loop, the article data array needs to be $article):

    {{ partial('ArticleRatings.rating', array('article' => $article)) }}

You don't need to include the array parameter if on the article view template.

###Render Manually

    <div class="rating" data-article-id="{{ article['Article']['id'] }}">
        <div class="star-rating" data-score="{{ article_rating_score }}" {{ article_rating_permission_check }}></div>
    <div class="current-rating">
        <strong>Current Rating:</strong> <span class="avg">{{ article_rating_avg }}</span>/5
    </div>
    <div class="total-votes">
        <strong>Total Votes:</strong> <span class="total">{{ article_rating_total }}</span>
    </div>
    </div>

###Block Support
You can do lists of most popular, most votes, etc. articles with blocks. When adding, select "Plugin - Article Ratings" and you can filter additionally by category. You can sort the list by average rating,
total amount of ratings and the combined rating score.

### More Resources

Check out the official [API Page](http://api.adaptcms.com/plugin/article-ratings).