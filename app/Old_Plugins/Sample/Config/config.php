<?php

$params = '[]';
$system_config = array(
    'admin_menu' => array(
        'controller' => 'sample',
        'plugin' => 'sample',
        'action' => 'index',
        'admin' => true
    ),
    'admin_menu_label' => 'Sample'
);

$config = json_decode($params, true);
Configure::write('Sample', array_merge($config, $system_config) );
?>