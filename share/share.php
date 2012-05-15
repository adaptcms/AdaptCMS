<?php
include("config.php");
$pageurl = "http://www.insanevisions.com/share/adaptcms2/";
$pre = "cms_";

$siteurl = $_GET['siteurl'];
$sitename = $_GET['sitename'];
$domain = $_GET['domain'];
$share_id = $_GET['share_id'];

$sitecheck = mysql_fetch_row(mysql_query("SELECT sitename,user_id,last_updated,date,secret_id FROM adaptcms2_websites WHERE siteurl = '".$siteurl."'"));
if (!$sitecheck[0] && $sitename && $siteurl) {
mysql_query("INSERT INTO adaptcms2_websites VALUES (null, '".$sitename."', '".$siteurl."', 0, 0, '".time()."', '".time()."', '".random("18", $domain)."-".nrandom("10000000000")."-".arandom("5", $domain)."')");
}
if ($sitecheck[1] == 0 or !$share_id or $share_id != md5($sitecheck[4])) {
$login = 1;
}

if ($_GET['do'] == "register") {
$js_code = "<script src='".$pageurl."inc/js/jquery.js' type='text/javascript' language='javascript'></script>
<link rel='stylesheet' href='".$pageurl."style.css'>

<script type='text/javascript' src='".$pageurl."inc/js/jquery.pstrength-min.1.2.js'>
</script>
<script type='text/javascript'>
$(function() {
$('.password').pstrength();
});
</script>

<script language='javascript'>
//<!---------------------------------+
//  Developed by Roshan Bhattarai 
//  Visit http://roshanbh.com.np for this script and more.
//  This notice MUST stay intact for legal use
// --------------------------------->
$(document).ready(function()
{
	$('#username').blur(function()
	{
		//remove all the class add the messagebox classes and start fading
		$('#msgbox').removeClass().addClass('messagebox').text('Checking...').fadeIn('slow');
		//check the username exists or not from ajax
		$.post('http://www.insanevisions.com/includes/check_user.php',{ user_name:$(this).val() } ,function(data)
        {
		  if(data=='no') //if username not avaiable
		  {
		  	$('#msgbox').fadeTo(200,0.1,function() //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('Sorry, this username is in use').addClass('messageboxerror').fadeTo(900,1);
			});		
          }
		  else
		  {
		  	$('#msgbox').fadeTo(200,0.1,function()  //start fading the messagebox
			{ 
			  //add message and change the class of the box and start fading
			  $(this).html('Username available to register').addClass('messageboxok').fadeTo(900,1);	
			});
		  }
				
        });
 
	});
});
</script>
<style type='text/css'>
.messagebox{
	position:absolute;
	width:100px;
	margin-left:30px;
	border:1px solid #c93;
	background:#ffc;
	padding:3px;
}
.messageboxok{
	position:absolute;
	width:auto;
	margin-left:30px;
	border:1px solid #349534;
	background:#C9FFCA;
	padding:3px;
	font-weight:bold;
	color:#008000;
	
}
.messageboxerror{
	position:absolute;
	width:auto;
	margin-left:30px;
	border:1px solid #CC0000;
	background:#F7CBCA;
	padding:3px;
	font-weight:bold;
	color:#CC0000;
}
body {
background-color:white;
}
td {
font-family:Helvetica,Arial,sans-serif;
font-size:13px;
font-variant:normal;
}
.password {
font-size : 12px;
border : 1px solid #cc9933;

font-family : arial, sans-serif;
}
.pstrength-minchar {
font-size : 10px;
}
</style>";

require_once('inc/recaptchalib.php');
echo $js_code;
echo "<div align='center'><h2>Insane Visions Registration</h2></div><form action='".$pageurl."share.php?do=register2' method='post'><table cellpadding='5' cellspacing='3' width='100%'><tr><td><p>Username</p></td><td><input type='text' id='username' name='username' class='input' size='16'> <span id='msgbox' style='display:none'></span></td></tr><tr><td>Password</td><td><input type='password' id='password' name='password' class='input' size='16'></td></tr><tr><td>Password Confirm</td><td><input type='password' id='password2' name='password2' class='input' size='16'></td></tr><tr><td>E-Mail</td><td><input type='text' name='email' class='input' size='16'></td></tr><tr><td>Captcha</td><td>".recaptcha_get_html($publickey)."</td></tr><tr><td><input type='submit' value='Register' class='input'></td></tr></table></form>";
}

if ($_GET['do'] == "register2") {
if ($_POST['username'] && $_POST['password'] && valid_email($_POST['email']) == TRUE && $_POST['password'] == $_POST['password2']) {
if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."users WHERE username = '".$_POST['username']."'")) > 0 or mysql_num_rows(mysql_query("SELECT * FROM ".$pre."users WHERE email = '".$_POST['email']."'")) > 0) {
echo "Sorry but that username and/or email is already in use! Please go back and try again.";
} else {
require_once('inc/recaptchalib.php');
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);


if (!$resp->is_valid) {
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
$pass = mysql_real_escape_string(check($_POST['password']));
$_POST['username'] = mysql_real_escape_string(check($_POST['username']));
$_POST['password']= md5(mysql_real_escape_string(check($_POST['password'])));
$_POST['email'] = mysql_real_escape_string(check($_POST['email']));

$fetch = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."groups WHERE options = 'default-member' ORDER BY `id` DESC"));
$fetch2 = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."levels WHERE type = 'level' ORDER BY `points` ASC"));

$sql = mysql_query("INSERT INTO ".$pre."users VALUES (null, '".$_POST['username']."', '".$_POST['password']."', '".$_POST['email']."', '".$fetch[0]."', '".$fetch2[0]."', '".time()."', '".time()."', '".$act."', '".$ver."')");
if ($sql == TRUE) {
echo "You are now a registered member of Insane Visions. You can login at that website <a href='http://www.insanevisions.com'>here</a> or continue on to login into <a href='".$pageurl."share.php'>IV Share</a>";
}
}
}
}
}

if (!$_GET['do'] && $login) {
require_once('recaptchalib.php');
$publickey = "6LfLXggAAAAAACD1RBKS6F3Es4nn3b3hiR_1qkd- "; // you got this from the signup page

echo "<center>Hello <b>".basename($sitename)."</b>! Please login to your Insane Visions account in order to access the Share features.</center><br />";
echo "<form action='admin.php?view=share&do=login' method='post'><table><tr><td>Username</td><td><input type='text' name='username' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444' size='16'></td></tr><tr><td>Password</td><td><input type='password' name='password' style='font-family: tahoma; font-size: 11px; border: 1px solid #444444' size='16'></td></tr><tr><td>Captcha</td><td>".recaptcha_get_html($publickey)."</td></tr><tr><td>";
if ($sitecheck[4]) {
echo "<input type='hidden' name='share_id' value='".$sitecheck[4]."'>";
}
echo "<input type='submit' value='Login' class='input'></td><td>

<link type='text/css' media='screen' rel='stylesheet' href='".$pageurl."inc/js/colorbox.css' />
		<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script>
		<script type='text/javascript' src='".$pageurl."inc/js/jquery.colorbox-min.js'></script>
		<script type=\"text/javascript\">
			$(document).ready(function(){
				$(\".help\").colorbox({width:\"40%\", height:\"50%\", iframe:true});
			});
		</script>
		
		
		<a class='help' href='".$pageurl."share.php?do=register'>Need to Register?</a>

</td></tr></table></form>";
}
if ($_GET['do'] == "login") {
require_once('recaptchalib.php');
$privatekey = "6LfLXggAAAAAAN4K3KJi417ETsCg5pCwKU37OGj9";
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_GET["recaptcha_challenge_field"],
                                $_GET["recaptcha_response_field"]);

if (!$resp->is_valid) {
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
$prf = sprintf("SELECT id FROM ".$pre."users WHERE username = '%s' AND password = '%s' LIMIT 1",
mysql_real_escape_string(strip_tags($_GET['username'])),
mysql_real_escape_string(strip_tags($_GET['password'])));

$login_check = mysql_fetch_row(mysql_query($prf));

if ($login_check[0]) {
if ($_GET['share_id']) {
mysql_query("UPDATE adaptcms2_websites SET user_id = '".$login_check[0]."' WHERE siteurl = '".$_GET['siteurl']."'");
} else {
if ($sitecheck[1] == 0) {
$data = $login_check[0];
} else {
$data = $sitecheck[1]."|".$login_check[0];
}
echo $data."/".$login_check[0]."/".$sitecheck[1];
mysql_query("UPDATE adaptcms2_websites SET user_id = '".$data."' WHERE siteurl = '".$_GET['siteurl']."'");
}
if ($sitecheck[1]) {
echo "Thanks for logging in. <a href='admin.php?view=share'>Continue</a>";
} else {
echo "Your Insane Visions account has now been linked and you can access the Share feature. <a href='admin.php?view=share'>Continue</a>";
}
} else {
echo "Sorry but that username and password combo is incorrect. Please try again.";
}

}
}

if (!$login) {
echo "<link rel='stylesheet' href='http://www.insanevisions.com/share/adaptcms2/menu-bar.css' media='screen' type='text/css'>
	<script type='text/javascript' src='http://www.insanevisions.com/share/adaptcms2/menu.js'></script>
	
<div id='js_menu'>
	<ul>
		<li><a href='admin.php?view=share'>Main</a>	
		</li>
		<li><b>Websites</b>			
			<ul>
						<li><a href='admin.php?view=share&do=add_network'>Add Network</a></li>
						<li><a href='admin.php?view=share&do=networks'>Manage Network(s)</a></li>
						<li><a href='admin.php?view=share&do=websites'>Manage Website(s)</a></li>
			</ul>
		</li>
		<li><a href='admin.php?view=share&do=fields'>Fields</a>
			<ul>
				<li><a href='admin.php?view=share&do=fields'>View List</a></li>
				<li><a href='admin.php?view=share&do=share&type=fields'>Share Data</a></li>
			</ul>	
		</li>
		<li><a href='admin.php?view=share&do=help'>Help Files</a>
			<ul>
				<li><a href='admin.php?view=share&do=help'>View List</a></li>
				<li><a href='admin.php?view=share&do=share&type=help'>Share Data</a></li>
			</ul>	
		</li>
		<li><a href='admin.php?view=share&do=skins'>Skins</a>
			<ul>
				<li><a href='admin.php?view=share&do=skins'>View List</a></li>
				<li><a href='admin.php?view=share&do=share&type=skins'>Share Data</a></li>
			</ul>	
		</li>
		<li><a href='admin.php?view=share&do=plugins'>Plugins</a>
			<ul>
				<li><a href='admin.php?view=share&do=plugins'>View List</a></li>
				<li><a href='admin.php?view=share&do=share&type=plugins'>Share Data</a></li>
			</ul>	
		</li>
		<li><a href='admin.php?view=share&do=profile'>Your Profile</a>
		<li><a href='admin.php?view=share&do=promote'>Promote Site</a>
	</ul>
	</div><br />";
if (!$_GET['do']) {
echo "Welcome to the soon to be named Share feature on AdaptCMS 2.0! With this feature AdaptCMS reaches true connectivity with those that use the script and then with each other. Other than sharing things like fields and skins with others, you can also manage your sites that use AdaptCMS with publicly viewable information and promote your latest site's articles with other CMS users! Enjoy.";
}

if ($_GET['do'] == "promote" && !$_GET['go']) {
$web_id = mysql_fetch_row(mysql_query("SELECT id FROM adaptcms2_websites WHERE siteurl = '".$siteurl."'"));

$math = time() - 86400;

$content_num = mysql_num_rows(mysql_query("SELECT * FROM adaptcms2_articles WHERE website_id = '".$web_id[0]."' AND date < '".$math."'"));
echo $content_num;
}

if ($_GET['do'] == "promote" && $_GET['go'] == "submit") {


}

if ($_GET['do'] == "websites") {
echo "<table cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td><b>Website</b></td><td><b>Network</b></td><td><b>Last Updated</b></td><td><b>Edit</b></td></tr>";
$i = 0;
$sql = mysql_query("SELECT * FROM adaptcms2_websites WHERE user_id = '".$sitecheck[1]."' ORDER BY `sitename` ASC");
while($r = mysql_fetch_array($sql)) {
$net = mysql_fetch_row(mysql_query("SELECT name,url FROM adaptcms2_networks WHERE id = '".$r[network_id]."'"));
if ($net[0]) {
$network = "<a href='".$net[1]."'>".stripslashes($net[0])."</a>";
} else {
$network = "None";
}

echo "<tr><td><a href='".$r[siteurl]."'>".stripslashes($r[sitename])."</a></td><td>".$network."</td><td>".date("F d, Y - g:i a", $r[last_updated])."</td><td><a href='admin.php?view=share&do=edit_website&id=".$r[id]."'><img src='".$pageurl."images/edit.png'></a></td></tr>";

$i++;
}
echo "</table>";
}

if ($_GET['do'] == "edit_website") {
$r = mysql_fetch_row(mysql_query("SELECT sitename,siteurl,network_id FROM adaptcms2_websites WHERE id = '".$_GET['id']."'"));

echo "<form action='admin.php?view=share&do=edit_website2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Site <span class='drop'>Name</span></p><input type='text' name='name' size='18' class='addtitle' value='".stripslashes($r[0])."'></td></tr><tr><td><p>Website <span class='drop'>URL</span></p><a href='".$r[1]."' target='popup'>".$r[1]."</a></td></tr><tr><td><p>Network</p><br /><select name='network' class='select'><option value=''></option>";
$sql = mysql_query("SELECT * FROM adaptcms2_networks WHERE user_id = '".$sitecheck[1]."' ORDER BY `name` ASC");
while($row = mysql_fetch_array($sql)) {
echo "<option value='".$row[id]."'>".stripslashes($row[name])."</option>";
}
echo "</select></td></tr><tr><td><br /><input type='submit' value='Update' class='addContent-button'></td></tr></table></form>";
}

if ($_GET['do'] == "edit_website2") {
$sql = mysql_query("UPDATE adaptcms2_websites SET sitename = '".urldecode(addslashes($_GET['name']))."', network_id = '".$_GET['network']."', last_updated = '".time()."' WHERE id = '".$_GET['id']."'");

if ($sql == TRUE) {
echo "The website has been updated. <a href='admin.php?view=share&do=websites'>Return</a>";
} else {
echo "Sorry, but the website couldn't be updated. Please report this.";
}
}

if ($_GET['do'] == "networks") {
echo "<table cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td><b>Name</b></td><td><b>Sites Linked</b></td><td><b>Websites</b></td></tr>";
$i = 0;
$sql = mysql_query("SELECT * FROM adaptcms2_networks WHERE user_id = '".$sitecheck[1]."' ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
$num = mysql_num_rows(mysql_query("SELECT * FROM adaptcms2_websites WHERE network_id = '".$r[id]."'"));

echo "<tr><td><a href='".$r[url]."'>".stripslashes($r[name])."</a></td><td>".$num."</td><td>";
if ($num > 0) {
$sql2 = mysql_query("SELECT * FROM adaptcms2_websites WHERE network_id = '".$r[id]."' ORDER BY `sitename` ASC");
while($row = mysql_fetch_array($sql2)) {
echo "<a href='".$row[siteurl]."'>".stripslashes($row[sitename])."</a><br>";
}
}
echo "</td></tr>";

$i++;
}
echo "</table>";
}

if ($_GET['do'] == "add_network") {
echo "<form action='admin.php?view=share&do=add_network2' method='post'><table cellpadding='5' cellspacing='2' border='0' width='100%' align='center'><tr><td><p>Name</p><input type='text' name='name' size='12' class='addtitle'></td></tr><tr><td><p>URL <span class='drop'>Address</span></p><input type='text' name='url' size='15' class='addtitle' value='http://'></td></tr><tr><td><br /><input type='submit' value='Add Network' class='addContent-button'></td></tr></table></form>";
}
if ($_GET['do'] == "add_network2") {
$num = mysql_num_rows(mysql_query("SELECT * FROM adaptcms2_networks WHERE name = '".urldecode(addslashes($_GET['name']))."'"));

if ($num == 0) {
$sql = mysql_query("INSERT INTO adaptcms2_networks VALUES (null, '".urldecode(addslashes($_GET['name']))."', '".urldecode(addslashes($_GET['url']))."', '".$sitecheck[1]."')");

if ($sql == TRUE) {
echo "The network has been added. <a href='admin.php?view=share&do=networks'>Return</a>";
} else {
echo "Sorry, but the network couldn't be added. Please report this.";
}
}
}

if ($_GET['do'] == "skins" && !$_GET['go'] or $_GET['do'] == "help" && !$_GET['go'] or $_GET['do'] == "fields" && !$_GET['go']) {
if ($_GET['do'] == "skins") {
$var1 = "Template/Skin";
$var2 = "type = 'skin' OR type = 'template'";
} elseif ($_GET['do'] == "help") {
$var1 = "Help Files";
$var2 = "type = 'help'";
} elseif ($_GET['do'] == "fields") {
$var1 = "Fields";
$var2 = "type = 'fields'";
}
echo "<div align='left'><a href='admin.php?view=share&do=share&type=".$_GET['do']."'><img src='".$pageurl."inc/images/add.png'> Share ".$var1."</a></div><br /><table cellpadding='7' cellspacing='5' border='0' width='100%' align='center' style='border-collapse: collapse'><tr style='border-bottom: 1px solid #262626'><td></td><td><b>Name</b></td><td><b>Type</b></td><td><b>Author</b></td><td><b>Posted</b></td><td><b>Views</b></td><td><b>Rating</b></td></tr>";
$sql = mysql_query("SELECT * FROM adaptcms2_share WHERE ".$var2." ORDER BY `views` DESC");
while($r = mysql_fetch_array($sql)) {
if ($r[status] == 1) {
$user = mysql_fetch_row(mysql_query("SELECT username FROM cms_users WHERE id = '".$r[user_id]."'"));

unset($ex,$cur_rat,$cur_rating);
$ex = @explode("|", $r[rating]);
$cur_rat = round($ex[1]);
if ($cur_rat == 0) {
$cur_rating .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 0) {
$cur_rating .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 0) {
$cur_rating .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 3 or $cur_rat == 0) {
$cur_rating .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($cur_rat == 5) {
$cur_rating .= "<img src='".$pageurl."images/star_on.gif'></img>";
} else {
$cur_rating .= "<img src='".$pageurl."images/star_off.gif'></img>";
}

echo "<tr><td></td><td><a href='admin.php?view=share&do=".$_GET['do']."&go=view&id=".$r[id]."'>".stripslashes($r[name])."</a></td><td>".ucwords($r[type])."</td><td>".$user[0]."</td><td>".date("F d, Y", $r[date])."</td><td>".number_format($r[views])."</td><td>".$cur_rating."</td></tr>";
}
}
echo "</table>";
}

if ($_GET['do'] == "skins" && $_GET['go'] == "view" or $_GET['do'] == "help" && $_GET['go'] == "view" or $_GET['do'] == "fields" && $_GET['go'] == "view") {
require_once('inc/recaptchalib.php');
if ($_GET['comment']) {
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_GET["recaptcha_challenge_field"],
                                $_GET["recaptcha_response_field"]);


if (!$resp->is_valid) {
echo "Sorry, but you entered the wrong code for the captcha. Go back and try again (ERROR: ".$resp->error.")";
} else {
$text = preg_replace("/\n/", "<br />\n", check(addslashes($_GET['comment'])));
mysql_query("INSERT INTO adaptcms2_comments VALUES (null, '".$_GET['id']."', '".$sitecheck[1]."', '".$text."', '0|0', '".check($_GET['email'])."', '".check($_GET['website'])."', '".$_SERVER['REMOTE_ADDR']."', '', '".time()."')");
echo "<i>Your comment has been posted</i>";
}
}

echo "<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js'></script><script src='".$pageurl."inc/prototype.js'></script><link rel='stylesheet' href='".$pageurl."inc/style.css' />";

mysql_query("UPDATE adaptcms2_share SET views=views+1 WHERE id = '".$_GET['id']."'");
$r = mysql_fetch_row(mysql_query("SELECT name,type,description,views,rating,date,user_id,downloads FROM adaptcms2_share WHERE id = '".$_GET['id']."'"));
$id = $_GET['id'];
$user = mysql_fetch_row(mysql_query("SELECT username FROM cms_users WHERE id = '".$r[6]."'"));
echo "<h2>".stripslashes($r[0])."</h2>By: <b>".$user[0]."</b> @ <i>".date("F d, Y", $r[5])."</i><br />Views: ".number_format($r[3])."<br />";

$ex = @explode("|", $r[4]);
$cur_rat = round($ex[1]);

if ($cur_rat == 0) {
$cur_rating .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 0) {
$cur_rating .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 0) {
$cur_rating .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($cur_rat == 1 or $cur_rat == 2 or $cur_rat == 3 or $cur_rat == 0) {
$cur_rating .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$cur_rating .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($cur_rat == 5) {
$cur_rating .= "<img src='".$pageurl."images/star_on.gif'></img>";
} else {
$cur_rating .= "<img src='".$pageurl."images/star_off.gif'></img>";
}

$num_rat = mysql_num_rows(mysql_query("SELECT * FROM adaptcms2_data WHERE type = 'rating' AND item_id = '".$id."' AND name = '".$sitecheck[1]."'"));

if ($num_rat > 0) {
$cur_rating2 = "<i>Voted Already</i>";
} else {
$cur_rating2 = "<script>
function rate( value ) {
	new Ajax.Updater( 'rating', '".$pageurl."inc/rating.php?id=".$_GET['id']."&rat_num=".$ex[0]."&rat_tot=".$ex[1]."&user_id=".$sitecheck[1]."&v='+value );
}
</script>

<div id='rating'>
<img src='".$pageurl."images/star_off.gif' onclick='rate(1)'></img>
<img src='".$pageurl."images/star_off.gif' onclick='rate(2)'></img>
<img src='".$pageurl."images/star_off.gif' onclick='rate(3)'></img>
<img src='".$pageurl."images/star_off.gif' onclick='rate(4)'></img>
<img src='".$pageurl."images/star_off.gif' onclick='rate(5)'></img>
</div>
<br/>";
}
echo "Rating: ".$cur_rating."<br />Vote: ".$cur_rating2."<p>".stripslashes($r[2])."</p><br /><br /><a href='admin.php?view=share&do=".$_GET['do']."&go=load&id=".$_GET['id']."'>Get ";
if ($_GET['do'] == "fields") {
echo "Field";
} elseif ($_GET['do'] == "help") {
echo "Help File";
} else {
echo ucwords($r[1]);
}
echo "!</a><br /><br /><h2>Comments</h2>";

$comm = mysql_query("SELECT * FROM adaptcms2_comments WHERE item_id = '".$id."'");
while($com = mysql_fetch_array($comm)) {
$cmid = $com[id];

$ex1 = @explode("|", $com[rating]);
$com_rat = round($ex1[1]);

if ($com_rat == 0) {
$crate .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($com_rat == 1 or $com_rat == 0) {
$crate .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($com_rat == 1 or $com_rat == 2 or $com_rat == 0) {
$crate .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($com_rat == 1 or $com_rat == 2 or $com_rat == 3 or $com_rat == 0) {
$crate .= "<img src='".$pageurl."images/star_off.gif'></img>";
} else {
$crate .= "<img src='".$pageurl."images/star_on.gif'></img>";
}
if ($com_rat == 5) {
$crate .= "<img src='".$pageurl."images/star_on.gif'></img>";
} else {
$crate .= "<img src='".$pageurl."images/star_off.gif'></img>";
}

$num_com = mysql_num_rows(mysql_query("SELECT * FROM adaptcms2_data WHERE type = 'comment' AND item_id = '".$cmid."' AND name = '".$sitecheck[1]."'"));

if ($num_com == 0) {
$cform = "<script>
function rate".$cmid."( value ) {
	new Ajax.Updater( 'rating_".$cmid."', '".$pageurl."inc/rating.php?id=".$cmid."&rat_num=".$ex1[0]."&rat_tot=".$ex1[1]."&type=comments&v='+value );
}
</script>

<div id='rating_".$com[id]."'>
<img src='".$pageurl."images/star_off.gif' onclick='rate".$cmid."(1)'></img>
<img src='".$pageurl."images/star_off.gif' onclick='rate".$cmid."(2)'></img>
<img src='".$pageurl."images/star_off.gif' onclick='rate".$cmid."(3)'></img>
<img src='".$pageurl."images/star_off.gif' onclick='rate".$cmid."(4)'></img>
<img src='".$pageurl."images/star_off.gif' onclick='rate".$cmid."(5)'></img>
</div>
<br/>";
} else {
$cform = "<i>Sorry, you cannot rate this item again</i>";
}

echo "<div id='comments'>
<table class='newstxt' cellpadding='5' cellspacing='2' border='0' style='border: 2px solid #868585' width='100%'><tr><td bgcolor='#868585'> ".$user[0].", ".date("F d, Y", $com[date])."</td></tr><tr><td>".stripslashes($com[comment])."</td></tr><tr><td bgcolor='#868585'><b>Rating:</b> ".$crate.", <b>Rate Comment:</b> ".$cform."</td></tr></table><br />

</div>";
}

echo "<script language='javascript' type='text/javascript' src='http://developer.adaptsoftware.org/AdaptCMS2/inc/js/tiny_mce/tiny_mce.js'></script>
<script language='javascript' type='text/javascript'>
	tinyMCE.init({
		theme : 'advanced',
		mode : 'textareas',
		elements : 'abshosturls',
		plugins : 'spellchecker,preview,searchreplace,emotions,media,tinyautosave,contextmenu,codeprotect',

		theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect,formatselect',
		theme_advanced_buttons2 : 'bullist,numlist,|,undo,redo,|,link,unlink,image,cleanup,code,preview,replace,spellchecker,emotions,media,tinyautosave',
		theme_advanced_buttons3 : '',
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		theme_advanced_statusbar_location : 'bottom',
		theme_advanced_resizing : true,

		remove_linebreaks : false,
        force_p_newlines : false,
		debug : false,
		relative_urls : false,
		remove_script_host : false
	});</script>";
if (!$_GET['comment']) {
echo "<hr><br /><a name='comments'></a><form action='admin.php?view=share&do=skins&go=view&id=".$_GET['id']."' method='post'><input type='hidden' name='url' value='".$pageurl."admin.php?view=share&do=skins&go=view&id=".$id."' class='input'><input type='text' name='email' class='input' id='email' /> &nbsp;email<br /><br /><input name='website' type='text' value='http://' class='input' /> &nbsp;website<br /><br /><textarea name='comment' class='input' id='comment' cols='40' rows='10'></textarea><br /><br />".recaptcha_get_html($publickey)."<input type='hidden' name='id' value='".$r[id]."' /><input type='submit' value='Post Comment' class='input' /></form>";
}
}

if ($_GET['do'] && $_GET['go'] == "load" && is_numeric($_GET['id'])) {
$r = mysql_fetch_row(mysql_query("SELECT name,type,description,views,rating,date,user_id FROM adaptcms2_share WHERE id = '".$_GET['id']."'"));
echo "<form action='admin.php?view=share&do=".$_GET['do']."&go=load2&id=".$_GET['id']."' method='post'><table cellpadding='5' cellspacing='0' border='0' width='90%' align='center'><input type='hidden' name='type' value='".$r[1]."'><input type='hidden' name='oldname' value='".stripslashes($r[0])."'>";
if ($_GET['do'] == "help") {
$data = mysql_fetch_row(mysql_query("SELECT data FROM adaptcms2_data WHERE item_id = '".$_GET['id']."'"));
echo "<tr><td>Help File Name</td><td><input type='text' name='name' value='".stripslashes($r[0])."' class='input'></td></tr><tr><td>Section</td><td><select name='section' class='input'><option value=''></option>";
for($i = 0; $i < $_GET['help_tot']; ++$i) {
echo "<option value='".$_GET["help$i"]."'>".$_GET["help$i"]."</option>";
}
echo "</select></td></tr><input type='hidden' name='data' value='".urlencode($data[0])."'>";
} elseif ($_GET['do'] == "fields") {
$data = mysql_fetch_row(mysql_query("SELECT `type`,description,data,editable,`limit`,required FROM adaptcms2_fields WHERE section = '".$_GET['id']."'"));
echo "<tr><td>Field Name</td><td><input type='text' name='name' value='".stripslashes($r[0])."' class='input'></td></tr><tr><td>Section</td><td><select name='section' class='input'><option value=''></option>";
for($i = 0; $i < $_GET['sec_tot']; ++$i) {
echo "<option value='".$_GET["sec$i"]."'>".$_GET["sec$i"]."</option>";
}
echo "</select></td></tr><input type='hidden' name='ftype' value='".urlencode($data[0])."'><input type='hidden' name='description' value='".urlencode($data[1])."'><input type='hidden' name='data' value='".urlencode($data[2])."'><input type='hidden' name='editable' value='".urlencode($data[3])."'><input type='hidden' name='limit' value='".urlencode($data[4])."'><input type='hidden' name='required' value='".urlencode($data[5])."'>";
} elseif ($r[1] == "skin") {
echo "<input type='hidden' name='type' value='skin'><tr><td>Skin Name</td><td><input type='text' name='name' value='".stripslashes($r[0])."' class='input'></td></tr>";
$i = 0;
$sql = mysql_query("SELECT * FROM adaptcms2_skins WHERE skin = '".$r[0]."' ORDER BY `name` ASC");
while($r = mysql_fetch_array($sql)) {
echo "<input type='hidden' name='temp_name".$i."' value='".urlencode($r[name])."'>";
$i++;
}
echo "<input type='hidden' name='temp_tot' value='".$i."'>";
} else {
$temp = mysql_fetch_row(mysql_query("SELECT template,id FROM adaptcms2_skins WHERE name = '".$r[0]."'"));
echo "<input type='hidden' name='type' value='template'><input type='hidden' name='oldname' value='".stripslashes($r[0])."'><input type='hidden' name='id' value='".$temp[1]."'><tr><td>Template Name</td><td><input type='text' name='name' value='".stripslashes($r[0])."' class='input'></td></tr><tr><td>Skin</td><td><select name='skin' class='input'><option value=''></option>";
for($i = 0; $i < $_GET['skin_tot']; ++$i) {
echo "<option value='".$_GET["skin$i"]."'>".$_GET["skin$i"]."</option>";
}
echo "</select></td></tr><input type='hidden' name='template' value='".urlencode($temp[0])."'>";
}
echo "<tr><td><input type='submit' value='Add ".ucwords($r[1])."' class='input'></td><td></td></tr></table></form>";
}

if ($_GET['do'] && $_GET['go'] == "load2" && is_numeric($_GET['id'])) {
mysql_query("UPDATE adaptcms2_share SET downloads=downloads+1 WHERE id = '".$_GET['id']."'");
echo "You have loaded the ".$_GET['do'].". <a href='admin.php?view=share'>Return</a>";
}

if ($_GET['do'] == "skins" && $_GET['go'] == "load3") {
echo "<--NEW-QUERY-->";
unset($sql, $r);
if ($_GET['type'] == "skin") {
$var = random(5, $_GET['oldname']);
$sql = mysql_query("SELECT * FROM adaptcms2_skins WHERE skin = '".check($_GET['oldname'])."' ORDER BY `name` ASC");
} else {
$sql = mysql_query("SELECT * FROM adaptcms2_skins WHERE id = '".check($_GET['id'])."' ORDER BY `name` ASC");
}
while($r = mysql_fetch_array($sql)) {
if ($_GET['type'] == "skin") {
echo addslashes($r[template])."{name-to-the-right}".addslashes($r[name])."<--NEW-QUERY-->";
} else {
echo addslashes($r[template])."{name-to-the-right}".addslashes($_GET['name']);
}
}
}

if ($_GET['do'] == "share") {
if ($_GET['skin_tot'] or $_GET['tmp_tot']) {
for($i = 0; $i < $_GET['skin_tot']; ++$i) {
$skins .= "<option value='".$_GET["skin$i"]."'>".$_GET["skin$i"]."</option>";
}
for($i = 0; $i < $_GET['tmp_tot']; ++$i) {
$ex = explode("::", $_GET["tmp$i"]);
if ($ex[0]) {
$skin1 = $ex[0]."/".$ex[1];
$skin2 = " (".$ex[0].")";
$tmps .= "<input type='hidden' name='tmps".$i."' value='".$ex[1]."'>";
} else {
$skin1 = $ex[1];
}
$tmp .= "<option value='".$skin1."'>".$ex[1].$skin2."</option>";
}
if ($tmps) {
$tmps .= "<input type='hidden' name='tmps_total' value='".$_GET['tmp_tot']."'>";
}
}
if ($_GET['help_tot']) {
$data .= "<input type='hidden' name='help_total' value='".$_GET['help_tot']."'>";
for($i = 0; $i < $_GET['help_tot']; ++$i) {
$ex = explode("::", $_GET["help$i"]);
$data .= "<input type='hidden' name='help".$i."_file' value='".addslashes(urldecode($ex[1]))."'><input type='hidden' name='help".$i."' value='".$ex[0]."'>";
$help .= "<option value='".$i."'>".$ex[0]."</option>";
}
}
if ($_GET['fields_tot']) {
$data .= "<input type='hidden' name='fields_total' value='".$_GET['fields_tot']."'>";
for($i = 0; $i < $_GET['fields_tot']; ++$i) {
$ex = explode("::", $_GET["field$i"]);
$data .= "<input type='hidden' name='field".$i."_data' value='".addslashes(urldecode($ex[1]))."::".addslashes(urldecode($ex[2]))."::".addslashes(urldecode($ex[3]))."::".addslashes(urldecode($ex[4]))."::".addslashes(urldecode($ex[5]))."::".addslashes(urldecode($ex[6]))."'><input type='hidden' name='field".$i."' value='".$ex[0]."'>";
$field .= "<option value='".$i."'>".$ex[0]."</option>";
}
}
echo "<script language='javascript' type='text/javascript' src='http://developer.adaptsoftware.org/AdaptCMS2/inc/js/tiny_mce/tiny_mce.js'></script>
<script language='javascript' type='text/javascript'>
	tinyMCE.init({
		theme : 'advanced',
		mode : 'textareas',
		elements : 'abshosturls',
		plugins : 'spellchecker,preview,searchreplace,emotions,media,tinyautosave,contextmenu,codeprotect',

		theme_advanced_buttons1 : 'bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontsizeselect,formatselect',
		theme_advanced_buttons2 : 'bullist,numlist,|,undo,redo,|,link,unlink,image,cleanup,code,preview,replace,spellchecker,emotions,media,tinyautosave',
		theme_advanced_buttons3 : '',
		theme_advanced_toolbar_location : 'top',
		theme_advanced_toolbar_align : 'left',
		theme_advanced_statusbar_location : 'bottom',
		theme_advanced_resizing : true,

		remove_linebreaks : false,
        force_p_newlines : false,
		debug : false,
		relative_urls : false,
		remove_script_host : false
	});</script>";

echo "<form action='admin.php?view=share&do=share2&type=".$_GET['type']."' method='post'><table cellpadding='5' cellspacing='0' border='0' width='100%' align='center' style='border: 2px solid #dddddd'>";
if ($_GET['type'] == "skins") {
echo $tmps."<tr><td>Share Skin</td><td><select name='skin' class='input'><option value=''></option>".$skins."</select></td></tr><tr><td>Share Template</td><td><select name='template' class='input'><option value=''></option>".$tmp."</select></td></tr><tr><td>Description</td><td><textarea name='desc' id='desc' cols='55' rows='15'></textarea></td></tr>";
} elseif($_GET['type'] == "help") {
echo $data."<tr><td>Share Help File</td><td><select name='help' class='input'><option value=''></option>".$help."</select></td></tr><tr><td>Description</td><td><textarea name='desc' id='desc' cols='55' rows='15'></textarea></td></tr>";
} elseif($_GET['type'] == "fields") {
echo $data."<tr><td>Share Field</td><td><select name='field' class='input'><option value=''></option>".$field."</select></td></tr><tr><td>Description</td><td><textarea name='desc' id='desc' cols='55' rows='15'></textarea></td></tr>";
}
echo "<tr><td><input type='submit' value='Share' class='input'></td></tr></table></form>";
}

if ($_GET['do'] == "share2") {
if ($_GET['type'] == "help") {
$text = preg_replace("/\n/", "<br />\n", check(addslashes($_GET["desc"])));
$i = $_GET["help"];
$name = $_GET["help$i"];

$num = mysql_num_rows(mysql_query("SELECT * FROM adaptcms2_share WHERE type = 'help' AND name = '".$name."'"));
if ($num > 0) {
$name = $name."_".random(5, $name);
}

mysql_query("INSERT INTO adaptcms2_share VALUES (null, 'help', '".$name."', '".$text."', '', 0, 0, '0|0', '".time()."', '".$sitecheck[1]."', 0)");
$id = mysql_fetch_row(mysql_query("SELECT id FROM adaptcms2_share WHERE name = '".$name."' AND type = 'help' AND user_id = '".$sitecheck[1]."' ORDER BY `id` DESC LIMIT 1"));
mysql_query("INSERT INTO adaptcms2_data VALUES (null, '".$name."', 'help-file', '".urldecode(addslashes($_GET["help".$i."_file"]))."', '".$id[0]."')");

echo "Your Help File has been added and is now awaiting approval. <a href='admin.php?view=share'>Return</a>";
}elseif ($_GET['type'] == "fields") {
$text = preg_replace("/\n/", "<br />\n", check(addslashes($_GET["desc"])));
$i = $_GET["field"];
$name = $_GET["field$i"];
$ex = explode("::", $_GET["field".$i."_data"]);

$num = mysql_num_rows(mysql_query("SELECT * FROM adaptcms2_share WHERE type = 'field' AND name = '".$name."'"));
if ($num > 0) {
$name = $name."_".random(5, $name);
}

mysql_query("INSERT INTO adaptcms2_share VALUES (null, 'fields', '".$name."', '".$text."', '', 0, 0, '0|0', '".time()."', '".$sitecheck[1]."', 0)");
$id = mysql_fetch_row(mysql_query("SELECT id FROM adaptcms2_share WHERE name = '".$name."' AND type = 'help' AND user_id = '".$sitecheck[1]."' ORDER BY `id` DESC LIMIT 1"));
mysql_query("INSERT INTO adaptcms2_fields VALUES (null, '".$name."', '".$id[0]."', '".$ex[0]."', '".urldecode(addslashes($ex[1]))."', '".urldecode(addslashes($ex[2]))."', '".$ex[3]."', '".$ex[4]."', '".$ex[5]."')");

echo "Your Field has been added and is now awaiting approval. <a href='admin.php?view=share'>Return</a>";
}elseif ($_GET['type'] == "skins") {
$text = preg_replace("/\n/", "<br />\n", check(addslashes($_GET["desc"])));
if ($_GET['template']) {
$ex = explode("/", $_GET["template"]);
if ($ex[0] && $ex[1]) {
$file = $ex[0]."/".$ex[1].".tpl";
$name = $ex[1];
$skin = $ex[0];
} else {
$file = $_GET['template'].".tpl";
$name = $_GET['template'];
}
$template = file_get_contents($siteurl."templates/".$file);

$num = mysql_num_rows(mysql_query("SELECT * FROM adaptcms2_share WHERE type = 'template' AND name = '".$name."'"));
if ($num > 0) {
$name = $name."_".random(5, $name);
}

mysql_query("INSERT INTO adaptcms2_share VALUES (null, 'template', '".$name."', '".$text."', '', 0, 0, '0|0', '".time()."', '".$sitecheck[1]."', 0)");
$id = mysql_fetch_row(mysql_query("SELECT id FROM adaptcms2_share WHERE name = '".$name."' AND type = 'template' AND user_id = '".$sitecheck[1]."' ORDER BY `id` DESC LIMIT 1"));
mysql_query("INSERT INTO adaptcms2_skins VALUES (null, '".$name."', '', '".addslashes($template)."', '".time()."', '".$id[0]."')");
}
if ($_GET['skin']) {
$num = mysql_num_rows(mysql_query("SELECT * FROM adaptcms2_share WHERE type = 'skin' AND name = '".$_GET['skin']."'"));
if ($num > 0) {
$oldskin = $_GET['skin'];
$_GET['skin'] = $_GET['skin']."_".random(5, $_GET['skin']);
} else {
$oldskin = $_GET['skin'];
}
mysql_query("INSERT INTO adaptcms2_share VALUES (null, 'skin', '".$_GET['skin']."', '".$text."', '', 0, 0, '0|0', '".time()."', '".$sitecheck[1]."', 0)");
$id = mysql_fetch_row(mysql_query("SELECT id FROM adaptcms2_share WHERE name = '".$_GET['skin']."' AND type = 'skin' AND user_id = '".$sitecheck[1]."' ORDER BY `id` DESC LIMIT 1"));
for($i = 0; $i < $_GET['tmps_total']; ++$i) {
if (stristr($vars, "{".$_GET["tmps$i"]."}") === FALSE) {
$template = file_get_contents($siteurl."templates/".$oldskin."/".$_GET["tmps$i"].".tpl");
mysql_query("INSERT INTO adaptcms2_skins VALUES (null, '".$_GET["tmps$i"]."', '".$_GET['skin']."', '".addslashes($template)."', '".time()."', '".$id[0]."')");
}
$vars .= "{".$_GET["tmps$i"]."}";
}
mysql_query("INSERT INTO adaptcms2_skins VALUES (null, '".$_GET['skin']."', '', 'skin, '".time()."', '".$id[0]."')");
}
echo "Your Skin/Template has been added and is now awaiting approval. <a href='admin.php?view=share'>Return</a>";
}

}


}
?>