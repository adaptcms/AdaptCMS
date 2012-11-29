<div id="flashMessage" class="alert alert-error">
	<button class="close" data-dismiss="alert">×</button>
	<strong>Error</strong> 
	<?php if (!empty($message)): ?>
		<?= $message ?>
	<?php endif ?>
</div>