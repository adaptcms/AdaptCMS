<?php
function affiliates($limit = 5) {
global $pre;
global $setting;
global $siteurl;
global $smarty;
global $skin;

if (!$limit) {
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'plugin_affiliates' ORDER BY `id` DESC");
} else {
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'plugin_affiliates' ORDER BY `id` DESC LIMIT ".$limit);
}
while($rs = mysql_fetch_array($sql)) {
unset($pab, $rab, $r, $url);
$r = explode("|", $rs[data]);

if ($r[3] == 1) {
if ($r[1] == "no") {
$url = $r[0];
} else {
$url = $siteurl."index.php?view=plugins&plugin=affiliates&module=track&id=".$rs[id];
}
if ($r[2]) {
$smarty->assign("affiliate", "<a href='".$url."' target='popup'><img src='".$r[2]."' border='0' style='border:1px solid black'></a>");
} else {
$smarty->assign("affiliate", "<a href='".$url."' target='popup'>".$rs[field_name]."</a>");
}
$smarty->assign("date", timef($r[4]));
}
$smarty->display($skin.'/plugin_affiliates.tpl');
}

return "<br><center>[ <a href='".$siteurl."index.php?view=plugins&plugin=affiliates&module=apply'><b>Apply for Affiliation</b></a> ]</center>";
}

if ($_GET['view'] == "plugins" && $_GET['do'] == "load" && $_GET['plugin'] == "affiliates") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=plugins&do=load&plugin=affiliates'>Manage</a> - <a href='admin.php?view=plugins&do=load&plugin=affiliates&module=add'>Add Affiliate</a> - <a href='admin.php?view=plugins&do=load&plugin=affiliates&module=code'>Get Code</a>");
}
?>