<?php $this->Html->addCrumb($this->request->data['Category']['title'], array(
	'controller' => 'categories',
	'action' => 'view',
	$this->request->data['Category']['slug']
)) ?>
<?php $this->Html->addCrumb($this->request->data['Article']['title'], null) ?>

<?php if (!empty($wysiwyg)): ?>
	<?php $this->TinyMce->editor(array('simple' => true)) ?>
<?php endif ?>

<?= $this->Html->script('jquery.smooth-scroll.min.js') ?>

<div class="span8 no-marg-left">
	<h1><?= $this->request->data['Article']['title'] ?></h1>

	<p class="lead">
		@ <em><?= $this->Admin->time($this->request->data['Article']['created']) ?></em>
	</p>

	<?= $this->Field->getTextAreaData($this->request->data) ?>

	<div id="post-options">
        <span class="pull-left">
        	<?= $this->Html->link($this->request->data['Category']['title'], array(
        		'controller' => 'categories',
        		'action' => 'view',
        		$this->request->data['Category']['slug']
        	), array('class' => 'btn btn-primary')) ?>
            <span style="margin-left: 10px">
                <i class="icon-search icon-user"></i>&nbsp;
                Posted by <?= $this->Html->link($this->request->data['User']['username'], array(
                	'controller' => 'users', 
                	'action' => 'profile', 
                	$this->request->data['User']['username']
                )) ?>
            </span>
        </span>
        <span class="pull-right">
        	<?php if (!empty($this->request->data['Article']['tags'])): ?>
	            <?php foreach($this->request->data['Article']['tags'] as $tag): ?>
	                <?= $this->Html->link('<span class="btn btn-success">'.$tag.'</span>', array(
	                		'controller' => 'articles', 
	                		'action' => 'tag', 
	                		$tag
	                ), array('class' => 'tags', 'escape' => false)) ?>
	            <?php endforeach ?>
        	<?php endif ?>
        </span>
    </div>
</div>

<div class="clearfix"></div>

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