<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Media Libraries', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Add Library', null) ?>

<?php $this->AdaptHtml->script('vendor/angular.min') ?>
<?php $this->AdaptHtml->script('media_modal') ?>

<?= $this->Form->create('Media', array('class' => 'well admin-validate', 'ng-app' => 'images')) ?>
	<h2>Add Media Library</h2>

	<?= $this->Form->input('title', array(
		'type' => 'text', 
		'class' => 'required'
	)) ?>

	<div  ng-controller="ImageModalCtrl">
		<?= stripslashes($this->Html->link('Attach Images <i class="fa fa-upload"></i>', '#', array(
			'class' => 'btn btn-primary',
			'escape' => false,
			'ng-click' => 'toggleModal($event, \'open\', \'primary\')'
		))) ?>

		<?= $this->element('media_modal', array('disable_parsing' => true)) ?>
		<?= $this->element('media_modal_image', array('disable_parsing' => true)) ?>
	</div>
	
<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>