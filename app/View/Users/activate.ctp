<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Login', array('action' => 'login')) ?>
<?php $this->Html->addCrumb('Activate Account', null) ?>

<h2>
    Activate Account
</h2>

<?= $this->Form->create('User') ?>
    <?= $this->Form->input('username', array(
    	'type' => 'text', 
    	'class' => 'required'
    )) ?>
    <?= $this->Form->input('activate_code', array(
    	'type' => 'text', 
    	'class' => 'required'
    )) ?>

<?= $this->Form->end('Submit') ?>