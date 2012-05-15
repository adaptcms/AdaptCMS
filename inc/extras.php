<?php
$js_includes = "<script src='".$siteurl."inc/js/jquery.js'></script>
	<script type='text/javascript' src='".$siteurl."inc/js/quick_edit/firebug/firebug.js'></script>
<script type='text/javascript' src='".$siteurl."inc/js/quick_edit/prototype.js'></script>";

	if ($_GET['view']) {
	$smarty->assign('acp_page_view', "<a href='admin.php?view=".$_GET['view']."'><span style='color:white'>".ucfirst($_GET['view'])."</span></a>");
	if ($_GET['view'] != "login" && $_GET['view'] != "login2") {
	$smarty->assign('acp_page_view2', "<a href='admin.php?view=".$_GET['view']."'>".ucfirst($_GET['view'])."</a>");
	}
	if ($_GET['do']) {
	$smarty->assign('acp_page_do', ucwords(str_replace("_", " ",$_GET['do']))." - ");
	$cur_page = ucwords(str_replace("_", " ",$_GET['do']))." > ".ucfirst($_GET['view']);
	} else {
	if ($_GET['view'] != "login" && $_GET['view'] != "login2") {
	$smarty->assign('acp_page_do', "Manage");
	}
	$cur_page = ucfirst($_GET['view']);
	}
	} else {
	$cur_page = "Main Page";
	}
	$smarty->assign('acp_page', $cur_page);

	if ($_GET['view'] == "fields" or $_GET['view'] == "sections" or $_GET['view'] == "media" or $_GET['view'] == "skins" or $_GET['view'] == "users" or $_GET['view'] == "groups" or $_GET['view'] == "levels" or $_GET['view'] == "polls" or $_GET['view'] == "pages" or $_GET['view'] == "settings" or $_GET['view'] == "plugins") {
	$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".strtolower($_GET['view'])."'"));
	$p = explode("|", $ps[0]);
	}

	// acp data directory bar


if ($p[0] && $_GET['view'] == "fields") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=fields&do=add'>Add Field</a>");
}
if ($p[0] && $_GET['view'] == "sections") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=sections&do=add'>Add Section</a>");
}
if ($p[0] && $_GET['view'] == "media") {
if ($_GET['do'] == "edit" && $_GET['id']) {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=media&do=add'>Add Media</a> - <a href='admin.php?view=media&do=upload&media_id=".$_GET['id']."'>Add File to Album</a>");
} else {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=media&do=add'>Add Media</a> - <a href='admin.php?view=media&do=upload'>Add File</a>");
}
}
if ($p[0] && $_GET['view'] == "skins") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=skins&do=add'>Add Skin</a> - <a href='admin.php?view=skins&do=add_template'>Add Template</a>");
}
if ($p[0] && $_GET['view'] == "users") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=users&do=add'>Add User</a>");
}
if ($p[0] && $_GET['view'] == "groups") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=groups&do=add'>Add Group</a>");
}
if ($p[0] && $_GET['view'] == "levels") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=levels&do=add'>Add level</a> - <a href='admin.php?view=levels&do=add_point'>Add Point Type</a>");
}
if ($p[0] && $_GET['view'] == "polls") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=polls&do=add'>Add Poll</a>");
}
if ($p[0] && $_GET['view'] == "pages") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=pages&do=add'>Add Page</a>");
}
if ($p[0] && $_GET['view'] == "settings") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=settings&do=add_section'>Add Section</a> - <a href='admin.php?view=settings&do=add_setting'>Add Setting</a>");
}
if ($_GET['view'] == "social") {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=social&do=blogs'>User Blogs</a> - <a href='admin.php?view=social&do=avatar'>Avatars</a> - <a href='admin.php?view=social&do=avatar&go=add'>Add Avatar</a>");
}
if ($p[0] && $_GET['view'] == "plugins" && !$_GET['plugin']) {
$smarty->assign('acp_bar_data', " / <a href='admin.php?view=plugins&do=install'>Install Plugin</a> - <a href='admin.php?view=share&do=plugins'>Download Plugins</a>");
}

$acpdata["content"] .= " / Sort: <select name='sort' class='input' onChange=\"jump('parent',this,0)\"><option value='' selected></option>";
$sqlst = mysql_query("SELECT * FROM ".$pre."sections ORDER BY `name` ASC");
while($y = mysql_fetch_array($sqlst)) {
unset($ret, $rets);
$ret = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE name = '".$y[name]."' AND `group` = '".$group."'"));
$rets = explode("|", $ret[0]);
if ($rets[0] == 1) {
$acpdata["content"] .= "<option value='admin.php?view=content&section=".$y[name]."'>".ucwords($y[name])."</option>";
}
}
$acpdata["content"] .= "</select>&nbsp;&nbsp;Add: <select name='addsection' class='input' onChange=\"jump('parent',this,0)\"><option value=''>-- Select Section --</option>";
$sql = mysql_query("SELECT * FROM ".$pre."sections ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
$pa = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$r[name]."'"));
$pd = explode("|", $pa[0]);
if ($pd[0] == 1) {
$acpdata["content"] .= "<option value='admin.php?view=content&do=add&section=".$r[name]."'>".ucwords($r[name])."</option>";
}
}
$acpdata["content"] .= "</select></form>";

if ($_GET['view'] == "content") {
$smarty->assign('acp_bar_data', $acpdata["content"]);
}

	$smarty->assign('acp_data', $acpdata);

unset($skin_options);
$skin_options = "<option value=''></option>";
$sql = mysql_query("SELECT * FROM ".$pre."skins WHERE skin = '' ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
if ($r[template] == "|yes" && perm("skins","edit") or $r[template] == "yes|yes" && perm("skins","edit") or $r[template] == "yes|" or $r[template] == "|") {
if ($r[name] == $skin) {
$skin_options .= "<option value='".$r[id]."' selected>-- ".$skin." --</option>";
} else {
$skin_options .= "<option value='".$r[id]."'>".stripslashes($r[name])."</option>";
}
}
}
$smarty->assign("change_skin", "<select name='change_skin' class='input'>".$skin_options."</select>");
?>