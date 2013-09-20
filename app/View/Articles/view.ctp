<?php $this->Html->addCrumb($article['Category']['title'], array(
	'controller' => 'categories',
	'action' => 'view',
	$article['Category']['slug']
)) ?>
<?php $this->Html->addCrumb($article['Article']['title'], null) ?>

<?php $this->set('title_for_layout', $article['Article']['title']) ?>

<?php if (!empty($wysiwyg)): ?>
	<?php $this->TinyMce->editor(array('simple' => true)) ?>
<?php endif ?>

<?= $this->Html->script('jquery.blockui.min.js') ?>
<?= $this->Html->script('jquery.smooth-scroll.min.js') ?>
<?= $this->Html->script('comments.js') ?>

<div class="span8 no-marg-left">
	<h1><?= $article['Article']['title'] ?></h1>

	<p class="lead">
		@ <em><?= $this->Admin->time($article['Article']['created']) ?></em>
	</p>

	<?= $this->Field->getTextAreaData($article) ?>

	<div id="post-options">
        <span class="pull-left">
        	<?= $this->Html->link($article['Category']['title'], array(
        		'controller' => 'categories',
        		'action' => 'view',
        		$article['Category']['slug']
        	), array('class' => 'btn btn-primary')) ?>
            <span style="margin-left: 10px">
                <i class="icon-search icon-user"></i>&nbsp;
                Posted by <?= $this->Html->link($article['User']['username'], array(
                	'controller' => 'users', 
                	'action' => 'profile', 
                	$article['User']['username']
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

<!--nocache-->
<?= $this->element('post_comment', array('cached' => false)) ?>
<!--/nocache-->

<?= $this->element('view_all_comments', array('comments' => $article['Comments'])) ?>