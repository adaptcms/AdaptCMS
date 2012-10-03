Hello,
<br /><br />
We have received a request to reset your password for the account <strong><?= $data['username'] ?></strong> at the website <strong><?= $sitename ?></strong>. If this is a mistake, please ignore this message, otherwise click the below link to proceed back to our site where you can enter in a new password.
<br /><br />
<?= $this->Html->url(array(
		'controller' => 'users',
		'action' => 'forgot_password',
		'username' => $data['username'],
		'activate' => $activate_code,
		'change' => 'forgot'
	),
	true
) ?>