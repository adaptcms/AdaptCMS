<div class="rating" data-article-id="{{ article['Article']['id'] }}">
	<div class="star-rating" data-score="{{ article_rating_score }}" {{ article_rating_permission_check }}></div>
<div class="current-rating">
	<strong>Current Rating:</strong> <span class="avg">{{ article_rating_avg }}</span>/5
</div>
<div class="user-rating">
	<strong>Your Rating:</strong> <span class="user">{{ article_rating_user }}</span>
</div>
<div class="total-votes">
	<strong>Total Ratings:</strong> <span class="total">{{ article_rating_total }}</span>
</div>
</div>