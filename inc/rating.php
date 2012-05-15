<?php
include("dbinfo.php");
include("functions.php");
mysql_select_db($dbname, mysql_connect($dbhost, $dbuser, $dbpass));

if (!$useridn && strtolower($setting["ratings_guests_content"]) == "yes" && is_numeric($_GET['id']) && $type == "content" or $useridn && is_numeric($_GET['id']) or $type == "comments" && !$useridn && strtolower($setting["ratings_guests_comments"]) == "yes" && is_numeric($_GET['id'])) {
$rat_num = check($_GET['rat_num']) + 1;
$rat_total = check($_GET['rat_tot']) + check($_GET['v']);
$id = check($_GET['id']);
$rating = $rat_num."|".$rat_total;
$type = check($_GET['type']);
$v = check($_GET['v']);

if ($type == "content") {
mysql_query("UPDATE ".$pre."content SET rating = '".$rating."' WHERE id = '".$id."'");
} elseif ($type == "comments") {
mysql_query("UPDATE ".$pre."comments SET rating = '".$rating."' WHERE id = '".$id."'");
}
$_SESSION["rating_".$type."_".$id] = 1;
}
?>
<img src="<?php echo $siteurl; ?>images/star_<?php echo( ($v>0)?'on':'off' ) ?>.gif"></img>
<img src="<?php echo $siteurl; ?>images/star_<?php echo( ($v>1)?'on':'off' ) ?>.gif"></img>
<img src="<?php echo $siteurl; ?>images/star_<?php echo( ($v>2)?'on':'off' ) ?>.gif"></img>
<img src="<?php echo $siteurl; ?>images/star_<?php echo( ($v>3)?'on':'off' ) ?>.gif"></img>
<img src="<?php echo $siteurl; ?>images/star_<?php echo( ($v>4)?'on':'off' ) ?>.gif"></img><br />
<i>You voted a <b><?php echo $v; ?></b>!</i>