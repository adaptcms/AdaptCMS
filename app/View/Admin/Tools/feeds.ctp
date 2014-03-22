<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Feeds', null) ?>

<h1>Feeds</h1>

<?= $this->Form->create('', array('class' => 'well')) ?>
	<h2>Articles</h2>

	<h4>Main Feed</h4>

	<?php $main_url = $this->Html->url(array(
		'admin' => false,
		'rss' => true,
		'controller' => 'articles',
		'action' => 'index'
	), true) ?>
	<i class="fa fa-rss"></i> <?php echo $this->Html->link($main_url, $main_url) ?>

	<h4>By Category/Limit</h4>

	<?= $this->Form->input('category', array(
		'class' => 'category',
		'empty' => '- choose category (optional) -'
	)) ?>
	<?= $this->Form->input('limit', array(
		'class' => 'limit',
		'empty' => '- choose limit (optional) -',
		'options' => $limits
	)) ?>

	<span class="category-feed-url"><i class="fa fa-rss"></i> <?php echo $this->Html->link($main_url, $main_url) ?></span>
	<span class="default-category-feed-url hidden"><?php echo $this->Html->link($main_url, $main_url) ?></span>
<?= $this->Form->end() ?>