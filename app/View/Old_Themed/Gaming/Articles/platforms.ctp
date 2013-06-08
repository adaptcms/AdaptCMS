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