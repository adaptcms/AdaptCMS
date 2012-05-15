<?php
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

include_once("config.php");

if ($gzip == 1) { ob_start('ob_gzhandler'); }
else { ob_start(); }
switch ($_GET['view']) {
	default:
		include_once("inc/acp/main.php");
		break;
	case 'content':
		include_once("inc/acp/content.php");
		break;
	case 'fields':
		include_once("inc/acp/fields.php");
		break;
	case 'file_releases':
		include_once("inc/acp/file_releases.php");
		break;
	case 'groups':
		include_once("inc/acp/groups.php");
		break;
	case 'help':
		include_once("inc/acp/help.php");
		break;
	case 'login':
		include_once("inc/acp/login.php");
		break;
	case 'logout':
		include_once("inc/acp/logout.php");
		break;
	case 'media':
		include_once("inc/acp/media.php");
		break;
	case 'pages':
		include_once("inc/acp/pages.php");
		break;
	case 'polls':
		include_once("inc/acp/polls.php");
		break;
	case 'plugins':
		include_once("inc/acp/plugins.php");
		break;
	case 'levels':
		include_once("inc/acp/levels.php");
		break;
	case 'sections':
		include_once("inc/acp/sections.php");
		break;
	case 'settings':
		include_once("inc/acp/settings.php");
		break;
	case 'share':
		include_once("inc/acp/share.php");
		break;
	case 'skins':
		include_once("inc/acp/skins.php");
		break;
	case 'social':
		include_once("inc/acp/social.php");
		break;
	case 'stats':
		include_once("inc/acp/stats.php");
		break;
	case 'support':
		include_once("inc/acp/support.php");
		break;
	case 'tools':
		include_once("inc/acp/tools.php");
		break;
	case 'users':
		include_once("inc/acp/users.php");
		break;
}
$content = ob_get_contents();
ob_end_clean();

include_once("inc/temp_parser.php");

$mtime = explode(' ', microtime());
echo "<br /><br />Page processed in ".round($mtime[0] + $mtime[1] - $starttime, 3)." seconds.";

$smarty->display($skin.'/admin_footer.tpl');
?>