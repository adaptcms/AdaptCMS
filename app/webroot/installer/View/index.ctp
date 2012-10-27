<?php $error = 0 ?>
<script>
$(document).ready(function() {
	$("#install").live('click', function() {
		window.location.href = "<?= $this->Html->url(array('action' => 'database')) ?>";
	});

	$("#upgrade").live('click', function() {
		window.location.href = "<?= $this->Html->url(array('action' => 'upgrade')) ?>";
	});
});
</script>

<h2>Requirements</h2>

<div class="well">
	<p>
		<?php if (version_compare(PHP_VERSION, '5.2.8', '>=')): ?>
			<span class="notice success">
				Your version of PHP is 5.2.8 or higher.
			</span>
		<?php else: ?>
			<?php $error = 1 ?>
			<span class="notice">
				Your version of PHP is too low. You need PHP 5.2.8 or higher to use AdaptCMS.
			</span>
		<?php endif ?>
	</p>
	<p>
		<?php if (is_writable(APP.'Config/database.php')): ?>
			<span class="notice success">
				Your database configuration file is writable.
			</span>
		<?php else: ?>
			<?php $error = 1 ?>
			<span class="notice">
				Your database configuration file is NOT writable.<br />Please chmod 777 - <?= APP.'Config/database.php' ?>
			</span>
		<?php endif ?>
	</p>
	<p>
		<?php if (is_writable(APP.'Config/config.php')): ?>
			<span class="notice success">
				Your core configuration file is writable.
			</span>
		<?php else: ?>
			<?php $error = 1 ?>
			<span class="notice">
				Your core configuration file is NOT writable.<br />Please chmod 777 - <?= APP.'Config/config.php' ?>
			</span>
		<?php endif ?>
	</p>
	<p>
		<?php if (is_writable(TMP)): ?>
			<span class="notice success">
				Your tmp directory is writable.
			</span>
		<?php else: ?>
			<?php $error = 1 ?>
			<span class="notice">
				Your tmp directory is NOT writable.<br />Please chmod 777 - <?= TMP ?>
			</span>
		<?php endif ?>
	</p>
	<p>
		<?php if (is_writable(WWW_ROOT.'uploads')): ?>
			<span class="notice success">
				Your uploads directory is writable.
			</span>
		<?php else: ?>
			<?php $error = 1 ?>
			<span class="notice">
				Your uploads directory is NOT writable.<br />Please chmod 777 - <?= WWW_ROOT.'uploads/' ?>
			</span>
		<?php endif ?>
	</p>
	<p>
		<?php if (is_writable(VIEW_PATH)): ?>
			<span class="notice success">
				Your templates directory is writable.
			</span>
		<?php else: ?>
			<?php $error = 1 ?>
			<span class="notice">
				Your templates directory is NOT writable. <br />Please chmod 777 - <?= VIEW_PATH ?>
			</span>
		<?php endif ?>
	</p>

	<?php if ($error != 1): ?>
		<div class="btn-group">
		  <button id="install" class="btn">Install</button>
		  <?php if (1 == 2): ?>
		  	<button id="upgrade" class="btn">Upgrade</button>
		  <?php endif ?>
		</div>
	<?php endif ?>
</div>