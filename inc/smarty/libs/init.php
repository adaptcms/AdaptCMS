<?php

// Include some commonly used custom functions
//require_once('inc/functions.php');

// Error reporting, site root
if ($_GET['sitepath']) {
$sitepath = "/home/".$dbuser."/public_html/";
}
$SITE_ROOT = $sitepath;

// Grab the Smarty files
if ($_GET['inc'] == 1) {
		require_once($sitepath.'smarty/libs/Smarty.class.php');
		$smarty = new Smarty;

		// Set Smarty directories
		$smarty->template_dir = $SITE_ROOT . '../templates/';
		$smarty->compile_dir  = $SITE_ROOT . 'smarty/templates_c/';
		$smarty->config_dir   = $SITE_ROOT . 'smarty/configs/';
		$smarty->cache_dir    = $SITE_ROOT . 'smarty/cache/';
} else {
		require_once($sitepath.'inc/smarty/libs/Smarty.class.php');
		$smarty = new Smarty;

		// Set Smarty directories
		$smarty->template_dir = $SITE_ROOT . 'templates/';
		$smarty->compile_dir  = $SITE_ROOT . 'inc/smarty/templates_c/';
		$smarty->config_dir   = $SITE_ROOT . 'inc/smarty/configs/';
		$smarty->cache_dir    = $SITE_ROOT . 'inc/smarty/cache/';
}

		$smarty->caching = false;
		$smarty->cache_modified_check = false;
		$smarty->compile_check = false;
		$smarty->allow_php_tag = true;

		// Assign our application's name
		$smarty->assign('app_name', $setting['sitename']);

		// Enable debugging via URL
		// This is recommended for development phases only
		$smarty->debugging_ctrl = 'URL';
?>