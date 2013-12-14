<div id="<?php if (!empty($id)): ?><?php echo $id ?><?php else: ?>flashMessage<?php endif ?>" class="alert alert-danger"<?php if (!empty($hidden)): ?> style="display: none;"<?php endif ?>>
	<button class="close" data-dismiss="alert">×</button>
	<strong>Error</strong>
	<?php if (!empty($message)): ?>
		<?php echo $message ?>
	<?php endif ?>
</div>