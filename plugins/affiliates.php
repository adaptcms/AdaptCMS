<?php
$protect = "yes";
$plugin_name = "Affiliates";
$plugin_url = "admin.php?view=plugins&do=load&plugin=affiliates";
$plugin_version = "1.0";

$apage = basename($_SERVER['PHP_SELF']);
$siteurl = "http://".$_SERVER['HTTP_HOST'].str_replace($apage, "", $_SERVER['PHP_SELF']);
$url = $_GET['url'];

if ($_GET['check'] == "status") {
echo 1;
}

if ($module == "install_".$url) {
$tot = 0;
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."plugins WHERE name = '".$plugin_name."'")) > 1) {
echo "<b>".$plugin_name."</b> Plugin already installed. <a href='admin.php?view=plugins&do=install'>Return</a>";
} else {
$data .= "Beginning to install the <b>".$plugin_name."</b> Plugin...<br /><br />";
$query1 = mysql_query("INSERT INTO ".$pre."data VALUES (null, 'AdaptCMS', 'plugin_affiliates', 'http://www.adaptcms.com|yes|http://www.adaptcms.com/button.png|1|".time()."|0', 0)");
$query2 = mysql_query("INSERT INTO ".$pre."data VALUES (null, 'Insane Visions', 'plugin_affiliates', 'http://www.insanevisions.com|yes||1|".time()."|0', 0)");
$query3 = mysql_query("INSERT INTO ".$pre."plugins VALUES (null, '".$plugin_name."', '".$_GET['url']."', '".$plugin_version."', 'On')");
$query4 = mysql_query("INSERT INTO ".$pre."settings VALUES (null, 'affiliate_email', 'If there is an email entered, you will be notified when someone applies to be an affiliate.', 'webmaster@".$_SERVER['HTTP_HOST']."', 'setting', 'Modules')");

$sqls = mysql_query("SELECT * FROM ".$pre."skins WHERE skin = '' ORDER BY `date` DESC");
while($row = mysql_fetch_array($sqls)) {
$query5 = mysql_query("INSERT INTO ".$pre."skins VALUES (null, 'plugin_".$plugin_name."', '".$row[name]."', '{affiliate}<br>', '".time()."')");

$fh = fopen($sitepath."templates/".$row[name]."/plugin_affiliates.tpl", 'w') or die("can't open file");
fwrite($fh, '{$affiliate}<br>');
fclose($fh);
}

if ($query1 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."data` MySQL data row #1 Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."data` MySQL data row #1 Inserted? <font color='red'>False</font><br />";
}
if ($query2 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."data` MySQL data row #2 Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."data` MySQL data row #2 Inserted? <font color='red'>False</font><br />";
}
if ($query3 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."skins` MySQL data row Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."skins` MySQL data row Inserted? <font color='red'>False</font><br />";
}
if ($query4 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."plugins` MySQL data row Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."plugins` MySQL data row Inserted? <font color='red'>False</font><br />";
}
if ($query5 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."settings` MySQL data row Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."settings` MySQL data row Inserted? <font color='red'>False</font><br />";
}
if ($tot > 4) {
$data .= "<br /><b>".$plugin_name."</b> Plugin installed <font color='green'>Sucessfully!</font>. <a href='".$plugin_url."'>".$plugin_name." Plugin</a>";
echo $data;
} else {
$data .= "<br /><b>".$plugin_name."</b> Plugin installed <font color='red'>Un-Sucessfully!</font>";
echo $data;
}
}
}

if ($module == "uninstall_".$url) {
$tot = 0;
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."plugins WHERE name = '".$plugin_name."'")) == 0) {
echo "<b>".$plugin_name."</b> Plugin already un-installed, or not yet installed. <a href='admin.php?view=plugins&do=install'>Return</a>";
} else {
$data .= "Beginning to un-install the <b>".$plugin_name."</b> Plugin...<br /><br />";
$query1 = mysql_query("DELETE FROM ".$pre."data WHERE field_type = 'plugin_affiliates'");
$query2 = mysql_query("DELETE FROM ".$pre."plugins WHERE name = '".$plugin_name."'");
$query3 = mysql_query("DELETE FROM ".$pre."settings WHERE name = 'affiliate_email'");
$query4 = mysql_query("DELETE FROM ".$pre."skins WHERE name = 'plugin_".$plugin_name."'");
if ($query1 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."data` MySQL data row(s) Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."data` MySQL data row(s) Deleted? <font color='red'>False</font><br />";
}
if ($query2 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."plugins` MySQL data row Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."plugins` MySQL data row Deleted? <font color='red'>False</font><br />";
}
if ($query3 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."settings` MySQL data row Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."settings` MySQL data row Deleted? <font color='red'>False</font><br />";
}
if ($query4 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."skins` MySQL data row Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."skins` MySQL data row Deleted? <font color='red'>False</font><br />";
}
if ($tot == 4) {
$data .= "<br /><b>".$plugin_name."</b> Plugin un-installed <font color='green'>Sucessfully!</font>. <a href='admin.php?view=plugins'>Plugins</a>";
echo $data;
} else {
$data .= "<br /><b>".$plugin_name."</b> Plugin un-installed <font color='red'>Un-Sucessfully!</font>";
echo $data;
}
}
}

if (basename($_SERVER['PHP_SELF']) == "admin.php") {
if (!$module) {
echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><colgroup>
	<col id='col1_1'></col>
		<col id='col1_2'></col>
		<col id='col1_3'></col>
		<col id='col1_4'></col>
		<col id='col1_5'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>Site Name</b></td><td><b>Clicks</b></td><td><b>Status</b></td><td><b>Date</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'plugin_affiliates' ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($rs = mysql_fetch_array($sql)) {
$r = explode("|", $rs[data]);
echo "<tr><td><a href='".$r[0]."' target='popup'>".stripslashes($rs[field_name])."</a></td><td>".$r[5]."</td><td>";
if ($r[3] == 1) {
echo "<font color='blue'>Verified</font>";
} else {
echo "<a href='".$plugin_url."&module=verify&id=".urlencode($rs[id])."'><font color='red'>Not Verified</font></a>";
}
echo "</td><td>".timef($r[4])."</td><td>";
if ($p[1]) {
echo "<a href='".$plugin_url."&module=edit&id=".urlencode($rs[id])."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='".$plugin_url."&module=delete&id=".urlencode($rs[id])."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>";
}

if ($r[3] == 0) {
echo "&nbsp;&nbsp;<a href='".$plugin_url."&module=verify&id=".urlencode($rs[id])."' onclick='return confirmVerify();'><img src='images/attn.png' title='Publish'></a></td></tr>";
}
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('S','N','S','N','S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."data WHERE field_type = 'plugin_affiliates'"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {
echo "<center>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$plugin_url."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$plugin_url."&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$plugin_url."&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($module == "code") {
if ((($p[0]) && ($p[1]) && ($p[2]))) {
echo "<table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>List <span class='drop'>Affiliates</span></p></td><td><i>{php} echo affiliates(\"5\"); {/php}</i></td></tr></table>";
}
}

if ($module == "add") {
if ($p[0]) {
echo "<form action='".$plugin_url."&module=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Site <span class='drop'>Name</span></p></td><td><input type='text' name='sitename' class='title' size='12'></td></tr><tr><td><p>Site <span class='drop'>URL</span></p></td><td><input type='text' name='siteurl' class='addtitle' size='12'></td></tr><tr><td><p>Track <span class='drop'>Clicks</span></p></td><td><select name='target' class='select'>";
if ($r[target] == "yes") {
echo "<option value='yes' selected>Yes</option><option value='no'>No</option>";
} else {
echo "<option value='no' selected>No</option><option value='yes'>Yes</option>";
}
echo "</select></td></tr><tr><td><p>Image <span class='drop'>URL</span></p></td><td><input type='text' name='imageurl' class='addtitle' size='12'>&nbsp;&nbsp;(optional)</td></tr><tr><td><input type='submit' value='Add Affiliate' class='addContent-button'></td></tr></table></form>";
}
}

if ($module == "add2") {
if ($p[0]) {
$query = mysql_query("INSERT INTO ".$pre."data VALUES (null, '".addslashes($_POST["sitename"])."', 'plugin_affiliates', '".addslashes($_POST["siteurl"])."|".addslashes($_POST["target"])."|".addslashes($_POST["imageurl"])."|1|".time()."|0', 0)");

if ($query == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The affiliate <b>".stripslashes($_POST['sitename'])."</b> has been added. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The affiliate could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($module == "edit") {
if ($p[1]) {
$rs = mysql_fetch_row(mysql_query("SELECT field_name,data FROM ".$pre."data WHERE id = '".$_GET['id']."'"));
$r = explode("|", $rs[1]);
echo "<form action='".$plugin_url."&module=edit2' method='post'><input type='hidden' name='id' value='".$_GET['id']."'><input type='hidden' name='clicks' value='".$r[5]."'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Site <span class='drop'>Name</span></p></td><td><input type='text' name='sitename' value='".$rs[0]."' class='title'></td></tr><tr><td><p>Site <span class='drop'>URL</span></p></td><td><input type='text' name='siteurl' value='".$r[0]."' class='addtitle'></td></tr><tr><td><p>Track <span class='drop'>Clicks</span></p></td><td><select name='target' class='select'>";
if ($r[1] == "yes") {
echo "<option value='yes' selected>Yes</option><option value='no'>No</option>";
} else {
echo "<option value='no' selected>No</option><option value='yes'>Yes</option>";
}
echo "</select></td></tr><tr><td><p>Image <span class='drop'>URL</span></p></td><td><input type='text' name='imageurl' value='".$r[2]."' class='addtitle'>&nbsp;&nbsp;(optional)</td></tr><tr><td><input type='submit' value='Update' class='addContent-button'></td></tr></table></form>";
}
}

if ($module == "edit2") {
if ($p[1]) {
$query = @mysql_query("UPDATE ".$pre."data SET field_name = '".addslashes($_POST["sitename"])."', data = '".addslashes($_POST["siteurl"])."|".addslashes($_POST["target"])."|".addslashes($_POST["imageurl"])."|1|".time()."|".$_POST['clicks']."' WHERE id = '".$_POST['id']."'");

if ($query == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The affiliate <b>".stripslashes($_POST['sitename'])."</b> has been updated. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The affiliate could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($module == "delete") {
if ($p[2]) {
$query = @mysql_query("DELETE FROM ".$pre."data WHERE id = '".addslashes($_GET["id"])."'");
mysql_query("ALTER TABLE ".$pre."data  AUTO_INCREMENT =".$_GET['id']);

if ($query == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The affiliate has been deleted. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The affiliate could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($module == "verify") {
$rs = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE id = '".$_GET['id']."'"));
$r = explode("|", $rs[0]);
$query = @mysql_query("UPDATE ".$pre."data SET data = '".$r[0]."|".$r[1]."|".$r[2]."|1|".$r[4]."|".$r[5]."' WHERE id = '".addslashes($_GET["id"])."'");

if ($query == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The affiliate has been verified. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The affiliate could not be verified. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if (basename($_SERVER['PHP_SELF']) == "index.php") {
if ($module == "apply") {
require_once('inc/recaptchalib.php');

$smarty->display($skin.'/header.tpl');
echo "<title>Affiliation Application - ".$setting["sitename"]."</title><form action='".$siteurl."index.php?view=plugins&plugin=affiliates&module=apply2' method='post'><table cellpadding='5' cellspacing='3' border='0' width='100%' align='center'><tr><td><p>Site Name</p></td><td><input type='text' name='sitename' class='title'></td></tr><tr><td><p>Site URL</p></td><td><input type='text' name='siteurl' class='title'></td></tr><tr><td>Image URL</td><td><input type='text' name='imageurl' class='title'>&nbsp;&nbsp;(optional)</td></tr><tr><td></td><td>".recaptcha_get_html($publickey)."</td></tr><tr><td><input type='submit' value='Submit Website' class='title'></td><td></td></tr></table></form>";
}

if ($module == "apply2") {
require_once('inc/recaptchalib.php');
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
$smarty->display($skin.'/header.tpl');
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
if ($_POST['sitename'] == "" or $_POST['siteurl'] == "") {
$smarty->display($skin.'/header.tpl');
echo "Sorry but the <b>Site Name</b> and/or <b>Site URL</b> field have not been entered with data, please go back and fill in both of those fields.";
} else {
if ($setting["affiliate_email"]) {
mail($setting["affiliate_email"], $setting["sitename"]." - Affiliate Application", "Hello,\r\nThe website '".check($_POST['sitename'])."' has applied to become an affiliate. Here are details below:\r\n\r\nSite URL: ".check($_POST['siteurl'])."\r\nManage Affiliates: ".$siteurl."admin.php?view=plugins&do=load&plugin=affiliates\r\n\r\nThank you.");
}

$query = @mysql_query("INSERT INTO ".$pre."data VALUES (null, '".check(addslashes($_POST["sitename"]))."', 'plugin_affiliates', '".check($_POST["siteurl"])."|yes|".check($_POST["imageurl"])."||".time()."|0', 0)");

$smarty->display($skin.'/header.tpl');
if ($query == TRUE) {
echo re_direct("1500", $siteurl);
echo "The website <b>".stripslashes($_POST['sitename'])."</b> has been submitted. <a href='".$siteurl."'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The website could not be submitted. This error has been sent to the <b>AdaptCMS</b> support team and the webmaster will be contacted shortly.";
}
}
}
}

if ($module == "affiliates" or !$module) {
$smarty->display($skin.'/header.tpl');
echo affiliates();
}

if ($module == "track" && is_numeric($_GET['id'])) {
$rs = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE id = '".$_GET['id']."'"));
$r = explode("|", $rs[0]);
$r[5] = $r[5] + 1;
mysql_query("UPDATE ".$pre."data SET data = '".$r[0]."|".$r[1]."|".$r[2]."|".$r[3]."|".$r[4]."|".$r[5]."' WHERE id = '".$_GET['id']."'");

header("location: ".$r[0]);
}
}
?>