<?php
$protect = "yes";
$plugin_name = "Form Builder";
$plugin_url = "admin.php?view=load_plugin&plugin=form_builder";
$plugin_version = "1.0";
$url = strtolower(str_replace(" ", "_", $plugin_name)).".php";

$apage = basename($_SERVER['PHP_SELF']);
$siteurl = "http://".$_SERVER['HTTP_HOST'].str_replace($apage, "", $_SERVER['PHP_SELF']);
$url = $_GET['url'];

if ($_GET['check'] == "status") {
echo 1;
}

if ($module == "install_".$url) {
$tot = 0;
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."plugins WHERE name = 'Form Builder'")) > 1) {
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
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."plugins WHERE name = 'Form Builder'")) > 1) {
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
echo " - <a href='admin.php?view=load_plugin&plugin=form_builder&module=add'>Add Form</a>";
}
echo "<br /><br /><br />";

echo "<table cellpadding='5' cellspacing='0' border='0' width='490' align='left'><tr><td><b>Name</b></td><td><b>Fields</b></td><td><b>Date</b></td><td><b>Options</b></td><td><b>Actions</b></td></tr>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["limit"]) - $setting["limit"]);

$sql = mysql_query("SELECT * FROM ".$pre."fielddata WHERE aid = 'plugin_form_builder' ORDER BY `id` DESC LIMIT $from, ".$setting["limit"]);
while($r = mysql_fetch_array($sql)) {
//$date = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_form_builder_".str_replace(" ","_",$r[id])."' AND fname = 'last_update'"));
$num = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."fielddata WHERE aid = 'plugin_form_builder_".$r[id]."'"));
$ex = explode("|", $r[data]);

echo "<tr><td>".stripslashes($r[fname])."</td><td>".$num."</td><td>".timef($ex[2])."</td>";
if ($p[0]) {
echo "<td><a href='".$plugin_url."&module=add_field&id=".urlencode($r[id])."'>Add Field</a></td>";
}
if ($p[1]) {
echo "<td><a href='".$plugin_url."&module=edit&id=".urlencode($r[id])."'>Edit</a> | ";
}
if ($p[2]) {
echo "<a href='".$plugin_url."&module=delete&id=".urlencode($r[id])."'>Delete</a>";
}
echo " | <a href='".$plugin_url."&module=code&id=".urlencode($r[id])."'>Code</a>";
}
echo "</table>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."fielddata WHERE aid = 'plugin_form_builder'"),0);
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
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='".$plugin_url."'>Manage Forms</a><br /><br /><br />";
echo "<form action='".$plugin_url."&module=add2' method='post'><table cellpadding='5' cellspacing='0' border='0' width='490' align='left'><tr><td><b>Form Name</b></td><td><input type='text' name='name' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr><tr><td><b>Captcha?</b></td><td><input type='checkbox' name='captcha' value='yes' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr><tr><td><b>Receiver</b></td><td><input type='text' name='receiver' value='email@address.com' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr>";

echo "<tr><td><input type='submit' value='Add Form' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr></table></form>";
}
}

if ($module == "add_field") {
if ($p[0]) {
echo '<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
function openDir(form) { 
var newIndex = form.fieldname.selectedIndex; 
if (newIndex > 0) {
cururl = form.fieldname.options[ newIndex ].value; 
window.location.assign( cururl ); 
} 
}
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script>';
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='".$plugin_url."'>Manage Forms</a><br /><br /><br />";
echo "<form name='form1' method='post'><center>
<select name='fieldname' onChange='openDir(this.form)'><option value='' selected>--Select a field type--</option><option value='".str_replace("add_field","add_field2",$_SERVER['REQUEST_URI'])."&type=text'>Text Field</option><option value='".str_replace("add_field","add_field2",$_SERVER['REQUEST_URI'])."&type=textarea'>Text Box</option><option value='".str_replace("add_field","add_field2",$_SERVER['REQUEST_URI'])."&type=select'>Dropdown</option><option value='".str_replace("add_field","add_field2",$_SERVER['REQUEST_URI'])."&type=checkbox'>Checkbox</option><option value='".str_replace("add_field","add_field2",$_SERVER['REQUEST_URI'])."&type=radio'>Radio</option></select></center></form>";
}
}

if ($module == "add_field2") {
if ($p[0]) {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='".$plugin_url."'>Manage Forms</a><br /><br /><br />";
echo "<form action='".$plugin_url."&module=add_field3' method='post'><table cellpadding='5' cellspacing='0' border='0' width='490' align='left'><input type='hidden' name='type' value='".$_GET['type']."'><input type='hidden' name='id' value='".$_GET['id']."'>";
if ($_GET['type'] == "text") {
echo "<tr><td><b>Field Name</b></td><td><input type='text' name='field_name' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr><tr><td><b>Displayed Name</b></td><td><input type='text' name='displayed_name' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr><tr><td><b>Required?</b></td><td><input type='checkbox' name='required' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='yes'></td></tr>";
} elseif ($_GET['type'] == "textarea") {
echo "<tr><td><b>Field Name</b></td><td><input type='text' name='field_name' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr><tr><td><b>Displayed Name</b></td><td><input type='text' name='displayed_name' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr><tr><td><b>Required?</b></td><td><input type='checkbox' name='required' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='yes'></td></tr><tr><td><b>Width/Height</b></td><td><input type='text' name='size' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='35/6' size='5'></td></tr>";
} elseif ($_GET['type'] == "select" or $_GET['type'] == "checkbox" or $_GET['type'] == "radio") {
echo "<tr><td><b>Field Name</b></td><td><input type='text' name='field_name' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr><tr><td><b>Displayed Name</b></td><td><input type='text' name='displayed_name' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr><tr><td><b>Required?</b></td><td><input type='checkbox' name='required' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='yes'></td></tr><tr><td><b>Options</b> (separate by comma ,)</td><td><textarea name='options' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' cols='35' rows='6'></textarea></td></tr>";
}
echo "<tr><td><input type='submit' value='Add Field' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr></table></form>";
}
}

if ($module == "add_field3") {
if ($p[0]) {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='admin.php?view=load_plugin&plugin=form_builder'>Manage Forms</a><br /><br /><br />";

$_POST['field_name'] = strtolower(str_replace(" ","_", addslashes($_POST['field_name'])));

$query = mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, '".$_POST['field_name']."', '".$_POST['type']."|".addslashes($_POST['displayed_name'])."|".$_POST['required']."|".$_POST['options']."', 'plugin_form_builder_".$_POST['id']."')");

if ($query == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The field <b>".stripslashes($_POST['field_name'])."</b> has been added. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($cpage, htmlspecialchars(mysql_error()), $domain);
echo "The field could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($module == "add2") {
if ($p[0]) {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='admin.php?view=load_plugin&plugin=form_builder'>Manage Forms</a><br /><br /><br />";

$query = mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, '".addslashes($_POST['name'])."', '".$_POST['captcha']."|".$_POST['receiver']."|".time()."', 'plugin_form_builder')");

if ($query == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The form <b>".stripslashes($_POST['name'])."</b> has been added. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($cpage, htmlspecialchars(mysql_error()), $domain);
echo "The form could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($module == "code") {
if ($p[0]) {
$name = mysql_fetch_row(mysql_query("SELECT fname,data FROM ".$pre."fielddata WHERE id = '".$_GET['id']."'"));
$ex = explode("|", $name[1]);

echo '
<script type="text/javascript">
function displayHTML(form) {
  var inf = form.htmlArea.value;
  win = window.open(", ", \'popup\', \'toolbar = yes, status = yes, menubar = yes, location = yes\');
  win.document.write("" + inf + "");
}
</script>';

echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='".$plugin_url."'>Manage Forms</a><br /><br /><br />";

echo "<form><textarea name='htmlArea' style='width:458px; height:200px; font-family: tahoma;font-size: 11px;color: #4a4a4a; font-weight: bold' cols='3' rows='3' readonly='readonly'>";
echo '<?php
session_start();
?>';
echo '
<script language="JavaScript"><!--
ts = <?php echo time(); ?>;
--></script>';
?>
<?php
echo "

<script type='text/javascript' src='".$siteurl."includes/jscripts/mootools.js'></script>
<script type='text/javascript' src='".$siteurl."includes/jscripts/mootools2.js'></script>
<link rel='stylesheet' type='text/css' href='".$siteurl."includes/jscripts/mootools.css'>

<div id='log'>
<div id='log_res'>
<!-- SPANNER -->
</div>
</div>

<div id='container'>
<form action='".$siteurl."plugins/form.php' method='post' id='registerForm'>
<input type='hidden' name='receiver' value='".$ex[1]."'>

<table cellpadding='2' cellspacing='0' border='0' align='center'><fieldset>";
$sql = mysql_query("SELECT * FROM ".$pre."fielddata WHERE aid = 'plugin_form_builder_".$_GET['id']."' ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$ex = explode("|",$r[data]);
echo "<input type='hidden' name='fields[]' value='".$r[fname]."'><tr><td><label for='".$r[fname]."'><b>".$ex[1].":</b></label></td><td>";
if ($ex[0] == "text") {
echo "<input class='text' type='text' size='20' name='".$r[fname]."' id='".$r[fname]."' />";
} elseif ($ex[0] == "textarea") {
$px = explode("/", $ex[3]);
echo "<textarea class='text' name='".$r[fname]."' id='".$r[fname]."' cols='".$px[0]."' rows='".$px[1]."' />&lt;/textarea&gt;";
} elseif ($ex[0] == "select") {
echo "<select class='text' name='".$r[fname]."' id='".$r[fname]."' />";
if ($ex[2] == "") {
echo "<option value='' selected>-------</option>";
}
$px = explode(",", $ex[3]);
while (list(, $i) = each ($px)) {
echo "<option value='".$i."'>".$i."</option>";
}
echo "</select>";
} elseif ($ex[0] == "radio") {
$px = explode(",", $ex[3]);
while (list(, $i) = each ($px)) {
echo $i." <input class='text' type='radio' size='20' name='".$r[fname]."' id='".$r[fname]."' /> ";
}
} elseif ($ex[0] == "checkbox") {
$px = explode(",", $ex[3]);
while (list(, $i) = each ($px)) {
echo $i." <input class='text' type='checkbox' size='20' name='".$r[fname]."' id='".$r[fname]."' /> ";
}
}	
echo "</td></tr>

";
}

if ($ex[0]) {
echo "<tr><td>
<img id='captcha_img' src='".$siteurl."includes/captcha.php?id=".time()."' style='border:1px solid #000000' /> <a href='no_matter' onclick='document.getElementById(\"captcha_img\").src = \"".$siteurl."includes/captcha.php?id=\" + ++ts; return false'><img src='".$siteurl."includes/refresh.png' border='0'></a></td><td><input class='text' type='text' size='10' name='captcha' id='captcha' /></td></tr>";
}
echo "

<tr><td><label><input class='submit' type='submit' name='register' id='register' value='Submit Form' /></label></td></tr>

</fieldset>
</table></form></div>";
echo "</textarea><br /><div align='right'><input type='button' value='View Preview' onclick='displayHTML(this.form)' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></div></form>";
}
}

if ($module == "edit") {
if ($p[1]) {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='".$plugin_url."'>Manage Forms</a>";
if ($p[0]) {
echo "- <a href='".$plugin_url."&module=add'>Add Form</a>";
}
echo "<br /><br /><br />";
$name = mysql_fetch_row(mysql_query("SELECT fname,data FROM ".$pre."fielddata WHERE id = '".$_GET['id']."'"));
$ex = explode("|", $name[1]);

echo "<form action='".$plugin_url."&module=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='0' border='0' width='490' align='left'><tr><td><b>Form Name</b></td><td><input type='text' name='name' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='".$name[0]."'></td></tr><tr><td><b>Captcha?</b></td><td><input type='checkbox' name='captcha' value='yes' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'";
if ($ex[0]) {
echo " checked";
}
echo "></td></tr><tr><td><b>Receiver</b></td><td><input type='text' name='receiver' value='";
if ($ex[1]) {
echo $ex[1];
} else {
echo "email@address.com";
}
echo "' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr>";

$i = 1;
$sql = mysql_query("SELECT * FROM ".$pre."fielddata WHERE aid = 'plugin_form_builder_".$_GET['id']."' ORDER BY `id` ASC");
while($r = mysql_fetch_array($sql)) {
$ex = explode("|",$r[data]);
echo "<input type='hidden' name='fields[]' value='".$r[id]."'><input type='hidden' name='type_".$r[id]."' value='".$ex[0]."'>";
if ($ex[0] == "text") {
echo "<tr><td><u>Field #".$i."</u></td><td><u>Text Field</u></td></tr><tr><td><b>Field Name</b></td><td><input type='text' name='field_name_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='".$r[fname]."'></td></tr><tr><td><b>Displayed Name</b></td><td><input type='text' name='displayed_name_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='".$ex[1]."'></td></tr><tr><td><b>Required?</b></td><td><input type='checkbox' name='required_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='yes'";
if ($ex[2]) {
echo " checked";
}
echo "></td></tr>";
} elseif ($ex[0] == "textarea") {
echo "<tr><td><u>Field #".$i."</u></td><td><u>Text Box</u></td></tr><tr><td><b>Field Name</b></td><td><input type='text' name='field_name_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='".$r[fname]."'></td></tr><tr><td><b>Displayed Name</b></td><td><input type='text' name='displayed_name_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='".$ex[1]."'></td></tr><tr><td><b>Required?</b></td><td><input type='checkbox' name='required_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='yes'";
if ($ex[2]) {
echo " checked";
}
echo "></td></tr><tr><td><b>Width/Height</b></td><td><input type='text' name='options_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='35/6' size='5' value='".$ex[3]."'></td></tr>";
} elseif ($ex[0] == "select" or $ex[0] == "checkbox" or $ex[0] == "radio") {
echo "<tr><td><u>Field #".$i."</u></td><td><u>";
if ($ex[0] == "select") {
echo "Dropdown";
} elseif ($ex[0] == "checkbox") {
echo "Checkbox";
} elseif ($ex[0] == "radio") {
echo "Radio";
}
echo "</u></td></tr><tr><td><b>Field Name</b></td><td><input type='text' name='field_name_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='".$r[fname]."'></td></tr><tr><td><b>Displayed Name</b></td><td><input type='text' name='displayed_name_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='".$ex[1]."'></td></tr><tr><td><b>Required?</b></td><td><input type='checkbox' name='required_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='yes'";
if ($ex[2]) {
echo " checked";
}
echo "></td></tr><tr><td><b>Options</b> (separate by comma ,)</td><td><textarea name='options_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' cols='35' rows='6'>".$ex[3]."</textarea></td></tr>";
}
echo "<tr><td><b>Delete Field?</b></td><td><input type='checkbox' name='delete_".$r[id]."' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px' value='yes'></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
$i = $i + 1;
}

echo "<tr><td><input type='submit' value='Update Form' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444;padding-left:1px'></td></tr></table></form>";
}
}


if ($module == "edit2") {
if ($p[1]) {
echo "<b>Directory</b>&nbsp;&nbsp;-&nbsp;&nbsp;Plugins / ".$plugin_name." Plugin - <a href='".$plugin_url."'>Manage Forms</a>";
if ($p[0]) {
echo "- <a href='".$plugin_url."&module=add'>Add Form</a>";
}
echo "<br /><br /><br />";

$query = mysql_query("UPDATE ".$pre."fielddata SET data = '".$_POST['captcha']."|".$_POST['receiver']."|".time()."', fname = '".addslashes($_POST['name'])."' WHERE id = '".$_GET['id']."'");

while (list(, $i) = each ($_POST['fields'])) {
if ($_POST["delete_".$i]) {
mysql_query("DELETE FROM ".$pre."fielddata WHERE id = '".$i."'");
} else {
$_POST["field_name_".$i] = strtolower(str_replace(" ","_", addslashes($_POST["field_name_".$i])));

mysql_query("UPDATE ".$pre."fielddata SET data = '".$_POST["type_".$i]."|".addslashes($_POST["displayed_name_".$i])."|".$_POST["required_".$i]."|".$_POST["options_".$i]."', fname = '".$_POST["field_name_".$i]."' WHERE id = '".$i."'");
}
}

if ($query == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The form <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($cpage, htmlspecialchars(mysql_error()), $domain);
echo "The form could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($module == "delete") {
if ($p[2]) {
echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Are you sure you want to delete this form?");
if (agree)
document.write("");
else
history.go(-1);
</SCRIPT>';
$query1 = mysql_query("DELETE FROM ".$pre."fielddata WHERE id = '".$_GET["id"]."'");
$query2 = mysql_query("DELETE FROM ".$pre."fielddata WHERE aid = 'plugin_form_builder_".$_GET['id']."'");

if ($query1 == TRUE && $query2 == TRUE) {
echo re_direct("1500", $plugin_url);
echo "The form has been deleted. <a href='".$plugin_url."'>Return</a>";
} else {
echo reporterror($cpage, htmlspecialchars(mysql_error()), $domain);
echo "The form could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}


}

if (basename($_SERVER['PHP_SELF']) == "index.php") {
if ($module == "form_builder") {
echo 1;
}
}
?>