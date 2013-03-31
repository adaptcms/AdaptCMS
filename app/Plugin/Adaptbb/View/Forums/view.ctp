<?php $this->Paginator->options(array('url' => array(
	'controller' => 'forums',
	'action' => 'view',
	'slug' => $forum['slug']
))) ?>

<?php $this->Html->addCrumb('Forums', array('action' => 'index')) ?>
<?php $this->Html->addCrumb($forum['title'] . ' Forum', null) ?>

<h2>
	<?= $forum['title'] ?> Forum
</h2>

<?= $this->Html->link('New Topic <i class="icon-plus"></i>', array(
	'controller' => 'forum_topics',
	'action' => 'add',
	'slug' => $forum['slug']
), array('class' => 'btn btn-info pull-right', 'style' => 'margin-bottom: 10px', 'escape' => false)) ?>

<?php if (empty($topics)): ?>
	<p>No Topics Found</p>
<?php else: ?>
	<table class="table table-striped">
		<thead>
			<tr>
				<th></th>
				<th><?= $this->Paginator->sort('subject') ?></th>
				<th><?= $this->Paginator->sort('replies') ?></th>
				<th><?= $this->Paginator->sort('views') ?></th>
				<th>Last Post</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach($topics as $topic): ?>
				<tr>
					<td>
						<?php if ($topic['ForumTopic']['status'] == 0): ?>
							<i class="icon-white icon-warning-sign icon-large" title="Closed Topic" alt="Closed Topic"></i>
						<?php elseif ($topic['ForumTopic']['num_posts'] >= Configure::read('Adaptbb.num_posts_hot_topic')): ?>
							<i class="icon-white icon-fire icon-large" title="Hot Topic" alt="Hot Topic"></i>
						<?php else: ?>
							<i class="icon-white icon-reorder icon-large"></i>
						<?php endif ?>
					</td>
					<td>
						<?= $this->Html->link($topic['ForumTopic']['subject'], array(
							'action' => 'view',
							'controller' => 'forum_topics',
							'forum_slug' => $topic['Forum']['slug'],
							'slug' => $topic['ForumTopic']['slug']
						)) ?><br />
					
						by <?= $this->Html->link($topic['User']['username'], array(
							'action' => 'profile',
							'controller' => 'users',
							$topic['User']['username']
						)) ?> 
						
						<em>				
							<?= $this->Admin->time(
	                            $topic['ForumTopic']['created'],
	                            'words'
	                        ) ?>
						</em>
					</td>
					<td>
						<?= $topic['ForumTopic']['num_posts'] ?>
					</td>
					<td>
						<?= $topic['ForumTopic']['num_views'] ?>
					</td>
					<td>
						<?php if (empty($topic['NewestPost'])): ?>
							No Replies Made
						<?php else: ?>
							<?= $this->Html->link($topic['NewestPost']['User']['username'], array(
								'plugin' => null,
								'controller' => 'users',
								'action' => 'profile',
								$topic['NewestPost']['User']['username']
							)) ?> 
							<?= $this->Html->link('<i class="icon-external-link icon-white"></i>', array(
								'controller' => 'forum_topics',
								'action' => 'view',
								'forum_slug' => $forum['slug'],
								'slug' => $topic['ForumTopic']['slug'],
								'#' => 'post' . $topic['NewestPost']['ForumPost']['id']
							), array('escape' => false)) ?><br />
							<em><?= $this->Admin->Time($topic['NewestPost']['ForumPost']['created'], 'words') ?></em>
						<?php endif ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>

<?= $this->element('admin_pagination') ?>