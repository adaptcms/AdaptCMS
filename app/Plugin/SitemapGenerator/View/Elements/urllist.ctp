<?= Router::url('/', true) ?>

<?php foreach($data['articles'] as $article): ?>
<?= $this->Html->url(array(
	'controller' => 'articles',
	'action' => 'view',
    'slug' => $article['Article']['slug'],
    'id' => $article['Article']['id']
), true) ?>

<?php endforeach ?>
<?php foreach($data['categories'] as $category): ?>
<?= $this->Html->url(array(
	'controller' => 'categories',
	'action' => 'view',
	$category['Category']['slug']
), true) ?>

<?php endforeach ?>
<?= $this->Html->url(array(
	'controller' => 'media',
	'action' => 'index',
), true) ?>

<?php foreach($data['libraries'] as $media): ?>
<?= $this->Html->url(array(
	'controller' => 'media',
	'action' => 'view',
	$media['Media']['slug']
), true) ?>

<?php endforeach ?>
<?php foreach($data['pages'] as $page): ?>
<?= $this->Html->url(array(
	'controller' => 'pages',
	'action' => 'display',
	$page['Page']['slug']
), true) ?>

<?php endforeach ?>
<?php foreach($data['users'] as $user): ?>
<?= $this->Html->url(array(
	'controller' => 'users',
	'action' => 'profile',
	$user['User']['username']
), true) ?>

<?php endforeach ?>