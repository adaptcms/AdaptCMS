<?php
$model = !empty($model) ? $model : 'ArticleValue';
?>
<div class="input img<?php echo !empty($field['Field']['required']) ? ' required' : '' ?>">
	<?= $this->Form->hidden($model . '.' . $key . '.type', array(
	    'value' => 'img'
	)) ?>
    <?= $this->Form->label($model . '.' . $key . 'data', $icon . $field['Field']['label'], array('escape' => false)) ?>

	<?= stripslashes($this->Html->link('Attach Image <i class="fa fa-upload"></i>', '#', array(
		'class' => 'btn btn-primary',
		'escape' => false,
		'ng-click' => 'toggleModal($event, \'open\', \'modal-' . $field['Field']['id'] . '\')'
	))) ?>

	<?= $this->element('media_modal_image', array(
		'disable_parsing' => true,
		'id' => 'modal-' . $field['Field']['id'],
		'limit' => 1,
		'name' => 'data[' . $model . '][' . $key . '][file_id]'
	)) ?>

	<?php if (!empty($field[$model][0]['File'])): ?>
		<div class="existing-images hidden" data-id="modal-<?php echo $field['Field']['id'] ?>">
			<span><?php echo json_encode($field[$model][0]['File']) ?></span>
		</div>
	<?php endif ?>

	<?php if (!empty($this->request->data['ArticleValue'][$key]['File'])): ?>
		<div class="existing-images hidden" data-id="modal-<?php echo $field['Field']['id'] ?>">
			<span><?php echo json_encode($this->request->data['ArticleValue'][$key]['File']) ?></span>
		</div>
	<?php endif ?>

	<?php if (!empty($this->validationErrors['ArticleValue'][$key])): ?>
		<div class="error-message">
			This field is required
		</div>
	<?php endif ?>
</div>
<div class="clearfix"></div>