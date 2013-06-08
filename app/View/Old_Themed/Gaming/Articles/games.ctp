<div class="span8 no-marg-left">
    <h1><?= $article['Article']['title'] ?></h1>

    <dl class="dl-horizontal">
        <?php if (!empty($article['Data']['developer'])): ?>
            <dt>Developer</dt>
            <dd><?= $article['Data']['developer'] ?></dd>
        <?php endif ?>
        <?php if (!empty($article['Data']['publisher'])): ?>
            <dt>Publisher</dt>
            <dd><?= $article['Data']['publisher'] ?></dd>
        <?php endif ?>
        <?php if (!empty($article['Data']['genre'])): ?>
            <dt>Genre</dt>
            <dd><?= $article['Data']['genre'] ?></dd>
        <?php endif ?>
        <?php if (!empty($article['Data']['release-date'])): ?>
            <dt>Release Date</dt>
            <dd><?= $article['Data']['release-date'] ?></dd>
        <?php endif ?>
        <?php if (!empty($article['Data']['esrb'])): ?>
            <dt>ESRB Rating</dt>
            <dd><?= $article['Data']['esrb'] ?></dd>
        <?php endif ?>
    </dl>

    <h2>Recent Articles</h2>

    <?php if (empty($related_articles)): ?>
        No Articles can be found
    <?php else: ?>
        <?php foreach($related_articles['all'] as $row): ?>
            <?= $this->Html->link($row['Article']['title'] . ' (' . $row['Category']['title'] . ')', array(
                'controller' => 'articles',
                'action' => 'view',
                'slug' => $row['Article']['slug'],
                'id' => $row['Article']['id']
            )) ?><br />
        <?php endforeach ?>
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
<div class="span3">
    <?php if (!empty($article['Data']['boxart'])): ?>
        <?= $this->Html->image('/' . $article['Data']['boxart']) ?>
    <?php endif ?>
</div>
<div class="clearfix"></div>

<h2>Comments</h2>

<?= $this->element('post_comment') ?>

<?= $this->element('view_all_comments', array('comments' => $article['Comments'])) ?>