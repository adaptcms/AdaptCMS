<?php foreach($data as $row): ?>
    <li>
        <?php if (!empty($row['Link']['file_id'])): ?>
            <?php
            $title = $this->Html->image('/' . $row['File']['dir'].$row['File']['filename'], array(
                'class' => 'span6',
	            'style' => 'max-width: 300px;'
            ));
            ?>
        <?php elseif (!empty($row['Link']['image_url'])): ?>
            <?php
            $title = $this->Html->image('/' . $row['Link']['image_url'], array(
                'class' => 'span6',
	            'style' => 'max-width: 300px;'
            ));
            ?>
        <?php else: ?>
            <?php $title = $row['Link']['link_title'] ?>
        <?php endif ?>

        <?= $this->Html->link($title, $row['Link']['url'], array(
            'target' => $row['Link']['link_target'],
            'escape' => false,
            'class' => 'track clearfix',
            'id' => $row['Link']['id']
        )) ?>
    </li>
<?php endforeach ?>

<div class="clearfix"></div>

<?= $this->Html->link('Submit Link <i class="icon-plus"></i>', array(
    'plugin' => 'links',
    'controller' => 'links',
    'action' => 'apply'
), array('class' => 'btn btn-info', 'escape' => false)) ?>