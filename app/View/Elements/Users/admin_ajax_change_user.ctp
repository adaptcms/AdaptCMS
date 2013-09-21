<?php if ($success): ?>
	<div id="user-change-status" class="alert alert-success">
		<button class="close" data-dismiss="alert">x</button>
		<strong>Success</strong> The user has been <?= $status ?>.
	</div>
<?php else: ?>
	<div id="user-change-status" class="alert alert-error">
		<button class="close" data-dismiss="alert">×</button>
		<strong>Error</strong> The user could not be <?= $status ?>.
	</div>
<?php endif ?>