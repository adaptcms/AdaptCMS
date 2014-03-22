<?php $this->Html->addCrumb('Admin', '/admin') ?>
<?php $this->Html->addCrumb('Media Libraries', array('action' => 'index')) ?>
<?php $this->Html->addCrumb('Edit Library', null) ?>

<?php $this->AdaptHtml->script('vendor/angular.min') ?>
<?php $this->AdaptHtml->script('media_modal') ?>

<div class="pull-right admin-edit-options">
    <?= $this->Html->link(
        '<i class="fa fa-chevron-left"></i> Return to Index',
        array('action' => 'index'),
        array('class' => 'btn btn-info', 'escape' => false
    )) ?>
    <?= $this->Html->link(
        '<i class="fa fa-trash-o"></i> Delete',
        array('action' => 'delete', $this->request->data['Media']['id'], $this->request->data['Media']['title']),
        array('class' => 'btn btn-danger', 'escape' => false, 'onclick' => "return confirm('Are you sure you want to delete this media library?')"));
    ?>
</div>
<div class="clearfix"></div>

<?= $this->Form->create('Media', array('class' => 'well admin-validate', 'ng-app' => 'images')) ?>
	<h2>Edit Media Library</h2>

	<?= $this->Form->input('title', array(
		'type' => 'text', 
		'class' => 'required'
	)) ?>

	<div ng-controller="ImageModalCtrl">
		<?= stripslashes($this->Html->link('Attach Images <i class="fa fa-upload"></i>', '#', array(
			'class' => 'btn btn-primary',
			'escape' => false,
			'ng-click' => 'toggleModal($event, \'open\', \'primary\')'
		))) ?>

		<?= $this->element('media_modal', array('disable_parsing' => true)) ?>
		<?= $this->element('media_modal_image', array('disable_parsing' => true)) ?>

		<?php if (!empty($this->request->data['File'])): ?>
			<div class="existing-images hidden" data-id="primary">
				<?php foreach($this->request->data['File'] as $key => $file): ?>
					<span><?php echo json_encode($file) ?></span>
				<?php endforeach ?>
			</div>
		<?php endif ?>
	</div>

    <?= $this->Form->hidden('id') ?>

<?= $this->Form->end(array(
	'label' => 'Submit',
	'class' => 'btn btn-primary'
)) ?>