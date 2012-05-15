<?php
include("../../../config.php");

usleep(1000000 * .5);

/*
 * These are the default parameters that get to the server from the in place editor
 *
 * $_POST['update_value']
 * $_POST['element_id']
 * $_POST['original_html']
 *
*/

/*
 * since the in place editor will display whatever the server returns
 * we're just going to echo out what we recieved. In reality, we can 
 * do validation and filtering on this value to determine if it was valid
*/
echo $_POST['update_value'];
if (1 == 2) {
sleep(3);

$id = $_POST["id"];
$field_type = $_GET['type'];

$ex = explode("|", $_POST['id']);
$name = $ex[0];
$rand = $ex[1];
$item_id = $ex[2];
$data = $_POST[$name."|".$rand."|".$item_id];

if ($form_type == "select") {
	$old_option		= $_POST["old_option"];
	$new_option		= $_POST["new_option"];
	$old_option_text	= $_POST["old_option_text"];
	$new_option_text	= $_POST["new_option_text"];
}

if ($type == "name") {
mysql_query("UPDATE ".$pre."content SET name = '".check(html_entity_decode($data))."', date = '".time()."' WHERE id = '".$id."'");
mysql_query("UPDATE ".$pre."data SET data = '".check(html_entity_decode($data))."' WHERE item_id = '".$id."' AND field_name = 'name'");
} elseif ($field_type == "textarea") {
$autobr = preg_replace("/<br>\n/","\n",$data);
$autobr = preg_replace("/<br \/>\n/","\n",$data);
$autobr = preg_replace("/(\015\012)|(\015)|(\012)/","<br />\n",$data);

mysql_query("UPDATE ".$pre."data SET data = '".preg_replace('#\r?\n#', '<br>', check($autobr))."' WHERE item_id = '".$item_id."' AND field_name = '".$name."'");
} elseif (!$field_type or $field_type == "text") {
mysql_query("UPDATE ".$pre."data SET data = '".check($data)."' WHERE item_id = '".$item_id."' AND field_name = '".$name."'");
} elseif ($field_type == "radio" or $field_type == "select" or $field_type == "check") {
mysql_query("UPDATE ".$pre."data SET data = '".$new_option."' WHERE item_id = '".$item_id."' AND field_name = '".$name."'");
}

echo stripslashes($data);
}
?>