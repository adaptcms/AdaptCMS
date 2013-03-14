<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Tools', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Convert Wordpress', null) ?>

<?= $this->Form->create('Convert', array('class' => 'well')) ?>
	<h2>Convert Wordpress Install</h2>

	<p>Users (password is reset with email sent), Posts, Post type pages, Comments and some site options are transferred. Please enter your wordpress table prefix, select a category for posts to be transferred to and pick a textarea field for post content.</p>

	<?= $this->Form->input('prefix', array('value' => 'wp_')) ?>
	<?= $this->Form->input('category', array(
		'label' => 'Pick Category for Posts'
	)) ?>
	<?= $this->Form->input('field', array(
		'label' => 'Pick Textfield for Post Content'
	)) ?>
<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>