<?php if (!empty($data['header'])): ?>
    <?php if ($data['header'] == 'text'): ?>
        <?= $data['title'] ?>
    <?php else: ?>
        <<?= $data['header'] ?>>
            <?= $data['title'] ?>
        </<?= $data['header'] ?>>
    <?php endif ?>
<?php endif ?>

<?php if ($data['separator'] == 'li'): ?>
    <?php $separator = 'li' ?>
    <ul class="nav nav-list">
<?php endif ?>

    <?php foreach($data['items'] as $item): ?>
        <?php if ($data['separator'] != 'br'): ?>
            <<?= $data['separator'] ?>>
        <?php endif ?>

        <?php if (!empty($item['url'])): ?>
            <?= $this->Html->link($item['url_text'], $item['url'], array('target' => '_blank')) ?>
        <?php elseif (!empty($item['page_id'])): ?>
            <?= $this->Html->link(str_replace('Page - ', '', $item['text']), array(
                'plugin' => null,
                'admin' => false,
                'controller' => 'pages',
                'action' => 'display',
                $item['page_slug']
            )) ?>
        <?php elseif (!empty($item['category_id'])): ?>
            <?= $this->Html->link(str_replace('Category - ', '', $item['text']), array(
                'plugin' => null,
                'admin' => false,
                'controller' => 'categories',
                'action' => 'view',
                $item['category_slug']
            )) ?>
        <?php endif ?>

        <?php if ($data['separator'] != 'br'): ?>
            </<?= $data['separator'] ?>>
        <?php else: ?>
            <br />
        <?php endif ?>
    <?php endforeach ?>

<?php if (!empty($separator)): ?>
    </ul>
<?php endif ?>