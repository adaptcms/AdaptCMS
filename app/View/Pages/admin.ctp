<?php $this->Html->addCrumb('Admin', null) ?>

<div class="span6 well no-margin-left">
	<h2>Newest Articles</h2>

	<?php if (!empty($block_data['admin-main-articles-newest'])): ?>
		<ul>
			<?php foreach($block_data['admin-main-articles-newest'] as $article): ?>
				<li>
					<?= $this->Admin->edit(
						$article['Article']['id'],
						'articles',
						$article['Article']['title']
					) ?> @ <?= $this->Admin->time($article['Article']['created'], 'words') ?>
				</li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
</div>

<div class="span6 well">
	<h2>Newest Users</h2>

	<?php if (!empty($block_data['admin-main-users-newest'])): ?>
		<ul>
			<?php foreach($block_data['admin-main-users-newest'] as $user): ?>
				<li>
					<?= $this->Admin->edit(
						$user['User']['id'],
						'users',
						$user['User']['username']
					) ?> @  <?= $this->Admin->time($user['User']['created'], 'words') ?>
				</li>
			<?php endforeach ?>
		</ul>
	<?php endif ?>
</div>
<div class="clearfix"></div>

<div class="span6 well no-margin-left">
	<h3>Newest Plugin from AdaptCMS.com</h3>

	<?php if (!empty($newest_plugin)): ?>
		<?php foreach($newest_plugin as $plugin): ?>
			<a href="<?= $this->Api->url() ?>plugin/<?= $plugin['slug'] ?>" target="_blank">
				<?= $plugin['title'] ?>
			</a><br />
			<em>@ <?= $this->Admin->time($plugin['created'], 'words') ?></em>

			<p style="margin-top: 10px">
				<?= $plugin['short_description'] ?>
			</p>
		<?php endforeach ?>
	<?php endif ?>

	<a href="<?= $this->Api->url() ?>plugins" class="btn btn-primary pull-right" target="_blank">View All Plugins »</a>
</div>

<div class="span6 well">
	<h3>Latest News from AdaptCMS.com</h3>

	<?php if (!empty($news)): ?>
		<?php foreach($news as $article): ?>
			<a href="<?= $this->Api->siteUrl() ?>article/<?= $article['slug'] ?>" target="_blank">
				<?= $article['title'] ?>
			</a><br />
			<em>@ <?= $this->Admin->time($article['created'], 'words') ?></em>

			<p>
				<?= $this->Text->truncate($article['data'], 150) ?>
			</p>
		<?php endforeach ?>
	<?php endif ?>

	<a href="<?= $this->Api->siteUrl() ?>category/news" class="btn btn-primary pull-right" target="_blank">View More News »</a>
</div>
<div class="clearfix"></div>

<div class="span6 well no-margin-left">
	<h3>Newest Theme from AdaptCMS.com</h3>

	<?php if (!empty($newest_theme)): ?>
		<?php foreach($newest_theme as $theme): ?>
			<a href="<?= $this->Api->url() ?>theme/<?= $theme['slug'] ?>" target="_blank">
				<?= $theme['title'] ?>
			</a><br />
			<em>@ <?= $this->Admin->time($theme['created'], 'words') ?></em>

			<p style="margin-top: 10px">
				<?= $theme['short_description'] ?>
			</p>
		<?php endforeach ?>
	<?php endif ?>

	<a href="<?= $this->Api->url() ?>themes" class="btn btn-success pull-right" target="_blank">View All Themes »</a>
	<div class="clearfix"></div>
</div>

<div class="span6 well">
	<h3>Latest Blog from AdaptCMS.com</h3>

	<?php if (!empty($blog)): ?>
		<?php foreach($blog as $article): ?>
			<a href="<?= $this->Api->siteUrl() ?>article/<?= $article['slug'] ?>" target="_blank">
				<?= $article['title'] ?>
			</a><br />
			<em>@ <?= $this->Admin->time($article['created'], 'words') ?></em>

			<p>
				<?= $this->Text->truncate($article['data'], 150) ?>
			</p>
		<?php endforeach ?>
	<?php endif ?>

	<a href="<?= $this->Api->siteUrl() ?>category/blog" class="btn btn-success pull-right" target="_blank">View More Blogs »</a>
	<div class="clearfix"></div>
</div>