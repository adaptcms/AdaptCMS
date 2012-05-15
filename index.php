<?php
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

require_once("config.php");

if ($gzip == 1 && 1 == 2) { ob_start('ob_gzhandler'); }
else { ob_start(); }
switch ($_GET['view']) {
	default:
		include_once("inc/web/main.php");
		break;
	case 'section':
		include_once('inc/web/section.php');
		break;
	case 'content':
		include_once('inc/web/content.php');
		break;
	case 'pages':
		include_once('inc/web/pages.php');
		break;
	case 'polls':
		include_once('inc/web/polls.php');
		break;
	case 'plugins':
		include_once('inc/web/plugins.php');
		break;
	case 'login':
		include_once('inc/web/login.php');
		break;
	case 'register':
		include_once('inc/web/register.php');
		break;
	case 'logout':
		include_once('inc/web/logout.php');
		break;
	case 'user':
		include_once('inc/web/user.php');
		break;
	case 'media':
		include_once('inc/web/media.php');
		break;
	case 'rss':
		include_once('inc/web/rss.php');
		break;
	case 'social':
		include_once('inc/web/social.php');
		break;
	case 'search':
		include_once('inc/web/search.php');
		break;
}
$content = ob_get_contents();
ob_end_clean();

echo $content;

if ($_GET['view'] != "rss") {
$mtime = explode(' ', microtime());
echo "<br />Page processed in ".round($mtime[0] + $mtime[1] - $starttime, 3)." seconds.";

$smarty->display($skin.'/footer.tpl');
}
?>