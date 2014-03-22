<?php
App::uses('CakeEventManager', 'Event');
App::uses('ArticleRatingsEventListener', 'ArticleRatings.EventListener');

$params = '[]';

$config = json_decode($params, true);
Configure::write('ArticleRatings', $config );

if (CakePlugin::loaded('ArticleRatings')) {
	CakeEventManager::instance()->attach(
		new ArticleRatingsEventListener(),
		'Model.Article.afterFind'
	);
}

$vars = array(
	array(
		'tag' => '{{ article_rating_score }}',
		'value' => '<?php echo (!empty($article["ArticleRating"]["avg"]) ? $article["ArticleRating"]["score"] : "") ?>'
	),
	array(
		'tag' => '{{ article_rating_avg }}',
		'value' => '<?php echo (!empty($article["ArticleRating"]["avg"]) ? $article["ArticleRating"]["avg"] : 0) ?>'
	),
	array(
		'tag' => '{{ article_rating_user }}',
		'value' => '<?php echo (!empty($article["ArticleRating"]["user"]) ? $article["ArticleRating"]["user"] : "N/A") ?>'
	),
	array(
		'tag' => '{{ article_rating_total }}',
		'value' => '<?php echo (!empty($article["ArticleRating"]["avg"]) ? $article["ArticleRating"]["total"] : 0) ?>'
	),
	array(
		'tag' => '{{ article_rating_permission_check }}',
		'value' => '<?php echo (!empty($article["ArticleRating"]["can_rate"]) ? "" : " disabled") ?>'
	)
);

$vars = array_merge_recursive(Configure::read('global_vars'), $vars);
Configure::write('global_vars', $vars);