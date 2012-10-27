<h2>Finish Installation</h2>

<div class="well">
	<p>Congratulations! You have completed your installation of AdaptCMS. Please delete the following folder to continue to your site:</p>
	
	<p>
		<span class="notice">
			<?= WWW_ROOT.'installer/' ?>
		</span>
	</p>
	
	<p>After the folder has been deleted/renamed, you can continue to your website, here's a link to your main page and the admin area:</p>
	
	<ul>
		<li>
			<?= $this->Html->link('Home Page', '/') ?>
		</li>
		<li>
			<?= $this->Html->link('Admin', '/admin') ?>
		</li>
	</ul>

	<p>For security purposes, please chmod the following files to <strong>644</strong>:</p>

	<p>
		<span class="notice">
			<?= APP.'Config/database.php' ?>
		</span>
		<span class="notice">
			<?= APP.'Config/config.php' ?>
		</span>
	</p>

	<p>Thanks again for installing and enjoy your copy of AdaptCMS!</p>
</div>