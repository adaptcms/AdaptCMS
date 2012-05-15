<?php
$smarty->display($skin.'/admin_header.tpl');
echo wysiwyg();

if ($_GET['do'] == "") {
echo '<script language="javascript">function jump(targ,selObj,restore){
eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
if (restore) selObj.selectedIndex=0;
}</script>';

echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><colgroup>
	<col id='col1_1'></col>
		<col id='col1_2'></col>
		<col id='col1_3'></col>
		<col id='col1_4'></col>
		<col id='col1_5'></col>
		<col id='col1_6'></col>
		<col id='col1_7'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Title</b></td><td><b>Author</b></td><td><b>Section</b></td><td><b>Posted</b></td><td></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

if ($_GET['section'] && !$_GET['status']) {
$perm = " WHERE section = '".$_GET['section']."'";
$perm2 = "&section=".$_GET['section'];
} elseif ($_GET['section'] && $_GET['status']) {
$perm = " WHERE section = '".$_GET['section']."' AND status != ''";
$perm2 = "&section=".$_GET['section']."&status=".$_GET['status'];
} elseif (!$_GET['section'] && $_GET['status']) {
$perm = " WHERE status != ''";
$perm2 = "&status=".$_GET['status'];
}

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."content".$perm." ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$r[section]."'"));
$p = explode("|", $ps[0]);

if ($p[0] && !$p[1] && !$p[2] && $r[user_id] == $useridn or $p[0] && $p[1]) {

if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
$cnum = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = '".$r[id]."'"));

echo "<td>".$r[id]."</td><td><a href='".url("content", $r[id], $r[name], $r[section])."'>".stripslashes($r[name])."</a></td><td>".get_user($r[user_id])."</td><td>".$r[section]."</td><td>".timef($r[date])."</td><td style='padding-right:15px'><img src='images/comments.png'> <a href='".url("content", $r[id], $r[name], $r[section])."#comments'><b>".$cnum."</b></a>&nbsp;&nbsp;</td><td>";
if ($p[1] or $p[0] && $r[user_id] == $useridn) {
echo "<a href='admin.php?view=content&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2] or $p[0] && $r[user_id] == $useridn) {
echo "<a href='admin.php?view=content&do=delete&id=".$r[id]."' onclick='return confirmDelete();'><img src='images/delete.png'></a>";
}
if ($p[0] && $p[1] && $p[2] && $r[status]) {
echo "&nbsp;&nbsp;<a href='admin.php?view=content&do=verify&id=".$r[id]."' onclick='return confirmVerify();'><img src='images/attn.png' title='Publish'></a>";
}
echo "</td></tr>";
}
$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','S', 'S', 'N', 'S', 'S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."content".$perm),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=content".$perm2."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=content".$perm2."&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=content".$perm2."&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($_GET['do'] == "add") {
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$_GET['section']."'"));
$p = explode("|", $ps[0]);
if ($p[0]) {
echo "<link rel='stylesheet' type='text/css' media='all' href='inc/js/calendar/calendar-win2k-cold-1.css' title='win2k-cold-1' />
<link rel='stylesheet' type='text/css' media='all' href='inc/js/validate.css' />
<script type='text/javascript' src='inc/js/calendar/calendar.js'></script>
<script type='text/javascript' src='inc/js/calendar/lang/calendar-en.js'></script>
<script type='text/javascript' src='inc/js/calendar/calendar-setup.js'></script>
<script type='text/javascript' src='inc/js/validate.js'></script>

		<script src='http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js' type='text/javascript'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/effects.js' type='text/javascript'></script>
		<script type='text/javascript' src='inc/js/fabtabulous.js'></script>
		<script type='text/javascript' src='inc/js/validation.js'></script>

		<script type='text/javascript'>
function popup() {
win = window.open('','myWin','toolbars=0,status=1,scrollbars=1,resizable=1');
document.post.action='".$siteurl."inc/preview.php?section=".$_GET['section']."';
document.post.target='myWin';
document.post.submit();
}
</script>

		<link rel='stylesheet' type='text/css' href='inc/js/style.css' />";
echo "<form action='admin.php?view=content&do=add2&section=".$_GET['section']."' method='post' id='test' name='post' target='_self'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Title</p><input type='text' class='addtitle' name='name' size='23' class='input'></td></tr>";

$sqlst = mysql_query("SELECT * FROM ".$pre."sections WHERE name != '".$_GET['section']."'");
while($y = mysql_fetch_array($sqlst)) {
$content = "";$xnum = "0";
echo "<tr><td><p>Relate <span class='drop'>".ucwords($y[name]);

$sqlsts = mysql_query("SELECT * FROM ".$pre."content WHERE section = '".$y[name]."' ORDER BY `name` ASC");
while($x = mysql_fetch_array($sqlsts)) {
$content.= "<option value='".$x[id]."'>".stripslashes($x[name])."</option>";
$xnum = $xnum + 1;
}

if ($xnum == 0) {
$sz = 1;
}
if (($xnum > 0) && ($xnum < 6)) {
$sz = $xnum + 1;
}
if ($xnum > 5) {
$sz = 5;
}
echo "</span><br /><input type='hidden' name='rels[]' value='".$y[name]."'><select class='select' name='rel_".$y[name]."[]' size='".$sz."' multiple><option value='' selected></option>".$content."</select></td></tr>";
}

$sqlsts2 = mysql_query("SELECT * FROM ".$pre."media ORDER BY `name` ASC");
while($x2 = mysql_fetch_array($sqlsts2)) {
$content2.= "<option value='".$x2[id]."'>".stripslashes($x2[name])."</option>";
$xnum2 = $xnum2 + 1;
}

if ($xnum2 == 0) {
$sz2 = 1;
}
if (($xnum2 > 0) && ($xnum2 < 6)) {
$sz2 = $xnum2 + 1;
}
if ($xnum2 > 5) {
$sz2 = 5;
}
if ($content2) {
echo "<tr><td><p>Relate <span class='drop'>Media</span><br /><select class='select' name='rel_media' size='".$sz2."'><option value='' selected></option>".$content2."</select></td></tr>";
}


$sql = mysql_query("SELECT * FROM ".$pre."fields WHERE section = '".$_GET['section']."' OR section = 'global' ORDER BY `type` ASC");
while($i = mysql_fetch_array($sql)) {
if ($i[limit] != "/") {
echo "<input type='hidden' name='cflimit_".$i[name]."' value='".$i[limit]."'>";
}
if ($i[required]) {
echo "<input type='hidden' name='cfrequired_".$i[name]."' value='yes'>";
}

echo "<input type='hidden' name='cftype_".$i[name]."' value='textarea'><input type='hidden' name='id[]' value='".$i[name]."'><tr><td><p>".str_replace("_", " ", str_replace("_", " ", ucwords($i[name])))."</p>";
if ($i[type] == "textfield") {
echo "<input type='text' name='cf_".$i[name]."'";
if ($i[required]) {
echo " class='required'";
}
echo " size='16' class='title'>";
}
if ($i[type] == "textarea") {
echo "<textarea class='textarea' cols='70' rows='16' id='cf_".$i[name]."' name='cf_".$i[name]."'";
if ($i[required]) {
echo " class='required'";
}
echo " class='textarea' class='mceSimple'></textarea>";
}
if ($i[type] == "radio") {
$ex = explode(",", $i[data]);
sort($ex);
while (list(, $a) = each ($ex)) {
echo $a." <input type='radio' name='cf_".$i[name]."' value='".$a."'";
if ($i[required]) {
echo " class='validate-one-required'";
}
echo " class='textarea'>";
}
}
if ($i[type] == "checkbox") {
$ex = explode(",", $i[data]);
sort($ex);
while (list(, $a) = each ($ex)) {
echo $a." <input type='checkbox' name='cf_".$i[name]."' value='".$a."'";
if ($i[required]) {
echo " class='validate-one-required'";
}
echo " class='textarea'>";
}
}
if ($i[type] == "select") {
echo "<select name='cf_".$i[name]."'";
if ($i[required]) {
echo " class='validate-selection'";
}
echo " class='select'><option value='' selected>-- Select --</option>";
$ex = explode(",", $i[data]);
sort($ex);
while (list(, $a) = each ($ex)) {
echo "<option value='".$a."'>".ucwords($a)."</option>";
}
echo "</select>";
}
if ($i[type] == "file") {
echo "<select name='cf_".$i[name]."'";
if ($i[required]) {
echo " class='validate-selection'";
}
echo " class='select'><option value='' selected>-- Select --</option>";
$sqlfl = mysql_query("SELECT * FROM ".$pre."files ORDER BY `id` DESC");
while($row = mysql_fetch_array($sqlfl)) {
if (preg_match("/http/", $row[filedir])) {
$filedir = $row[filedir];
} else {
$filedir = $siteurl.$row[filedir];
}
echo "<option value='".$filedir.$row[filename]."'>";
if (strlen($row[filename]) > 40) {
echo substr(urldecode($row[filename]), 0, 37)."...";
} else {
echo substr(urldecode($row[filename]), 0, 40);
}
echo "</option>";
}
echo "</select>";
}
echo "<br /><small>".stripslashes($i[description])."</small></td></tr>";
}
echo "<input type='hidden' name='postpone' id='f_date_e' />
<tr><td><p>Publish <span class='drop'>Later?</span><br /><span id=\"show_e\">-- not entered --</span> <img
src=\"inc/js/calendar/img.gif\" id=\"f_trigger_e\" style=\"cursor: pointer; border: 1px solid
red;\" title=\"Date selector\" onmouseover=\"this.style.background='red';\"
onmouseout=\"this.style.background=''\" /></td></tr>

<script type='text/javascript'>
    Calendar.setup({
        inputField     :    'f_date_e',     // id of the input field
        ifFormat       :    '%s',     // format of the input field (even if hidden, this format will be honored)
		showsTime      :    true,            // will display a time selector
		timeFormat     :	12,
        displayArea    :    'show_e',       // ID of the span where the date is to be shown
        daFormat       :    '%A, %B %d, %Y - %I:%M %p',// format of the displayed date
        button         :    'f_trigger_e',  // trigger button (well, IMG in our case)
        align          :    'Tl',           // alignment (defaults to 'Bl')
        singleClick    :    true
    });
</script>
<tr><td><p>Different <span class='drop'>Author?</span><br /><select name='author' class='select'><option value='".$useridn."' selected>".$user_cookie."</option>";
$sqlgrp = mysql_query("SELECT * FROM ".$pre."groups ORDER BY `name` ASC");
while($gr = mysql_fetch_array($sqlgrp)) {
$fet = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$gr[name]."' AND name = '".$_GET['section']."'"));
$value = explode("|", $fet[0]);
if ($value[0]) {

$sql = mysql_query("SELECT * FROM ".$pre."users WHERE act = 'yes' AND ver = 'yes' AND `group` = '".$gr[name]."' ORDER BY `username` ASC");
while($u = mysql_fetch_array($sql)) {
if ($u[id] != $useridn) {
echo "<option value='".$u[id]."'>".$u[username]."</option>";
}
}

}
}
echo "</select></td></tr>
<tr><td><p>Tags</p><input name='tags' class='title' size='18'><br /><small>Separate tags with commas</small></td></tr>";

echo "<tr><td>";
if (!$p[3]) {
echo "<br /><input type='submit' name='type' value='Add Content' class='addContent-button'>&nbsp;&nbsp;";
}
echo "<input type='submit' name='type' value='Save Draft' class='addContent-button'></td><td><input type='submit' class='addContent-button' value='Preview'  onclick=\"popup();\"></td></tr></table></form>

					<script type='text/javascript'>
						function formCallback(result, form) {
							window.status = \"valiation callback for form '\" + form.id + \"': result = \" + result;
						}
						
						var valid = new Validation('test', {immediate : true, useTitles:true, onFormValidate : formCallback});
					</script>";
}
}

if ($_GET['do'] == "add2" && $_POST['name']) {
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$_GET['section']."'"));
$p = explode("|", $ps[0]);
if ($p[0]) {

if ($_POST['postpone']) {
$ver = $_POST['postpone'];
} else {
if ($_POST['type'] == "Save Draft") {
$ver = "saved";
} else {
$ver = "";
}
}

$query = mysql_query("INSERT INTO ".$pre."content VALUES (null, '".addslashes($_POST["name"])."', '".$_GET['section']."', '".$_POST['author']."', '".$ver."', '".time()."', '0', '".date("m")."', '".date("Y")."', '0|0', '0')");
$error = 0;

$id = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."content ORDER BY `date` DESC LIMIT 1"));
mysql_query("INSERT INTO ".$pre."data VALUES (null, 'name', 'content-name', '".addslashes($_POST["name"])."', '".$id[0]."')");

mysql_query("INSERT INTO ".$pre."data VALUES (null, 'tags', 'content-tags', '".strip_tags($_POST['tags'])."', '".$id[0]."')");

while (list(, $i) = each ($_POST['id'])) {
if ($i) {
if ((strtolower($setting["wysiwyg"]) == "no") && ($_POST["cftype_$i"])) {
$text = preg_replace("/\n/", "<br />\n", addslashes($_POST["cf_$i"]));
if ($contents == "") {
$contents = $text;
}
$contents2 .= $text;
} else {
$text = addslashes($_POST["cf_$i"]);
}
if ($_POST["cf_$i"] && $text) {
if ($_POST["cflimit_$i"]) {
$lim = "";
$lim = explode("/", $_POST["cflimit_$i"]);
}
if (strlen($text) <= $lim[0] && strlen($text) >= $lim[1] or !$_POST["cflimit_$i"] or !$lim[0] && !$lim[1]) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$i."', 'content-custom-data', '".$text."', '".$id[0]."')");
} else {
$error = $error + 1;
}
}
}
}

if ($_POST['rel_media']) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, 'Media', 'relate-content', '".$_POST['rel_media']."', '".$id[0]."')");
}

while (list(, $y) = each ($_POST['rels'])) {
while (list(, $x) = each ($_POST["rel_$y"])) {
if ($x && $y) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$y."', 'content-relate', '".$x."', '".$id[0]."')");
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$_GET['cat']."', 'content-relate', '".$id[0]."', '".$x."')");
}
}
}

if ($setting["trackback_urls"] && $_POST['ping']) {
include("../trackback.php");
$urls = explode(",", $setting["trackback_urls"]);
while (list(, $zz) = each ($urls)) {
if (send_trackback($mrw_1["content"].fthrough($_POST['name'], $_GET['section'], $id[0]), $_POST['name'], $contents, $setting['sitename'], $zz) == 1) {
echo "<a href='".$zz."'><b>".$zz."</b></a> pinged<br />";
} else {
echo "<a href='".$zz."'><b>".$zz."</b></a> <font color='red'>could not be</font> pinged<br />";
}
}
if ($tb_array = auto_discovery($contents2)) {
     	foreach($tb_array as $tb_key => $tb_url) {
     		if (send_trackback($mrw_1["content"].fthrough($_POST['name'], $_GET['cat'], $id[0]), $_POST['name'], $contents, $setting['sitename'], $tb_url)) {
     			// Successful ping...
     			echo "Trackback sent to <a href='".$tb_url."'><b>".$tb_url."</b></a><br />";
     		} else {
     			// Error pinging...
     			echo "Trackback to <a href='".$tb_url."'><b>".$tb_url."</b></a><br />";
     		}
     	}
     } else {
     	// No trackbacks in TEXT...
     	echo "No trackbacks were auto-discovered...<br />";
     }

}
echo "<br /><br />";

if ($query == TRUE) {
echo "The content <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=content'>Return</a>";
if ($error > 0) {
echo "<br/><br />There were, however, ".$error." fields that did not have there data added. Reason - Min/Max Character amount too little or too much entered.";
}
if (1 == 1 or $setting["article_promote"] == "yes") {
echo "<br /><br /><br /><h2>Promote Article</h2><br />

<b>Submit to Social Sites</b><br />
<form name='formname' action='".$siteurl."inc/promote.php' target='windowName'
method='post' onsubmit='window.open(\"\", this.target,
\"dialog,modal,scrollbars=yes,resizable=yes,width=800,height=640,left=362,top=284\");'><table cellpadding='5' cellspacing='2' align='left'><tr><td>Title</td><td><input type='text' name='title' value=\"".stripslashes($_POST['name'])."\" size='20'></td></tr><tr><td>Description</td><td><textarea cols='30' rows='12' name='description'></textarea></td></tr><input type='hidden' name='url' value='".url("content", $id[0], $_POST['name'], $_GET['section'])."'>

<tr><td>Submit</td><td><input type='submit' name='site' value='Digg'>&nbsp;&nbsp;<input type='submit' name='site' value='Reddit'>&nbsp;&nbsp;<input type='submit' name='site' value='Stumbleupon'>&nbsp;&nbsp;<input type='submit' name='site' value='Facebook'>&nbsp;&nbsp;<input type='submit' name='site' value='N4G'></td></tr></table></form><br /><br /><br /><br clear='all' /><b>Post to Twitter</b><br /><br />

<a href='http://twitter.com/share' class='twitter-share-button' data-url='".url("content", $id[0], $_POST['name'], $_GET['section'])."' data-text='".$_POST['name']."' data-count='none'>Tweet</a><script type='text/javascript' src='http://platform.twitter.com/widgets.js'></script>

<br /><br /><b>Post via Share</b><br /><br /><a href='admin.php?view=share&do=promote' target='new'>Submit Now!</a>";

}
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The content could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT name,section,user_id,status FROM ".$pre."content WHERE id = '".$_GET['id']."'"));
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$r[1]."'"));
$p = explode("|", $ps[0]);
if ($r[2] == $useridn or $p[1]) {


$min = $mins * 5 + 1;
$time1 = time() - $min;
$time2 = time() + 1;
$current = 0;
$cursql = mysql_query("SELECT * FROM ".$pre."stats WHERE page = '".$pageurl."' AND time_last_visit > '".$time1."' AND user_id != '' OR page = '".$pageurl."' AND time_last_visit < '".$time2."' AND user_id != ''");
while($row = mysql_fetch_array($cursql)) {
if ($row[user_id] != $useridn) {
$grabs = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."stats WHERE user_id = '".$row[user_id]."' ORDER BY `id` DESC LIMIT 1"));
if ($grabs[0] != $row[id] or !$grabs[0]) {
$current++;
if ($current != 1) {
$data .= ", ";
}
$data .= get_user($row[user_id]);
}
}
}
if ($current > 0) {
echo "<small>There have been ".$current." users editing this article in the last 5 minutes. (".$data.")</small><br />";
}

echo "<link rel='stylesheet' type='text/css' media='all' href='inc/js/calendar/calendar-win2k-cold-1.css' title='win2k-cold-1' />
<link rel='stylesheet' type='text/css' media='all' href='inc/js/validate.css' />
<script type='text/javascript' src='inc/js/calendar/calendar.js'></script>
<script type='text/javascript' src='inc/js/calendar/lang/calendar-en.js'></script>
<script type='text/javascript' src='inc/js/calendar/calendar-setup.js'></script>
<script type='text/javascript' src='inc/js/validate.js'></script>

		<script src='http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js' type='text/javascript'></script>
		<script src='http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/effects.js' type='text/javascript'></script>
		<script type='text/javascript' src='inc/js/fabtabulous.js'></script>
		<script type='text/javascript' src='inc/js/validation.js'></script>

				<script type='text/javascript'>
function popup() {
win = window.open('','myWin','toolbars=0,status=1,scrollbars=1,resizable=1');
document.post.action='".$siteurl."inc/preview.php?section=".$r[1]."';
document.post.target='myWin';
document.post.submit();
}
</script>

		<link rel='stylesheet' type='text/css' href='inc/js/style.css' />";
echo "<form action='admin.php?view=content&do=edit2&id=".$_GET['id']."' method='post' name='post' target='_self'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><input type='hidden' name='section' value='".$r[1]."'><input type='hidden' name='user_id' value='".$r[2]."'><tr><td><p>Name</p><input type='text' name='name' value=\"".htmlspecialchars(stripslashes($r[0]))."\" class='addtitle'></td></tr>";
$tags = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE item_id = '".$_GET['id']."' AND field_name = 'tags' ORDER BY `id` DESC"));

$sqlst = mysql_query("SELECT * FROM ".$pre."sections WHERE name != '".$r[1]."'");
while($y = mysql_fetch_array($sqlst)) {
$content = "";$xnum = "0";
echo "<tr><td><p>Relate <span class='drop'>".ucwords($y[name]);

$sqlsts = mysql_query("SELECT * FROM ".$pre."content WHERE section = '".$y[name]."' ORDER BY `name` ASC");
while($x = mysql_fetch_array($sqlsts)) {
$xnum = $xnum + 1;
$datas = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."data WHERE field_name = '".$y[name]."' AND item_id = '".$_GET['id']."' AND data = '".$x[id]."' OR field_name = '' AND item_id = '".$_GET['id']."' AND data = '".$x[id]."'"));
if ($datas[0]) {
echo "<input type='hidden' name='reldata_".$x[id]."' value='".$x[id]."'><input type='hidden' name='relid_".$y[name]."[]' value='".$x[id]."'>";
$content.= "<option value='".$x[id]."' selected>-- ".stripslashes($x[name])." --</option>";
$find = 1;
} else {
$content.= "<option value='".$x[id]."'>".stripslashes($x[name])."</option>";
}
}

if ($xnum == 0) {
$sz = 1;
}
if (($xnum > 0) && ($xnum < 6)) {
$sz = $xnum + 1;
}
if ($xnum > 5) {
$sz = 5;
}
echo "</span><br /><input type='hidden' name='rels[]' value='".$y[name]."'><select name='rel_".$y[name]."[]' class='select' size='".$sz."' multiple><option value=''";
if (!$find) {
echo " selected";
}
echo "></option>".$content."</select></td></tr>";
unset($find);
}

$sqlsts2 = mysql_query("SELECT * FROM ".$pre."media ORDER BY `name` ASC");
while($x2 = mysql_fetch_array($sqlsts2)) {
$datas2 = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."data WHERE field_name = 'Media' AND item_id = '".$_GET['id']."' AND data = '".$x2[id]."'"));
if ($datas2[0]) {
echo "<input type='hidden' name='relmedia' value='".$x2[id]."'>";
$content2.= "<option value='".$x2[id]."' selected>-- ".stripslashes($x2[name])." --</option>";
} else {
$content2.= "<option value='".$x2[id]."'>".stripslashes($x2[name])."</option>";
}
$xnum2 = $xnum2 + 1;
}

if ($xnum2 == 0) {
$sz2 = 1;
}
if (($xnum2 > 0) && ($xnum2 < 6)) {
$sz2 = $xnum2 + 1;
}
if ($xnum2 > 5) {
$sz2 = 5;
}
if ($content2) {
echo "<tr><td><p>Relate <span class='drop'>Media</span></p><br /><select name='rel_media' class='select' size='".$sz2."'><option value='' selected></option>".$content2."</select></td></tr>";
}


$sql = mysql_query("SELECT * FROM ".$pre."fields WHERE section = '".$r[1]."' OR section = 'global' ORDER BY `type` ASC");
while($i = mysql_fetch_array($sql)) {
$data = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE item_id = '".$_GET['id']."' AND field_name = '".$i[name]."'"));
echo "<input type='hidden' name='id[]' value='".$i[name]."'><input type='hidden' name='cfdata_".$i[name]."' value='".htmlentities(stripslashes($data[0]))."'>";

if ($i[limit] != "/") {
echo "<input type='hidden' name='cflimit_".$i[name]."' value='".$i[limit]."'>";
}
echo "<tr><td><p>".str_replace("_", " ", ucwords($i[name]))."</p><br />";
if ($i[type] == "textfield") {
echo "<input type='text' name='cf_".$i[name]."'";
if ($i[required]) {
echo " class='required'";
}
echo " size='16' class='title' value=\"".htmlspecialchars(stripslashes($data[0]))."\">";
}
if ($i[type] == "textarea") {
echo "<textarea cols='70' rows='16' id='cf_".$i[name]."' name='cf_".$i[name]."'";
if ($i[required]) {
echo " class='required'";
}
echo " class='textarea' class='mceSimple'>".stripslashes($data[0])."</textarea>";
}
if ($i[type] == "radio") {
$ex = explode(",", $i[data]);
sort($ex);
while (list(, $a) = each ($ex)) {
echo $a." <input type='radio' name='cf_".$i[name]."' value='".$a."'";
if ($i[required]) {
echo " class='validate-one-required'";
}
echo " class='select'";
if ($a == stripslashes($data[0])) {
echo " checked";
}
echo ">";
}
}
if ($i[type] == "checkbox") {
$ex = explode(",", $i[data]);
sort($ex);
while (list(, $a) = each ($ex)) {
echo $a." <input type='checkbox' name='cf_".$i[name]."' value='".$a."'";
if ($i[required]) {
echo " class='validate-one-required'";
}
echo " class='select'";
if ($a == stripslashes($data[0])) {
echo " checked";
}
echo ">";
}
}
if ($i[type] == "select") {
echo "<select name='cf_".$i[name]."'";
if ($i[required]) {
echo " class='validate-selection'";
}
echo " class='select'><option value='' selected>-- Select --</option>";
$ex = explode(",", $i[data]);
sort($ex);
while (list(, $a) = each ($ex)) {
echo "<option value='".$a."'";
if ($a == stripslashes($data[0])) {
echo " selected";
}
echo ">".ucwords($a)."</option>";
}
echo "</select>";
}
if ($i[type] == "file") {
echo "<select name='cf_".$i[name]."'";
if ($i[required]) {
echo " class='validate-selection'";
}
echo " class='select'><option value='' selected>-- Select --</option>";
$sqlfl = mysql_query("SELECT * FROM ".$pre."files ORDER BY `id` DESC");
while($row = mysql_fetch_array($sqlfl)) {
if (preg_match("/http/", $row[filedir])) {
$filedir = $row[filedir];
} else {
$filedir = $siteurl.$row[filedir];
}
echo "<option value='".$filedir.$row[filename]."'";
if ($filedir.$row[filename] == $data[0]) {
echo " selected>-- ";
if (strlen($row[filename]) > 40) {
echo substr(urldecode($row[filename]), 0, 37)."...";
} else {
echo substr(urldecode($row[filename]), 0, 40);
}
echo " --";
} else {
echo ">";
if (strlen($row[filename]) > 40) {
echo substr(urldecode($row[filename]), 0, 37)."...";
} else {
echo substr(urldecode($row[filename]), 0, 40);
}

}
echo "</option>";
}
echo "</select>";
}
echo "<br /><small>".stripslashes($i[description])."</small></td></tr>";
}

if (is_numeric($r[3])) {
echo "<tr><td><p>Publish <span class='drop'>Date</span></p><br />".date("l, F d, Y - h:i A", $r[3])."</td></tr>";
}
echo "<input type='hidden' name='postpone' id='f_date_e' /><tr><td><p>";
if (is_numeric($r[3])) {
echo "New Publish<span class='drop'> Date?</span>";
} else {
echo "Publish <span class='drop'>Later?</span>";
}
echo "</p><br /><span id=\"show_e\">-- not entered --</span> <img
src=\"inc/js/calendar/img.gif\" id=\"f_trigger_e\" style=\"cursor: pointer; border: 1px solid
red;\" title=\"Date selector\" onmouseover=\"this.style.background='red';\"
onmouseout=\"this.style.background=''\" /></td></tr>

<script type='text/javascript'>
    Calendar.setup({
        inputField     :    'f_date_e',     // id of the input field
        ifFormat       :    '%s',     // format of the input field (even if hidden, this format will be honored)
		showsTime      :    true,            // will display a time selector
		timeFormat     :	12,
        displayArea    :    'show_e',       // ID of the span where the date is to be shown
        daFormat       :    '%A, %B %d, %Y - %I:%M %p',// format of the displayed date
        button         :    'f_trigger_e',  // trigger button (well, IMG in our case)
        align          :    'Tl',           // alignment (defaults to 'Bl')
        singleClick    :    true
    });
</script><tr><td><p>Different <span class='drop'>Author?</span><br /><select name='author' class='select'><option value='".$useridn."'>".$user_cookie."</option>";
$sqlgrp = mysql_query("SELECT * FROM ".$pre."groups ORDER BY `name` ASC");
while($gr = mysql_fetch_array($sqlgrp)) {
$fet = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$gr[name]."' AND name = '".$r[1]."'"));
$value = explode("|", $fet[0]);
if ($value[0]) {

$sql = mysql_query("SELECT * FROM ".$pre."users WHERE act = 'yes' AND ver = 'yes' AND `group` = '".$gr[name]."' ORDER BY `username` ASC");
while($u = mysql_fetch_array($sql)) {
if ($u[id] != $useridn && $u[id] == $r[2]) {
echo "<option value='".$u[id]."' selected>".$u[username]."</option>";
} elseif ($u[id] != $useridn && $u[id] != $r[2]) {
echo "<option value='".$u[id]."'>".$u[username]."</option>";
}
}

}
}
echo "</select></td></tr>
<input type='hidden' name='tags_old' value='".$tags[0]."'>
<tr><td><p>Tags</p><input name='tags' value='".$tags[0]."' class='title'><br /><small>Separate tags with commas</small></td></tr>";
if (is_numeric($r[3])) {
echo "<tr><td><p>Publish <span class='drop'>Now?</span></p><input type='checkbox' name='publish_now' value='yes' class='input'></td></tr>";
}
echo "<input type='hidden' name='art_status' value='".$r[3]."'><tr><td><input type='submit' name='type' value='Update' class='addContent-button'>&nbsp;&nbsp;";
if ($r[3] == "saved") {
echo "<input type='submit' name='type' value='Publish Now' class='addContent-button'>&nbsp;&nbsp;";
} else {
echo "<input type='submit' name='type' value='Publish Later' class='addContent-button'>&nbsp;&nbsp;";
}
echo "&nbsp;&nbsp;<input type='submit' class='addContent-button' value='Preview'  onclick=\"popup();\"></td></tr></table></form>

					<script type='text/javascript'>
						function formCallback(result, form) {
							window.status = \"valiation callback for form '\" + form.id + \"': result = \" + result;
						}
						
						var valid = new Validation('test', {immediate : true, useTitles:true, onFormValidate : formCallback});
					</script>";
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = '".$_GET['id']."'")) > 0 && $p[1]) {
echo "<center><h2>Related Comments</h2></center><form action='admin.php?view=content&do=comments' method='post'><table id='mytable' cellpadding='3' cellspacing='1' border='0' width='100%' align='center' style='border-collapse: collapse'><input type='hidden' name='section' value='".$r[1]."'><input type='hidden' name='user_id' value='".$r[2]."'><tr style='border-bottom: 1px solid #262626'><td><b>Username</b></td><td><b>Comment</b></td><td><b>Website</b></td><td><b>Delete?</b></td></tr>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = '".$_GET['id']."' ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<input type='hidden' name='comments[]' value='".$r[id]."'><td>".get_user($r[user_id])."</td><td><textarea name='comment_".$r[id]."' cols='30' rows='10' class='input'>".stripslashes($r[comment])."</textarea></td>";
if (1 == 2) {
if ($r[user_id]) {
echo $r[email]."<input type='hidden' name='email_".$r[id]."' value='".$r[email]."'>";
} else {
echo "<input type='text' name='email_".$r[id]."' value='".$r[email]."' class='input'>";
}
}
echo "<td><input type='text' name='website_".$r[id]."' value='".$r[website]."' class='input'></td><td>".timef($r[date])."</td><td><input type='checkbox' name='delete_".$r[id]."' value='yes' class='input'></td></tr>";
$i = $i + 1;
}
echo "<tr><td><input type='submit' value='Update' class='addContent-button'></table></form><br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."pages"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";

if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$pageurl."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$pageurl."&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$pageurl."&page=$next\">Next>></a>";
}
echo "</center>";
}
}

}
}

if ($_GET['do'] == "comments" && $_POST['section']) {
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$_POST['section']."'"));
$p = explode("|", $ps[0]);
if ($_POST['user_id'] == $useridn or $p[1]) {

while (list(, $i) = each ($_POST['comments'])) {
if ($_POST["delete_$i"]) {
mysql_query("DELETE FROM ".$pre."comments WHERE id = '".$i."'");
$myql = 1;
} else {
mysql_query("UPDATE ".$pre."comments SET comment = '".$_POST["comment_$i"]."', email = '".$_POST["email_$i"]."', website = '".$_POST["website_$i"]."' WHERE id = '".$i."'");
$myql = 1;
}
}
}

if ($myql  == 1) {
echo re_direct("1500", "admin.php?view=content");
echo "The comments have been updated/deleted. <a href='admin.php?view=content'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The comments could not be updated/deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

if ($_GET['do'] == "edit2") {
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$_POST['section']."'"));
$p = explode("|", $ps[0]);
if ($_POST['user_id'] == $useridn or $p[1]) {

if ($_POST['postpone']) {
$status = $_POST['postpone'];
} else {
if ($_POST['publish_now'] or !$_POST['type']) {
$status = "";
} else {
if ($_POST['type'] == "Publish Now" or !$_POST['art_status'] && $_POST['type'] == "Update") {
$status = "";
} elseif ($_POST['type'] == "Update" && $_POST['art_status'] == "saved" or $_POST['type'] == "Publish Later") {
$status = "saved";
}
}
}

$query = mysql_query("UPDATE ".$pre."content SET name = '".addslashes($_POST["name"])."', status = '".$status."', last_edit = '".time()."', user_id = '".$_POST['author']."' WHERE id = '".$_GET['id']."'");
mysql_query("UPDATE ".$pre."data SET data = '".addslashes($_POST["name"])."' WHERE field_name = 'name' AND item_id = '".$_GET['id']."'");

if ($_POST['tags'] && $_POST['tags'] != $_POST['tags_old']) {
mysql_query("UPDATE ".$pre."data SET data = '".strip_tags($_POST["tags"])."' WHERE field_name = 'tags' AND item_id = '".$_GET['id']."'");
} elseif (!$_POST['tags_old'] && $_POST['tags']) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, 'tags', 'content-tags', '".strip_tags($_POST['tags'])."', '".$_GET['id']."')");
}

while (list(, $i) = each ($_POST['id'])) {
if ($i) {
if ((strtolower($setting["wysiwyg"]) == "no") && ($_POST["cftype_$i"])) {
$text = preg_replace("/\n/", "<br />\n", addslashes($_POST["cf_$i"]));
} else {
$text = addslashes($_POST["cf_$i"]);
}
if ($text) {
if ($_POST["cflimit_$i"]) {
$lim = "";
$lim = explode("/", $_POST["cflimit_$i"]);
}
if (strlen($text) <= $lim[0] && strlen($text) >= $lim[1] or !$_POST["cflimit_$i"] or !$lim[0] && !$lim[1]) {
if (!$_POST["cfdata_$i"]) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$i."', 'content-custom-data', '".$text."', '".$_GET['id']."')");
} else {
mysql_query("UPDATE ".$pre."data SET data = '".$text."' WHERE field_name = '".$i."' AND item_id = '".$_GET['id']."'");
}
} else {
$error = $error + 1;
}
}
}
}

if ($_POST['rel_media']) {
if (!$_POST["relmedia"]) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, 'Media', 'media-relate', '".$_POST['rel_media']."', '".$_GET['id']."')");
} else {
mysql_query("UPDATE ".$pre."data SET data = '".$_POST['rel_media']."' WHERE field_name = 'Media' AND item_id = '".$_GET['id']."'");
}
}

while (list(, $y) = each ($_POST['rels'])) {
if (!$_POST["rel_$y"]) {
while (list(, $z) = each ($_POST["relid_$y"])) {
mysql_query("DELETE FROM ".$pre."data WHERE item_id = '".$_GET['id']."' AND field_name = '".$y."' AND data = '".$z."' OR data = '".$_GET['id']."' AND field_name = '".$_POST['section']."' AND item_id = '".$z."'");
}
} else {
if (is_array($_POST["relid_$y"])) {
while (list(, $z) = each ($_POST["relid_$y"])) {
if (!in_array($z, $_POST["rel_$y"])) {
mysql_query("DELETE FROM ".$pre."data WHERE item_id = '".$_GET['id']."' AND field_name = '".$y."' AND data = '".$z."' OR data = '".$_GET['id']."' AND field_name = '".$_POST['section']."' AND item_id = '".$z."'");
}

}
}

while (list(, $x) = each ($_POST["rel_$y"])) {
if (!$_POST["reldata_$x"]) {
if ($x && $y) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$y."', 'content-relate', '".$x."', '".$_GET['id']."')");
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$_POST['section']."', 'content-relate', '".$_GET['id']."', '".$x."')");
}
} elseif ($x != $_POST["reldata_$x"] && $x) {
mysql_query("UPDATE ".$pre."data SET data = '".$x."' WHERE field_name = '".$y."' AND item_id = '".$_GET['id']."'");
mysql_query("UPDATE ".$pre."data SET data = '".$_GET['id']."' WHERE field_name = '".$_POST['section']."' AND item_id = '".$x."'");
}
}
}

}

if ($query == TRUE) {
//echo re_direct("1500", "admin.php?view=content");
echo "The content <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=content'>Return</a>";
if ($error > 0) {
echo "<br/><br />There were, however, ".$error." fields that did not have there data added. Reason - Min/Max Character amount too little or too much entered.";
}
if (1 == 1 or $setting["article_promote"] == "yes") {
echo "<br /><br /><br /><h2>Promote Article</h2><br />

<b>Submit to Social Sites</b><br />
<form name='formname' action='".$siteurl."inc/promote.php' target='windowName'
method='post' onsubmit='window.open(\"\", this.target,
\"dialog,modal,scrollbars=yes,resizable=yes,width=800,height=640,left=362,top=284\");'><table cellpadding='5' cellspacing='2' align='left'><tr><td>Title</td><td><input type='text' name='title' value=\"".stripslashes($_POST['name'])."\" size='20'></td></tr><tr><td>Description</td><td><textarea cols='30' rows='12' name='description'></textarea></td></tr><input type='hidden' name='url' value='".url("content", $_GET['id'], $_POST['name'], $_POST['section'])."'>

<tr><td>Submit</td><td><input type='submit' name='site' value='Digg'>&nbsp;&nbsp;<input type='submit' name='site' value='Reddit'>&nbsp;&nbsp;<input type='submit' name='site' value='Stumbleupon'>&nbsp;&nbsp;<input type='submit' name='site' value='Facebook'>&nbsp;&nbsp;<input type='submit' name='site' value='N4G'></td></tr></table></form><br /><br /><br clear='all' /><b>Post to Twitter</b><br /><br />

<a href='http://twitter.com/share' class='twitter-share-button' data-url='".url("content", $_GET['id'], $_POST['name'], $_POST['section'])."' data-text='".$_POST['name']."' data-count='none'>Tweet</a><script type='text/javascript' src='http://platform.twitter.com/widgets.js'></script>

<br /><br /><b>Post via Share</b><br /><br /><a href='admin.php?view=share&do=promote' target='new'>Submit Now!</a>";

}
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The content could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($_GET['do'] == "delete") {
$r = mysql_fetch_row(mysql_query("SELECT name,section,user_id,status FROM ".$pre."content WHERE id = '".$_GET['id']."'"));
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$r[1]."'"));
$p = explode("|", $ps[0]);
if ($r[2] == $useridn or $p[2]) {

if (mysql_query("DELETE FROM ".$pre."content WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
mysql_query("ALTER TABLE ".$pre."content AUTO_INCREMENT =".$_GET['id']);
$query2 = mysql_query("DELETE FROM ".$pre."data WHERE item_id = '".addslashes($_GET["id"])."'");

echo re_direct("1500", "admin.php?view=content");
echo "The content has been deleted. <a href='admin.php?view=content'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The content could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($_GET['do'] == "verify") {
$r = mysql_fetch_row(mysql_query("SELECT name,section,user_id,status FROM ".$pre."content WHERE id = '".$_GET['id']."'"));
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$r[1]."'"));
$p = explode("|", $ps[0]);
if ($r[2] == $useridn or $p[0] && $p[1] && $p[2]) {

if (mysql_query("UPDATE ".$pre."content SET status = '' WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
echo re_direct("1500", "admin.php?view=content");
echo "The content has been published. <a href='admin.php?view=content'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The content could not be published. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
?>