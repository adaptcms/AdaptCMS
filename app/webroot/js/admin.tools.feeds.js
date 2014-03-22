$(document).ready(function() {
    $('.category').on('change', function() {
        updateCategoryLimitFeedUrl();
    });

    $('.limit').on('change', function() {
        updateCategoryLimitFeedUrl();
    });
});

function updateCategoryLimitFeedUrl()
{
    var limit = $('.limit').val();
    var category = $('.category').val();

    var default_val = $('.default-category-feed-url').text();
    var span = $('.category-feed-url a');

    if (category) {
        default_val += '/' + category;
    } else {
        default_val += '/';
    }

    if (limit) {
        default_val += '/' + limit;
    } else {
        default_val += '/';
    }

    span.text(default_val);
    span.attr('href', default_val);
}