<?php if (!empty($data['Poll']['title'])): ?>
    <?= $this->Form->create('Poll', array('class' => 'poll-vote', 'data-id' => 'Poll' . $data['Poll']['id'])) ?>

    <?= $this->Form->input('option', array(
        'options' => $data['options'],
        'type' => 'radio',
        'legend' => $data['Poll']['title'],
		'separator' => '<div class="clearfix"></div>'
    )) ?>

    <?= $this->Form->submit('Vote', array(
        'div' => false,
        'class' => 'pull-left'
    )) ?>
    <?= $this->Html->link('View Results', '#', array(
        'class' => 'pull-right results',
        'data-block-title' => (!empty($data['Block']['title']) ? $data['Block']['title'] : $data['Poll']['id'])
    )) ?>
    <?= $this->Form->hidden('id', array('value' => $data['Poll']['id'])) ?>

    <div class="clearfix"></div>
    <?= $this->Form->end() ?>
<?php endif ?>