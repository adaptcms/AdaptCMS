<div id="<?= !empty($id) ? $id : 'flashMessage' ?>" class="alert alert-danger"<?= !empty($hidden) ? ' style="display: none;"' : '' ?>>
	<button class="close" data-dismiss="alert">×</button>
	<strong>Error</strong> 
	<?php if (!empty($message)): ?>
		<?= $message ?>
	<?php endif ?>
</div>