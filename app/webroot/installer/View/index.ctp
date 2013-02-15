<?php $error = 0 ?>
<script>
$(document).ready(function() {
	$("#install").live('click', function() {
		window.location.href = "<?= $this->Html->url(array('action' => 'database')) ?>";
	});

	$(".upgrade").live('click', function() {
		window.location.href = $("#webroot").text() + "install/upgrade/" + $(this).attr('id');
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
		<?php if (is_writable(WWW_ROOT)): ?>
			<span class="notice success">
				Your webroot directory is writable.
			</span>
		<?php else: ?>
			<?php $error = 1 ?>
			<span class="notice">
				Your webroot directory is NOT writable.<br />Please chmod 777 - <?= WWW_ROOT ?>
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
	<p>
		<?php if (is_writable(APP . DS . 'Plugin')): ?>
			<span class="notice success">
				Your Plugin directory is writable.
			</span>
		<?php else: ?>
			<?php $error = 1 ?>
			<span class="notice">
				Your Plugin directory is NOT writable.<br />Please chmod 777 - <?= APP . DS . 'Plugin' ?>
			</span>
		<?php endif ?>
	</p>
	<p>
		<?php if (is_writable(APP . DS . 'Old_Plugins')): ?>
			<span class="notice success">
				Your Inactive Plugins directory is writable.
			</span>
		<?php else: ?>
			<?php $error = 1 ?>
			<span class="notice">
				Your Inactive Plugins directory is NOT writable.<br />Please chmod 777 - <?= APP . DS . 'Old_Plugins' ?>
			</span>
		<?php endif ?>
	</p>

	<?php if ($error != 1): ?>
		<div class="btn-group">
		  <?php if (!empty($upgrade_versions)): ?>
		  	<?php foreach($upgrade_versions as $version => $info): ?>
		  		<button id="<?= $version ?>" class="btn upgrade">Upgrade from <?= ucfirst($version) ?></button>
		  	<?php endforeach ?>
		  <?php endif ?>

		  <button id="install" class="btn">Install</button>
		</div>
	<?php endif ?>
</div>