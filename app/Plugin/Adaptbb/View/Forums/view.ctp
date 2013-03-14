<?php $this->Html->addCrumb('Forums', array('action' => 'index')) ?>
<?php $this->Html->addCrumb($forum['title'] . ' Forum', null) ?>

<h2>
	<?= $forum['title'] ?> Forum
</h2>

<?= $this->Html->link('New Topic', array(
	'controller' => 'forum_topics',
	'action' => 'add',
	$forum['slug']
), array('class' => 'btn btn-info pull-right', 'style' => 'margin-bottom: 10px')) ?>

<?php if (empty($topics)): ?>
	<p>No Topics Found</p>
<?php else: ?>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th><?= $this->Paginator->sort('subject') ?></th>
				<th><?= $this->Paginator->sort('User.username', 'Author') ?></th>
				<th><?= $this->Paginator->sort('created') ?></th>
			</tr>
		</thead>

		<tbody>
			<?php foreach($topics as $topic): ?>
				<tr>
					<td>
						<?= $this->Html->link($topic['ForumTopic']['subject'], array(
							'action' => 'view',
							'controller' => 'forum_topics',
							$topic['ForumTopic']['slug']
						)) ?>
					</td>
					<td>
						<?= $this->Html->link($topic['User']['username'], array(
							'action' => 'profile',
							'controller' => 'users',
							$topic['User']['username']
						)) ?>
					</td>
					<td>
						<?= $this->Admin->time(
                            $topic['ForumTopic']['created']
                        ) ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>