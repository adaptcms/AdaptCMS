<?php $this->TinyMce->editor(array('simple' => true)) ?>

<?= $this->Html->script('jquery.smooth-scroll.min.js') ?>

<?php $this->set('title_for_layout', 'Forums - ' . $forum['title'] . ' :: ' . $topic['subject']) ?>

<?php $this->Html->addCrumb('Forums', array('action' => 'index', 'controller' => 'forums')) ?>
<?php $this->Html->addCrumb($forum['title'] . ' Forum', array(
	'action' => 'view', 
	'controller' => 'forums', 
	'slug' => $forum['slug']
)) ?>
<?php $this->Html->addCrumb('View Topic', null) ?>

<h2>
	Viewing Topic
	<small>
		<?= $topic['subject'] ?>
	</small>
</h2>

<!--nocache-->
<?php if ($this->Admin->hasPermission($permissions['related']['forum_topics']['change_status'])): ?>
	<?= $this->Html->link(($topic['status'] == 0 ? 'Open' : 'Close') . ' Topic <i class="icon-' . ($topic['status'] == 0 ? 'un' : '') . 'lock"></i>', 
		array(
			'action' => 'change_status',
			$topic['id']
		),
		array(
			'escape' => false, 
			'class' => 'btn btn-info pull-right marg-btm'
		)
	) ?>
<?php endif ?>
<!--/nocache-->
<div class="clearfix"></div>

<?= $this->element('flash_error', array('message' => 'Your post could not be made.', 'hidden' => true)) ?>
<?= $this->element('flash_success', array('message' => 'Your post is live.', 'hidden' => true)) ?>

<div id="posts" class="span12 no-marg-left">
	<?php foreach($posts as $post): ?>
		<a name="post<?= $post['ForumPost']['id'] ?>"></a>
		<div class="post well span12 no-marg-left" id="post-<?= $post['ForumPost']['id'] ?>">
			<div class="post-info span3 pull-left">
				<?php if (!empty($post['User']['username'])): ?>
					<?= $this->Html->link('<h4 class="user">' . $post['User']['username'] . '</h4>', array(
						'controller' => 'users',
						'action' => 'profile',
						$post['User']['username']
					), array('escape' => false)) ?>
				<?php else: ?>
					<h4 class="user">Guest</h4>
				<?php endif ?>

				<?php if (!empty($post['User']['settings']['avatar'])): ?>
					<?= $this->Html->image(
						'/uploads/avatars/' . $post['User']['settings']['avatar'],
						array('style' => 'max-width: 140px;margin-bottom: 15px', 'class' => 'img-polaroid')
					) ?>
				<?php endif ?>

				<div class="info">
					<strong>Joined</strong> 
					<?= $this->Admin->time($post['User']['created'], 'F d, Y') ?>
				</div>
				<div class="clearfix"></div>

				<div class="actions">
                    <!--nocache-->
					<?= $this->Html->link('Send Message <i class="icon-envelope icon-white"></i>', array(
						'plugin' => null,
						'controller' => 'messages',
						'action' => 'send',
						$post['User']['username']
					), array('class' => 'btn btn-primary', 'escape' => false)) ?>

					<?php if ($topic['status'] == 1 && $this->Admin->hasPermission($permissions['related']['forum_posts']['ajax_post'])): ?>
						<div class="btn-group no-marg-left">
							<?= $this->Html->link('Reply <i class="icon-reply"></i>', '#', array(
								'class' => 'btn btn-warning reply', 
								'escape' => false
							)) ?>
							<?= $this->Html->link('Quote <i class="icon-quote-right"></i>', '#', array(
								'class' => 'btn btn-success quote', 
								'escape' => false
							)) ?>
						</div>
					<?php endif ?>

					<div class="btn-group no-marg-left">
						<?php if ($post['type'] == 'post'): ?>
							<?php if ($this->Admin->hasPermission($permissions['related']['forum_posts']['ajax_edit'])): ?>
								<?= $this->Html->link('Edit <i class="icon-pencil"></i>', 
									array(
										'action' => 'ajax_edit',
										'controller' => 'forum_posts',
										$post['ForumPost']['id']
									),
									array(
										'escape' => false, 
										'class' => 'btn btn-primary edit-post',
										'data-id' => 'edit_post_' . $post['ForumPost']['id']
									)
								) ?>
							<?php endif ?>
						<?php else: ?>
							<?php if ($this->Admin->hasPermission($permissions['related']['forum_topics']['edit'])): ?>
								<?= $this->Html->link('Edit <i class="icon-pencil"></i>', 
									array(
										'action' => 'edit',
										'controller' => 'forum_topics',
										$post['ForumPost']['id']
									),
									array(
										'escape' => false, 
										'class' => 'btn btn-primary'
									)
								) ?>
							<?php endif ?>
						<?php endif ?>

						<?php if ($this->Admin->hasPermission($permissions['related']['forum_' . $post['type'] . 's']['delete'])): ?>
							<?= $this->Html->link('Delete <i class="icon-minus"></i>', 
								array(
									'action' => 'delete',
									'controller' => 'forum_' . $post['type'] . 's',
									$post['ForumPost']['id']
								),
								array(
									'escape' => false, 
									'class' => 'btn btn-danger btn-confirm',
									'title' => 'this ' . $post['type']
								)
							) ?>
						<?php endif ?>
					</div>
                    <!--/nocache-->
				</div>
			</div>
			<div class="post-body span9 no-marg-left">
				<h4 class="subject">
					<?php if (empty($post['ForumPost']['subject'])): ?>
						RE: 
					<?php endif ?>
					<?= $topic['subject'] ?>
					<small>
						@ <?= $this->Admin->time($post['ForumPost']['created'], 'words') ?>
					</small>
				</h4>

				<span class="message">
					<?= $post['ForumPost']['content'] ?>
				</span>

                <!--nocache-->
				<?php if ($post['type'] == 'post' && $this->Admin->hasPermission($permissions['related']['forum_posts']['ajax_edit'])): ?>
					<?= $this->element('post_reply', array(
						'topic_id' => $topic['id'],
						'forum_id' => $forum['id'],
						'post_id' => $post['ForumPost']['id'],
						'post' => $post['ForumPost']
					)) ?>
				<?php endif ?>
                <!--/nocache-->
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
	<?php endforeach ?>

	<?php $this->Paginator->options(array('url' => array(
		'controller' => 'forum_topics',
		'action' => 'view',
		'slug' => $topic['slug'],
		'forum_slug' => $forum['slug']
	))) ?>

	<?= $this->element('admin_pagination') ?>
</div>
<div class="clearfix"></div>

<?php if ($topic['status'] == 1): ?>
	<?= $this->element('post_reply', array(
		'topic_id' => $topic['id'],
		'forum_id' => $forum['id']
	)) ?>
<?php endif ?>