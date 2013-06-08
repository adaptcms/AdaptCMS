<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Convert AdaptCMS', null) ?>

<?= $this->Form->create('Convert', array('class' => 'well')) ?>
	<h2>Convert AdaptCMS Install</h2>

	<p>Users (password is reset with email sent), Sections, Content, Fields, Content field data and comments are transferred. Please enter your AdaptCMS 2.x table prefix to start conversion.</p>

	<?= $this->Form->input('prefix', array('value' => 'adaptcms_')) ?>
<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>