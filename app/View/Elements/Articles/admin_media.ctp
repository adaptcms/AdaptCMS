<h3>Media Libraries</h3>

<div class="media-libraries input-group col-lg-5" style="margin-bottom: 9px;">
	<?= $this->Form->label('library', 'Library') ?>
	<div class="clearfix"></div>

	<?php echo $this->Form->input('library', array(
		'div' => false,
		'label' => false,
		'class' => 'form-control form-control-inline',
		'style' => 'margin-bottom: 0',
		'empty' => '- add library -',
		'options' => $media_list
	)) ?>
	<?php echo $this->Form->button('Add', array(
		'class' => 'btn btn-info add-media',
		'type' => 'button'
	)) ?>
</div>
<div class="media_libraries col-lg-8 no-pad-l">
	<?php if (!empty($this->request->data['Media'])): ?>
		<?php foreach($this->request->data['Media'] as $key => $media): ?>
			<div id="data-<?php echo $key ?>">
                <span class="label label-info">
                    <?php echo is_array($media) ? $media['title'] : $media_list[$media] ?> <a href="#" class="fa fa-times fa-white"></a>
                </span>
				<input type="hidden" id="Article.Media[]" name="data[Article][Media][]" value="<?php echo is_array($media) ? $media['id'] : $media ?>">
			</div>
		<?php endforeach ?>
	<?php endif ?>
</div>
<div class="clearfix media"></div>