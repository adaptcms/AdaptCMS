<?php
if ($_GET['show'] == "all") {
setcookie("media_show", "all", time()+60*60*24*30);
} elseif($_GET['show'] == "default") {
setcookie("media_show", "default", time()+60*60*24*30);
}

if ($_COOKIE['media_show'] == "all") {

} elseif($_COOKIE['media_show'] == "default") {
$grabm = " WHERE media_id = ''";
}

$smarty->display($skin.'/admin_header.tpl');

if ($_GET['do'] == "") {
echo '<script language="javascript">function jump(targ,selObj,restore){
eval(targ+".location=\'"+selObj.options[selObj.selectedIndex].value+"\'");
if (restore) selObj.selectedIndex=0;
}</script>';

if(!isset($_GET['page1'])){
    $page1 = 1;
} else {
    $page1 = $_GET['page1'];
}
$from1 = (($page1 * 9) - 9);

echo "<table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr>";
$sql = mysql_query("SELECT * FROM ".$pre."media ORDER BY `id` DESC LIMIT $from1, 9");
for($i = 0; $r = mysql_fetch_assoc($sql); $i++) {
$file = mysql_fetch_row(mysql_query("SELECT filedir,filename FROM ".$pre."files WHERE media_id = '".$r[id]."' ORDER BY `id` DESC LIMIT 1"));
if ($file[0] == $setting["upload_folder"]) {
$info = pathinfo($siteurl.$file[0].$file[1]);
} else {
$info = pathinfo($file[0].$file[1]);
}

if (($i % 3) === 0) {
echo "</tr><tr>";
}
echo "<td align='center'><table><tr><td><a href='admin.php?view=media&do=upload&media_id=".$r[id]."'>";
if (file_type($info["extension"], "") == "image") {
echo "<img src='";
if ($file[0] == $setting["upload_folder"]) {
echo $siteurl.$file[0]."thumbs/".$file[1];
} else {
echo $file[0].$file[1];
}
echo "' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'>";
} elseif(file_type($info["extension"], "")) {
echo "<img src='".file_type($info["extension"], "image")."' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'>";
} else {
echo "<img src='".$siteurl."images/nopreview.jpg' class='input'>";
}
echo "</a></td></tr><tr><td align='center'><u>".$r[name]."</u></td></tr><tr><td align='center'>";
if ($p[0]) {
echo "<a href='admin.php?view=media&do=upload&media_id=".$r[id]."'><img src='images/add.png' title='Upload'></a>&nbsp;&nbsp;";
}
if ($p[1]) {
echo "<a href='admin.php?view=media&do=edit&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=media&do=delete&id=".$r[id]."'><img src='images/delete.png' title='Delete'></a>";
}
echo "</td></tr></table></td>";
}
echo "</tr></table><br clear='all'>";
$total_results1 = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."media"),0);
$total_pages1 = ceil($total_results1 / 9);

if ($total_pages1 > "1") {
echo "<center>";
if($page1 > 1){
    $prev = ($page1 - 1);
    echo "<a href=\"admin.php?view=media&page1=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages1; $i++){
    if(($page1) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=media&page1=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page1 < $total_pages1){
    $next = ($page1 + 1);
    echo "<a href=\"admin.php?view=media&page1=$next\">Next>></a>";
}
echo "</center>";
}

echo "<br /><table cellpadding='5' cellspacing='0' border='0' width='100%' align='center'><tr><td align='right'>
<select name='sort' class='input' onChange=\"jump('parent',this,0)\"><option value='' selected>- Choose -</option><option value='admin.php?view=media&show=default'>Default</option><option value='admin.php?view=media&show=all'>Show all</option></select></td></tr></table>

<table id='mytable' cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'>
<colgroup>
	<col id='col1_1'></col>
		<col id='col1_2'></col>
		<col id='col1_3'></col>
		<col id='col1_4'></col>
		<col id='col1_5'></col>
</colgroup>
<thead>
<tr style='border-bottom: 1px solid #262626'><td><b>ID #</b></td><td><b>File Name</b></td><td><b>Date</b></td><td><b>Actions</b></td></tr></thead><tbody>";

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["admin_limit"]) - $setting["admin_limit"]);

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."files".$grabm." ORDER BY `id` DESC LIMIT $from, ".$setting["admin_limit"]);
while($r = mysql_fetch_array($sql)) {
if (($i % 2) === 0) {
echo "<tr class='light'>";
} else {
echo "<tr class='dark'>";
}
echo "<td>".$r[id]."</td><td><a href='".$r[filedir].$r[filename]."' title='".$r[filename]."'>";
if (strlen($r[filename]) > 40) {
echo substr(stripslashes($r[filename]),0,37)."...";
} else {
echo substr(stripslashes($r[filename]),0,40);
}
echo "</a></td><td>".timef($r[date])."</td><td>";
if ($p[1]) {
echo "<a href='admin.php?view=media&do=edit_file&id=".$r[id]."'><img src='images/edit.png' title='Edit'></a>&nbsp;&nbsp;";
}
if ($p[2]) {
echo "<a href='admin.php?view=media&do=delete_file&id=".$r[id]."' onclick='return confirmDelete();'><img src='images/delete.png' title='Delete'></a>";
}
echo "</td></tr>";
$i = $i + 1;
}
echo "</tbody></table>
<script type='text/javascript'>
initSortTable('mytable',Array('N','S','N','N','S'));
</script>
<br clear='all'>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM ".$pre."files WHERE media_id = ''"),0);
$total_pages = ceil($total_results / $setting["admin_limit"]);

if ($total_pages > "1") {

echo "<center>";


if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"admin.php?view=media".$grab."&page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"admin.php?view=media".$grab."&page=$i\">$i</a>&nbsp;";
			if (($i/25) == (int)($i/25)) {echo "<br />";}
    }
}


if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"admin.php?view=media".$grab."&page=$next\">Next>></a>";
}
echo "</center>";
}
}

if ($p[0]) {
if ($_GET['do'] == "upload") {
$ext = explode(",", $setting["file_extensions"]);
while (list($k, $i) = each ($ext)) {
if ($k == 0) {
$accept .= $i;
} else {
$accept .= "|".$i;
}
}

if ($_GET['media_id']) {
echo "<form action='admin.php?view=media&do=upload&media_id=".$_GET['media_id']."' method='post'>";
} else {
echo "<form action='admin.php?view=media&do=upload' method='post'>";
}

echo "<table cellpadding='5' cellspacing='0' border='0' width='100%' style='padding-left:5px' align='center'><tr><td>Add more Files: <input type='text' name='amount' class='title'>&nbsp;&nbsp;<input type='submit' value='Update' class='addContent-button'><br /><br /></td></tr></table></form><br />";

echo "<form action='admin.php?view=media&do=upload2";
if ($_GET['media_id']) {
echo "&media_id=".$_GET['media_id'];
}
echo "' method='post' enctype='multipart/form-data'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center' id='form'>";

echo "<tr><td><p>File #0</p>&nbsp;&nbsp;<input type='file' name='file_0' size='16' class='select'>&nbsp;&nbsp;...<i>or</i> link to a file&nbsp;&nbsp;<input type='text' name='file2_0' size='16'><br />Watermark <input type='checkbox' name='watermark_0'>&nbsp;&nbsp;Re-Size <input type='title' name='resize1_0' size='2'> / <input type='text' name='resize2_0' size='2'>&nbsp;&nbsp;&nbsp;&nbsp;Caption <input type='text' name='caption_0'></td></tr>";

$_POST['amount'] = $_POST['amount'] - 1;
for ($i = 1; $i <= $_POST['amount']; $i = $i + 1) {
echo "<tr><td><p>File #".$i."</p>&nbsp;&nbsp;<input type='file' name='file_".$i."' size='16' class='select'>&nbsp;&nbsp;...<i>or</i> link to a file&nbsp;&nbsp;<input type='text' name='file2_".$i."' size='16'><br />Watermark <input type='checkbox' name='watermark_".$i."'>&nbsp;&nbsp;Re-Size <input type='text' name='resize1_".$i."' size='2' class='input'> / <input type='text' name='resize2_".$i."' size='2' class='input'>&nbsp;&nbsp;&nbsp;&nbsp;Caption <input type='text' name='caption_".$i."' class='input'></td></tr>";
}

echo "<input type='hidden' name='amount' value='".$_POST['amount']."'><tr><td><br /><input type='submit' value='Upload Files' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "upload2") {
if ($_POST['amount'] < 0) {
$_POST['amount'] = 1;
}
for ($i = 0; $i <= $_POST['amount']; $i = $i + 1) {
if ($_FILES["file_$i"]["name"]) {
if ($_POST["watermark_$i"]) {
copy ($_FILES["file_$i"]["tmp_name"], $sitepath.$setting["upload_folder"].$_FILES["file_$i"]["name"]);
createThumbs($sitepath.$setting["upload_folder"],$sitepath.$setting["upload_folder"]."thumbs/", $_FILES["file_$i"]["name"]);

$ex = explode(".", $_FILES["file_$i"]["name"]);
$filet = $ex[0]."_wm.".$ex[1];
watermark($sitepath.$setting["upload_folder"].$_FILES["file_$i"]["name"], $sitepath.$setting["upload_folder"]."watermark.png", $sitepath.$setting["upload_folder"].$_FILES["file_$i"]["name"]);

$filename = $_FILES["file_$i"]["name"];
$filedir = $setting["upload_folder"];
} else {
copy ($_FILES["file_$i"]["tmp_name"], $sitepath.$setting["upload_folder"].$_FILES["file_$i"]["name"]);
$filename = $_FILES["file_$i"]["name"];
$filedir = $setting["upload_folder"];
createThumbs($setting["upload_folder"],$setting["upload_folder"]."thumbs/", $filename);
}
} else {
$filename = basename($_POST["file2_$i"]);
$filedir = str_replace($filename, "", $_POST["file2_$i"]);
}
if ($filename) {
$query = mysql_query("INSERT INTO ".$pre."files VALUES (null, '".$filename."', '".$filedir."', '".addslashes($_POST["caption_$i"])."', '".$_GET['media_id']."', '".time()."', '0|0')");
}
if ($_POST["resize1_$i"] && $_POST["resize2_$i"]) {
if (!extension_loaded('gd') && !extension_loaded('gd2'))
    {
        trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }

list($width,$height,$image_type)=getimagesize($filedir.$filename);

    switch ($image_type) {
        case 1: $src = imagecreatefromgif($filedir.$filename); break;
        case 2: $src = imagecreatefromjpeg($filedir.$filename);  break;
        case 3: $src = imagecreatefrompng($filedir.$filename); break;
        default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
    }

$max_width = $_POST["resize1_$i"];
$max_height = $_POST["resize2_$i"];

$x_ratio = $max_width / $width;
$y_ratio = $max_height / $height;

    $tn_width = $max_width;
    $tn_height = $max_height;

$tmp=imagecreatetruecolor($tn_width,$tn_height);

    if ($image_type == 1 or $image_type == 3) {
        imagealphablending($tmp, false);
        imagesavealpha($tmp,true);
        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
        imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
    }
imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

    switch ($image_type) {
        case 1: imagegif($tmp, $filedir.$filename); break;
        case 2: imagejpeg($tmp, $filedir.$filename, 100);  break; // best quality
        case 3: imagepng($tmp, $filedir.$filename, 0); break; // no compression
        default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
    }

imagedestroy($src);
imagedestroy($tmp);
}
}

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=media");
echo "The file(s) have been uploaded. <a href='admin.php?view=media'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The files could not be uploaded. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

if ($_GET['do'] == "add") {
echo "<form action='admin.php?view=media&do=add2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center' id='form'><tr><td><p>Name</p><input type='text' name='name' size='12' class='addtitle' class='required'></td></tr><tr><td><br /><input type='submit' value='Add Media' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "add2" && $_POST['name']) {
if (mysql_query("INSERT INTO ".$pre."media VALUES (null, '".addslashes($_POST["name"])."', 0, '0|0')") == TRUE) {

echo re_direct("1500", "admin.php?view=media");
echo "The media <b>".stripslashes($_POST['name'])."</b> has been added. <a href='admin.php?view=media'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The media could not be added. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[1]) {
if ($_GET['do'] == "edit") {
$r = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."media WHERE id = '".$_GET['id']."'"));

echo "<form action='admin.php?view=media&do=edit2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center' style='border-bottom:1px solid black'><tr><td><p>Name</p><input type='text' name='name' value='".$r[0]."' size='12' class='addtitle'><input type='hidden' name='old_name' value='".$r[0]."'></td></tr></table><br /><table cellpadding='8' cellspacing='2' border='0' width='100%' align='center' >";

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."files WHERE media_id = '".$_GET['id']."' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
$i++;
echo "<input type='hidden' name='id[]' value='".$r[id]."'><tr>";
if (getimagesize($r[filedir].$r[filename])) {
echo "<td align='center' valign='center' width='30%'><a href='".$r[filedir].$r[filename]."'><img src='";
if (stristr($r[filedir], 'http') === FALSE) {
echo $siteurl.$r[filedir]."thumbs/".$r[filename];
} else {
echo $r[filedir].$r[filename];
}
echo "' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input' target='popup'></a></td>";
} else {
echo "<td>&nbsp;</td>";
}
echo "<td><p>File #".$i."</p>&nbsp;&nbsp;";
if (stristr($_POST["filedir_$i"], 'http') === FALSE) {
echo "<input type='hidden' name='oldfile_".$r[id]."' value='".$r[filename]."'><input type='hidden' name='filedir_".$r[id]."' value='".$r[filedir]."'><input type='text' name='file_".$r[id]."' value='".$r[filename]."' size='16' class='addtitle'>";
} else {
echo "<input type='hidden' name='file_".$r[id]."' value='".$r[filename]."'><b>".$r[filename]."</b>";
}
echo "&nbsp;&nbsp;Delete? <input type='checkbox' name='delete_".$r[id]."' value='yes'><br /><br />";
if (stristr($_POST["filedir_$i"], 'http') === FALSE) {
echo "Watermark <input type='checkbox' name='watermark_".$r[id]."'>&nbsp;&nbsp;Re-Size <input type='text' name='resize1_".$r[id]."' size='2' class='input'> / <input type='text' name='resize2_".$r[id]."' size='2' class='input'>&nbsp;&nbsp;&nbsp;&nbsp;Caption <input type='text' name='caption_".$r[id]."' value='".$r[caption]."' class='input'>";
} else {
echo "Caption <input type='text' name='caption_".$r[id]."' value='".$r[caption]."' class='input'>";
}
echo "<br /><br /></td></tr>";
}

echo "<tr><td><br /><input type='submit' value='Update Media' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "edit2") {
if (mysql_query("UPDATE ".$pre."media SET name = '".addslashes($_POST["name"])."' WHERE id = '".$_GET['id']."'") == TRUE) {

while (@list(, $i) = @each ($_POST['id'])) {
if ($_POST["delete_$i"]) {
@unlink($sitepath.$_POST["filedir_$i"].$_POST["file_$i"]);
@unlink($sitepath.$_POST["filedir_$i"]."thumbs/".$_POST["file_$i"]);
mysql_query("DELETE FROM ".$pre."files WHERE id = '".$i."'");
} else {
if ($_POST["oldfile_$i"] != $_POST["file_$i"]) {
rename($sitepath.$_POST["filedir_$i"].$_POST["oldfile_$i"], $sitepath.$_POST["filedir_$i"].$_POST["file_$i"]);
@rename($sitepath.$_POST["filedir_$i"]."thumbs/".$_POST["oldfile_$i"], $sitepath.$_POST["filedir_$i"]."thumbs/".$_POST["file_$i"]);
}
mysql_query("UPDATE ".$pre."files SET filename = '".$_POST["file_$i"]."', caption = '".$_POST["caption_$i"]."' WHERE id = '".$i."'");

if ($_POST["watermark_$i"] && stristr($_POST["filedir_$i"], 'http') === FALSE) {
$ex = explode(".", $_POST["file_$i"]);
$filet = $ex[0]."_wm.".$ex[1];
watermark($sitepath.$_POST["filedir_$i"].$_POST["file_$i"], $sitepath.$_POST["filedir_$i"]."watermark.png", $sitepath.$_POST["filedir_$i"].$_POST["file_$i"]);
}

if ($_POST["resize1_$i"] && $_POST["resize2_$i"]) {
if (!extension_loaded('gd') && !extension_loaded('gd2'))
    {
        trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }

list($width,$height,$image_type)=getimagesize($_POST["filedir_$i"].$_POST["file_$i"]);

    switch ($image_type) {
        case 1: $src = imagecreatefromgif($_POST["filedir_$i"].$_POST["file_$i"]); break;
        case 2: $src = imagecreatefromjpeg($_POST["filedir_$i"].$_POST["file_$i"]);  break;
        case 3: $src = imagecreatefrompng($_POST["filedir_$i"].$_POST["file_$i"]); break;
        default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
    }

$max_width = $_POST["resize1_$i"];
$max_height = $_POST["resize2_$i"];

$x_ratio = $max_width / $width;
$y_ratio = $max_height / $height;

    $tn_width = $max_width;
    $tn_height = $max_height;

$tmp=imagecreatetruecolor($tn_width,$tn_height);

    if ($image_type == 1 or $image_type == 3) {
        imagealphablending($tmp, false);
        imagesavealpha($tmp,true);
        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
        imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
    }
imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

    switch ($image_type) {
        case 1: imagegif($tmp, $_POST["filedir_$i"].$_POST["file_$i"]); break;
        case 2: imagejpeg($tmp, $_POST["filedir_$i"].$_POST["file_$i"], 100);  break; // best quality
        case 3: imagepng($tmp, $_POST["filedir_$i"].$_POST["file_$i"], 0); break; // no compression
        default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
    }

imagedestroy($src);
imagedestroy($tmp);

createThumbs($sitepath.$_POST["filedir_$i"],$sitepath.$_POST["filedir_$i"]."thumbs/", $_POST["file_$i"]);
}
}
}

echo re_direct("1500", "admin.php?view=media");
echo "The media <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=media'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The media could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

if ($_GET['do'] == "edit_file") {
echo "<form action='admin.php?view=media&do=edit_file2&id=".$_GET['id']."' method='post'><table cellpadding='8' cellspacing='2' border='0' width='100%' align='center'>";

$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."files WHERE id = '".$_GET['id']."' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
$i++;
echo "<input type='hidden' name='id[]' value='".$r[id]."'><tr>";
if (getimagesize($r[filedir].$r[filename])) {
echo "<td align='center' valign='center' width='30%'><img src='";
if (stristr($r[filedir], 'http') === FALSE) {
echo $siteurl.$r[filedir]."thumbs/".$r[filename];
} else {
echo $r[filedir].$r[filename];
}
echo "' width='".$setting["gallery_width"]."' height='".$setting["gallery_height"]."' class='input'></td>";
} else {
echo "<td>&nbsp;</td>";
}
echo "<td><p>File #".$i."</p>&nbsp;&nbsp;";
if (stristr($_POST["filedir_$i"], 'http') === FALSE) {
echo "<input type='hidden' name='oldfile_".$r[id]."' value='".$r[filename]."'><input type='hidden' name='filedir_".$r[id]."' value='".$r[filedir]."'><input type='text' name='file_".$r[id]."' value='".$r[filename]."' size='16' class='addtitle'>";
} else {
echo "<input type='hidden' name='file_".$r[id]."' value='".$r[filename]."'><b>".$r[filename]."</b>";
}
echo "&nbsp;&nbsp;<a href='".$r[filedir].$r[filename]."'>View File</a><br /><br />";
if (stristr($_POST["filedir_$i"], 'http') === FALSE) {
echo "Watermark <input type='checkbox' name='watermark_".$r[id]."'>&nbsp;&nbsp;Re-Size <input type='text' name='resize1_".$r[id]."' size='2' class='input'> / <input type='text' name='resize2_".$r[id]."' size='2' class='input'>&nbsp;&nbsp;&nbsp;&nbsp;Caption <input type='text' name='caption_".$r[id]."' value='".$r[caption]."' class='input'>&nbsp;&nbsp;Delete? <input type='checkbox' name='delete_".$r[id]."' value='yes'>";
} else {
echo "Caption <input type='text' name='caption_".$r[id]."' value='".$r[caption]."' class='input'>";
}
echo "<br /><br />Media <select name='media_".$r[id]."' class='input'><option value=''></option>";
$sqls = mysql_query("SELECT * FROM ".$pre."media ORDER BY `id` DESC");
while($row = mysql_fetch_array($sqls)) {
if ($row[id] == $r[media_id]) {
echo "<option value='".$row[id]."' selected>- ".stripslashes($row[name])." -</option>";
} else {
echo "<option value='".$row[id]."'> ".stripslashes($row[name])." </option>";
}
}
echo "</td></tr>";
}

echo "<tr><td><input type='submit' value='Update File' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "edit_file2") {
while (@list(, $i) = @each ($_POST['id'])) {
if ($_POST["delete_$i"]) {
@unlink($sitepath.$_POST["filedir_$i"].$_POST["file_$i"]);
@unlink($sitepath.$_POST["filedir_$i"]."thumbs/".$_POST["file_$i"]);
mysql_query("DELETE FROM ".$pre."files WHERE id = '".$i."'");
} else {
if ($_POST["oldfile_$i"] != $_POST["file_$i"]) {
rename($sitepath.$_POST["filedir_$i"].$_POST["oldfile_$i"], $sitepath.$_POST["filedir_$i"].$_POST["file_$i"]);
@rename($sitepath.$_POST["filedir_$i"]."thumbs/".$_POST["oldfile_$i"], $sitepath.$_POST["filedir_$i"]."thumbs/".$_POST["file_$i"]);
}
$query = mysql_query("UPDATE ".$pre."files SET filename = '".$_POST["file_$i"]."', caption = '".$_POST["caption_$i"]."', media_id = '".$_POST["media_$i"]."' WHERE id = '".$i."'");

if ($_POST["watermark_$i"] && stristr($_POST["filedir_$i"], 'http') === FALSE) {
$ex = explode(".", $_POST["file_$i"]);
$filet = $ex[0]."_wm.".$ex[1];
watermark($sitepath.$_POST["filedir_$i"].$_POST["file_$i"], $sitepath.$_POST["filedir_$i"]."watermark.png", $sitepath.$_POST["filedir_$i"].$_POST["file_$i"]);
}

if ($_POST["resize1_$i"] && $_POST["resize2_$i"]) {
if (!extension_loaded('gd') && !extension_loaded('gd2'))
    {
        trigger_error("GD is not loaded", E_USER_WARNING);
        return false;
    }

list($width,$height,$image_type)=getimagesize($_POST["filedir_$i"].$_POST["file_$i"]);

    switch ($image_type) {
        case 1: $src = imagecreatefromgif($_POST["filedir_$i"].$_POST["file_$i"]); break;
        case 2: $src = imagecreatefromjpeg($_POST["filedir_$i"].$_POST["file_$i"]);  break;
        case 3: $src = imagecreatefrompng($_POST["filedir_$i"].$_POST["file_$i"]); break;
        default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
    }

$max_width = $_POST["resize1_$i"];
$max_height = $_POST["resize2_$i"];

$x_ratio = $max_width / $width;
$y_ratio = $max_height / $height;

    $tn_width = $max_width;
    $tn_height = $max_height;

$tmp=imagecreatetruecolor($tn_width,$tn_height);

    if ($image_type == 1 or $image_type == 3) {
        imagealphablending($tmp, false);
        imagesavealpha($tmp,true);
        $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
        imagefilledrectangle($tmp, 0, 0, $tn_width, $tn_height, $transparent);
    }
imagecopyresampled($tmp,$src,0,0,0,0,$tn_width, $tn_height,$width,$height);

    switch ($image_type) {
        case 1: imagegif($tmp, $_POST["filedir_$i"].$_POST["file_$i"]); break;
        case 2: imagejpeg($tmp, $_POST["filedir_$i"].$_POST["file_$i"], 100);  break; // best quality
        case 3: imagepng($tmp, $_POST["filedir_$i"].$_POST["file_$i"], 0); break; // no compression
        default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
    }

imagedestroy($src);
imagedestroy($tmp);

createThumbs($sitepath.$_POST["filedir_$i"],$sitepath.$_POST["filedir_$i"]."thumbs/", $_POST["file_$i"]);
}
}
}

if ($query == TRUE) {
echo re_direct("1500", "admin.php?view=media");
echo "The file <b>".stripslashes($_POST['name'])."</b> has been updated. <a href='admin.php?view=media'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The file could not be updated. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}

if ($p[2]) {
if ($_GET['do'] == "delete") {
if (mysql_query("DELETE FROM ".$pre."media WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
echo re_direct("1500", "admin.php?view=media");
echo "The media has been deleted. <a href='admin.php?view=media'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The media could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}

if ($_GET['do'] == "delete_file") {
$r = mysql_fetch_row(mysql_query("SELECT filedir,filename FROM ".$pre."files WHERE id = '".$_GET['id']."'"));

if (@file_exists($sitepath.$r[0].$r[1])) {
@unlink($sitepath.$r[0].$r[1]);
@unlink($sitepath.$r[0]."thumbs/".$r[1]);
}

if (mysql_query("DELETE FROM ".$pre."files WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
echo re_direct("1500", "admin.php?view=media");
echo "The file has been deleted. <a href='admin.php?view=media'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The file could not be deleted. This error has been sent to the <b>AdaptCMS</b> support team and you will be contacted soon.";
}
}
}
?>