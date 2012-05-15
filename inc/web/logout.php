<?php
if ($useridn) {
$_SESSION[$cookiename."username"] = "";
$_SESSION[$cookiename."password"] = "";
setcookie($cookiename."username", "", time()-60*60*24*30);
setcookie($cookiename."password", "", time()-60*60*24*30);

$smarty->display($skin.'/header.tpl');
echo re_direct("1500", $siteurl);
echo "You are now logged out! <a href='".$siteurl."'>Return</a>";
}

?>