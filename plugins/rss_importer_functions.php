<?php
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

$path = $sitepath."cache";
include_once($sitepath."includes/rss/simplepie.inc");

$feed = new SimplePie();
$sql = mysql_query("SELECT * FROM ".$pre."fielddata WHERE aid = 'plugin_rss' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {
$freq = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'freq'"));
$date = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'last_update'"));
$time = $freq[0] * 60;
$diff = time() - $date[0];

if ($freq[0]) {
if ($diff > $time or $date[0] == 0 or $_GET['rss_manual'] == 1) {
$url = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'url'"));
$section = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'section'"));

$limit = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'limit'"));

$field_url = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'field_url'"));
$field_des = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'field_des'"));

$lev = mysql_fetch_row(mysql_query("SELECT name FROM ".$pre."levels WHERE value = '1' ORDER BY `id` DESC LIMIT 1"));
$user = mysql_fetch_row(mysql_query("SELECT username FROM ".$pre."users WHERE level = '".$lev[0]."' ORDER BY `id` ASC LIMIT 1"));

$exclude = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'exclude'"));
$include = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'include'"));

$gallery = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'Gallery'"));

$sqlst = mysql_query("SELECT * FROM ".$pre."relations WHERE sub = '".$section[0]."' OR section = '".$section[0]."'");
while($y = mysql_fetch_array($sqlst)) {
if ($y[sub] == $section[0]) {
$namem = $y[section];
} else {
$namem = $y[sub];
}

$grab = mysql_fetch_row(mysql_query("SELECT data FROM ".$pre."fielddata WHERE aid = 'plugin_rss_".$r[id]."' AND fname = '".$namem."'"));

$array[] = $namem;
$ids[$namem] = str_replace("plugin_rss_","",$grab[0]);
}

$feed->force_feed(true);
$feed->set_feed_url($url[0]);


$feed->set_image_handler($siteurl.'includes/rss/handler_image.php', 'imagem');
$feed->set_favicon_handler($siteurl.'includes/rss/handler_image.php', 'favicon');

$feed->set_cache_duration($time);
$feed->set_cache_location($sitepath."cache");
$feed->set_item_limit($limit[0]);

$feed->init();
$feed->handle_content_type();

if ($feed->error()) {
echo $feed->error()."-".$url[0];
} else {
foreach(array_reverse($feed->get_items(0, $limit[0])) as $item):
unset($skip, $go, $title, $link, $desc);

$title = rtrim(trim(addslashes($item->get_title())));
if ($item->get_id()) {
$link = $item->get_id();
} elseif ($item->get_permalink()) {
$link = $item->get_permalink();
} else {
$link = $item->get_link();
}
$date = $item->get_date();

if ($enclosure = $item->get_enclosure(0)) {
if (preg_match("/image/", $enclosure->get_type())) {
$desc .= "<img src='".$enclosure->get_link()."' style='float:left'>";
} else {
$desc .= '<p>' . $enclosure->native_embed(array('audio' => $siteurl."includes/rss/includes/place_audio.png",'video' => $siteurl."includes/rss/includes/place_video.png",'mediaplayer' => $siteurl."includes/rss/includes/mediaplayer.swf", 'alt' => '<img src="'.$siteurl.'includes/rss/includes/mini_podcast.png" class="download" border="0" title="Download the Podcast (' . $enclosure->get_extension() . '; ' . $enclosure->get_size() . ' MB)" />','altclass' => 'download')) . '</p>';
$desc .= '<p class="footnote" align="center">(' . $enclosure->get_type();
if ($enclosure->get_size()) {
$desc .= '; ' . $enclosure->get_size() . ' MB';								
}
$desc .= ')</p>';
}
}

$desc .= $item->get_content();

if ($exclude[0]) {
$exc = explode(",", $exclude[0]);
while (list(, $x) = each ($exc)) {
if (preg_match("/".$x."/", $title) or preg_match("/".$x."/", $desc)) {
$skip = 1;
}
}
}

if ($include[0]) {
$inc = explode(",", $include[0]);
while (list(, $in) = each ($inc)) {
if (preg_match("/".$in."/", $title) or preg_match("/".$in."/", $desc)) {
$go = 1;
}
}
} else {
$go = 1;
}

if (!$skip && $go) {
$updated = 1;
mysql_query("UPDATE ".$pre."fielddata SET data = '".time()."' WHERE aid = 'plugin_rss_".$r[id]."' AND fname = 'last_update'");
$check = mysql_fetch_row(mysql_query("SELECT * FROM ".$pre."articles WHERE name = '".$title."'"));
if ($check == 0) {
mysql_query("INSERT INTO ".$pre."articles VALUES (null, '".$title."', '".$user[0]."', '".addslashes($section[0])."', '', '".time()."', '".date("m")."', '".date("Y")."', '0|0', 0)");
$id = mysql_fetch_row(mysql_query("SELECT id FROM ".$pre."articles WHERE name = '".$title."' ORDER BY `date` DESC LIMIT 1"));
mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, 'name', '".$title."', '".$id[0]."')");
if ($field_url[0]) {
mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, '".$field_url[0]."', '".$link."', '".$id[0]."')");
} else {
$desc .= "<br><a href='".$link."'></a>";
}
if ($field_des[0]) {
mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, '".$field_des[0]."', '".addslashes($desc)."', '".$id[0]."')");
}
if ($array) {
while (list(, $n) = each ($array)) {
if ($ids[$n]) {
mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, '".$n."', '".$ids[$n]."', '".$id[0]."')");
mysql_query("INSERT INTO ".$pre."fielddata VALUES (null, '".$section[0]."', '".$id[0]."', '".$ids[$n]."')");
}

}
}

}
}

endforeach;
}

}

}

}

$feed->__destruct();
unset($feed); 

if ($updated) {
$mtime = explode(' ', microtime());
//echo "Page processed in ".round($mtime[0] + $mtime[1] - $starttime, 3)." seconds.";
}
?>