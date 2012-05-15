<?php
$smarty->display($skin.'/header.tpl');
echo $js_includes;

if (!$_GET['do']) {
echo media("media_list", "media_list", 12, "", "", "", "", 1);
} elseif ($_GET['do'] == "gallery") {
echo media("media_page", "media_page", 12, $_GET['id'], "", "", "", 1);
} elseif ($_GET['do'] == "file") {
echo media("file_view", "file_view", 1, $_GET['id']);
}


?>