<?php if ($status): ?>
	<div id="flashMessage" class="alert alert-success">
		<button class="close" data-dismiss="alert">x</button>
		<strong>Success</strong> Your comment has been posted.
	</div>
<?php else: ?>
	<div id="flashMessage" class="alert alert-danger">
		<button class="close" data-dismiss="alert">x</button>
		<strong>Error</strong> <?= $message ?>
	</div>
<?php endif ?>