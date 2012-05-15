<?php
$smarty->display($skin.'/admin_header.tpl');

if ($_GET['do'] == "") {
echo "<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><colgroup>
	<col id='col1_1'></col>
		<col id='col1_2'></col>
		<col id='col1_3'></col>
		<col id='col1_4'></col>
		<col id='col1_5'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>Name</b></td><td><b>Version</b></td><td><b>Status</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."plugins ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td><a href='admin.php?view=plugins&do=load&plugin=".strtolower(str_replace(" ", "_", $r[name]))."'>".ucwords($r[name])."</a></td><td>".$r[version]."</td><td><font color='";
if ($r[status] == "On") {
echo "blue'>On";
} else {
echo "red'>Off";
}
echo "</font></td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=plugins&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=plugins&do=uninstall&id=".$r[id]."' onclick='return confirmDelete();'><img src='images/delete.png' title='Un-Install'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','N','S','S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."plugins"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=plugins&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=plugins&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=plugins&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($_GET['do'] == "load") {
$plugin = mysql_fetch_row(mysql_query("SELECT url,status FROM ".$pre."plugins WHERE name = '".strtolower(str_replace("_", " ", $_GET['plugin']))."'"));
if ($plugin[1] == "Off") {
echo "Sorry, but the <b>".ucwords($_GET['plugin'])."</b> Plugin is offline";
} else {
$module = $_GET['module'];
include ($sitepath."plugins/".strtolower(str_replace("_", " ", $_GET['plugin']))."/".$plugin[0]);
}
}

if ($p[0]) {
if ($_GET['do'] == "install_plugin") {
$module = "install_".$_GET['url'];
include ($sitepath."plugins/".$_GET['url']);
}

if ($_GET['do'] == "install") {
echo "<table cellpadding='5' cellspacing='0' border='0' width='480' style='padding-left:5px' align='left'><tr><td><b>Name</b></td><td><b>Size</b></td></tr>";

function size($size) {
  $i=0;
  $iec = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
  while (($size/1024)>1) {
   $size=$size/1024;
   $i++;
  }
  return substr($size,0,strpos($size,'.')+4)." ".$iec[$i];
}

if ($handle = @opendir($sitepath."plugins")) {
while (false !== ($file = @readdir($handle))) {
if ($file != '.' and $file != '..' and $file != 'index.html') {
if (preg_match("/functions/", $file)) {
} else {
$plg_check = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."plugins WHERE url = '".$file."'"));
if ($plg_check == 0) {
$ex = explode(".", $file);
$check = file_get_contents($siteurl."plugins/".$file."?check=status");
if ($check == 1) {
echo "<tr><td><a href='admin.php?view=plugins&do=install_plugin&url=".$file."'>".ucwords($ex[0])."</a></td><td>".size(filesize($sitepath."plugins/".$file))."</td></tr>";
}
}
}
}
}
@closedir($handle);
}
echo "</table><br clear='all'>";
}

}

if ($p[1]) {
if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT name,url,version,status FROM ".$pre."plugins WHERE id = '".$_GET['id']."'"));
echo "<form action='admin.php?view=plugins&do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='0' border='0' width='100%' align='center' style='border: 2px solid #dddddd'><tr><td><h4>Edit Plugin</h4></td><td>&nbsp;</td></tr><tr><td>Name</td><td><input type='text' name='name' value='".$r[0]."' size='12' class='input'><input type='hidden' name='old_name' value='".$r[0]."'></td></tr><tr><td>URL</td><td><input type='text' name='url' value='".$r[1]."' class='input'></td></tr><tr><td>Version</td><td><input type='text' name='version' value='".$r[2]."' class='input' value='popup'></td></tr><tr><td>Status</td><td><select name='status' class='input'><option value='".$r[3]."'>-- ".$r[3]." --</option><option value='On'>On</option><option value='Off'>Off</option></select></td></tr><tr><td><input type='submit' value='Update Plugin' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px;background:url(inc/images/topbg.jpg) repeat-x;'></td></tr></table></form>";
}

if ($_GET['do'] == "edit2") {
if (mysql_query("UPDATE ".$pre."plugins SET name = '".addslashes($_POST["name"])."', url = '".addslashes($_POST["url"])."', version = '".addslashes($_POST["version"])."', status = '".addslashes($_POST["status"])."' WHERE id = '".$_GET['id']."'") == TRUE) {
echo re_direct("1500", "admin.php?view=plugins");
echo "The plugin <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=plugins'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The plugin could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[2]) {
if ($_GET['do'] == "uninstall") {
$admin_page = "yes";
$r = mysql_fetch_row(mysql_query("SELECT name,url,version,status FROM ".$pre."plugins WHERE id = '".$_GET['id']."'"));
echo '<script>
var checkobj
function agreesubmit(el){
checkobj=el
if (document.all||document.getElementById){
for (i=0;i<checkobj.form.length;i++){  //hunt down submit button
var tempobj=checkobj.form.elements[i]
if(tempobj.type.toLowerCase()=="submit")
tempobj.disabled=!checkobj.checked
}
}
}
function defaultagree(el){
if (!document.all&&!document.getElementById){
if (window.checkobj&&checkobj.checked)
return true
else{
alert("Please confirm the box in order to un-install this plugin.")
return false
}
}
}
</script>';
echo "<form action='admin.php?view=plugins&do=uninstall2&url=".strtolower($r[0])."' name='agreeform' onSubmit='return defaultagree(this)' method='post'><input type='hidden' name='id' value='".$_GET['id']."'><input type='hidden' name='url' value='".$r[1]."'>Un-Installing the <a href='admin.php?view=plugins&do=load&plugin=".strtolower($r[0])."'>".ucwords($r[0])."</a> Plugin will remove it from the Plugin List as well as all MySQL data for it. The file will <b>NOT</b> be deleted, you may choose to do that yourself, especially if you want to re-install it. Please confirm to un-install the plugin below:<br /><br />";

echo '<input name="agreecheck" type="checkbox" style="font-family: tahoma; font-size: 11px; border: 1px solid #444444" onClick="agreesubmit(this)"><b>I want to proceed to un-install the ';
echo "<a href='admin.php?view=plugins&do=load&plugin=".strtolower($r[0])."'>".ucwords($r[0])."</a> ";
echo 'Plugin</b><br /><input type="Submit" value="Submit!" style="font-family: tahoma; font-size: 11px; border: 1px solid #444444" disabled></form>

<script>
document.forms.agreeform.agreecheck.checked=false
</script>';
}

if ($_GET['do'] == "uninstall2") {
$module = "uninstall_".$_GET['url'];
include ($sitepath."plugins/".$_GET['url']."/".$_GET['url'].".php");
}
}
?>