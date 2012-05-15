<?php
    session_start();

	$cpage = basename($_SERVER['REQUEST_URI']);
	$apage = basename($_SERVER['PHP_SELF']);
	$replace = array("inc/", "acp/", "quick_edit/", "js/", "plugins");
	$sitepath = str_replace($replace,"",str_replace($apage, "", $_SERVER['SCRIPT_FILENAME']));
	$siteurl = "http://".$_SERVER['HTTP_HOST'].str_replace("acp/","",str_replace("inc/","",str_replace($apage, "", $_SERVER['PHP_SELF'])));

	$sitedir = str_replace($apage, "", $_SERVER['PHP_SELF']);
	$pageurl = substr($siteurl,0,-1).str_replace($sitedir,"/",$_SERVER['REQUEST_URI']);
	if ((strpos($_SERVER['HTTP_HOST'],'www.')===false)) { header("Location: http://www.".str_replace("http://","",$pageurl)); exit(); }

    include ($sitepath."inc/dbinfo.php");
	if ($dbhost == "cms_dbhost" && !$install) {
	header("location: ".$siteurl."install.php");
	die;
	}
	if (!$install) {
	if (mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass)) == FALSE) {
	echo "Sorry but we are currently experiencing some issues. Please try again in a moment. Thank you.";
	die;
	}
	}

	include ($sitepath."inc/functions.php");
	if (!$install && !$salt or !$install && $salt == "salt") {
	insertnewSalt();
	}

	array_map('clean', $_GET);
	array_map('clean', $_POST);
	array_map('clean', $_COOKIE);
	array_map('clean', $_SESSION);
	$_GET['view'] = htmlentities(check($_GET['view']));
	$pageurl = addslashes(htmlentities(check($pageurl)));

	$version = "2.0.2";

	$domain = check_domain($siteurl);
	if (!$install) {
	require_once($sitepath.'inc/smarty/libs/init.php');
	$smarty->assign('siteurl', $siteurl);
	$smarty->assign('sitepath', $sitepath);
	$smarty->assign('adaptcms_version', $version);
	}

	if (stristr($_SERVER['REQUEST_URI'], "admin.php") or stristr($_SERVER['SCRIPT_NAME'], "inc")) {
	$page_type = "acp";
	} else {
	$page_type = "web";
	}
   
    if (!$install) {
	$smarty->assign('page_type', $page_type);
	
	if ($page_type == "web") {
	if ($_GET['view']) {
	$build_url .= "view=".$_GET['view']."&";
	}
	if ($_GET['do']) {
	$build_url .= "do=".$_GET['do']."&";
	}
	if ($_GET['id']) {
	$build_url .= "id=".$_GET['id']."&";
	}
	if ($_GET['section']) {
	$build_url .= "section=".$_GET['section']."&";
	}
	if ($_GET['username']) {
	$build_url .= "username=".$_GET['username']."&";
	}
	if ($_GET['search']) {
	$build_url .= "search=".$_GET['search']."&";
	}
	$buildurl = "index.php?".$build_url;
	}

	$sql = mysql_query("SELECT * FROM ".$pre."settings WHERE type = 'setting'");
	while($row = @mysql_fetch_array($sql)) {
	$name = $row[name];
	$count = 1;

	if (!$setting[$name]) {
	$setting[$name] = $row[data];
	$smarty->assign($name, $row[data]);
	}
	$name = "";
	}
	if (!$count) {
	echo "Critical AdaptCMS error, no settings pulled - either you have not installed the script yet or the table was cleaned out. If the former, manually add a setting into the DB or re-install.";
	die;
	}
	$cookiename = $setting["cookie_prefix"];
	$smarty->assign('cookiename', $cookiename);
	if (strtolower($setting["gzip"]) == "yes") {
	$gzip = 1;
	}

	// Checks for articles that were set to be published at a certain date and time
	$sql_pub = mysql_query("SELECT * FROM ".$pre."content WHERE status != '' AND status != 'saved' AND status <= '".time()."'");
	while($pubr = mysql_fetch_array($sql_pub)) {
	mysql_query("UPDATE ".$pre."content SET status = '', date = '".$pubr[status]."', mdate = '".date("m", $pubr[status])."', ydate = '".date("Y", $pubr[status])."' WHERE id = '".$pubr[id]."'");
	}

	if ($_POST["vote"] && is_numeric($_POST["poll_id"]) or $_POST["vote"]) {
	if ($_POST['custom'] && $_POST["vote"]) {
	$vote2 = badwords(substr(htmlentities(check($_POST["vote"])), 0, 20));
	mysql_query("INSERT INTO ".$pre."polls VALUES (null, '".htmlentities(check($vote[2]))."', '".$vote2."', 'custom_option', '', '".htmlentities(urldecode($_POST['question']))."', 1, '".time()."')");
	mysql_query("UPDATE ".$pre."polls SET votes=votes+1 WHERE poll_id = '".htmlentities(urldecode($_POST["poll_id"]))."' AND id = '".htmlentities(urldecode($_POST["poll_id"]))."'");
	mysql_query("UPDATE ".$pre."polls SET votes=votes+1 WHERE id = '".$vote2."' AND poll_id = '".htmlentities(urldecode($_POST["poll_id"]))."'");
	$poll_id = htmlentities(stripslashes($_POST["poll_id"]));
	$_SESSION["poll_".$poll_id] = $vote;
	} else {
	if ($_POST['multi']) {
	while (list(, $i) = each($_POST["vote"])) {
	mysql_query("UPDATE ".$pre."polls SET votes=votes+1 WHERE id = '".addslashes(urldecode($i))."' AND poll_id = '".htmlentities(urldecode($_POST["poll_id"]))."'");
	mysql_query("UPDATE ".$pre."polls SET votes=votes+1 WHERE poll_id = '".htmlentities(urldecode($_POST["poll_id"]))."' AND id = '".htmlentities(urldecode($_POST["poll_id"]))."'");
	$op .= htmlentities(urldecode($i)).", ";
	}
	$poll_id = htmlentities(stripslashes($_POST["poll_id"]));
	$_SESSION["poll_".$poll_id] = $op;
	} else {
	mysql_query("UPDATE ".$pre."polls SET votes=votes+1 WHERE id = '".htmlentities(urldecode($_POST["vote"]))."' AND poll_id = '".htmlentities(urldecode($_POST["poll_id"]))."'");
	mysql_query("UPDATE ".$pre."polls SET votes=votes+1 WHERE poll_id = '".htmlentities(urldecode($_POST["poll_id"]))."' AND id = '".htmlentities(urldecode($_POST["poll_id"]))."'");
	$poll_id = htmlentities(stripslashes($_POST["poll_id"]));
	$_SESSION["poll_".$poll_id] = htmlentities(stripslashes($_POST["vote"]));
	}
	}
	}
	$op = "";$poll_id = "";$i = "";

	
	$user_cookie = $_COOKIE[$cookiename."username"];
	$smarty->assign('user_name', $user_cookie);
    
	if ($_COOKIE[$cookiename."username"] && $_COOKIE[$cookiename."password"]) {
	$upd = sprintf("UPDATE ".$pre."users SET last_login = '".time()."' WHERE username = '%s' AND password = '%s'",
    mysql_real_escape_string(check($_COOKIE[$cookiename."username"])),
    mysql_real_escape_string(md5($salt.$_COOKIE[$cookiename."username"].$_COOKIE[$cookiename."password"])));
	mysql_query($upd);
	$prf2 = sprintf("SELECT email,`group`,level,last_login,reg_date,id,act,ver,status,status_time,skin FROM ".$pre."users WHERE username = '%s' AND password = '%s'",
    mysql_real_escape_string(check($_COOKIE[$cookiename."username"])),
    mysql_real_escape_string(md5($salt.$_COOKIE[$cookiename."username"].$_COOKIE[$cookiename."password"])));

	$prf = mysql_fetch_row(mysql_query($prf2));
	$email = $prf[0];
	$group = $prf[1];
	$rank = $prf[2];
	$last_login = $prf[3];
	$regdate = $prf[4];
	$useridn = $prf[5];
	$status = $prf[8];
	$status_time = $prf[9];
	$skin = $prf[10];

	$act = $prf[6];
	$ver = $prf[7];
	} else {
	$grp2 = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."groups WHERE options = 'default-guest' ORDER BY `id` ASC"));
	$group = $grp2[0];
	$def_skin = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."skins WHERE skin = '' AND template = 'yes|'"));
	$skin = $def_skin[0];
	}
	if (@file_exists($sitepath."templates/".$skin."/admin_header.tpl") == FALSE) {
	$skin = "main";
	}

	if ($_COOKIE[$cookiename."username"] && $_COOKIE[$cookiename."password"] && !$useridn && $_GET['view'] != "login" && $_GET['view'] != "logout" && $_GET['view'] != "register" && $_GET['do'] != "forgot_password") {
	header("location: ".url("login"));
	die;
	}

	$adinfo = sprintf("SELECT email,`group`,level,last_login,reg_date,id FROM ".$pre."users WHERE username = '%s' AND password = '%s'",
    mysql_real_escape_string(check($_SESSION[$cookiename."username"])),
    mysql_real_escape_string(md5($salt.$_SESSION[$cookiename."username"].$_SESSION[$cookiename."password"])));
	$admin_info = mysql_fetch_row(mysql_query($adinfo));

	if (!$smp) {
	$plugins_sql = @mysql_query("SELECT * FROM ".$pre."plugins ORDER BY `name` ASC");
	while($rplgs = @mysql_fetch_array($plugins_sql)) {
	if ($rplgs['status'] == "On" && $rplgs['name']) {
	if (file_exists($sitepath."plugins/".strtolower(str_replace(" ", "_", $rplgs['name']))."/functions.php")) {
	include ($sitepath."plugins/".strtolower(str_replace(" ", "_", $rplgs['name']))."/functions.php");
	}
	}
	}
	
	if ($page_type == "acp") {
	if ($_GET['view'] != "denied" && $_GET['view'] != "login") {
	if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."permissions WHERE `group` = '".$group."' AND data != '||'")) == 0 && $useridn) {
	echo "Access Denied";
	die;
	}
	if (!$admin_info[1] or !$useridn) {
	header('location: admin.php?view=login');
	}
	}
	}

	admin_bar();

	if (is_numeric($_GET['hide_comment']) && is_numeric($_GET['content_id'])) {
	$get = mysql_fetch_row(mysql_query("SELECT section,user_id FROM ".$pre."content WHERE id = '".$_GET['content_id']."'"));
	$ps = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."permissions WHERE `group` = '".$group."' AND name = '".$get[0]."'"));
	$p = explode("|", $ps[0]);

	if ($p[1] or $get[1] == $useridn) {
	if ($_GET['show'] == 1) {
	mysql_query("UPDATE ".$pre."comments SET status = '' WHERE id = '".$_GET['hide_comment']."'");
	} else {
	mysql_query("UPDATE ".$pre."comments SET status = 1 WHERE id = '".$_GET['hide_comment']."'");
	}
	}
	}
	}

	require_once('inc/detector.php');
	$pc = &new Detector($_SERVER["REMOTE_ADDR"], $_SERVER["HTTP_USER_AGENT"]);
	$referer_keyword = get_search_phrase($_SERVER['HTTP_REFERER']);

	// Daily stats work
	if (date("z") == 0) {
	$yesterday = 365;
	$yesterday_year = date("Y") - 1;
	} else {
	$yesterday = date("z") - 1;
	$yesterday_year = date("Y");
	}
	$stats_check = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'day' AND data = '".$yesterday."' AND year = '".$yesterday_year."'"));
	if ($stats_check == 0) {
	// if no stats entered in archive for yesterday, then see if there are stats in stats table to create new archive entry
	$stats_check2 = mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats WHERE tday = '".$yesterday."' AND year = '".$yesterday_year."'"));
	if ($stats_check2 > 0) {
	stats_update("new_day");
	}
	}

	if (date("N") == 1) {
	stats_update("new_week");
	}
	if (date("j") == 1) {
	stats_update("new_month");
	}
	if (date("z") == 0) {
	stats_update("new_year");
	}

	if (detect_bot()) {
	$referer_name = detect_bot();
	$bot1 = 1;
	} else {
	if (check_domain($_SERVER['HTTP_REFERER']) == check_domain($pageurl)) {
	$referer_name = $setting["sitename"];
	} else {
	$referer_name = check_domain($_SERVER['HTTP_REFERER']);
	}
	}

	if ($_COOKIE[$cookiename."data"]) {
	// okay then you check to see if page has been viewed by this guy today and if so, you add +1 to visits_num
	$stats_num = mysql_fetch_row(mysql_query("SELECT time_last_visit FROM ".$pre."stats WHERE cookie_id = '".$_COOKIE[$cookiename."data"]."' AND page = '".$pageurl."'"));
	if (!$stats_num[0] or time() - $stats_num[0] > 86400) {
	mysql_query("INSERT INTO ".$pre."stats VALUES (null, '".$pageurl."', '".$_SERVER['HTTP_REFERER']."', '".$referer_name."', '".$referer_keyword."', 1, 'view', '".$_COOKIE[$cookiename."data"]."', '".$pc->browser." ".$pc->browser_version."', '".$pc->os." ".$pc->os_version."', '".$useridn."', '".$_SERVER['REMOTE_ADDR']."', '".date("d")."', '".date("z")."', '".date("n")."', '".date("W")."', '".date("Y")."', '".time()."', '".time()."')"); 
	} else {
	mysql_query("UPDATE ".$pre."stats SET referer_url = '".$_SERVER['HTTP_REFERER']."', referer_name = '".$referer_name."', referer_keyword = '".$referer_keyword."', visits_num=visits_num+1, time_last_visit = '".time()."' WHERE cookie_id = '".$_COOKIE[$cookiename."data"]."' AND page = '".$pageurl."'");
	}
	} else {
	if (!detect_bot()) {
	setcookie($cookiename."data", randompass(12), time()+60*60*24, str_replace($apage, "", $_SERVER['PHP_SELF']), ".".$domain);
	mysql_query("INSERT INTO ".$pre."stats VALUES (null, '".$pageurl."', '".$_SERVER['HTTP_REFERER']."', '".$referer_name."', '".$referer_keyword."', 1, 'unique', '".$_COOKIE[$cookiename."data"]."', '".$pc->browser." ".$pc->browser_version."', '".$pc->os." ".$pc->os_version."', '".$useridn."', '".$_SERVER['REMOTE_ADDR']."', '".date("d")."', '".date("z")."', '".date("n")."', '".date("W")."', '".date("Y")."', '".time()."', '".time()."')"); 
	} else {
	$stats_num = mysql_fetch_row(mysql_query("SELECT * FROM ".$pre."stats WHERE ip = '".$_SERVER['REMOTE_ADDR']."'"));
	if ($stats_num == 0) {
	setcookie($cookiename."data", randompass(12), time()+60*60*24, str_replace($apage, "", $_SERVER['PHP_SELF']), ".".$domain);
	mysql_query("INSERT INTO ".$pre."stats VALUES (null, '".$pageurl."', '".$_SERVER['HTTP_REFERER']."', '".$referer_name."', '".$referer_keyword."', 1, 'unique', '".$_COOKIE[$cookiename."data"]."', '".$pc->browser." ".$pc->browser_version."', '".$pc->os." ".$pc->os_version."', '".$useridn."', '".$_SERVER['REMOTE_ADDR']."', '".date("d")."', '".date("z")."', '".date("n")."', '".date("W")."', '".date("Y")."', '".time()."', '".time()."')"); 	
	} else {
	mysql_query("UPDATE ".$pre."stats SET referer_url = '".$_SERVER['HTTP_REFERER']."', referer_keyword = '".$referer_keyword."', visits_num=visits_num+1, time_last_visit = '".time()."' WHERE ip = '".$_SERVER['REMOTE_ADDR']."' AND page = '".$pageurl."'");
	}
	}
	}

	if (is_numeric($_POST['change_skin'])) {
	$changeskin = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."skins WHERE id = '".$_POST['change_skin']."'"));
	if ($changeskin[0]) {
	mysql_query("UPDATE ".$pre."users SET skin = '".$changeskin[0]."' WHERE id = '".$useridn."'");
	}
	}

	// Advanced stats
	if ($page_type == "web") {
	if ($pageurl) {
	if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'page' AND data = '".$pageurl."' AND week = '".date("W")."' AND year = '".date("Y")."'")) == 0) {
	mysql_query("INSERT INTO ".$pre."stats_archive VALUES (null, 'page', '".$pageurl."', '".date("W")."', '".date("n")."', '".date("Y")."', 1, 1, '".time()."')");
	} else {
	mysql_query("UPDATE ".$pre."stats_archive SET views=views+1, date = '".time()."' WHERE name = 'page' AND data = '".$pageurl."'");
	}
	}

	if ($_SERVER['HTTP_REFERER'] && stristr($_SERVER['HTTP_REFERER'], "admin.php") === FALSE) {
	if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'referer' AND data = '".$_SERVER['HTTP_REFERER']."' AND week = '".date("W")."' AND year = '".date("Y")."'")) == 0) {
	mysql_query("INSERT INTO ".$pre."stats_archive VALUES (null, 'referer', '".$_SERVER['HTTP_REFERER']."', '".date("W")."', '".date("n")."', '".date("Y")."', 1, 1, '".time()."')");
	} else {
	mysql_query("UPDATE ".$pre."stats_archive SET views=views+1, date = '".time()."' WHERE name = 'referer' AND data = '".$_SERVER['HTTP_REFERER']."'");
	}
	}

	if ($bot1 == 1) {
	if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'bot' AND data = '".$referer_name."' AND week = '".date("W")."' AND year = '".date("Y")."'")) == 0) {
	mysql_query("INSERT INTO ".$pre."stats_archive VALUES (null, 'bot', '".$referer_name."', '".date("W")."', '".date("n")."', '".date("Y")."', 1, 1, '".time()."')");
	} else {
	mysql_query("UPDATE ".$pre."stats_archive SET views=views+1, date = '".time()."' WHERE name = 'bot' AND data = '".$referer_name."'");
	}
	}

	if ($referer_keyword && $referer_keyword != ".") {
	if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'keyword' AND data = '".$referer_keyword."' AND week = '".date("W")."' AND year = '".date("Y")."'")) == 0) {
	mysql_query("INSERT INTO ".$pre."stats_archive VALUES (null, 'keyword', '".$referer_keyword."', '".date("W")."', '".date("n")."', '".date("Y")."', 1, 1, '".time()."')");
	} else {
	mysql_query("UPDATE ".$pre."stats_archive SET views=views+1, date = '".time()."' WHERE name = 'keyword' AND data = '".$referer_keyword."'");
	}
	}

	if (!$_SESSION[$cookiename."stats"]) {
	if ($pc->os != "") {
	if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'operating_system' AND data = '".$pc->os." ".$pc->os_version."' AND week = '".date("W")."' AND year = '".date("Y")."'")) == 0) {
	mysql_query("INSERT INTO ".$pre."stats_archive VALUES (null, 'operating_system', '".$pc->os." ".$pc->os_version."', '".date("W")."', '".date("n")."', '".date("Y")."', 1, 1, '".time()."')");
	$_SESSION[$cookiename."stats"] = time();
	} else {
	mysql_query("UPDATE ".$pre."stats_archive SET views=views+1, date = '".time()."' WHERE name = 'operating_system' AND data = '".$pc->os." ".$pc->os_version."'");
	$_SESSION[$cookiename."stats"] = time();
	}
	}

	if ($pc->browser != "") {
	if (mysql_num_rows(mysql_query("SELECT * FROM ".$pre."stats_archive WHERE name = 'browser' AND data = '".$pc->browser." ".$pc->browser_version."' AND week = '".date("W")."' AND year = '".date("Y")."'")) == 0) {
	mysql_query("INSERT INTO ".$pre."stats_archive VALUES (null, 'browser', '".$pc->browser." ".$pc->browser_version."', '".date("W")."', '".date("n")."', '".date("Y")."', 1, 1, '".time()."')");
	$_SESSION[$cookiename."stats"] = time();
	} else {
	mysql_query("UPDATE ".$pre."stats_archive SET views=views+1, date = '".time()."' WHERE name = 'browser' AND data = '".$pc->browser." ".$pc->browser_version."'");
	$_SESSION[$cookiename."stats"] = time();
	}
	}
	}
	}

	if ($_COOKIE[$cookiename."timezone"]) {
	putenv("TZ=".$_COOKIE[$cookiename."timezone"]);
	}

	include($sitepath."inc/extras.php");
	}
?>