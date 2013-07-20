<h2>Finish Installation</h2>

<div class="well">
	<p>Congratulations! You have completed your installation of AdaptCMS. Here's a link to your main page and the admin area:</p>

	<ul>
		<li>
			<?= $this->Html->link('Home Page', '/') ?>
		</li>
		<li>
			<?= $this->Html->link('Admin', '/admin') ?>
		</li>
	</ul>

	<p>Please login to your admin panel and then afterwards, for security purposes, please chmod the following files to <strong>644</strong>:</p>

	<p>
		<div class="alert alert-info">
			<?= APP.'Config/database.php' ?>
		</div>
		<div class="alert alert-info">
			<?= APP.'Config/config.php' ?>
		</div>
	</p>

	<p>Thanks again for installing and enjoy your copy of AdaptCMS!</p>
</div>