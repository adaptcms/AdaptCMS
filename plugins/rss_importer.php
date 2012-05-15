<?php
$protect = "yes";
$plugin_name = "RSS Importer";
$plugin_url = "admin.php?view=load_plugin&plugin=rss_importer";
$plugin_version = "1.2";
$url = strtolower(str_replace(" ", "_", $plugin_name)).".php";

$apage = basename($_SERVER['PHP_SELF']);
$siteurl = "http://".$_SERVER['HTTP_HOST'].str_replace($apage, "", $_SERVER['PHP_SELF']);
$url = $_GET['url'];

if ($_GET['check'] == "status") {
echo 1;
}

if ($module == "install_".$url) {
$tot = 0;
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."plugins WHERE name = 'RSS Importer'")) > 1) {
echo "<b>".$plugin_name."</b> Plugin already installed. <a href='admin.php?view=install_plugins'>Return</a>";
} else {
echo "Beginning to install the <b>".$plugin_name."</b> Plugin...<br /><br />";
$query1 = mysql_query("INSERT INTO ".$pre."plugins VALUES (null, '".$plugin_name."', '".$_GET['url']."', '".$plugin_version."', 'On')");
if ($query1 == TRUE) {
$tot = $tot + 1;
echo "`".$pre."plugins` MySQL data row Inserted? <font color='green'>True</font><br />";
} else {
echo "`".$pre."plugins` MySQL data row Inserted? <font color='red'>False</font><br />";
}
if ($tot == 1) {
echo "<br /><b>".$plugin_name."</b> Plugin installed <font color='green'>Sucessfully!</font>. <a href='".$plugin_url."'>".$plugin_name." Plugin</a>";
} else {
echo "<br /><b>".$plugin_name."</b> Plugin installed <font color='red'>Un-Sucessfully!</font>. Please check mysql settings and if need be, please submit a <a href='admin.php?view=support'>support ticket</a>.";
}
}
}

if ($module == "uninstall_".$url) {
$tot = 0;
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."plugins WHERE name = 'RSS Importer'")) > 1) {
echo "<b>".$plugin_name."</b> Plugin already un-installed, or not yet installed. <a href='admin.php?view=install_plugins'>Return</a>";
} else {
echo "Beginning to un-install the <b>".$plugin_name."</b> Plugin...<br /><br />";
$query1 = mysql_query("DELETE FROM ".$pre."plugins WHERE name = '".$plugin_name."'");
if ($query1 == TRUE) {
$tot = $tot + 1;
echo "`".$pre."plugins` MySQL data row Deleted? <font color='green'>True</font><br />";
} else {
echo "`".$pre."plugins` MySQL data row Deleted? <font color='red'>False</font><br />";
}
if ($tot == 1) {
echo "<br /><b>".$plugin_name."</b> Plugin un-installed <font color='green'>Sucessfully!</font>. <a href='admin.php?view=plugins'>Plugins</a>";
echo $data;
} else {
echo "<br /><b>".$plugin_name."</b> Plugin un-installed <font color='red'>Un-Sucessfully!</font>. Please check mysql settings and if need be, please submit a <a href='admin.php?view=support'>support ticket</a>.";
echo $data;
}
}
}

if (basename($_SERVER['PHP_SELF']) == "admin.php") {
if ($module == "") {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin";
if ($p[0]) {
echo " - <a href='admin.php?view=load_plugin&plugin=rss_importer&module=add'>Add Feed</a>";
}
echo "<br /><br /><br />";

echo "<table cellpadding='5' cellspacing='0' border='0' width='490' align='left'><tr><td><b>Feed</b></td><td><b>Section</b></td><td><b>Last Updated</b></td><td><b>Actions</b></td></tr>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["limit"]) - $setting["limit"]);

$sql = mysql_query("SELECT * FROM ".$pre."fielddata WHERE aid = 'plugin_rss' ORDER BY `id` DESC LIMIT $from, ".$setting["limit"]);
while($r = mysql_fetch_array($sql)) {
$url = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".str_replace(" ","_",$r[id])."' AND fname = 'url'"));
if ($url[0]) {
$section = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".str_replace(" ","_",$r[id])."' AND fname = 'section'"));
$date = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".str_replace(" ","_",$r[id])."' AND fname = 'last_update'"));
if ($date[0]) {
$datem = timef($date[0]);
} else {
$datem = "Not fed yet!";
}
echo "<tr><td><a href='".$url[0]."' target='popup'>".stripslashes($r[data])."</a></td><td>".$section[0]."</td><td>".$datem."</td>";
if ($p[1]) {
echo "<td><a href='".$plugin_url."&module=edit&id=".urlencode($r[id])."'>Edit</a> | ";
}
if ($p[2]) {
echo "<a href='".$plugin_url."&module=delete&id=".urlencode($r[id])."'>Delete</a>";
}
echo " | <a href='".$plugin_url."&module=update&rss_manual=1'>Update</a>";
}
}
echo "</table>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."fielddata WHERE aid = 'plugin_rss'"),0);
$total_pages = ceil($total_results / $setting["limit"]);

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

if ($module == "add") {
if ($p[0]) {
echo '<script language="javascript">function jump(targ,selObj,restore){
eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
if (restore) selObj.selectedIndex=0;
}</script>';

echo "<form name='form1'><b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='admin.php?view=load_plugin&plugin=rss_importer'>Manage Feeds</a><br /><br /><br />";
echo "<center>Feed items for which section?: <select name='section' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' onChange=\"jump('parent',this,0)\"><option value=''>-- Select Section --</option>";
$sql = mysql_query("SELECT * FROM ".$pre."sections ORDER BY `type` ASC, `name` ASC");
while($r = mysql_fetch_array($sql)) {
$pb = "";
$pb = mysql_fetch_row(mysql_query("SELECT padd FROM ".$pre."permissions WHERE level = '".$level."' AND name = '".$r[name]."'"));
if ($pb[0]) {
echo "<option value='".$plugin_url."&module=add1&section=".$r[name]."'>".ucwords($r[name])." (";
if ($r[type] == "sub") {
echo "sub-";
}
echo "section)</option>";
$cat = $r[name];
}
}
echo "</select></form>";
}
}

if ($module == "add1") {
if ($p[0]) {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='admin.php?view=load_plugin&plugin=rss_importer'>Manage Feeds</a><br /><br /><br />";
echo "<form action='".$plugin_url."&module=add2' method='post'><table cellpadding='5' cellspacing='0' border='0' width='490' align='left'><tr><td><b>Feed Name</b></td><td><input type='text' name='rss_name'></td></tr><tr><td><b>Feed URL</b></td><td><input type='text' name='rss_url'></td></tr><input type='hidden' name='rss_section' value='".$_GET['section']."'><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td><b>Update Frequency</b></td><td><input type='text' name='rss_freq' value='30'> (in minutes - 1440 = 1 day, 10080 = 1 week)</td></tr><tr><td><b>Feed Limit</b></td><td><input type='text' name='rss_limit' value='10'> (how many items to get from feed)</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td><b>Field: URL/Link</b> (optional)</td><td><select name='rss_field_url'><option value='' selected></option>";
unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."fields WHERE type = 'textfield' AND cat = '".$_GET['section']."' ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".$r[name]."'>".ucwords($r[name])."</option>";
}
echo "</select></td></tr><tr><td><b>Field: Description</b></td><td><select name='rss_field_des'>";
unset($sql, $r);
$sql = mysql_query("SELECT * FROM ".$pre."fields WHERE type = 'textarea' AND cat = '".$_GET['section']."' ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
echo "<option value='".$r[name]."'>".ucwords($r[name])."</option>";
}
echo "</select></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td><b>Exclusions</b></td><td><input type='text' name='rss_exclude'> (separate by comma, articles containing any of the words will not be added)</td></tr><tr><td><b>Includes</b></td><td><input type='text' name='rss_include'> (separate by comma, rss feed item won't be added unless it contains one of the words)</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr>";

$sqlst = mysql_query("SELECT * FROM ".$pre."relations WHERE sub = '".$_GET['section']."' OR section = '".$_GET['section']."'");
while($y = mysql_fetch_array($sqlst)) {
$content = "";$xnum = "0";
echo "<tr><td><b>Relate ";
if ($y[sub] == $_GET['section']) {
echo ucwords($y[section]);
$name = $y[section];
} else {
echo ucwords($y[sub]);
$name = $y[sub];
}

$sqlsts = mysql_query("SELECT * FROM ".$pre."articles WHERE section = '".$name."' ORDER BY `name` ASC");
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
echo "</b></td><td><input type='hidden' name='rels[]' value='".$name."'><select name='rel_".$name."[]' size='".$sz."' multiple><option value='' selected></option>".$content."</select></td></tr>";
}

$sqlsts2 = mysql_query("SELECT * FROM ".$pre."gallery ORDER BY `id` DESC");
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

echo "<tr><td><b>Relate Gallery</b></td><td><select name='rel_gallery' size='".$sz2."'><option value='' selected></option>".$content2."</select></td></tr>";

echo "<tr><td><input type='submit' value='Add Feed' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444'></td></tr></table></form>";
}
}

if ($module == "add2") {
if ($p[0]) {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='admin.php?view=load_plugin&plugin=rss_importer'>Manage Feeds</a><br /><br /><br />";

$query1 = mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'name', '".addslashes($_POST['rss_name'])."', 'plugin_rss')");
$id = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."fielddata WHERE aid = 'plugin_rss' AND data = '".addslashes($_POST['rss_name'])."'"));
$query2 = mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'url', '".addslashes($_POST['rss_url'])."', 'plugin_rss_".$id[0]."')");
$query3 = mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'section', '".addslashes($_POST['rss_section'])."', 'plugin_rss_".$id[0]."')");

$query4 = mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'freq', '".addslashes($_POST['rss_freq'])."', 'plugin_rss_".$id[0]."')");
$query5 = mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'limit', '".addslashes($_POST['rss_limit'])."', 'plugin_rss_".$id[0]."')");

mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'field_url', '".addslashes($_POST['rss_field_url'])."', 'plugin_rss_".$id[0]."')");
$query7 = mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'field_des', '".addslashes($_POST['rss_field_des'])."', 'plugin_rss_".$id[0]."')");
$query8 = mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'last_update', '0', 'plugin_rss_".$id[0]."')");

mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'exclude', '".addslashes($_POST['rss_exclude'])."', 'plugin_rss_".$id[0]."')");
mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'include', '".addslashes($_POST['rss_include'])."', 'plugin_rss_".$id[0]."')");

if ($_POST['rel_gallery']) {
@mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'Gallery', '".$_POST['rel_gallery']."', 'plugin_rss_".$id[0]."')");
}

while (@list(, $y) = @each ($_POST['rels'])) {
while (@list(, $x) = @each ($_POST["rel_$y"])) {
if ($x && $y) {
@mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, '".$y."', 'plugin_rss_".$x."', 'plugin_rss_".$id[0]."')");
@mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, '".$_GET['section']."', 'plugin_rss_".$id[0]."', 'plugin_rss_".$x."')");
}
}
}

if ($query1 == TRUE && $query2 == TRUE && $query3 == TRUE && $query4 == TRUE && $query5 == TRUE && $query7 == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The feed <b>".stripslashes($_POST['rss_name'])."</b> has been added. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($cpage, htmlspecialchars(mysql_error()), $domain);
echo "The feed could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($module == "edit") {
if ($p[1]) {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='".$plugin_url."'>Manage Feeds</a>";
if ($p[0]) {
echo "- <a href='".$plugin_url."&module=add'>Add Feed</a>";
}
echo "<br /><br /><br />";
$name = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE id = '".$_GET['id']."'"));
$url = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$_GET['id']."' AND fname = 'url'"));
$section = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$_GET['id']."' AND fname = 'section'"));
$freq = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$_GET['id']."' AND fname = 'freq'"));
$limit = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$_GET['id']."' AND fname = 'limit'"));
$field_url = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$_GET['id']."' AND fname = 'field_url'"));
$field_des = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$_GET['id']."' AND fname = 'field_des'"));
$exclude = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$_GET['id']."' AND fname = 'exclude'"));
$include = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$_GET['id']."' AND fname = 'include'"));

echo "<form action='".$plugin_url."&module=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='0' border='0' width='490' align='left'><tr><td><b>Feed Name</b></td><td><input type='text' name='rss_name' value='".$name[0]."'></td></tr><tr><td><b>Feed URL</b></td><td><input type='text' name='rss_url' value='".$url[0]."'></td></tr><input type='hidden' name='rss_section' value='".$section[0]."'><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td><b>Update Frequency</b></td><td><input type='text' name='rss_freq' value='".$freq[0]."'> (in minutes)</td></tr><tr><td><b>Feed Limit</b></td><td><input type='text' name='rss_limit' value='".$limit[0]."'> (how many items to get from feed)</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td><b>Field: URL/Link</b> (optional)</td><td><select name='rss_field_url'><option value='' selected></option>";
$sql = mysql_query("SELECT * FROM ".$pre."fields WHERE type = 'textfield' AND cat = '".$section[0]."' ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
if ($r[name] == $field_url[0]) {
echo "<option value='".$r[name]."' selected>-- ".ucwords($r[name])." --</option>";
} else {
echo "<option value='".$r[name]."'>".ucwords($r[name])."</option>";
}
}
echo "</select></td></tr><tr><td><b>Field: Description</b></td><td><select name='rss_field_des'>";
$sql = mysql_query("SELECT * FROM ".$pre."fields WHERE type = 'textarea' AND cat = '".$section[0]."' ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
if ($r[name] == $field_des[0]) {
echo "<option value='".$r[name]."' selected>-- ".ucwords($r[name])." --</option>";
} else {
echo "<option value='".$r[name]."'>".ucwords($r[name])."</option>";
}
}
echo "</select></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td><b>Exclusions</b></td><td><input type='text' name='rss_exclude' value='".$exclude[0]."'> (separate by comma, articles containing any of the words will not be added)</td></tr><tr><td><b>Includes</b></td><td><input type='text' name='rss_include' value='".$include[0]."'> (separate by comma, rss feed item won't be added unless it contains one of the words)</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr>";

$sqlst = mysql_query("SELECT * FROM ".$pre."relations WHERE sub = '".$section[0]."' OR section = '".$section[0]."'");
while($y = mysql_fetch_array($sqlst)) {
$content = "";$xnum = "0";
echo "<tr><td><b>Relate ";
if ($y[sub] == $section[0]) {
echo ucwords($y[section]);
$name = $y[section];
} else {
echo ucwords($y[sub]);
$name = $y[sub];
}
echo "</b>";

$sqlsts = mysql_query("SELECT * FROM ".$pre."articles WHERE section = '".$name."' ORDER BY `name` ASC");
while($x = mysql_fetch_array($sqlsts)) {
$xnum = $xnum + 1;
$datas = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."fielddata WHERE fname = '".$name."' AND aid = 'plugin_rss_".$_GET['id']."' AND data = 'plugin_rss_".$x[id]."'"));
if ($datas[0]) {
echo "<input type='hidden' name='reldata_".$x[id]."' value='".$x[id]."'>";
$content.= "<option value='".$x[id]."' selected>-- ".stripslashes($x[name])." --</option>";
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
echo "</td><td><input type='hidden' name='rels[]' value='".$name."'><select name='rel_".$name."[]' size='".$sz."' multiple><option value=''></option>".$content."</select>";
}

$sqlsts2 = mysql_query("SELECT * FROM ".$pre."gallery ORDER BY `name` ASC");
while($x2 = mysql_fetch_array($sqlsts2)) {
$xnum2 = $xnum2 + 1;
$datas2 = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."fielddata WHERE fname = 'Gallery' AND aid = 'plugin_rss_".$_GET['id']."' AND data = '".$x2[id]."'"));
if ($datas2[0]) {
echo "<input type='hidden' name='relgal' value='".$x2[id]."'>";
$content2.= "<option value='".$x2[id]."' selected>-- ".stripslashes($x2[name])." --</option>";
} else {
$content2.= "<option value='".$x2[id]."'>".stripslashes($x2[name])."</option>";
}
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

echo "</td></tr><tr><td><b>Relate Gallery</b></td><td><select name='rel_gallery' size='".$sz2."'><option value=''></option>".$content2."</select></td></tr>";

echo "<tr><td><input type='submit' value='Update Feed' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444'></td></tr></table></form>";
}
}


if ($module == "edit2") {
if ($p[1]) {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='".$plugin_url."'>Manage Feeds</a>";
if ($p[0]) {
echo "- <a href='".$plugin_url."&module=add'>Add Feed</a>";
}
echo "<br /><br /><br />";

$query1 = mysql_query("UPDATE ".$pre."fielddata SET data = '".addslashes($_POST['rss_name'])."' WHERE id = '".$_GET['id']."'");
$query2 = mysql_query("UPDATE ".$pre."fielddata SET data = '".addslashes($_POST['rss_url'])."' WHERE fname = 'url' AND aid = 'plugin_rss_".$_GET['id']."'");
$query3 = mysql_query("UPDATE ".$pre."fielddata SET data = '".addslashes($_POST['rss_section'])."' WHERE fname = 'section' AND aid = 'plugin_rss_".$_GET['id']."'");

$query4 = mysql_query("UPDATE ".$pre."fielddata SET data = '".addslashes($_POST['rss_freq'])."' WHERE fname = 'freq' AND aid = 'plugin_rss_".$_GET['id']."'");
$query5 = mysql_query("UPDATE ".$pre."fielddata SET data = '".addslashes($_POST['rss_limit'])."' WHERE fname = 'limit' AND aid = 'plugin_rss_".$_GET['id']."'");

mysql_query("UPDATE ".$pre."fielddata SET data = '".addslashes($_POST['rss_field_url'])."' WHERE fname = 'field_url' AND aid = 'plugin_rss_".$_GET['id']."'");
$query7 = mysql_query("UPDATE ".$pre."fielddata SET data = '".addslashes($_POST['rss_field_des'])."' WHERE fname = 'field_des' AND aid = 'plugin_rss_".$_GET['id']."'");

mysql_query("UPDATE ".$pre."fielddata SET data = '".addslashes($_POST['rss_exclude'])."' WHERE fname = 'exclude' AND aid = 'plugin_rss_".$_GET['id']."'");
mysql_query("UPDATE ".$pre."fielddata SET data = '".addslashes($_POST['rss_include'])."' WHERE fname = 'include' AND aid = 'plugin_rss_".$_GET['id']."'");

if ($_POST['rel_gallery']) {
if ($_POST["relgal"] == "") {
@mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'Gallery', '".$_POST['rel_gallery']."', 'plugin_rss_".$_GET['id']."')");
} else {
@mysql_query("UPDATE ".$pre."fielddata SET data = '".$_POST['rel_gallery']."' WHERE fname = 'Gallery' AND aid = 'plugin_rss_".$_GET['id']."'");
}
}

while (@list(, $y) = @each ($_POST['rels'])) {
while (@list(, $x) = @each ($_POST["rel_$y"])) {
if ($x && $y) {
if ($_POST["reldata_$x"] == "") {
@mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, '".$y."', 'plugin_rss_".$x."', 'plugin_rss_".$_GET['id']."')");
@mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, '".addslashes($_POST['rss_section'])."', 'plugin_rss_".$_GET['id']."', 'plugin_rss_".$x."')");
} else {
if ($x != $_POST["reldata_$x"]) {
@mysql_query("UPDATE ".$pre."fielddata SET data = 'plugin_rss_".$x."' WHERE fname = '".$y."' AND aid = 'plugin_rss_".$_GET['id']."'");
@mysql_query("UPDATE ".$pre."fielddata SET data = 'plugin_rss_".$_GET['id']."' WHERE fname = '".addslashes($_POST['rss_section'])."' AND aid = 'plugin_rss_".$x."'");
}
}
}
}
}

if ($query1 == TRUE && $query2 == TRUE && $query3 == TRUE && $query4 == TRUE && $query5 == TRUE && $query7 == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The feed <b>".stripslashes($_POST['rss_name'])."</b> has been updated. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($cpage, htmlspecialchars(mysql_error()), $domain);
echo "The feed could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($module == "update") {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='".$plugin_url."'>Manage Feeds</a>";
if ($p[0]) {
echo "- <a href='".$plugin_url."&module=add'>Add Feed</a>";
}
echo "<br /><br /><br />";
echo "Feeds have been updated. <a href='".$plugin_url."'>Return</a>";
}

if ($module == "delete") {
if ($p[2]) {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to delete this feed?");
if (agree)
document.write("");
else
history.go(-1);
</SCRIPT>';
$name = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE id = '".$_GET['id']."'"));
$query1 = mysql_query("DELETE FROM ".$pre."fielddata WHERE id = '".$_GET["id"]."' OR aid = 'plugin_rss_".$_GET['id']."'");

if ($query1 == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The feed has been deleted. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($cpage, htmlspecialchars(mysql_error()), $domain);
echo "The feed could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}


}

if (basename($_SERVER['PHP_SELF']) == "index.php") {
if ($module == "rss_importer") {
echo 1;
}
}
?>