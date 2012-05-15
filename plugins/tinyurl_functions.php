<?php
if ($_GET['view'] == "plugins" && $_GET['do'] == "load" && $_GET['plugin'] == "tinyurl") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=plugins&do=load&plugin=tinyurl'>Manage</a> - <a href='admin.php?view=plugins&do=load&plugin=tinyurl&module=add'>Add Redirect</a>");
}
?>