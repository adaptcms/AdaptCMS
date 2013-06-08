<div id="flashMessage" class="alert alert-success"<?= !empty($hidden) ? ' style="display: none;"' : '' ?>>
	<button class="close" data-dismiss="alert">×</button>
	<strong>Success</strong> 
	<?php if (!empty($message)): ?>
		<?= $message ?>
	<?php endif ?>
</div>