<?php
$smarty->display($skin.'/header.tpl');
echo $js_includes;

if (is_numeric($_GET['id'])) {
$section = mysql_fetch_row(mysql_query("SELECT section FROM ".$pre."content WHERE id = '".$_GET['id']."'"));

echo content(strtolower($section[0]), '', 1, '', $_GET['id']);

mysql_query("UPDATE ".$pre."content SET views=views+1 WHERE id = '".$_GET['id']."'");
} else {

echo content('homepage_content', '', 1, '', $_GET['id']);
}

?>