<?php $this->TinyMce->editor(array('simple' => true)) ?>

<?= $this->Html->script('jquery.smooth-scroll.min.js') ?>

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

<?= $this->element('flash_error', array('message' => 'Your post could not be made.')) ?>
<?= $this->element('flash_success', array('message' => 'Your post is live.')) ?>

<div id="posts" class="span12 no-marg-left">
	<?php foreach($posts as $post): ?>
		<a name="post<?= $post['ForumPost']['id'] ?>"></a>
		<div class="post well span12 no-marg-left">
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
						array('style' => 'max-width: 160px;margin-bottom: 15px', 'class' => 'img-polaroid')
					) ?>
				<?php endif ?>

				<div class="info">
					<strong>Joined</strong> 
					<?= $this->Admin->time($post['User']['created'], 'F d, Y') ?>
				</div>
				<div class="clearfix"></div>

				<div class="actions">
					<?= $this->Html->link('Send Message <i class="icon-envelope icon-white"></i>', array(
						'plugin' => null,
						'controller' => 'messages',
						'action' => 'send',
						$post['User']['username']
					), array('class' => 'btn btn-primary', 'escape' => false)) ?>

					<?php if ($topic['status'] == 1): ?>
						<div class="btn-group">
							<?= $this->Html->link('Reply <i class="icon-reply"></i>', '#', array(
								'class' => 'btn btn-info reply', 
								'escape' => false
							)) ?>
							<?= $this->Html->link('Quote <i class="icon-quote-right"></i>', '#', array(
								'class' => 'btn btn-danger quote', 
								'escape' => false
							)) ?>
						</div>
					<?php endif ?>
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