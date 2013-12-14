<?php $this->TinyMce->editor() ?>

<?= $this->Html->script('bootstrap-typeahead.js') ?>

<?php $this->Html->addCrumb('Profile', array(
    'action' => 'profile',
    $this->Session->read('Auth.User.username')
)) ?>
<?php $this->Html->addCrumb('Messages', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Send Message', null) ?>

<h1>Send Message</h1>

<?= $this->Html->link('« Back to Messages', array('action' => 'index'), array(
	'class' => 'btn btn-primary pull-right', 
	'style' => 'margin-bottom: 10px;'
)) ?>
<div class="clearfix"></div>

<?= $this->Form->create('Message', array('class' => 'well span12 no-marg-left admin-validate')) ?>
	
	<?= $this->Form->input('recipient', array(
	        'data-provide' => 'typeahead', 
	        'data-source' => '[]', 
	        'autocomplete'=>'off',
            'value' => !empty($this->params['pass'][0]) ? $this->params['pass'][0] : ''
	)) ?>
	<?= $this->Form->input('title', array(
		'label' => 'Subject',
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('message', array(
		'class' => 'required span7',
		'style' => 'height: 100%'
	)) ?>

	<?= $this->Form->hidden('parent_id', array('value' => 0)) ?>
	<?= $this->Form->hidden('receiver_user_id') ?>

	<?= $this->Form->submit('Send Message', array(
		'class' => 'btn btn-info',
		'style' => 'margin-top: 10px'
	)) ?>
<?= $this->Form->end() ?>