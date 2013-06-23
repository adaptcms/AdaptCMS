<?php $this->set('title_for_layout', 'Forums') ?>

<?php $this->Html->addCrumb('Forums', null) ?>

<h1>Forum Index</h1>

<div class="span12 no-marg-left" id="forum-index">
	<?php foreach($categories as $category): ?>
		<?php if (!empty($category['Forum'])): ?>
			<table class="table well category">
				<thead>
					<tr class="btn-success">
						<th>
							<h3><?= $category['ForumCategory']['title'] ?></h3>
						</th>
						<th>
							Topics
						</th>
						<th>
							Posts
						</th>
						<th>
							Newest Post
						</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($category['Forum'])): ?>	
						<?php foreach($category['Forum'] as $forum): ?>
							<tr>
								<td>
									<?= $this->Html->link($forum['title'], array(
										'action' => 'view',
										'slug' => $forum['slug']
									)) ?>

									<?php if (!empty($forum['description'])): ?>
										<?= $forum['description'] ?>
									<?php endif ?>
								</td>
								<td>
									<?= $forum['num_topics'] ?>
								</td>
								<td>
									<?= $forum['num_posts'] ?>
								</td>
								<td>
									<?php if (empty($forum['NewestPost'])): ?>
										No Posts Made
									<?php else: ?>
										by 
										<?= $this->Html->link($forum['NewestPost']['User']['username'], array(
											'plugin' => null,
											'controller' => 'users',
											'action' => 'profile',
											$forum['NewestPost']['User']['username']
										)) ?> 
										<?= $this->Html->link('<i class="icon-external-link icon-white"></i>', array(
											'controller' => 'forum_topics',
											'action' => 'view',
											'forum_slug' => $forum['slug'],
											'slug' => $forum['NewestPost']['ForumTopic']['slug'],
											'#' => 'post' . $forum['NewestPost']['ForumPost']['id']
										), array('escape' => false)) ?><br />
										<em><?= $this->Admin->Time($forum['NewestPost']['ForumPost']['created'], 'words') ?></em>
									<?php endif ?>
								</td>
							</tr>
						<?php endforeach ?>
					<?php endif ?>
				</tbody>
			</table>
			<div class="clearfix"></div>
		<?php endif ?>
	<?php endforeach ?>
</div>
<div class="clearfix"></div>

<div class="header btn-primary" id="forum-index-stats-header">
	<h3>Stats</h3>
</div>
<div class="well" id="forum-stats">
	<dl class="pull-left dl-horizontal">
		<dt>Total Topics</dt>
		<dd><?= $categories['Stats']['topics'] ?></dd>

		<dt>Total Posts</dt>
		<dd><?= $categories['Stats']['posts'] ?></dd>
	</dl>
	<dl class="pull-left dl-horizontal">
		<dt>Total Users</dt>
		<dd><?= $categories['Stats']['users'] ?></dd>

		<dt>Newest User</dt>
		<dd>
            <!--nocache-->
			<?php if ($this->Admin->hasPermission($permissions['related']['users']['profile'])): ?>
				<?= $this->Html->link($categories['Stats']['newest_user']['User']['username'], array(
					'plugin' => null,
					'controller' => 'users',
					'action' => 'profile',
					$categories['Stats']['newest_user']['User']['username']
				)) ?> 
			<?php else: ?>
				<?= $categories['Stats']['newest_user']['User']['username'] ?>
			<?php endif ?>
            <!--/nocache-->
			<br />
			<em><?= $this->Admin->Time($categories['Stats']['newest_user']['User']['created'], 'words') ?></em>
		</dd>
	</dl>
	<div class="clearfix"></div>
</div>
<div class="clearfix"></div>