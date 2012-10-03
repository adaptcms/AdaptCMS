Hello,
<br /><br />
You have successfully created an account at <strong><?= $sitename ?></strong> with the username <?= $data['username'] ?>. Please click the below link to activate your account:
<br /><br />
<?= $this->Html->url(array(
		'controller' => 'users',
		'action' => 'activate',
		$data['username'],
		$activate_code
	),
	true
) ?>