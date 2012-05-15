<?php
$protect = "yes";
$plugin_name = "TinyURL";
$plugin_url = "admin.php?view=plugins&do=load&plugin=tinyurl";
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
$query1 = mysql_query("INSERT INTO ".$pre."plugins VALUES (null, '".$plugin_name."', '".$_GET['url']."', '".$plugin_version."', 'On')");
if ($query1 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."plugins` MySQL data row Inserted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."plugins` MySQL data row Inserted? <font color='red'>False</font><br />";
}
if ($tot == 1) {
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
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."plugins WHERE name = '".$plugin_name."'")) > 1) {
echo "<b>".$plugin_name."</b> Plugin already un-installed, or not yet installed. <a href='admin.php?view=plugins&do=install'>Return</a>";
} else {
$data .= "Beginning to un-install the <b>".$plugin_name."</b> Plugin...<br /><br />";
$query1 = mysql_query("DELETE FROM ".$pre."plugins WHERE name = '".$plugin_name."'");
if ($query1 == TRUE) {
$tot = $tot + 1;
$data .= "`".$pre."plugins` MySQL data row Deleted? <font color='green'>True</font><br />";
} else {
$data .= "`".$pre."plugins` MySQL data row Deleted? <font color='red'>False</font><br />";
}
if ($tot == 1) {
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
		<col id='col1_6'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>URL</b></td><td><b>Clicks</b></td><td><b>Domain</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["limit"]) - $setting["limit"]);

$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'plugin_tinyurl' ORDER BY `id` DESC LIMIT $from, ".$setting["limit"]);
while($r = mysql_fetch_array($sql)) {
echo "<tr><td>".$r[id]."</td><td>";
if ($r[field_name]) {
echo "<a href='".$siteurl."re-direct/".$r[id]."/' target='popup'>".$r[field_name];
} else {
echo "<a href='".$siteurl."re-direct/".$r[id]."/' target='popup'>".$r[data];
}
echo "</a></td><td>".$r[item_id]."</td><td><a href='".$r[data]."' target='popup'>".check_domain($r[data])."</a></td><td>";
if ($p[1]) {
echo "<a href='".$plugin_url."&module=delete&id=".urlencode($r[id])."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>&nbsp;&nbsp;<a href='".$plugin_url."&module=code&id=".urlencode($r[id])."'><img src='images/content.png' title='Code'></a>";
}
echo "</td></tr>";
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','N','S','S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."data WHERE field_type = 'plugin_tinyurl'"),0);
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

$handle = fopen($sitepath.".htaccess", 'a+');
while(1){
		
$line = fgets($handle);
if($line == null)break;
		
if (preg_match("/tinyurl/", $line)) {
$return .= 1;
} else {
$return .= 0;
}
$str.= $line;
}
if (preg_match("/1/", $return) == FALSE) {
$new_line = '
RewriteRule ^re-direct/([^/]+)/?$ index.php?view=plugins&plugin=tinyurl&module=go&id=$1';
}

$str.= $new_line;
	
rewind($handle);
ftruncate($handle, filesize($file_name));
fwrite($handle, $str);

fclose($handle);
}

if ($module == "add") {
if ($p[0]) {
echo "<form action='".$plugin_url."&module=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Short <span class='drop'>Name</span></p></td><td><input type='text' name='name' class='title'></td></tr><tr><td><p>URL <span class='drop'>Redirect</span></p></td><td><input type='text' name='url' class='title'></td></tr><tr><td><input type='submit' value='Add URL' class='addContent-button'></td></tr></table></form>";
}
}

if ($module == "code") {
if ($p[0]) {
$r = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE id = '".$_GET['id']."'"));
echo '<SCRIPT LANGUAGE="JavaScript">
<!-- Source:  http://dblast.cjb.net -->
function copyit(theField) {
var tempval=eval("document."+theField)
tempval.focus()
tempval.select()
therange=tempval.createTextRange()
therange.execCommand("Copy")
}
</script>';

echo "<form name='form'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><b>Link Code</b></td><td><textarea name='code' style='width:300px; height:75px; font-family: tahoma;font-size: 11px;color: #4a4a4a; font-weight: bold' rows='5' readonly='readonly'>&lt;a href='".$siteurl."re-direct/".$_GET['id']."/'&gt;Click Here&lt/&gt;</textarea></td><td><input onclick='copyit(\"form.code\")' type='button' value='Select Code' name='cpy'></td></tr><tr><td><b>URL Code</b></td><td><a href='".$siteurl."re-direct/".$_GET['id']."/'>".$siteurl."re-direct/".$_GET['id']."/</a></td></tr></table></form>";
}
}

if ($module == "add2") {
if ($p[0]) {
if (mysql_query("INSERT INTO ".$pre."data VALUES (null, '".addslashes($_POST['name'])."', 'plugin_tinyurl', '".addslashes($_POST['url'])."', 0)") == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The url redirect has been added. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The redirect could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($module == "delete") {
if ($p[2]) {
if (mysql_query("DELETE FROM ".$pre."data WHERE id = '".$_GET["id"]."'") == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The redirect has been deleted. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The redirect could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}


}

if (basename($_SERVER['PHP_SELF']) == "index.php") {
if ($module == "go") {
$r = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE id = '".$_GET['id']."'"));
mysql_query("UPDATE ".$pre."data SET item_id=item_id+1 WHERE id = '".$_GET['id']."'");

header("location: ".$r[0]);
}
}
?>