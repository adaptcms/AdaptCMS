<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Plugins', array(
    'controller' => 'plugins', 
    'action' => 'index',
    'plugin' => false
)) ?>
<?php $this->Html->addCrumb('Links', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Link', null) ?>

<?php $this->AdaptHtml->script('vendor/angular.min') ?>
<?php $this->AdaptHtml->script('media_modal') ?>

<?= $this->Form->create('Link', array('class' => 'well admin-validate', 'ng-app' => 'images')) ?>
	<h2>Add Link</h2>

	<?= $this->Form->input('title', array('class' => 'required')) ?>
	<?= $this->Form->input('url', array(
		'class' => 'required url',
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
		'class' => 'url',
		'placeholder' => 'http://'
	)) ?>

	<div class="file_id" ng-controller="ImageModalCtrl">
		<?= $this->Html->link('Attach Image <i class="fa fa-upload"></i>', '#', array(
			'class' => 'btn btn-primary', 
			'escape' => false,
			'ng-click' => 'toggleModal($event, \'open\', \'primary\')'
		)) ?>

		<?= $this->element('media_modal', array('disable_parsing' => true)) ?>
		<?= $this->element('media_modal_image', array(
			'disable_parsing' => true,
			'limit' => 1
		)) ?>
	</div>

	<div class="clearfix"></div>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>

<?= $this->element('media_modal', array('limit' => 1)) ?>