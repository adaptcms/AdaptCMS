<h1><?= $this->request->data['Article']['title'] ?></h1>

posted by: <?= $this->request->data['User']['username'] ?><br />
at: <?= $this->Time->format('F jS, Y h:i A', $this->request->data['Article']['created']) ?><br />
category: <?= $this->request->data['Category']['title'] ?>

<p>
	<ul>
	<?php foreach($this->request->data['ArticleValue'] as $data): ?>
		<li><?= $data['Field']['title'] ?>: 
			<?php if (is_array(json_decode($data['data']))): ?>
				<?= implode(", ", json_decode($data['data'])) ?>
			<?php else: ?>
				<?= $data['data'] ?>
			<?php endif ?>
		</li>
	<?php endforeach; ?>
	</ul>
</p>

<h2>Comments</h2>

<?= $this->element('post_comment') ?>

<div id="comments">
	<?php if (!empty($this->request->data['Comments'])): ?>
		<?php foreach($this->request->data['Comments'] as $parent_id_1 => $comment): ?>

			<?= $this->element('view_comment', array('data' => $comment, 'level' => 1)) ?>

			<?php if (!empty($comment['children'])): ?>
				<?php foreach($comment['children'] as $parent_id_2 => $comment_2): ?>

					<?= $this->element('view_comment', array('data' => $comment_2, 'level' => 2)) ?>

					<?php if (!empty($comment_2['children'])): ?>
						<?php foreach($comment_2['children'] as $parent_id_3 => $comment_3): ?>
						
							<?= $this->element('view_comment', array('data' => $comment_3, 'level' => 3)) ?>
						<?php endforeach ?>
					<?php endif ?>

				<?php endforeach ?>
			<?php endif ?>

		<?php endforeach ?>
	<?php endif ?>
</div>