<h1>
	<?= $this->request->data['User']['username'] ?>'s Profile
</h1>

<ul id="profile-tabs" class="nav nav-tabs left" style="margin-bottom:0">
	<li class="active">
		<a href="#main" data-toggle="tab">Details</a>
	</li>
	<?php if (!empty($this->request->data['Article'])): ?>
		<li>
			<a href="#articles" data-toggle="tab">Posted Articles</a>
		</li>
	<?php endif ?>
	<?php if (!empty($this->request->data['Comment'])): ?>
		<li>
			<a href="#comments" data-toggle="tab">Posted Comments</a>
		</li>
	<?php endif ?>
	<li>
		<?= $this->Html->link('Messages', array(
			'controller' => 'messages',
			'action' => 'index'
		)) ?>
	</li>
	<?php if ($this->Admin->isLoggedIn()): ?>
		<li class="pull-right">
			<?= $this->Html->link('Edit Your Profile', array(
				'action' => 'edit'
			)) ?>
		</li>
	<?php endif ?>
</ul>

<div id="myTabContent" class="tab-content well">
	<div class="tab-pane fade active in" id="main">
		<dl class="dl-horizontal">
			<dt>
				Username
			</dt>
			<dd>
				<?= $this->request->data['User']['username'] ?>
			</dd>

			<dt>
				Email
			</dt>
			<dd>
				<?= $this->Text->autoLinkEmails($this->request->data['User']['email']) ?>
			</dd>

			<dt>
				Status
			</dt>
			<dd>
				<?php if ($this->request->data['User']['status'] == 1): ?>
					Active Account
				<?php else: ?>
					Non-Active Account
				<?php endif ?>
			</dd>

			<dt>
				Signed Up
			</dt>
			<dd>
				<?= $this->Admin->time($this->request->data['User']['created']) ?>
			</dd>

			<dt>
				Last Login
			</dt>
			<dd>
				<?= $this->Admin->time($this->request->data['User']['login_time']) ?>
			</dd>

			<dt>
				Role
			</dt>
			<dd>
				<?= $this->request->data['Role']['title'] ?>
			</dd>
		</dl>
	</div>

	<div class="tab-pane" id="articles">
		<h3>Last 10 Articles</h3>

		<ul>
			<?php foreach($this->request->data['Article'] as $article): ?>
				<li>
					<?= $this->Html->link($article['title'], array(
							'controller' => 'articles',
							'action' => 'view',
							$article['slug']
						),
						array(
							'target' => '_blank'
						)
					) ?> 
					(<strong><?= $article['Category']['title'] ?></strong>)
				</li>
			<?php endforeach ?>
		</ul>
	</div>

	<div class="tab-pane" id="comments">
		<h3>Last 10 Comments</h3>

		<ul class="unstyled">
			<?php foreach($this->request->data['Comment'] as $comment): ?>
				<li>
					@ <?= $this->Admin->time($comment['created'], 'words') ?> - 
					<?= $this->Html->link('view comment', array(
							'controller' => 'articles',
							'action' => 'view',
							$comment['Article']['slug'],
							'#' => 'comment_'.$comment['id']
						),
						array(
							'target' => '_blank',
							'escape' => false
						)
					) ?>

					<em>
						<?= $this->Text->truncate($comment['comment_text'], 150) ?>
					</em>

					<div class="clearfix"></div><br />
				</li>
			<?php endforeach ?>
		</ul>
	</div>
</div>