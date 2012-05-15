<?php
// trackback.php - Recieves a trackback, and functions for sending a trackback
// trackback.php - author: Eaden McKee <email@eadz.co.nz>
/*                                                                          
** bBlog Weblog http://www.bblog.com/
** Copyright (C) 2003  Eaden McKee <email@eadz.co.nz>    
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License as published by
** the Free Software Foundation; either version 2 of the License, or 
** (at your option) any later version. 
** 
** This program is distributed in the hope that it will be useful, 
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
** GNU General Public License for more details. 
**
** You should have received a copy of the GNU General Public License
** along with this program; if not, write to the Free Software
** Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/ 

function receive_trackback() {
global $pre;

$tburi_ar = explode('/',$_SERVER['PATH_INFO']);
$tbpost = $tburi_ar[1];
$tbcid  = $_GET['id'];

	// incoming trackback ping. 
	// we checked that :
	// a ) url is suplied by POST
	// b ) that the tbpost, suplied by GET, is valid. 
	// GET varibles from the trackback url:
	if(is_numeric($tbcid) && $tbcid > 0) {
		$replyto = $tbcid;
	} else {
		$replyto = 0;
	}
	
	// POST varibles - the trackback protocol no longer supports GET.  
	$tb_url = addslashes($_POST['url']);
	$title = addslashes($_POST['title']);
	$excerpt = addslashes($_POST['excerpt']);
	$blog_name = addslashes($_POST['blog_name']);

	// according to MT, only url is _required_. So we'll set some useful defaults. 
	
	// if we got this far, we can assume that this file is not included 
	// as part of bBlog but is being called seperatly. 
	// so we include the config file and therefore have access to the 
	// bBlog object.
    if ($blog_name or $tb_url or $excerpt) {
	$q = mysql_query("INSERT INTO ".$pre."comments VALUES (null, '".$_GET['id']."', '<p><a href=\'".$tb_url."\'>".$title."</a> - Trackback</p><p>&quot;".$excerpt."&quot;</p>', '".$blog_name."', 'webmaster@".check_domain($tb_url)."', '".$tb_url."', '".$_SERVER['REMOTE_ADDR']."', '".time()."')");
	}
	
	if ($q == FALSE) { 
		trackback_response(1,"Error adding trackback : ".mysql_error());
	} else {
		trackback_response(0,"");
	}

}



// Send a trackback-ping.
function send_trackback($url, $title="", $excerpt="", $blog_name="", $t) {
    
    //parse the target-url
    $target = parse_url($t);
    
    if ($target["query"] != "") $target["query"] = "?".$target["query"];
    
    //set the port
    if (!is_numeric($target["port"])) $target["port"] = 80;
     
    //connect to the remote-host  
    $fp = fsockopen($target["host"], $target["port"]);
    
    if ($fp){

        // build the Send String
        $Send = "url=".rawurlencode($url).
                "&title=".rawurlencode($title).
                "&blog_name=".rawurlencode($blog_name).
                "&excerpt=".rawurlencode($excerpt);
        
        // send the ping
        fputs($fp, "POST ".$target["path"].$target["query"]." HTTP/1.1\n");
        fputs($fp, "Host: ".$target["host"]."\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
        fputs($fp, "Content-length: ". strlen($Send)."\n");
        fputs($fp, "Connection: close\n\n");
        fputs($fp, $Send);

		//print_r($target);
        
        //read the result
        while(!feof($fp)) {
            $res .= fgets($fp, 128);
        }
        
        //close the socket again  
        fclose($fp);
        
        //return success        
        return true;
    }else{
    
        //return failure
        return false;
    }
    
}

function trackback_response($error = 0, $error_message = '') {
	header("Content-Type: application/xml");    
	if ($error) {
		echo '<?xml version="1.0" encoding="iso-8859-1"?'.">\n";
		echo "<response>\n";
		echo "<error>1</error>\n";
		echo "<message>$error_message</message>\n";
		echo "</response>";
	} else {
		echo '<?xml version="1.0" encoding="iso-8859-1"?'.">\n";
		echo "<response>\n";
		echo "<error>0</error>\n";
		echo "</response>";
	}
	die();
}

    /**
     * Search text for links, and searches links for trackback URLs.
     * 
     * <code><?php
     * 
     * include('trackback_cls.php');
     * $trackback = new Trackback('BLOGish', 'Ran Aroussi', 'UTF-8');
     * 
     * if ($tb_array = $trackback->auto_discovery(string TEXT)) {
     * 	// Found trackbacks in TEXT. Looping...
     * 	foreach($tb_array as $tb_key => $tb_url) {
     * 	// Attempt to ping each one...
     * 		if ($trackback->ping($tb_url, string URL, [string TITLE], [string EXPERT])) {
     * 			// Successful ping...
     * 			echo "Trackback sent to <i>$tb_url</i>...\n";
     * 		} else {
     * 			// Error pinging...
     * 			echo "Trackback to <i>$tb_url</i> failed....\n";
     * 		}
     * 	}
     * } else {
     * 	// No trackbacks in TEXT...
     * 	echo "No trackbacks were auto-discovered...\n"
     * }
     * ?</code>
     * 
     * @param string $text 
     * @return array Trackback URLs.
     */

function auto_discovery($text)
    { 
        // Get a list of UNIQUE links from text...
        // ---------------------------------------
        // RegExp to look for (0=>link, 4=>host in 'replace')
        $reg_exp = "/(http)+(s)?:(\\/\\/)((\\w|\\.)+)(\\/)?(\\S+)?/i"; 
        // Make sure each link ends with [sapce]
        $text = eregi_replace("www.", "http://www.", $text);
        $text = eregi_replace("http://http://", "http://", $text);
        $text = eregi_replace("\"", " \"", $text);
        $text = eregi_replace("'", " '", $text);
        $text = eregi_replace(">", " >", $text); 
        // Create an array with unique links
        $uri_array = array();
        if (preg_match_all($reg_exp, strip_tags($text, "<a>"), $array, PREG_PATTERN_ORDER)) {
            foreach($array[0] as $key => $link) {
                foreach((array(",", ".", ":", ";")) as $t_key => $t_value) {
                    $link = trim($link, $t_value);
                } 
                $uri_array[] = ($link);
            } 
            $uri_array = array_unique($uri_array);
        } 
        // Get the trackback URIs from those links...
        // ------------------------------------------
        // Loop through the URIs array and extract RDF segments
        $rdf_array = array(); // <- holds list of RDF segments
        foreach($uri_array as $key => $link) {
            if ($link_content = implode("", @file($link))) {
                preg_match_all('/(<rdf:RDF.*?<\/rdf:RDF>)/sm', $link_content, $link_rdf, PREG_SET_ORDER);
                for ($i = 0; $i < count($link_rdf); $i++) {
                    if (preg_match('|dc:identifier="' . preg_quote($link) . '"|ms', $link_rdf[$i][1])) {
                        $rdf_array[] = trim($link_rdf[$i][1]);
                    } 
                } 
            } 
        } 
        // Loop through the RDFs array and extract trackback URIs
        $tb_array = array(); // <- holds list of trackback URIs
        if (!empty($rdf_array)) {
            for ($i = 0; $i < count($rdf_array); $i++) {
                if (preg_match('/trackback:ping="([^"]+)"/', $rdf_array[$i], $array)) {
                    $tb_array[] = trim($array[1]);
                } 
            } 
        } 
        // Return Trackbacks
        return $tb_array;
    } 
?>
