<?php
if ($_GET['username']) {
$_GET['username'] = str_replace("-", " ",check(strip_tags($_GET['username'])));
$check1 = sprintf("SELECT email,`group`,level,last_login,reg_date,id,status,status_time FROM ".$pre."users WHERE username = '%s'",
    mysql_real_escape_string($_GET['username']));
$r = mysql_fetch_row(mysql_query($check1));
if (!$r[0]) {
$smarty->display($skin.'/header.tpl');
echo $js_includes;
echo "Sorry, but this account does not exist. <a href='".$siteurl."'>Return to Homepage</a>";
die;
}
} else {

if ($_GET['do'] == "forgot_password" && !$_GET['go']) {
require_once('inc/recaptchalib.php');
$smarty->display($skin.'/header.tpl');

echo "<form action='".$pageurl."&go=1' method='post'><h2>Forgot Password</h2><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'>";

echo "<tr><td>Username</td><td><input type='text' name='username' class='input'></td></tr><tr><td>E-Mail</td><td><input type='text' name='email' class='input'></td></tr><tr><td></td><td>".recaptcha_get_html($publickey)."</td></tr>";

echo "<tr><td><input type='submit' value='Submit' class='input'></td></tr></table></form>";
}

if ($_GET['do'] == "forgot_password" && $_GET['go'] == 1 && valid_email($_POST['email'])) {
require_once('inc/recaptchalib.php');

$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
$smarty->display($skin.'/header.tpl');
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {

$check_user = sprintf("SELECT * FROM ".$pre."users WHERE username = '%s' AND email = '%s'",
    mysql_real_escape_string(check($_POST['username'])),
    mysql_real_escape_string(check($_POST['email'])));
if (mysql_num_rows(mysql_query($check_user)) == 0) {
$smarty->display($skin.'/header.tpl');
echo "Sorry, but we cannot find a match for that username and email you submitted. Go back and try again";
} else {
$newpass = generateSalt();
mysql_query("UPDATE ".$pre."users SET password = '".md5($salt.$_POST['username'].md5($newpass))."' WHERE username = '".mysql_real_escape_string(check($_POST['username']))."'");

$msg = "<html><head>
<title>Changed Password</title>
</head>
<body>
<h2>".$_POST['sitename']." - New Password</h2>

<p>Hello <b>".$_POST['username']."</b>, a forgot password form has been filled out with your e-mail and username. If you did not submit this, please alert the webmaster. Otherwise, proceed below.</p>

<p>Your new password is <b>".$newpass."</b> and can <a href='".url("login")."'>login here</a>. You can change this at ".get_user("", $_POST['username'])." after logging in with that password. Thank you.</p>

<p>Sincerely,<br />
- ".$setting['sitename']."
</p>
</body>
</html>";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
mail($_POST['email'], "New Password at ".$setting['sitename'], $msg, $headers);

$smarty->display($skin.'/header.tpl');
echo "A new password has been made for your user account, please check your email to find the new password and login. From there you can change your password after logging in.";
}
}
}


}

if (!$_GET['do'] && $_GET['username'] or !$_GET['do'] && !$_GET['username'] or $_GET['do'] == "messages" && $_GET['go'] != "send2" or $_GET['do'] == "friends" or $_GET['do'] == "status" or $_GET['do'] == "edit" or $_GET['do'] == "edit2" or $_GET['do'] == "report" && !$_GET['go'] or $_GET['do'] == "blogs") {
$smarty->display($skin.'/header.tpl');
echo $js_includes;

if (!$r[0]) {
$r[0] = $email;
$r[1] = $group;
$r[2] = $rank;
$r[3] = $last_login;
$r[4] = $reg_date;
$r[5] = $useridn;
$r[6] = $status;
$r[7] = $status_time;
$_GET['username'] = $_COOKIE[$cookiename."username"];
}

if ($r[0]) {
if ($_GET['username'] == $_COOKIE[$cookiename."username"] or !$useridn) {
$smarty->assign("username", $_GET['username']);
} elseif (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-friend' AND item_id = '".$r[5]."' OR field_type = 'social-friend' AND field_name = '".$r[5]."' OR field_type = 'social-friend-request' AND field_name = '".$r[5]."'")) == 0 && $useridn) {
$smarty->assign("username", $_GET['username']." <a href='".$siteurl."index.php?view=social&do=friends&go=add&id=".$r[5]."'><img src='".$siteurl."inc/images/add.png' border='0'></a>");
}
$smarty->assign("email", $r[0]);
$smarty->assign("group", stripslashes($r[1]));
$smarty->assign("level", stripslashes($r[2]));
$smarty->assign("last_login", date($setting['date_format'], $r[3]));
$smarty->assign("register_date", date($setting['date_format'], $r[4]));
if ($status) {
$smarty->assign("status", stripslashes($r[6]));
$smarty->assign("status_time", date($setting['date_format'], $r[7]));
}
if ($useridn == $r[5] && $_GET['go'] != "send" && $_GET['go'] != "reply" && $_GET['do'] != "report" && $_GET['go'] != "edit" && $_GET['go'] != "add") {
$smarty->assign("status_update", "<script src='".$siteurl."inc/js/status.js'></script>
<script src='".$siteurl."inc/js/jquery.MaxInput.js'></script>
<link rel='stylesheet' type='text/css' href='".$siteurl."inc/js/style_1.css' media='screen'/>

<div id='status_update' class='jmax'>
  <form name='contact' method='post' action=''>
    <fieldset>
      <label for='status' id='status_label'></label>
	  <textarea name='status' id='status' class='input' cols='35' rows='2'></textarea>
      <label class='error' for='status' id='status_error'></label>&nbsp;&nbsp;<input type='submit' name='submit' class='button' id='submit_btn' value='Send' />
    </fieldset>
  </form>
</div>

<script type='text/javascript'>
		$(function() {
			$('#status_update').maxinput({
				position	: 'topleft',
				showtext 	: true,
				limit		: ".$setting["status_char_limit"]."
			});
			});
		</script>");
}

// start - custom fields
$sql_cf = mysql_query("SELECT * FROM ".$pre."fields WHERE section = 'web-profile' OR section = ''");
while ($row1 = mysql_fetch_array($sql_cf)) {
$name = "$row1[name]";

$data = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_name = '".$name."' AND item_id = '".$r[5]."' AND field_type = 'custom-profile-data'"));
//$fdata[$name][$sid] = stripslashes(html_entity_decode($data[0]));

if ($data[0]) {

if (strlen($data[0]) < 100) {
if (check_domain($data[0])) {
$url = 1;
} else {
$url = 0;
}
}
$field_names[] = $name;
$smarty->assign($name, stripslashes(html_entity_decode($data[0])));
}
}
// end - custom fields

$smarty->assign("friends_url_req", url("friends"));
$smarty->assign("friends_url", url("friends", "", $_GET['username']));
$smarty->assign("profile_url", url("profile", "", $_GET['username']));
$smarty->assign("messages_url", url("messages"));
$smarty->assign("edit_profile_url", url("edit-profile"));
$smarty->assign("status_url", url("status", "", $_GET['username']));
$smarty->assign("blogs_url", url("blogs", "", $_GET['username']));
$smarty->assign("blogs_url_add", url("blogs-add"));

if (!$_GET['do'] or $_GET['do'] == "status") {
if (!$_GET['do'] == "status") {
if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["status_limit"]) - $setting["status_limit"]);

$sqlf = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-friend' AND item_id = '".$r[5]."' OR field_type = 'social-friend' AND field_name = '".$r[5]."' ORDER BY `id` DESC");
while($fr = mysql_fetch_array($sqlf)) {
if ($fr[field_name] == $r[5]) {
$user = $fr[item_id];
} else {
$user = $fr[field_name];
}
$friends .= " OR field_type = 'status-update' AND item_id = '".$user."'";
}
$stat = "field_type = 'status-update' AND item_id = '".$r[5]."'";
$end = "  ORDER BY `id` DESC";
} elseif ($_GET['do'] == "status" && $_GET['id']) {
$stat = " id = '".$_GET['id']."'";
} elseif ($_GET['do'] == "status" && !$_GET['id']) {
$stat = "field_type = 'status-update' AND item_id = '".$r[5]."'";
}
if ($stat) {
$start = "WHERE ";
} else {
$start = "WHERE 1 = 2 ";
}
if ($from) {
$froms = "$from,";
}

$sid = 0;
// what's the point of this? look into
if (1 == 2) {
$status_id[$sid] = 0;
$status_date[$sid] = "<a href='".url("status", 0)."'>".date($setting['date_format'], $r[7])."</a>";
$status_data[$sid] = stripslashes($r[6]);
$status_username[$sid] = get_user($r[5]);
$row2[item_id] = $r[5];

$co[] = $sid;
$sid++;
}

if (!$end) {
$end = " ORDER BY `id` DESC";
}

$sql2 = mysql_query("SELECT * FROM ".$pre."data ".$start.$stat.$friends.$end." LIMIT ".$froms.$setting["status_limit"]);
while($row2 = mysql_fetch_array($sql2)) {

$status_id[$sid] = $row2[id];
$status_date[$sid] = "<a href='".url("status", $row2[id])."'>".date($setting['date_format'], $row2[field_name])."</a>";
$status_data[$sid] = stripslashes($row2[data]);
$status_username[$sid] = get_user($row2[item_id]);

// start - custom fields
$sql_cf13 = mysql_query("SELECT * FROM ".$pre."fields WHERE section = 'web-profile' OR section = ''");
while ($row = mysql_fetch_array($sql_cf13)) {
$name = "$row[name]";

$data = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_name = '".$name."' AND item_id = '".$row2[item_id]."' AND field_type = 'custom-profile-data'"));
$fdata[$name][$sid] = $data[0];

if ($data[0]) {

if (strlen($data[0]) < 100) {
if (check_domain($data[0])) {
$url = 1;
} else {
$url = 0;
}
}

$cfields[] = $name;
$cfield[$name] = stripslashes(html_entity_decode($data[0]));
}
}
unset($url, $data, $sql_cf1, $row);
// end - custom fields

//}

$co[] = $sid;
$sid++;
}

$smarty->assign("statuses", $co);
$smarty->assign("status_id", $status_id);
$smarty->assign("status_date", $status_date);
$smarty->assign("status_data", $status_data);
$smarty->assign("status_username", $status_username);

while (@list(, $i) = @each ($cfields)) {
$smarty->assign("status_".$i, $fdata[$i]);
}
if (!$cfields) {
while (@list(, $i) = @each ($field_names)) {
$smarty->assign("status_".$i, $fdata1[$i]);
}
}
}

$avatar = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_type = 'custom-profile-data' AND field_name = 'avatar' AND item_id = '".$r[5]."'"));
$smarty->assign("avatar", $avatar[0]);

echo "<link rel='stylesheet' href='".$siteurl."inc/js/menu-bar.css' media='screen' type='text/css'>
	<script type='text/javascript' src='".$siteurl."inc/js/menu-bar.js'></script>";
$smarty->display($skin.'/social_header.tpl');
}

if ($_GET['do'] == "edit") {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to edit your own profile.";
} else {
echo "
		<script src='http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/effects.js' type='text/javascript'></script>
		<script type='text/javascript' src='".$siteurl."inc/js/fabtabulous.js'></script>
		<script type='text/javascript' src='".$siteurl."inc/js/validation.js'></script>";

$tmz_options = array('Kwajalein' => -12.00,
        'Pacific/Midway' => -11.00,
        'Pacific/Honolulu' => -10.00,
        'America/Anchorage' => -9.00,
        'America/Los_Angeles' => -8.00,
        'America/Denver' => -7.00,
        'America/Tegucigalpa' => -6.00,
        'America/New_York' => -5.00,
        'America/Caracas' => -4.30,
        'America/Halifax' => -4.00,
        'America/St_Johns' => -3.30,
        'America/Argentina/Buenos_Aires' => -3.00,
        'America/Sao_Paulo' => -3.00,
        'Atlantic/South_Georgia' => -2.00,
        'Atlantic/Azores' => -1.00,
        'Europe/Dublin' => 0,
        'Europe/Belgrade' => 1.00,
        'Europe/Minsk' => 2.00,
        'Asia/Kuwait' => 3.00,
        'Asia/Tehran' => 3.30,
        'Asia/Muscat' => 4.00,
        'Asia/Yekaterinburg' => 5.00,
        'Asia/Kolkata' => 5.30,
        'Asia/Katmandu' => 5.45,
        'Asia/Dhaka' => 6.00,
        'Asia/Rangoon' => 6.30,
        'Asia/Krasnoyarsk' => 7.00,
        'Asia/Brunei' => 8.00,
        'Asia/Seoul' => 9.00,
        'Australia/Darwin' => 9.30,
        'Australia/Canberra' => 10.00,
        'Asia/Magadan' => 11.00,
        'Pacific/Fiji' => 12.00,
        'Pacific/Tongatapu' => 13.00);
while (list($loc, $diff) = each ($tmz_options)) {
if ($diff > 0) {
$diff = "+".$diff;
}
if ($loc == $_COOKIE[$cookiename."timezone"]) {
$tmz .= "<option value='".$loc."' selected>-- (GMT ".$diff.") ".str_replace("_", " ",$loc)." --</option>";
} else {
$tmz .= "<option value='".$loc."'>(GMT ".$diff.") ".str_replace("_", " ",$loc)."</option>";
}
}
echo "<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js'></script>
<script type='text/javascript'>
$(document).ready(function(){
$('#preview').hide();	
$(\"#photo\").change(update);
$(\"#title\").keyup(update);
});
	
function update(){		
		
$('#preview').slideDown('slow');
var title = $(\"#title\").val();
var photo = $(\"#photo\").val();
$('#Displaytitle').html(title);
$('#image').html('<img src=\"'+photo+'\"/>');
}
</script>
<style>
#preview {
	min-height:247px;
	background-color:#FFC;
	padding:10px;
	font-size:12px;
	color:#999;
	border:1px solid #FF9;
}
#title {
	margin-top:10px;
	padding:5px;
	font-size:13px;
	color:#000;
	border:1px solid #CCC;
	font-family:Verdana, Geneva, sans-serif;
}
#photo {
	margin-bottom:10px;
}
#image {
	margin-top:5px;
}
#Displaytitle {
	font-size:14px;
	color:#333;
	margin-top:5px;
}
</style>";
$avat = "<select name='avatar' class='click' id='photo'>";
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-avatar' OR field_type = 'social-avatar-custom' AND item_id = '".$useridn."' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
if ($avatar[0] == $siteurl.$setting["upload_folder"]."avatar/".$r[data]) {
$avat .= "<option value='".$siteurl.$setting["upload_folder"]."avatar/".$r[data]."' selected>-- ".stripslashes($r[field_name])." --</option>";
} else {
$avat .= "<option value='".$siteurl.$setting["upload_folder"]."avatar/".$r[data]."'>".stripslashes($r[field_name])."</option>";
}
}
$avat .= "</select><br /><div id='image'></div>
      <div id='Displaytitle'></div><input type='file' name='file'>";

$smarty->assign("form_start", "<form action='".url("edit-profile-2")."' method='post' id='test' enctype='multipart/form-data'>");
if (strtolower($setting["profile_username_change"]) == "yes") {
$smarty->assign("username_input", "<input type='hidden' name='user_name' value='".mysql_real_escape_string(stripslashes($_COOKIE[$cookiename."username"]))."'><input type='text' id='username' name='username' class='input' size='16' value='".mysql_real_escape_string(stripslashes($_COOKIE[$cookiename."username"]))."'> <span id='msgbox' style='display:none'></span>");
} else {
$smarty->assign("username_input", mysql_real_escape_string(stripslashes($_COOKIE[$cookiename."username"])));
}
$smarty->assign("password_input", "<input type='password' id='password' name='password' class='input' size='16'>");
$smarty->assign("password_input2", "<input type='password' id='password2' name='password2' class='input' size='16'>");
$smarty->assign("email_input", "<input type='hidden' name='email_old' value='".$email."'><input type='text' name='email' class='input' size='16' value='".$email."'>");
$smarty->assign("skin_input", "<select name='change_skin' class='input'>".$options."</select>");
$smarty->assign("timezone_input", "<select name='timezone' class='input'>".$tmz."</select>");
$smarty->assign("avatar_select", $avat);

$cid = 0;
$sql = mysql_query("SELECT * FROM ".$pre."fields WHERE section = 'web-profile' OR section = 'global' ORDER BY `type` ASC");
while($i = mysql_fetch_array($sql)) {
$fetch = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_name = '".$i[name]."' AND field_type = 'custom-profile-data' AND item_id = '".$useridn."'"));
unset($data);
if ($i[limit] != "/") {
$data .= "<input type='hidden' name='cflimit_".$i[name]."' value='".$i[limit]."'>";
}
if ($i[required]) {
$data .= "<input type='hidden' name='cfrequired_".$i[name]."' value='yes'>";
}

$data .= "<input type='hidden' name='id[]' value='".$i[name]."'><input type='hidden' name='cfdata_".$i[name]."' value='".$fetch[0]."'>";

if ($i[type] == "textfield") {
$data .= "<input type='text' name='cf_".$i[name]."'";
if ($i[required]) {
$data .= " class='required'";
}
$data .= " size='16' class='input' value='".stripslashes($fetch[0])."'>";
}
if ($i[type] == "textarea") {
$data .= "<input type='hidden' name='cftype_".$i[name]."' value='textarea'><textarea cols='60' rows='16' id='cf_".$i[name]."' name='cf_".$i[name]."'";
if ($i[required]) {
$data .= " class='required'";
}
$data .= " class='input' class='mceSimple'>".stripslashes($fetch[0])."</textarea></td>";
}
if ($i[type] == "radio") {
$ex = explode(",", $i[data]);
sort($ex);
while (list(, $a) = each ($ex)) {
$data .= $a." <input type='radio' name='cf_".$i[name]."' value='".$a."'";
if ($i[required]) {
$data .= " class='validate-one-required'";
}
$data .= " class='input'";
if ($a == stripslashes($fetch[0])) {
$data .= " selected";
}
$data .= ">";
}
}
if ($i[type] == "checkbox") {
$ex = explode(",", $i[data]);
sort($ex);
while (list(, $a) = each ($ex)) {
$data .= $a." <input type='checkbox' name='cf_".$i[name]."' value='".$a."'";
if ($i[required]) {
$data .= " class='validate-one-required'";
}
$data .= " class='input'";
if ($a == stripslashes($fetch[0])) {
$data .= " selected";
}
$data .= ">";
}
}
if ($i[type] == "select") {
$data .= "<select name='cf_".$i[name]."'";
if ($i[required]) {
$data .= " class='validate-selection'";
}
$data .= " class='input'><option value=''>-- Select --</option>";
$ex = explode(",", $i[data]);
sort($ex);
while (list(, $a) = each ($ex)) {
if ($a == stripslashes($fetch[0])) {
$data .= "<option value='".stripslashes($fetch[0])."'>-- ".stripslashes($fetch[0])." --</option>";
} else {
$data .= "<option value='".$a."'>".ucwords($a)."</option>";
}
}
$data .= "</select>";
}
if ($i[type] == "file") {
$data .= "<select name='cf_".$i[name]."'";
if ($i[required]) {
$data .= " class='validate-selection'";
}
$data .= " class='input'><option value=''>-- Select --</option>";
$sqlfl = mysql_query("SELECT * FROM ".$pre."files ORDER BY `id` DESC");
while($row = mysql_fetch_array($sqlfl)) {
if (preg_match("/http/", $row[filedir])) {
$filedir = $row[filedir];
} else {
$filedir = $siteurl.$row[filedir];
}
if ($filedir.$row[filename] == stripslashes($fetch[0])) {
$data .= "<option value='".stripslashes($fetch[0])."'>-- ".stripslashes($fetch[0])." --</option>";
} else {
$data .= "<option value='".$filedir.$row[filename]."'>";
if (strlen($row[filename]) > 40) {
$data .= substr(urldecode($row[filename]), 0, 37)."...";
} else {
$data .= substr(urldecode($row[filename]), 0, 40);
}
$data .= "</option>";
}
}
$data .= "</select>";
}

$field_name[$cid] = str_replace("_", " ", ucwords($i[name]));
$field_input[$cid] = $data;
$field_info[$cid] = stripslashes($i[des]);

$co[] = $cid;
$cid++;
}

$smarty->assign("fields", $co);
$smarty->assign("field_name", $field_name);
$smarty->assign("field_input", $field_input);
$smarty->assign("field_info", $field_info);

$smarty->display($skin.'/edit_profile.tpl');

echo "<script type='text/javascript'>
						function formCallback(result, form) {
							window.status = \"valiation callback for form '\" + form.id + \"': result = \" + result;
						}
						
						var valid = new Validation('test', {immediate : true, useTitles:true, onFormValidate : formCallback});
					</script>";
}
}


if ($_GET['do'] == "edit2") {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to edit your own profile.";
} else {
$_POST['password'] = check($_POST['password']);
$_POST['password2'] = check($_POST['password2']);
$_POST['email'] = check($_POST['email']);
$_POST['timezone'] = check($_POST['timezone']);
unset($var);

if ($_POST['timezone'] && $_POST['timezone'] != $_COOKIE[$cookiename."timezone"]) {
setcookie($cookiename."timezone", strip_tags($_POST['timezone']), time()+60*60*24*30, "/");
}

if ($_POST['password'] && $_POST['password'] == $_POST['password2']) {
setcookie($cookiename."password", strip_tags(md5($_POST['password'])), time()+60*60*24*30, "/");
$var .= ", password = '".mysql_real_escape_string(md5($salt.$_COOKIE[$cookiename."username"].md5(stripslashes($_POST["password"]))))."'";
}

if ($_POST['email'] && $_POST['email'] != $_POST['old_email']) {
$var .= ", email = '".mysql_real_escape_string(stripslashes($_POST["email"]))."'";
}

if ($_POST['username'] && $_POST['username'] != $_POST['user_name']) {
$check_user = sprintf("SELECT * FROM ".$pre."users WHERE username = '%s'", mysql_real_escape_string(check($_POST['username'])));
if (mysql_num_rows(mysql_query($check_user)) == 0) {
setcookie($cookiename."username", strip_tags($_POST['username']), time()+60*60*24*30, "/");
$var .= ", username = '".mysql_real_escape_string(stripslashes($_POST["username"]))."'";
}
}

if ($_FILES["file"]["name"]) {
$filename = $_FILES["file"]["name"];
$ex = explode(".", $filename);

$a = 0;
$ext = explode(",", $setting["file_extensions"]);
while (list($k, $i) = each ($ext)) {
if ($i == $ex[1]) {
$a++;
}
}
if ($a) {
copy ($_FILES["file"]["tmp_name"], $sitepath.$setting["upload_folder"]."avatar/".$_FILES["file"]["name"]);
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$ex[0]."', 'social-avatar-custom', '".$filename."', '".$useridn."')");
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE item_id = '".$useridn."' AND field_name = 'avatar' AND field_type = 'custom-profile-data'")) == 0) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, 'avatar', 'custom-profile-data', '".$siteurl.$setting["upload_folder"]."avatar/".$filename."', '".$useridn."')");
} else {
mysql_query("UPDATE ".$pre."data SET data = '".$siteurl.$setting["upload_folder"]."avatar/".$filename."' WHERE item_id = '".$useridn."' AND field_name = 'avatar' AND field_type = 'custom-profile-data'");
}
}
} elseif ($_POST['avatar']) {
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE item_id = '".$useridn."' AND field_name = 'avatar' AND field_type = 'custom-profile-data'")) == 0) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, 'avatar', 'custom-profile-data', '".addslashes($_POST['avatar'])."', '".$useridn."')");
} else {
mysql_query("UPDATE ".$pre."data SET data = '".addslashes($_POST['avatar'])."' WHERE item_id = '".$useridn."' AND field_name = 'avatar' AND field_type = 'custom-profile-data'");
}
}

$query = mysql_query("UPDATE ".$pre."users SET last_login = '".time()."'".$var." WHERE id = '".$useridn."'");

while (@list(, $i) = @each ($_POST['id'])) {
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
$lim = "";
$lim = explode("/", $_POST["cflimit_$i"]);
if (strlen($text) < $lim[0] && strlen($text) > $lim[1] or !$lim[0] && !$lim[1]) {
if (!$_POST["cfdata_$i"]) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$i."', 'custom-profile-data', '".$text."', '".$useridn."')");
} elseif ($_POST["cfdata_$i"] != $text) {
mysql_query("UPDATE ".$pre."data SET data = '".$text."' WHERE field_name = '".$i."' AND field_type = 'custom-profile-data' AND item_id = '".$useridn."'");
}
} else {
$error = $error + 1;
}
}

}
}

if ($query == TRUE) {
echo re_direct("1500", url("profile", "", $_COOKIE[$cookiename."username"]));
echo "Your profile has been updated. <a href='".url("profile", "", $_COOKIE[$cookiename."username"])."'>View Profile</a>";
}
if ($pw) {
echo "<br /><br /><i>Your password has been changed!</i>";
}
}

}

if (!$_GET['do'] && $_GET['username'] or $_GET['do'] == "status") {
$smarty->display($skin.'/view_profile.tpl');
paginate($pre."data ".$start.$stat.$friends.$end, $siteurl."index.php?view=".$_GET['view']."&do=".$_GET['do']."&username=".$_GET['username']."&", $setting["status_limit"]);
}

if ($_GET['do'] == "messages") {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to view messages.";
} else {
if ($_GET['box']) {
$folder = check($_GET['box']);
} else {
$folder = "inbox";
}
if ($folder == "sent") {
$bla = "sender_id = '".$useridn."'";
} else {
$bla = "receiver_id = '".$useridn."' AND box = '".$folder."'";
}
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

$smarty->assign("folder", ucwords($folder));
$smarty->assign("messages_num", $numb);
$smarty->assign("messages_percent", $percent."%");
$smarty->assign("max_messages", $setting["message_limit"]);
$smarty->assign("folder_dropdown", $fol);
$smarty->assign("send_message", "<a href='".$siteurl."index.php?view=social&do=messages&go=send'>Send Message</a>");

if (!$_GET['go']) {
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
}
}

if ($_GET['go'] == "send") {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to send a message.";
} else {
echo wysiwyg();
require_once('inc/recaptchalib.php');
if ($_GET['group']) {
$opt = " AND `group` = '".check(mysql_real_escape_string($_GET['group']))."'";
}

$sql = mysql_query("SELECT * FROM ".$pre."users WHERE act = 'yes' AND ver = 'yes'".$opt);
while($r = mysql_fetch_array($sql)) {
if ($r[username] != $_COOKIE[$cookiename."username"]) {
if ($_GET['user_id'] == $r[id]) {
$to_who .= "<option value='".$r[id]."' selected>- ".$r[username]." -</option>";
} else {
$to_who .= "<option value='".$r[id]."'>".$r[username]."</option>";
}
}
}

$smarty->assign("form_start", "<form action='".$pageurl."2' method='post'>");
$smarty->assign("form_end", "</form>");
$smarty->assign("receivers_input", "<select name='receivers[]' class='input' size='5' multiple>".$to_who."</select>");
$smarty->assign("subject_input", "<input type='text' name='subject' size='35' class='input'>");
$smarty->assign("message_input", "<textarea id='message' name='message' cols='60%' rows='15%' class='input'></textarea>");
$smarty->assign("captcha_input", recaptcha_get_html($publickey));
$smarty->assign("submit", "<input type='submit' value='Send Message' class='input'>");

$smarty->display($skin.'/message_send.tpl');
}
}

if ($_GET['go'] == "send2") {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to send a message.";
} else {
require_once('inc/recaptchalib.php');

$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
$smarty->display($skin.'/header.tpl');
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
$smarty->display($skin.'/header.tpl');
echo "<table cellpadding='5' cellspacing='0' border='0' width='100%' align='center' style='border: 2px solid #dddddd'><tr><td><font size='4'><b>Send Message</b></font></td><td> </td></tr><tr><td>";
if (!$_POST['subject'] or !$_POST['message'] or !$_POST['receivers']) {
echo "Sorry but you have not filled out all the fields. Please go back.";
} else {
$autobr = preg_replace("/<br>\n/","\n",addslashes($_POST["message"]));
$autobr = preg_replace("/<br \/>\n/","\n",addslashes($_POST["message"]));
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br />\n",addslashes($_POST["message"]));

$count = 0;
while (list(, $i) = each ($_POST['receivers'])) {
if (mysql_query("INSERT INTO ".$pre."messages VALUES (null, 1, '".check(html_entity_decode(addslashes($_POST["subject"])))."', '".check($autobr)."', '".$useridn."', '".$i."', '".time()."', 'inbox')") == TRUE) {
$count = $count + 1;
}
}
}

if ($count > 0) {
echo re_direct("1500", $siteurl."index.php?view=social&do=messages");
echo "The message has been sent. <a href='".$siteurl."index.php?view=social&do=messages'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The message could not be sent. This error has been sent to <b>".$setting['sitename']."</b> and will be fixed shortly. <a href='".$siteurl."index.php?view=social&do=messages'>Return</a>";
}
}
echo "</td></tr></table>";
}
}

if ($_GET['go'] == "message" && is_numeric($_GET['id'])) {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to view a message.";
} else {
echo "<style type='text/css'>.quote {
	line-height: 125%;
	background-color: white; border: #dddddd; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px
}
.quote1 {
	line-height: 125%;
	background-color: #ebebeb; border: #dddddd; border-style: solid;
	border-left-width: 1px; border-top-width: 1px; border-bottom-width: 0px; border-right-width: 1px;
}</style>";

$sql = mysql_query("SELECT * FROM ".$pre."messages WHERE id = '".$_GET['id']."' AND receiver_id = '".$useridn."' OR id = '".$_GET['id']."' AND sender_id = '".$useridn."'");
while($r = mysql_fetch_array($sql)) {
if ($r[receiver_id] == $useridn && $r[viewed] == 1) {
mysql_query("UPDATE ".$pre."messages SET viewed = '0' WHERE id = '".$_GET['id']."'");
}

$poster = mysql_fetch_row(mysql_query("SELECT email,username,reg_date FROM ".$pre."users WHERE id = '".$r[sender_id]."'"));

$rank = mysql_fetch_row(mysql_query("SELECT level FROM ".$pre."users WHERE id = '".$r[sender_id]."'"));
$rank_img = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."levels WHERE name = '".$rank[0]."'"));
$rank_image = "<img src='".$rank_img[0]."'>";

$min = 901;
$time1 = time() - $min;
$time2 = time() + 1;
$poster_stat = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE user_id = '".$r[sender_id]."' AND time_last_visit < ".$time2." AND time_last_visit > ".$time1));
if ($poster_stat == 0) {
$poster_status = "Offline";
} else {
$poster_status = "Online";
}

if ($r[viewed] == 1) {
$icon = "<img src='{$siteurl}templates/".$skin."/images/mail_small_new.png' title='New Message'>";
} else {
$icon = "<img src='{$siteurl}templates/".$skin."/images/mail_small_read.png' title='Read Message'>";
}

$smarty->assign("icon", $icon);
$smarty->assign("date", date($setting['date_format'], $r[date]));
if ($r[viewed] == 1) {
$smarty->assign("subject", "<i>".stripslashes($r[subject])."</i>");
} else {
$smarty->assign("subject", stripslashes($r[subject]));
}
$smarty->assign("reply", "<a href='".$siteurl."index.php?view=social&do=messages&go=reply&id=".$r[id]."'>Reply</a>");
$smarty->assign("forward", "<a href='".$siteurl."index.php?view=social&do=messages&go=forward&id=".$r[id]."'>FW</a>");
if ($r[sender_id] != $useridn) {
$smarty->assign("delete", "<a href='".$siteurl."index.php?view=social&do=messages&go=delete&id=".$r[id]."' onclick='return confirm(\"Are you sure you wish to delete this entry?\")'>Del</a>");
}
$smarty->assign("message", str_replace("</blockquote>","</blockquote><br />",str_replace("</p>","</p><br /><br />",parse_text($r[message]))));

$smarty->assign("poster_username", get_user($r[sender_id], ""));
$smarty->assign("poster_email", $poster[0]);
$smarty->assign("poster_join_date", date("M Y", $poster[2]));
$smarty->assign("poster_message", "<a href='".$siteurl."index.php?view=social&do=messages&go=send&user_id=".$r[sender_id]."'>Message</a>");
$smarty->assign("poster_status", $poster_status);
$smarty->assign("rank_image", $rank_image);

// start - custom fields
$sql_cf2 = mysql_query("SELECT * FROM ".$pre."fields WHERE section = 'web-profile' OR section = ''");
while ($row = mysql_fetch_array($sql_cf2)) {
$name = "$row[name]";

$data = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_name = '".$name."' AND item_id = '".$r[sender_id]."' AND field_type = 'custom-profile-data'"));
$fdata[$name] = $data[0];

if ($data[0]) {

if (strlen($data[0]) < 100) {
if (check_domain($data[0])) {
$url = 1;
} else {
$url = 0;
}
}

$smarty->assign("poster_".$name, stripslashes(html_entity_decode($data[0])));
}
}
// end - custom fields

$smarty->display($skin.'/message_view.tpl');
}
}
}

if ($_GET['go'] == "reply" && is_numeric($_GET['id'])) {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to reply to a message.";
} else {
echo wysiwyg();
$r = mysql_fetch_row(mysql_query("SELECT subject,message,sender_id,receiver_id FROM ".$pre."messages WHERE id = '".$_GET['id']."' AND receiver_id = '".$useridn."' OR id = '".$_GET['id']."' AND sender_id = '".$useridn."'"));

$sql = mysql_query("SELECT * FROM ".$pre."users WHERE act = 'yes' AND ver = 'yes'");
while($row = mysql_fetch_array($sql)) {
if ($row[id] != $useridn) {
$to_who .= "<option value='".$row[id]."'";
if ($row[id] == $r[3] or $row[id] == $r[2]) {
$to_who .= " selected";
}
$to_who .= ">".$row[username]."</option>";
}
}

$message = str_replace("<blockquote>","[quote]",str_replace("</blockquote>","[/quote]",stripslashes($r[1])));

$smarty->assign("form_start", "<form action='".$siteurl."index.php?view=social&do=messages&go=send2' method='post'>");
$smarty->assign("form_end", "</form>");
$smarty->assign("receivers_input", "<select name='receivers[]' class='input' size='5' multiple>".$to_who."</select>");
$smarty->assign("subject_input", "<input type='text' name='subject' value=\"Re: ".str_replace("Re: ","",htmlspecialchars(stripslashes($r[0])))."\" size='35' class='input'>");
$smarty->assign("message_input", "<textarea id='message' name='message' cols='60%' rows='15%' class='input'>[quote]".$message."[/quote]<br /><br /><br /></textarea>");
$smarty->assign("submit", "<input type='submit' value='Reply to Message' class='input'>");

$smarty->display($skin.'/message_send.tpl');
}
}

if ($_GET['go'] == "forward") {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to reply to a message.";
} else {
echo wysiwyg();
$r = mysql_fetch_row(mysql_query("SELECT subject,message FROM ".$pre."messages WHERE id = '".$_GET['id']."' AND receiver_id = '".$useridn."' OR id = '".$_GET['id']."' AND sender_id = '".$useridn."'"));

$message = str_replace("<blockquote>","[quote]",str_replace("</blockquote>","[/quote]",stripslashes($r[1])));

$sql = mysql_query("SELECT * FROM ".$preu."users WHERE act = 'yes' AND ver = 'yes'");
while($row = mysql_fetch_array($sql)) {
if ($row[username] != $_COOKIE[$cookiename."username"]) {
$to_who .= "<option value='".$row[id]."'>".$row[username]."</option>";
}
}

$smarty->assign("form_start", "<form action='".$siteurl."index.php?view=social&do=messages&go=send2' method='post'>");
$smarty->assign("form_end", "</form>");
$smarty->assign("receivers_input", "<select name='receivers[]' class='input' size='5' multiple>".$to_who."</select>");
$smarty->assign("subject_input", "<input type='text' name='subject' value=\"Re: ".str_replace("Re: ","",htmlspecialchars(stripslashes($r[0])))."\" size='35' class='input'>");
$smarty->assign("message_input", "<textarea id='message' name='message' cols='60%' rows='15%' class='input'>[quote]".$message."[/quote]<br /><br /><br /></textarea>");
$smarty->assign("submit", "<input type='submit' value='Forward Message' class='input'>");

$smarty->display($skin.'/message_send.tpl');
}
}

if ($_GET['go'] == "delete" && is_numeric($_GET['id'])) {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to delete a message.";
} else {
echo "<table cellpadding='5' cellspacing='0' border='0' width='100%' align='center' style='border: 2px solid #dddddd'><tr><td><font size='4'><b>Delete Message</b></font></td><td> </td></tr><tr><td>";
$r = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."messages WHERE id = '".$_GET['id']."' AND receiver_id = '".$useridn."'"));
if ($r == 1) {
if (mysql_query("DELETE FROM ".$pre."messages WHERE id = '".addslashes($_GET["id"])."'") == TRUE) {
mysql_query("ALTER TABLE ".$pre."messages AUTO_INCREMENT =".$_GET['id']);
echo re_direct("1500", $siteurl."index.php?view=social&do=messages");
echo "The message has been deleted. <a href='".$siteurl."index.php?view=social&do=messages'>Return</a>";
} else {
echo reporterror($pageurl, mysql_error(mysql_connect($dbhost, $dbuser, $dbpass)), $domain);
echo "The message could not be deleted. This error has been sent to <b>".$setting['sitename']."</b> and will be fixed shortly. <a href='".$siteurl."index.php?view=social&do=messages'>Return</a>";
}
}
echo "</td></tr></table>";
}
}
}

if ($_GET['do'] == "blogs" && !$_GET['go'] && !$_GET['id']) {
$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = 'content'"));
$p = explode("|", $ps[0]);
echo "<title>".$setting['sitename']." - Blogs from ".$_GET['username']."</title>";

if (!$setting["blog_limit"]) {
$setting["blog_limit"] = 10;
}
$i = 0;
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-blog' AND item_id = '".$r[5]."' ORDER BY `id` DESC LIMIT ".$setting["blog_limit"]);
while($row = mysql_fetch_array($sql)) {
$i++;
$ex = explode("|||||", $row[data]);
$smarty->assign("title", stripslashes($ex[0]));
if ($p[1] or $row[item_id] == $useridn) {
$other1 = "&nbsp;&nbsp;<a href='".$siteurl."index.php?view=social&do=blogs&go=edit&id=".$row[id]."'><img src='".$siteurl."inc/images/edit.png' border='0'></a>";
}
if ($p[2] or $row[item_id] == $useridn) {
$other2 = "&nbsp;&nbsp;<a href='".$siteurl."index.php?view=social&do=blogs&go=delete&id=".$row[id]."'><img src='".$siteurl."inc/images/delete.png' border='0' onclick='return confirm(\"Are you sure you wish to delete this entry?\")'></a>";
}
$smarty->assign("link", "<a href='".url("blogs", "", $row[id], $ex[0])."'>".stripslashes($ex[0])."</a>".$other1.$other2);
$smarty->assign("blog", stripslashes($ex[1]));
$smarty->assign("date", date($setting['date_format'], $row[field_name]));
$smarty->assign("username", get_user($row[item_id]));

$smarty->display($skin.'/social_blogs_list.tpl');
unset($other1, $other2);
}
if ($i == 0) {
echo "Sorry, but there are currently no blogs for <b>".$_GET['username']."</b>.";
}
}

if ($_GET['do'] == "blogs" && !$_GET['go'] && is_numeric($_GET['id'])) {
$sql = mysql_query("SELECT * FROM ".$pre."data WHERE id = '".$_GET['id']."'");
while($row = mysql_fetch_array($sql)) {
$ex = explode("|||||", $row[data]);
$smarty->assign("title", stripslashes($ex[0]));
$smarty->assign("link", "<a href='".url("blogs", "", $row[id], $ex[0])."'>".stripslashes($ex[0])."</a>");
$smarty->assign("blog", stripslashes($ex[1]));
$smarty->assign("date", date($setting['date_format'], $row[field_name]));
$smarty->assign("username", get_user($row[item_id]));

$cid = 0;
//id,user_id,comment,rating,author,email,website,date
$comm = mysql_query("SELECT * FROM ".$pre."comments WHERE article_id = 'blog-".$row[id]."'");
while($com = mysql_fetch_array($comm)) {
unset($crate, $cform);
$cmid = $com[id];

$ex1 = @explode("|", $com[rating]);
$com_rat = round($ex1[1]);

if ($com_rat == 0) {
$crate .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($com_rat == 1 or $com_rat == 0) {
$crate .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($com_rat == 1 or $com_rat == 2 or $com_rat == 0) {
$crate .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($com_rat == 1 or $com_rat == 2 or $com_rat == 3 or $com_rat == 0) {
$crate .= "<img src='".$siteurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$siteurl."images/star_on.gif'></img>";
}
if ($com_rat == 5) {
$crate .= "<img src='".$siteurl."images/star_on.gif'></img>";
} else {
$crate .= "<img src='".$siteurl."images/star_off.gif'></img>";
}

if (!$useridn && strtolower($setting["ratings_guests_comment"]) == "yes" && !$_SESSION["rating_comments_$cmid"] or $useridn && !$_SESSION["rating_comments_$cmid"]) {
$cform = "<script>
function rate".$cmid."( value ) {
	new Ajax.Updater( 'rating_".$cmid."', '".$siteurl."inc/rating.php?id=".$cmid."&rat_num=".$ex1[0]."&rat_tot=".$ex1[1]."&type=comments&v='+value );
}
</script>

<div id='rating_".$com[id]."'>
<img src='".$siteurl."images/star_off.gif' onclick='rate".$cmid."(1)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate".$cmid."(2)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate".$cmid."(3)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate".$cmid."(4)'></img>
<img src='".$siteurl."images/star_off.gif' onclick='rate".$cmid."(5)'></img>
</div>
<br/>";
} else {
$cform = "<i>Sorry, you cannot rate this item again</i>";
}

$comments_id[$cid] = $com[id];
$comments_userid[$cid] = get_user($com[user_id]);
if ($com[status]) {
$com[comment] = "<i>Comment has been flagged</i> <a href='#comment".$com[id]."' onclick='var myBox=document.getElementById(\"comment".$com[id]."\");myBox.style.display=(myBox.style.display==\"none\" ? \"block\": \"none\");return false;'>Show/Hide</a><div id='comment".$com[id]."' style='display:none'>".$com[comment]."</div>";
}
if ($useridn) {
if ($p[1] && !$com[status] or !$com[status] && $r[user_id] == $useridn) {
$comments_comment[$cid] = "<a name='comment".$com[id]."'></a>".badwords(stripslashes($com[comment]))."<br /><br /><a href='".$siteurl.$buildurl."hide_comment=".$com[id]."&content_id=".$r[id]."'>Lock Comment</a>";
} elseif ($p[1] or $r[user_id] == $useridn) {
$comments_comment[$cid] = "<a name='comment".$com[id]."'></a>".badwords(stripslashes($com[comment]))."<br /><br /><a href='".$siteurl.$buildurl."hide_comment=".$com[id]."&content_id=".$r[id]."&show=1'>Unlock Comment</a>";
} else {
$comments_comment[$cid] = "<a name='comment".$com[id]."'></a>".badwords(stripslashes($com[comment]))."<br /><br /><a href='".$siteurl."?view=social&do=report&report_comment=".$com[id]."'>Report Comment</a>";
}
} else {
$comments_comment[$cid] = "<a name='comment".$com[id]."'></a>".badwords(stripslashes($com[comment]));
}
$comments_rating[$cid] = $crate;
$comments_rating_form[$cid] = $cform;
$comments_email[$cid] = $com[email];
$comments_website[$cid] = "<a href='".$com[website]."'>".$com[website]."</a>";
$comments_date[$cid] = timef($com[date]);

$co[] = $cid;
$cid++;
}
$smarty->assign("comments", $co);
$smarty->assign("comments_id", $comments_id);
$smarty->assign("comments_username", $comments_userid);
$smarty->assign("comments_comment", $comments_comment);
$smarty->assign("comments_rating", $comments_rating);
$smarty->assign("comments_rating_form", $comments_rating_form);
$smarty->assign("comments_email", $comments_email);
$smarty->assign("comments_website", $comments_website);
$smarty->assign("comments_date", $comments_date);

if (!perm("comments", "add")) {
$smarty->assign("comments_form", "Sorry but you cannot post a comment, you do not have the necessary permissions to.");
} else {
$diff = time() - $_SESSION['last_comment_time'];
if ($_SESSION['last_comment_time'] && $diff < $settiong["comment_flood_limit"] or !$_SESSION['last_comment_time']) {
if (strtolower($setting["captcha_comments"]) == "yes") {
require_once('inc/recaptchalib.php');
$smarty->assign("comments_form", "<a name='comments'></a><form id='cform'><input type='hidden' name='url' value='".$pageurl."' class='input'><input type='text' name='email' class='input' id='email' value='".$email."' /> &nbsp;email<br /><br /><input name='website' type='text' value='http://' class='input' /> &nbsp;website<br /><br /><textarea name='comment' class='input' id='comment' cols='40' rows='10'></textarea><br /><br />".recaptcha_get_html($publickey)."<input type='hidden' name='article_id' value='blog-".$r[id]."' /><input type='button' onclick='addcomment()' value='Post Comment' /></form>

<script>
function addcomment()
{
  new Ajax.Updater('comments', '".$siteurl."inc/comments.php',
	{
		method: 'post',
		parameters: $('cform').serialize(),
		onSuccess: function() {
			$('comment').value = '';
		}
	} );
}
</script>");
} else {
$smarty->assign("comments_form", "<a name='comments'></a><form id='cform'><input type='hidden' name='url' value='".$pageurl."' class='input'><input type='text' name='email' class='input' value='".$email."' /> &nbsp;email<br /><br /><input name='website' type='text' value='http://' class='input' /> &nbsp;website<br /><br /><textarea name='comment' class='input' id='comment' cols='40' rows='10'></textarea><br /><br /><input type='hidden' name='article_id' value='blog-".$r[id]."' /><input type='button' onclick='addcomment()' value='Post Comment' /></form>

<script>
function addcomment()
{
  new Ajax.Updater('comments', '".$siteurl."inc/comments.php',
	{
		method: 'post',
		parameters: $('cform').serialize(),
		onSuccess: function() {
			$('comment').value = '';
		}
	} );
}
</script>");
}

} else {
$smarty->assign("comments_form", "You have reached the comment flood limit of ".$_SESSION['last_comment_time']." seconds. Please wait ".$diff." more seconds to comment again");
}
}

$smarty->display($skin.'/social_blogs_view.tpl');
}
}

if ($_GET['do'] == "blogs" && $_GET['go'] == "delete" && is_numeric($_GET['id'])) {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to delete a blog.";
} else {
$row = mysql_fetch_row(mysql_query("SELECT item_id FROM ".$pre."data WHERE id = '".$_GET['id']."'"));

$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = 'content'"));
$p = explode("|", $ps[0]);

if ($useridn == $row[0] or $p[2]) {

echo "<center>Your blog has been deleted - <a href='".url("blogs", "", $_GET['username'])."'>Return</a></center>";
}
}

if ($_GET['do'] == "blogs" && $_GET['go'] == "edit" && is_numeric($_GET['id'])) {
$row = mysql_fetch_row(mysql_query("SELECT data,item_id FROM ".$pre."data WHERE id = '".$_GET['id']."'"));
$ex = explode("|||||", $row[0]);

$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = 'content'"));
$p = explode("|", $ps[0]);



if ($p[1] or $useridn == $row[1]) {
require_once('inc/recaptchalib.php');
//echo wysiwyg();

$smarty->assign("form_start", "<form action='".$siteurl."index.php?view=social&do=blogs&go=edit2&id=".$_GET['id']."' method='post'>");
$smarty->assign("title_input", "<input type='text' name='title' size='15' class='addtitle' value=\"".stripslashes($ex[0])."\">");
$smarty->assign("blog_input", "<textarea id='blog' name='blog' cols='60%' rows='15%' class='textarea'>".stripslashes($ex[1])."</textarea>");
$smarty->assign("captcha", recaptcha_get_html($publickey));
$smarty->assign("submit_button", "<input type='submit' value='Update' class='addContent-button'>");

$smarty->display($skin.'/social_blogs_add.tpl');
}
}
}

if ($_GET['do'] == "blogs" && $_GET['go'] == "edit2" && is_numeric($_GET['id'])) {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to edit a blog.";
} else {
require_once('inc/recaptchalib.php');
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} elseif (!$_POST['title'] or !$_POST['blog']) {
echo "Sorry but you must fill in both the 'title' and 'blog' fields. Thank you.";
} else {
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE item_id = '".$useridn."' AND id = '".$_GET['id']."'")) == 1) {
mysql_query("UPDATE ".$pre."data SET data = '".addslashes($_POST['title'])."|||||".addslashes($_POST['blog'])."', field_name = '".time()."' WHERE id = '".$_GET['id']."'");

echo "<center>Your blog has been updated - <a href='".url("blogs", "", $_GET['username'])."'>Return</a></center>";
}
}
}
}

if ($_GET['do'] == "blogs" && $_GET['go'] == "add") {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to add a blog.";
} else {
require_once('inc/recaptchalib.php');
//echo wysiwyg();
$smarty->assign("form_start", "<form action='".$siteurl."index.php?view=social&do=blogs&go=add2' method='post' id='test'>");
$smarty->assign("title_input", "<input type='text' name='title' size='15' class='addtitle'>");
$smarty->assign("blog_input", "<textarea id='blog' name='blog' cols='1' rows='10' class='textarea'></textarea>");
$smarty->assign("captcha", recaptcha_get_html($publickey));
$smarty->assign("submit_button", "<input type='submit' value='Add' class='addContent-button'>");

$smarty->display($skin.'/social_blogs_add.tpl');
}
}

if ($_GET['do'] == "blogs" && $_GET['go'] == "add2") {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to add a blog.";
} else {
require_once('inc/recaptchalib.php');
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} elseif (!$_POST['title'] or !$_POST['blog']) {
echo "Sorry but you must fill in both the 'title' and 'blog' fields. Thank you.";
} else {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".time()."', 'social-blog', '".addslashes(htmlentities($_POST['title']))."|||||".addslashes($_POST['blog'])."', '".$useridn."')");

echo "<center>Your blog has been added - <a href='".url("blogs", "", $_GET['username'])."'>Return</a></center>";
}
}
}

if ($_GET['do'] == "friends" && $_GET['go'] == "req" && is_numeric($_GET['id'])) {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to manage a friends list.";
} else {
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-friend-request' AND field_name = '".$r[5]."'")) == 1) {
echo "<center>Are you sure you want to become friends with ".get_user($_GET['id'])."? <a href='".$siteurl."index.php?view=social&do=friends&go=req2&id=".$_GET['id']."'><b>Yes</b></a></center>";
} elseif (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-friend-request' AND item_id = '".$r[5]."'")) == 1) {
echo "<center>Are you sure you want to become friends with ".get_user($_GET['id'])."? <a href='".$siteurl."index.php?view=social&do=friends&go=req2&id=".$_GET['id']."'><b>Yes</b></a></center>";
}
}
}

if ($_GET['do'] == "friends" && $_GET['go'] == "req2" && is_numeric($_GET['id'])) {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to manage a friends list.";
} else {
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-friend-request' AND field_name = '".$r[5]."' OR field_type = 'social-friend-request' AND item_id = '".$r[5]."'")) == 1) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$_GET['id']."', 'social-friend', '".time()."', '".$useridn."')");
mysql_query("DELETE FROM ".$pre."data WHERE field_type = 'social-friend-request' AND field_name = '".$r[5]."' OR field_type = 'social-friend-request' AND item_id = '".$r[5]."'");

echo "<center>You are now friends with ".get_user($_GET['id'])."! <a href='".url("profile", $user_name)."'>Return</a></center>";
}
}
}

if ($_GET['do'] == "friends" && $_GET['go'] == "add" && is_numeric($_GET['id'])) {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to manage a friends list.";
} else {
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-friend-request' AND field_name = '".$_GET['id']."' OR field_type = 'social-friend-request' AND item_id = '".$_GET['id']."'")) == 0) {
echo "<center>Are you sure you want to become friends with ".get_user($_GET['id'])."? <a href='".$siteurl."index.php?view=social&do=friends&go=add2&id=".$_GET['id']."'><b>Yes</b></a></center>";
}
}
}

if ($_GET['do'] == "friends" && $_GET['go'] == "add2" && is_numeric($_GET['id'])) {
if (!$useridn) {
echo "Please <a href='".url("login")."'>Login</a> or <a href='".url("register")."'>Register</a> in order to manage a friends list.";
} else {
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-friend-request' AND field_name = '".$_GET['id']."' OR field_type = 'social-friend-request' AND item_id = '".$_GET['id']."'")) == 0) {
mysql_query("INSERT INTO ".$pre."data VALUES (null, '".$_GET['id']."', 'social-friend-request', '".time()."', '".$useridn."')");

echo "<center>Your friend request has been submitted. <a href='".url("profile", $user_name)."'>Return</a></center>";
}
}
}

if ($_GET['do'] == "friends" && !$_GET['go']) {
$i = 0;

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}

$from = (($page * $setting["status_limit"]) - $setting["status_limit"]);

$sql = mysql_query("SELECT * FROM ".$pre."data WHERE field_type = 'social-friend' AND item_id = '".$r[5]."' OR field_type = 'social-friend' AND field_name = '".$r[5]."' OR field_type = 'social-friend-request' AND field_name = '".$r[5]."' ORDER BY `id` DESC LIMIT $from, ".$setting["status_limit"]);
while($row3 = mysql_fetch_array($sql)) {
if ($row3[item_id] == $r[5]) {
$id = $row3[field_name];
} else {
$id = $row3[item_id];
}

$user = mysql_fetch_row(mysql_query("SELECT username,email,`group`,level,last_login,reg_date,status FROM ".$pre."users WHERE id = '".$id."'"));
if ($row3[field_type] == "social-friend-request") {
$smarty->assign("friend_username", get_user($id)." (<a href='".$siteurl."index.php?view=social&do=friends&go=req&id=".$row3[item_id]."'>Pending</a>)");
} else {
$smarty->assign("friend_username", get_user($id));
}
$smarty->assign("friend_email", $user[1]);
$smarty->assign("friend_group", stripslashes($user[2]));
$smarty->assign("friend_level", stripslashes($user[3]));
$smarty->assign("friend_last_login", date($setting['date_format'], $user[4]));
$smarty->assign("friend_register_date", date($setting['date_format'], $user[5]));
$smarty->assign("friend_status", stripslashes($user[6]));

// start - custom fields
$sql_cf = mysql_query("SELECT * FROM ".$pre."fields WHERE section = 'web-profile' OR section = ''");
while ($row = mysql_fetch_array($sql_cf)) {
$name = "$row[name]";

$data = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."data WHERE field_name = '".$name."' AND item_id = '".$id."' AND field_type = 'custom-profile-data'"));
$fdata[$name] = $data[0];

if ($data[0]) {

if (strlen($data[0]) < 100) {
if (check_domain($data[0])) {
$url = 1;
} else {
$url = 0;
}
}

$smarty->assign("friend_".$name, stripslashes(html_entity_decode($data[0])));
}
}
// end - custom fields
$smarty->assign("i", $i);
$i++;
$smarty->display($skin.'/social_friends.tpl');
paginate($pre."data WHERE field_type = 'social-friend' AND item_id = '".$r[5]."' OR field_type = 'social-friend' AND field_name = '".$r[5]."' OR field_type = 'social-friend-request' AND field_name = '".$r[5]."'", $siteurl."index.php?view=".$_GET['view']."&do=".$_GET['do']."&username=".$_GET['username']."&", $setting["status_limit"]);
}

}

if ($_GET['do'] == "report" && !$_GET['go'] && is_numeric($_GET['report_comment']) && $useridn) {
require_once('inc/recaptchalib.php');
$r = mysql_fetch_row(mysql_query("SELECT comment,article_id,user_id FROM ".$pre."comments WHERE id = '".$_GET['report_comment']."'"));
echo "<form action='".$pageurl."&go=1' method='post'><input type='hidden' name='content_id' value='".$r[1]."'><input type='hidden' name='comment' value='".$r[0]."'><input type='hidden' name='user_id' value='".$r[2]."'><h2>Report Comment</h2><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center' style='border: 2px solid #dddddd'>";

echo "<tr><td>Reporting Comment</td><td>".stripslashes($r[0])."</td></tr><tr><td>Reason</td><td><input type='text' name='subject' class='input'></td></tr><tr><td>Explanation</td><td><textarea name='message' cols='35' rows='10' class='input'></textarea></td></tr><tr><td></td><td>".recaptcha_get_html($publickey)."</td></tr>";

echo "<tr><td><input type='submit' value='Report Comment' class='input'></td></tr></table></form>";
} elseif ($_GET['go'] == 1 && $_POST['subject'] && $_POST['message']) {
require_once('inc/recaptchalib.php');

$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
$smarty->display($skin.'/header.tpl');
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
$smarty->display($skin.'/header.tpl');
$data = mysql_fetch_row(mysql_query("SELECT name,section FROM ".$pre."content WHERE id = '".$_POST['content_id']."'"));

$sql1 = mysql_query("SELECT * FROM ".$pre."permissions WHERE name = '".$data[1]."' AND data = '1|1|1' OR name = '".$data[1]."' AND data = '1|1|'");
while($row1 = mysql_fetch_array($sql1)) {

$sql2 = mysql_query("SELECT * FROM ".$pre."users WHERE `group` = '".$row1[group]."'");
while($row2 = mysql_fetch_array($sql2)) {
mysql_query("INSERT INTO ".$pre."messages VALUES (null, 1, 'Reported Comment', 'There has been a comment reported at the article <a href=\'".url("content", $_POST['content_id'], $data[0], $data[1])."#comment".$_GET['report_comment']."\'>".$data[0]."</a>, posted by ".addslashes(get_user($_POST['user_id'])).". Here is the explanation below:<br /><br /><b>Reason:</b> ".mysql_real_escape_string(check(addslashes($_POST['subject'])))."<br /><b>Explanation:</b> ".mysql_real_escape_string(check(addslashes($_POST['message'])))."<br /><br /><b>Reported Comment:</b> ".addslashes($_POST['comment'])."', '".$useridn."', '".$row2[id]."', '".time()."', 'report')");
}
}
echo "Thank you for reporting this comment. <a href='".url("content", $_POST['content_id'], $data[0], $data[1])."'>Return to Article</a>";
}
}

if (!$_GET['do'] && $_GET['username'] or !$_GET['do'] && !$_GET['username'] or $_GET['do'] == "messages" or $_GET['do'] == "friends" or $_GET['do'] == "status" or $_GET['do'] == "report" or $_GET['do'] == "edit" or $_GET['do'] == "edit2" or $_GET['do'] == "blogs") {
$smarty->display($skin.'/social_footer.tpl');
}
}
?>