<?php $this->Html->addCrumb($article['Category']['title'], array(
	'controller' => 'categories',
	'action' => 'view',
	$article['Category']['slug']
)) ?>
<?php $this->Html->addCrumb($article['Article']['title'], null) ?>

<?php if (!empty($wysiwyg)): ?>
	<?php $this->TinyMce->editor(array('simple' => true)) ?>
<?php endif ?>

<?= $this->Html->script('jquery.smooth-scroll.min.js') ?>

<div class="span8 no-marg-left">
	<h1><?= $article['Article']['title'] ?></h1>

	<p class="lead">
		@ <em><?= $this->Admin->time($article['Article']['created']) ?></em>
	</p>

	<?= $this->Field->getTextAreaData($this->request->data) ?>

	<div id="post-options">
        <span class="pull-left">
        	<?= $this->Html->link($article['Category']['title'], array(
        		'controller' => 'categories',
        		'action' => 'view',
        		$article['Category']['slug']
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
        	<?php if (!empty($article['Article']['tags'])): ?>
	            <?php foreach($article['Article']['tags'] as $tag): ?>
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

<?= $this->element('view_all_comments', array('comments' => $this->request->data['Comments'])) ?>