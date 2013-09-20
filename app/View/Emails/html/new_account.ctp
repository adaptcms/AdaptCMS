Hello,
<br /><br />
You have successfully created an account at <strong><?= $sitename ?></strong> with the username <?= $data['username'] ?>. Below is your account information and a link to the website:
<br /><br />
<strong>Username:</strong> <?= $data['username'] ?><br />
<strong>Password:</strong> <?= $data['password'] ?><br />
<?= Router::url('/', true) ?>