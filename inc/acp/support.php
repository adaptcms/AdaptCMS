<?php
$smarty->display($skin.'/admin_header.tpl');

while (list($k, $i) = each ($_POST)) {
$data .= "&".$k."=".urlencode($i);
}
while (list($k, $i) = each ($_GET)) {
$data .= "&".$k."=".urlencode($i);
}
echo file_get_contents("http://www.insanevisions.com/share/adaptcms2/support.php?sitename=".urlencode($setting['sitename'])."&siteurl=".urlencode($siteurl)."&share_id=".urlencode($_COOKIE['share_id'])."&version=".urlencode($version)."&domain=".urlencode($domain)."&do=".urlencode($_GET['do'])."&id=".urlencode($_GET['id'])."&go=".urlencode($_GET['go']).$data);
?>