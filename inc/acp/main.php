<?php
$smarty->display($skin.'/admin_header.tpl');
if (!$_GET['do']) {
echo @file_get_contents("http://www.insanevisions.com/share/adaptcms2/updates.php?sitename=".urlencode($setting['sitename'])."&siteurl=".urlencode($siteurl)."&version=".urlencode($version)."&domain=".urlencode($domain));

echo "Hello, welcome to the ACP ".get_user($useridn)."! You have ".messages("new")." new and ".messages("")." total messages - <a href='".url("messages")."'>Messages</a><br /><br />";

$bla = "receiver_id = '".$useridn."' AND box = '".$folder."'";

$num = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."messages WHERE receiver_id = '".$useridn."' OR sender_id = '".$useridn."'"));
$numb = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."messages WHERE ".$bla));
$percent = @$num / $setting["message_limit"];

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["message_limit_page"]) - $setting["message_limit_page"]);

$fol .= "<form action='".$siteurl."index.php' method='get'><input type='hidden' name='view' value='social'><input type='hidden' name='do' value='messages'><select name='box' onchange='this.form.submit()' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'><option value=''></option>";
$array = array("inbox","sent","report","track");
while (list(, $i) = each ($array)) {
if ($i == "sent") {
$bla2 = "sender_id = '".$useridn."'";
} else {
$bla2 = "receiver_id = '".$useridn."' AND box = '".$i."'";
}
$num = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."messages WHERE ".$bla2));
$fol .= "<option value='".$i."'>".ucwords($i)." (".$num." messages)</option>";
}
$fol .= "</select></form>";

$sqlg = mysql_query("SELECT * FROM ".$pre."groups ORDER BY `name` ASC");
while($gr = mysql_fetch_array($sqlg)) {
$grp_opt .= "<option value='".$gr[name]."'>".$gr[name]."</option>";
}

$smarty->assign("folder", "Inbox");
$smarty->assign("messages_num", $numb);
$smarty->assign("messages_percent", $percent."%");
$smarty->assign("max_messages", $setting["message_limit"]);
$smarty->assign("folder_dropdown", $fol);
$smarty->assign("send_message", "<form action='".$siteurl."index.php' method='get'><a href='".$siteurl."index.php?view=social&do=messages&go=send'>Send Message</a> - <input type='hidden' name='view' value='social'><input type='hidden' name='do' value='messages'><input type='hidden' name='go' value='send'><select name='group' onchange='this.form.submit()' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'><option value=''></option>".$grp_opt."</select></form>");

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."messages WHERE ".$bla." ORDER BY `id` DESC LIMIT $from, ".$setting["message_limit_page"]);
while($r = mysql_fetch_array($sql)) {
if ($r[viewed] == 1) {
$icon2 = "<img src='{$siteurl}templates/".$skin."/images/mail_new.png' title='New Message'>";
$icon = "New";
} else {
$icon2 = "<img src='{$siteurl}templates/".$skin."/images/mail_read.png' title='Read Message'>";
$icon = "Read";
}

if (($i % 2) === 0) {
$msg_class[$i] = " class='light'";
} else {
$msg_class[$i] = " class='dark'";
}
$msg_icon[$i] = $icon;
if ($r[viewed] == 1) {
$msg_subject[$i] = "<a href='".$siteurl."index.php?view=social&do=messages&go=message&id=".$r[id]."'><i>".stripslashes($r[subject])."</i></a>";
} else {
$msg_subject[$i] = "<a href='".$siteurl."index.php?view=social&do=messages&go=message&id=".$r[id]."'>".stripslashes($r[subject])."</a>";
}
$msg_sender[$i] = get_user($r[sender_id], "");
$msg_date[$i] = date($setting['date_format'], $r[date]);
if ($folder == "sent") {
$msg_options[$i] = "<a href='".$siteurl."index.php?view=social&do=messages&go=reply&id=".$r[id]."'>Reply</a>&nbsp;&nbsp;<a href='".$siteurl."index.php?view=social&do=messages&go=forward&id=".$r[id]."'>FW</a>";
} else {
$msg_options[$i] = "<a href='".$siteurl."index.php?view=social&do=messages&go=reply&id=".$r[id]."'>Reply</a>&nbsp;&nbsp;<a href='".$siteurl."index.php?view=social&do=messages&go=forward&id=".$r[id]."'>FW</a>&nbsp;&nbsp;<a href='".$siteurl."index.php?view=social&do=messages&go=delete&id=".$r[id]."' onclick='return confirm(\"Are you sure you wish to delete this entry?\")'>Del</a>";
}

$co[] = $i;
$i++;
}
$smarty->assign("messages", $co);
$smarty->assign("class", $msg_class);
$smarty->assign("icon", $msg_icon);
$smarty->assign("subject", $msg_subject);
$smarty->assign("sender", $msg_sender);
$smarty->assign("date", $msg_date);
$smarty->assign("options", $msg_options);

$smarty->display($skin.'/message_list.tpl');
paginate($pre."messages WHERE ".$bla, $siteurl."index.php?view=".$_GET['view']."&do=".$_GET['do']."&box=".$_GET['box']."&", $setting["message_limit_page"]);



if ($_GET['quick_link'] && $_SERVER['HTTP_REFERER']) {
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'acp-quick-links' AND item_id = '".$useridn."' AND field_name = '".$_SERVER['HTTP_REFERER']."'")) == 0) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$_SERVER['HTTP_REFERER']."', 'acp-quick-links', '<a href=\'{url}\'>Quick Link ".rand(1,20)."</a>&nbsp;&nbsp;{edit}&nbsp;&nbsp;{delete}<br /><br />', '".$useridn."')");
}
}
?>
<script type="text/javascript">
	var rememberPositionedInCookie = true;
	var rememberPosition_cookieName = 'demo';
	</script>	
	<script type="text/javascript" src="<?php echo $siteurl; ?>inc/js/dragable-content.js"></script>
<table cellpadding='3' cellspacing='5' border='0'><tr>
<?php
$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'acp-quick-links' AND item_id = '".$useridn."' LIMIT 9");
while($r = mysql_fetch_array($sql)) {
$var1[] = "{url}";
$var1[] = "{edit}";
$var1[] = "{delete}";
$var2[] = $r[field_name];
$var2[] = "<a href='admin.php?do=edit&id=".$r[id]."'><img src='images/edit.png'></a>";
$var2[] = "<a href='admin.php?do=delete&id=".$r[id]."' onclick='return confirmDelete();'><img src='images/delete.png'></a>";
echo "<td style='width:165px;
		float:left;
		padding-left:3px;
		background-color:#E2EBED;
		border:1px dotted #000;
		padding:2px;
		height:125px;
' class='dragableElement'>".str_replace($var1,$var2,stripslashes($r[data]))."</td>";
unset($var1, $var2);
$i++;
if ($i == 3 or $i == 6) {
echo "</tr><tr>";
}
}
?>
</tr></table>
<?php
}

if ($_GET['do'] == "edit" && is_numeric($_GET['id'])) {
$r = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE id = '".$_GET['id']."'"));
echo "<script language='javascript' type='text/javascript' src='inc/js/edit_area/edit_area_full.js'></script>
<script language='Javascript' type='text/javascript'>
		editAreaLoader.init({
			id: 'data'	// id of the textarea to transform		
			,start_highlight: true	// if start with highlight
			,allow_resize: 'both'
			,allow_toggle: true
			,toolbar: 'search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, highlight, reset_highlight, |, help'
			,syntax_selection_allow: 'css,html,js,php,python,vb,xml,c,cpp,sql,basic,pas'
			,word_wrap: true
			,language: 'en'
			,syntax: 'html'	
		});
</script>

<form action='admin.php?do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Box <span class='drop'>Data</p></td><td><textarea id='data' name='data' style='height: 250px; width: 100%' class='textarea'>".stripslashes($r[0])."</textarea></td></tr><tr><td><br /><input type='submit' value='Update' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "edit2" && is_numeric($_GET['id'])) {
if (mysql_query("UPDATE ".$pre."data SET data = '".addslashes($_POST['data'])."' WHERE id = '".$_GET['id']."'") == TRUE) {
echo re_direct("1500", "admin.php");
echo "The box has been updated. <a href='admin.php'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The box data could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

if ($_GET['do'] == "delete" && is_numeric($_GET['id'])) {
if (mysql_query("DELETE FROM ".$pre."data WHERE id = '".$_GET['id']."'") == TRUE) {
echo re_direct("1500", "admin.php");
echo "The box has been deleted. <a href='admin.php'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The box data could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
?>