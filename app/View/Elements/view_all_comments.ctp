<div id="comments">
    <?php if (!empty($comments)): ?>
        <?php foreach($comments as $parent_id_1 => $comment): ?>

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