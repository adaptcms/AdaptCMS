<?php $this->TinyMce->editor(array('simple' => true)) ?>

<?php $this->set('title_for_layout', 'Add Ticket') ?>

<?php $this->Html->addCrumb('Tickets', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('New Ticket', null) ?>

<?= $this->Form->create('Ticket', array('type' => 'file', 'class' => 'well admin-validate')) ?>
	<h2>Submit Ticket</h2>
	
	<?= $this->Form->input('full_name', array(
		'type' => 'text', 
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('email', array(
		'type' => 'text', 
		'class' => 'required email',
		'value' => !empty($current_user['email']) ? $current_user['email'] : ''
	)) ?>

	<?= $this->Form->input('subject', array(
		'type' => 'text', 
		'class' => 'required'
	)) ?>
	<?= $this->Form->input('category_id', array('class' => 'required')) ?>
	<?= $this->Form->input('message', array('rows' => 15, 'style' => 'width:500px', 'class' => 'required')) ?>

	<?= $this->Form->input('priority', array('type' => 'text', 'class' => 'required')) ?>
	<?= $this->Form->hidden('created', array('value' => $this->Admin->datetime() )) ?>

	<?php if (!empty($captcha)): ?>
		<div id="captcha">
			<?= $this->Captcha->form() ?>
		</div>
	<?php endif ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>