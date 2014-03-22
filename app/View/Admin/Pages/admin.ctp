<?php $this->Html->addCrumb('Admin', null) ?>

<div class="col-lg-12">
	<div class="col-lg-6 no-pad-l">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h2 class="panel-title">Newest Articles</h2>
			</div>
			<div class="panel-body panel-grey">
				<?php if (!empty($block_data['admin-main-articles-newest'])): ?>
					<ul>
						<?php foreach($block_data['admin-main-articles-newest'] as $article): ?>
							<li>
								<?= $this->Html->link($article['Article']['title'], array(
									'admin' => true,
									'controller' => 'articles',
									'action' => 'edit',
									$article['Article']['id']
								)) ?>
								@ <?= $this->Admin->time($article['Article']['created'], 'words') ?>
							</li>
						<?php endforeach ?>
					</ul>
				<?php endif ?>
			</div>
		</div>
	</div>

	<div class="col-lg-6  no-pad-r">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h2 class="panel-title">Newest Users</h2>
			</div>
			<div class="panel-body panel-grey">
				<?php if (!empty($block_data['admin-main-users-newest'])): ?>
					<ul>
						<?php foreach($block_data['admin-main-users-newest'] as $user): ?>
							<li>
								<?= $this->Html->link($user['User']['username'], array(
									'admin' => true,
									'controller' => 'users',
									'action' => 'edit',
									$user['User']['id']
								)) ?>
								@  <?= $this->Admin->time($user['User']['created'], 'words') ?>
							</li>
						<?php endforeach ?>
					</ul>
				<?php endif ?>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>

	<div class="col-lg-6 no-pad-l">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<h2 class="panel-title">Latest News from AdaptCMS.com</h2>
			</div>
			<div class="panel-body panel-grey">
				<?php if (!empty($news)): ?>
					<a href="<?= $this->Api->siteUrl() ?>article/view/<?= $news['slug'] ?>" target="_blank">
						<?= $news['title'] ?>
					</a><br />
					<em>@ <?= $this->Admin->time($news['created'], 'words') ?></em>

					<?= $this->Field->getFirstParagraph($news['data']) ?>
				<?php endif ?>

				<a href="<?= $this->Api->siteUrl() ?>category/news" class="btn btn-warning admin-homepage pull-right" target="_blank">View More News »</a>
			</div>
		</div>
	</div>

	<div class="col-lg-6 no-pad-r">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<h2 class="panel-title">Latest Blog from AdaptCMS.com</h2>
			</div>
			<div class="panel-body panel-grey">
				<?php if (!empty($blog)): ?>
					<a href="<?= $this->Api->siteUrl() ?>article/view/<?= $blog['slug'] ?>" target="_blank">
						<?= $blog['title'] ?>
					</a><br />
					<em>@ <?= $this->Admin->time($blog['created'], 'words') ?></em>

					<?= $this->Field->getFirstParagraph($blog['data']) ?>
				<?php endif ?>

				<a href="<?= $this->Api->siteUrl() ?>category/blog" class="btn btn-warning admin-homepage pull-right" target="_blank">View More Blogs »</a>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>

	<div class="col-lg-6 no-pad-l">
		<div class="panel panel-success">
			<div class="panel-heading">
				<h2 class="panel-title">Newest Plugin from AdaptCMS.com</h2>
			</div>
			<div class="panel-body panel-grey">
				<?php if (!empty($plugin)): ?>
					<a href="<?= $this->Api->url() ?>plugin/<?= $plugin['slug'] ?>" target="_blank">
						<?= $plugin['title'] ?>
					</a><br />
					<em>@ <?= $this->Admin->time($plugin['created'], 'words') ?></em>

					<p style="margin-top: 10px">
						<?= $plugin['short_description'] ?>
					</p>
				<?php endif ?>

				<a href="<?= $this->Api->url() ?>plugins" class="btn btn-success admin-homepage pull-right" target="_blank">View All Plugins »</a>
			</div>
		</div>
	</div>

	<div class="col-lg-6 no-pad-r">
		<div class="panel panel-success">
			<div class="panel-heading">
				<h2 class="panel-title">Newest Theme from AdaptCMS.com</h2>
			</div>
			<div class="panel-body panel-grey">
				<?php if (!empty($theme)): ?>
					<a href="<?= $this->Api->url() ?>theme/<?= $theme['slug'] ?>" target="_blank">
						<?= $theme['title'] ?>
					</a><br />
					<em>@ <?= $this->Admin->time($theme['created'], 'words') ?></em>

					<p style="margin-top: 10px">
						<?= $theme['short_description'] ?>
					</p>
				<?php endif ?>

				<a href="<?= $this->Api->url() ?>themes" class="btn btn-success admin-homepage pull-right" target="_blank">View All Themes »</a>
			</div>
		</div>
	</div>
</div>