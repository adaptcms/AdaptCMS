<?php
include("../config.php");

$smarty->display($skin.'/header.tpl');

if ($p[1] or $useridn == $useridn) {
$quick_edit = 1;
$smarty->assign("link", "<span id='name|0'><a href='".url("content", 0, htmlentities($_POST['name']), $_GET['section'])."'>".stripslashes(htmlentities($_POST['name']))."</a>&nbsp;&nbsp;</span>");
$smarty->assign($_GET['section']."_link", "<span id='name|0'><a href='".url("content", 0, htmlentities($_POST['name']), $_GET['section'])."'>".stripslashes(htmlentities($_POST['name']))."</a>&nbsp;&nbsp;</span>");
} else {
$smarty->assign("link", "<a href='".url("content", 0, htmlentities($_POST['name']), $_GET['section'])."'>".stripslashes(htmlentities($_POST['name']))."</a>");
$smarty->assign($_GET['section']."_link", "<a href='".url("content", 0, htmlentities($_POST['name']), $_GET['section'])."'>".stripslashes(htmlentities($_POST['name']))."</a>");
}
$smarty->assign("date", timef(time()));
$smarty->assign($_GET['section']."_date", timef(time()));
if ($p[1] && $_GET['id'] != 0 or $useridn == $useridn && $_GET['id'] != 0) {
$smarty->assign("story", "<span id='".$fetch[1]."|0|textarea'>".parse_text($data)."</span>");
$smarty->assign($_GET['section']."_story", "<span id='".$fetch[1]."|0|textarea'>".parse_text($data)."</span>");
} else {
$smarty->assign("story", stripslashes(html_entity_decode(stripslashes($data))));
$smarty->assign($_GET['section']."_story", stripslashes(html_entity_decode(stripslashes($data))));
}
$smarty->assign("comments_link", "<a href='".url("content", 0, htmlentities($_POST['name']), $_GET['section'])."#comments'>Comments</a>");
$smarty->assign($_GET['section']."_comments_link", "<a href='".url("content", 0, htmlentities($_POST['name']), $_GET['section'])."#comments'>Comments</a>");
$smarty->assign("comments_num", $comments_num);
$smarty->assign($_GET['section']."_comments_num", $comments_num);
$smarty->assign("author", get_user($useridn));
$smarty->assign($_GET['section']."_author", get_user($useridn));
$smarty->assign("username", get_user($useridn));
$smarty->assign($_GET['section']."_username", get_user($useridn));
$smarty->assign("section", $_GET['section']);
$smarty->assign($_GET['section']."_section", $_GET['section']);
$smarty->assign("category", $_GET['section']);
$smarty->assign($_GET['section']."_category", $_GET['section']);
$smarty->assign("url", url("content", 0, htmlentities($_POST['name']), $_GET['section']));
$smarty->assign($_GET['section']."_url",  url("content", 0, htmlentities($_POST['name']), $_GET['section']));
$smarty->assign("title", stripslashes(htmlentities($_POST['name'])));
$smarty->assign($_GET['section']."_title", stripslashes(htmlentities($_POST['name'])));
$smarty->assign("subject", stripslashes(htmlentities($_POST['name'])));
$smarty->assign($_GET['section']."_subject", stripslashes(htmlentities($_POST['name'])));
if ($p[1] or $useridn == $useridn) {
$smarty->assign("name", "<span id='name|0'>".stripslashes(htmlentities($_POST['name']))."</span>");
$smarty->assign($_GET['section']."_name", "<span id='name|0'>".stripslashes(htmlentities($_POST['name']))."</span>");
} else {
$smarty->assign("name", stripslashes(htmlentities($_POST['name'])));
$smarty->assign($_GET['section']."_name", stripslashes(htmlentities($_POST['name'])));
}
$smarty->assign("id", 0);
$smarty->assign($_GET['section']."_id", 0);
$smarty->assign("views", number_format($r[views]));
$smarty->assign($_GET['section']."_views", number_format($r[views]));
$smarty->assign("rating", $r[rating]); // temp
$smarty->assign($_GET['section']."_rating", $r[rating]); // temp
$smarty->assign("social_icons", soc_bookmark(url("content", 0, htmlentities($_POST['name']), $_GET['section']), htmlentities($_POST['name']), "", $data, 'Y'));
$smarty->assign($_GET['section']."_social_icons", soc_bookmark(url("content", 0, htmlentities($_POST['name']), $_GET['section']), htmlentities($_POST['name']), "", $data, 'Y'));
$smarty->assign("tags", $tagg);
$smarty->assign($_GET['section']."_tags", $tagg);

// start - custom fields
$name = "";$data = "";$row = "";
$sql_cf = mysql_query("SELECT * FROM ".$pre."fields WHERE section = '".$_GET['section']."' OR section = 'user-profile'");
while ($row = mysql_fetch_array($sql_cf)) {
$name = "$row[name]";

$data[0] = $_POST["cf_".$name];
$fdata[$name] = $data[0];

if (!$data[0]) {
$smarty->assign($name, "");
$smarty->assign($_GET['section']."_".$name, "");
} else {
if (strlen($data[0]) < 100) {
if (check_domain($data[0])) {
$url = 1;
} else {
$url = 0;
}
}
if ($url == 1) {
$smarty->assign($name, stripslashes(html_entity_decode($data[0])));
$smarty->assign($_GET['section']."_".$name, stripslashes(html_entity_decode($data[0])));
} else {
if ($p[1] or $r[user_id] == $useridn or $useridn && $row[editable]) {
$smarty->assign($name, stripslashes(html_entity_decode($data[0])));
$smarty->assign($_GET['section']."_".$name, stripslashes(html_entity_decode($data[0])));
}
}
}
}
// end - custom fields

$smarty->display($skin."/".strtolower(htmlentities($_GET['section'])).".tpl");

$smarty->display($skin.'/footer.tpl');
?>