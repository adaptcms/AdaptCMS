<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Sample Items', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Sample', null) ?>

<?php $this->TinyMce->editor() ?>

<?= $this->Form->create('Sample', array('class' => 'well admin-validate')) ?>
    <h2>Add Sample</h2>

    <?= $this->Form->input('title', array('type' => 'text', 'class' => 'required')) ?>
    <?= $this->Form->input('text', array('type' => 'textarea', 'class' => 'required')) ?>

<?= $this->Form->end(array(
    'label' => 'Submit',
    'class' => 'btn btn-primary'
)) ?>