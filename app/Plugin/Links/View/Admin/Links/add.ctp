<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Links', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Link', null) ?>

<?= $this->Form->create('Link', array('class' => 'well admin-validate')) ?>
	<h2>Add Link</h2>

	<?= $this->Form->input('title', array('class' => 'required')) ?>
	<?= $this->Form->input('url', array(
		'class' => 'required', 
		'label' => 'Website Address',
		'placeholder' => 'http://'
	)) ?>
	<?= $this->Form->input('link_title') ?>
	<?= $this->Form->input('link_target', array(
		'options' => array(
			'_new' => '_new',
			'_blank' => '_blank'
		)
	)) ?>

    <?= $this->Form->input('active', array(
        'type' => 'checkbox',
        'checked'
    )) ?>

	<?= $this->Form->input('type', array(
			'options' => array(
				'file' => 'Pick an Image',
				'external' => 'External Image URL'
			),
			'empty' => '- Choose Image Type (optional) -'
	)) ?>

	<?= $this->Form->input('image_url', array(
			'div' => array(
				'class' => 'text input image_url'
			),
			'placeholder' => 'http://'
	)) ?>

	<div class="file_id">
		<?= $this->Html->link('Attach Image <i class="icon icon-white icon-upload"></i>', '#media-modal', array(
			'class' => 'btn btn-primary', 
			'escape' => false, 
			'data-toggle' => 'modal'
		)) ?>

		<p>&nbsp;</p>
		<ul class="selected-images span12 thumbnails"></ul>
	</div>

	<div class="clearfix"></div>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>

<?= $this->element('media_modal', array('limit' => 1)) ?>