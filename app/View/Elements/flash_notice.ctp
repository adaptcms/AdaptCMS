<div id="flashMessage" class="alert alert-info">
	<button class="close" data-dismiss="alert">×</button>
	<strong>Notice</strong> 
	<?php if (!empty($message)): ?>
		<?= $message ?>
	<?php endif ?>
</div>