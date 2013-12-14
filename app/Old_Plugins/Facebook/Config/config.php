<?php

$params = '{"appId":"521513811192026","apiKey":"521513811192026","secret":"1e7e0a85209be4083606e83d2ea85b0f","cookie":"1","locale":"en_US"}';
$system_config = array(
	'admin_menu' => false
);

$config = json_decode($params, true);
Configure::write('Facebook', array_merge($config, $system_config) );

$vars = array(
	array(
		'find' => '{{ facebook.init()',
		'replace' => '<?php echo $this->Facebook->init()'
	),
	array(
		'find' => '{{ facebook.html()',
		'replace' => '<?php echo $this->Facebook->html()'
	),
	array(
		'find' => '{{ facebook_logout }}',
		'replace' => '<?php echo $this->Facebook->logout(array("redirect" => array("plugin" => null, "action" => "logout", "controller" => "users"), "img" => "facebook-logout.png")) ?>'
	),
	array(
		'find' => '{{ facebook_registration',
		'replace' => '<?php echo $this->Facebook->registration()'
	),
	array(
		'find' => '{{ facebook.login',
		'replace' => '<?php echo $this->Facebook->login()'
	)
);
$routes = array_merge_recursive(Configure::read('global_vars'), $vars);
Configure::write('global_vars', $routes);
?>