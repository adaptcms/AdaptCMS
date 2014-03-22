<?php

$params = '[]';
$system_config = array(
    'admin_menu' => array(
        'controller' => 'google_maps',
        'plugin' => 'google_maps',
        'action' => 'index',
        'admin' => true
    ),
    'admin_menu_label' => 'Google Maps'
);

$config = json_decode($params, true);
Configure::write('GoogleMaps', array_merge($config, $system_config) );
?>