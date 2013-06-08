<div class="span8 no-marg-left">
    <h1><?= $article['Article']['title'] ?></h1>

    <?php if (!empty($article['Data']['review-text'])): ?>
      <?= $article['Data']['review-text'] ?>
    <?php endif ?>

    <?php if (!empty($article['Data']['score'])): ?>
      <h2>Overall Score:</h2>
      <?= $article['Data']['score'] ?>
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
</div>

<?php if (!empty($related_articles['games'][0])): ?>
    <?php $game = $related_articles['games'][0]; ?>
    <div class="span3">
        <h2>Game Spotlight</h2>

        <?= $this->Html->link($game['Article']['title'], array(
            'controller' => 'articles',
            'action' => 'view',
            'slug' => $game['Article']['slug'],
            'id' => $game['Article']['id']
        )) ?>

        <?php if (!empty($game['Data']['boxart'])): ?>
            <?= $this->Html->image('/' . $game['Data']['boxart']) ?>
        <?php endif ?>
    </div>
<?php endif ?>
  
<h2>Comments</h2>

<?= $this->element('post_comment') ?>

<?= $this->element('view_all_comments', array('comments' => $article['Comments'])) ?>