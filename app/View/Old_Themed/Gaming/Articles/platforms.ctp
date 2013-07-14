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

<h1>
  <?= $article['Article']['title'] ?>
  <?php if (!empty($article['Data']['system-icon'])): ?>
    <?= $this->Html->image('/' . $article['Data']['system-icon']) ?>
  <?php endif ?>
</h1>

<?php if (!empty($article['Data']['release-date'])): ?>
  <strong>Released:</strong> 
  <?= $article['Data']['release-date'] ?>
<?php endif ?>
  
<div class="clearfix"></div>
  
<?php if (!empty($article['Data']['system-history'])): ?>
  <?= $article['Data']['system-history'] ?>
<?php endif ?>

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
  
<h2>Recent Articles</h2>

<?php if (empty($related_articles)): ?>
  No Articles can be found
<?php else: ?>
  <?php foreach($related_articles['all'] as $article): ?>
    <?= $this->Html->link($article['Article']['title'] . ' (' . $article['Category']['title'] . ')', array(
    	'controller' => 'articles',
        'action' => 'view',
        'slug' => $article['Article']['slug'],
        'id' => $article['Article']['id']
    )) ?><br />
  <?php endforeach ?>
<?php endif ?>

<h2>Comments</h2>

<!--nocache-->
<?= $this->element('post_comment', array('cached' => false)) ?>
<!--/nocache-->

<?= $this->element('view_all_comments', array('comments' => $this->request->data['Comments'])) ?>