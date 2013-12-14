<?php $error = 0 ?>
<script>
    $(document).ready(function() {
        $("#install").live('click', function() {
            window.location.href = $("#webroot").text() + "install/database";
        });

        $(".upgrade").live('click', function() {
            window.location.href = $("#webroot").text() + "install/upgrade/" + $(this).attr('id');
        });
    });
</script>

<h2>Requirements</h2>

<div class="well">
    <?php if (version_compare(PHP_VERSION, '5.2.8', '>=')): ?>
        <div class="alert alert-success">
            <strong>Success</strong>
            Your version of PHP is 5.2.8 or higher.
        </div>
    <?php else: ?>
        <?php $error = 1 ?>
        <div class="alert alert-error">
            <strong>Error</strong>
            Your version of PHP is too low. You need PHP 5.2.8 or higher to use AdaptCMS.
        </div>
    <?php endif ?>
    <?php if (is_writable(APP.'Config/database.php')): ?>
        <div class="alert alert-success">
            <strong>Success</strong>
            Your database configuration file is writable.
        </div>
    <?php else: ?>
        <?php $error = 1 ?>
        <div class="alert alert-error">
            <strong>Error</strong>
            Your database configuration file is NOT writable.<br />Please chmod 777 - <?= APP.'Config/database.php' ?>
        </div>
    <?php endif ?>
    <?php if (is_writable(APP.'Config/config.php')): ?>
        <div class="alert alert-success">
            <strong>Success</strong>
            Your core configuration file is writable.
        </div>
    <?php else: ?>
        <?php $error = 1 ?>
        <div class="alert alert-error">
            <strong>Error</strong>
            Your core configuration file is NOT writable.<br />Please chmod 777 - <?= APP.'Config/config.php' ?>
        </div>
    <?php endif ?>
	<?php if (is_writable(APP.'Config/configuration.php')): ?>
		<div class="alert alert-success">
			<strong>Success</strong>
			Your system configuration file is writable.
		</div>
	<?php else: ?>
		<?php $error = 1 ?>
		<div class="alert alert-error">
			<strong>Error</strong>
			Your system configuration file is NOT writable.<br />Please chmod 777 - <?= APP.'Config/configuration.php' ?>
		</div>
	<?php endif ?>
    <?php if (is_writable(TMP)): ?>
        <div class="alert alert-success">
            <strong>Success</strong>
            Your tmp directory is writable.
        </div>
    <?php else: ?>
        <?php $error = 1 ?>
        <div class="alert alert-error">
            <strong>Error</strong>
            Your tmp directory is NOT writable.<br />Please chmod 777 - <?= TMP ?>
        </div>
    <?php endif ?>
    <?php if (is_writable(WWW_ROOT)): ?>
        <div class="alert alert-success">
            <strong>Success</strong>
            Your webroot directory is writable.
        </div>
    <?php else: ?>
        <?php $error = 1 ?>
        <div class="alert alert-error">
            <strong>Error</strong>
            Your webroot directory is NOT writable.<br />Please chmod 777 - <?= WWW_ROOT ?>
        </div>
    <?php endif ?>
    <?php if (is_writable(VIEW_PATH)): ?>
        <div class="alert alert-success">
            <strong>Success</strong>
            Your templates directory is writable.
        </div>
    <?php else: ?>
        <?php $error = 1 ?>
        <div class="alert alert-error">
            <strong>Error</strong>
            Your templates directory is NOT writable. <br />Please chmod 777 - <?= VIEW_PATH ?>
        </div>
    <?php endif ?>
    <?php if (is_writable(APP . DS . 'Plugin')): ?>
        <div class="alert alert-success">
            <strong>Success</strong>
            Your Plugin directory is writable.
        </div>
    <?php else: ?>
        <?php $error = 1 ?>
        <div class="alert alert-error">
            <strong>Error</strong>
            Your Plugin directory is NOT writable.<br />Please chmod 777 - <?= APP . DS . 'Plugin' ?>
        </div>
    <?php endif ?>
    <?php if (is_writable(APP . DS . 'Old_Plugins')): ?>
        <div class="alert alert-success">
            <strong>Success</strong>
            Your Inactive Plugins directory is writable.
        </div>
    <?php else: ?>
        <?php $error = 1 ?>
        <div class="alert alert-error">
            <strong>Error</strong>
            Your Inactive Plugins directory is NOT writable.<br />Please chmod 777 - <?= APP . DS . 'Old_Plugins' ?>
        </div>
    <?php endif ?>

    <?php if ($error != 1): ?>
        <div class="btn-group">
            <?php if (!empty($upgrade_versions)): ?>
                <?php foreach($upgrade_versions as $version => $info): ?>
                    <button id="<?= Inflector::slug($version) ?>" class="btn upgrade">Upgrade from <?= ucfirst($version) ?></button>
                <?php endforeach ?>
            <?php endif ?>

            <button id="install" class="btn">Install</button>
        </div>
    <?php endif ?>
</div>