<?php
//require_once('libcurlemu.inc.php');
//
// Detector class (c) Mohammad Hafiz bin Ismail 2006
// detect location by ipaddress
// detect browser type and operating system
//
// November 27, 2006
//
// by : Mohammad Hafiz bin Ismail (info@mypapit.net)
//
//
//
// EXAMPLE USAGE :
//	require_once('detector.php');
//
//	$dip = &new Detector('205.144.25.123');
//	$dip = &new Detector($_SERVER['REMOTE_ADDR'],$_SERVER['HTTP_USER_AGENT']);
//
//	echo "$dip->country $dip->latitude $dip->longitude $dip->state" ;
//
// 
// You are allowed to use this work under the terms of 
// Creative Commons Attribution-Share Alike 3.0 License
// 
// Reference : http://creativecommons.org/licenses/by-sa/3.0/
// 
class Detector {
	
	var $town;
	var $state;
	var $country;
	var $ccode;
	var $longitude;
	var $latitude;
	var $ipaddress;
	var $txt;
	
	var $browser;
	var $browser_version;
	var $os_version;
	var $os;
	var $useragent;
	
	
	function Detector($ip, $ua="")
	{	
		$apiserver="http://showip.fakap.net/txt/";
		
		if (preg_match('/\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/',$ip,$matches))
		  {
		    $this->ipaddress=$ip;
		  }
  	  
		else { $this->ipaddress = "0.0.0.0"; }
		

		//uncomment this below if CURL doesnt work		
		//$this->txt=file_get_contents($apiserver . "$ip");
		
		
		// if CURL isn't installed, then 
		// comment the following lines until you reach "STOP COMMENTING"
		if (extension_loaded('curl') or function_exists('curl_init')) {
		$ch = @curl_init();
		$timeout = 25; // set to zero for no timeout
		@curl_setopt ($ch, CURLOPT_URL, $apiserver . "$ip" );
		@curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		@curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$this->txt = @curl_exec($ch);
		@curl_close($ch);
		}
		//** STOP COMMENTING
		
		$wtf=$this->txt;
		$this->processTxt($wtf);
		$this->useragent=$ua;
		$this->check_os($ua);
		$this->check_browser($ua);
		
	}
	
	function processTxt($wtf)
	{
	
//	  	$tok = strtok($txt, ',');
	  	$this->town = strtok($wtf,',');
	  	$this->state = strtok(',');
	  	$this->country=strtok(',');
	  	$this->ccode = strtok(',');
	  	$this->latitude=strtok(',');
	  	$this->longitude=strtok(',');
	  		  	
	}
	
	
	
	function check_os($useragent) {
			$os = ""; $version = "";
			if (preg_match("/Windows NT 5.1/",$useragent,$match) or preg_match("/Windows NT 5.2/",$useragent,$match)) {
				$os = "Windows"; $version = "XP";
			} elseif (preg_match("/Windows NT 6.1/",$useragent,$match)) {
				$os = "Windows"; $version = "7";
			} elseif (preg_match("/Windows NT 6.0/",$useragent,$match)) {
				$os = "Windows"; $version = "Vista";
			} elseif (preg_match("/(?:Windows NT 5.0|Windows 2000)/",$useragent,$match)) {
				$os = "Windows"; $version = "2000";
			} elseif (preg_match("/(?:WinNT|Windows\s?NT)\s?([0-9\.]+)?/",$useragent,$match)) {
				$os = "Windows"; $version = "NT ".$match[1];
			} elseif (preg_match("/Mac OS X/",$useragent,$match)) {
				$os = "Mac OS"; $version = "X";
			} elseif (preg_match("/(Mac_PowerPC|Macintosh)/",$useragent,$match)) {
				$os = "Mac OS"; $version = "";
			} elseif (preg_match("/(?:Windows95|Windows 95|Win95|Win 95)/",$useragent,$match)) {
				$os = "Windows"; $version = "95";
			} elseif (preg_match("/(?:Windows98|Windows 98|Win98|Win 98)/",$useragent,$match)) {
				$os = "Windows"; $version = "98";
			} elseif (preg_match("/(?:WindowsCE|Windows CE|WinCE|Win CE)/",$useragent,$match)) {
				$os = "Windows"; $version = "CE";
			} elseif (preg_match("/PalmOS/",$useragent,$match)) {
				$os = "PalmOS";
			} elseif (preg_match("/Ubuntu\/([0-9\.]+)/",$useragent,$match)) {
				$os = "Ubuntu";
			} elseif (preg_match("/Fedora\/([0-9\.]+)/",$useragent,$match)) {
				$os = "Fedora";
			} elseif (preg_match("/\(PDA(?:.*)\)(.*)Zaurus/",$useragent,$match)) {
				$os = "Sharp Zaurus";
			} elseif (preg_match("/Linux\s*((?:i[0-9]{3})?\s*(?:[0-9]\.[0-9]{1,2}\.[0-9]{1,2})?\s*(?:i[0-9]{3})?)?/",$useragent,$match)) {
				$os = "Linux"; $version = $match[1];
			} elseif (preg_match("/NetBSD\s*((?:i[0-9]{3})?\s*(?:[0-9]\.[0-9]{1,2}\.[0-9]{1,2})?\s*(?:i[0-9]{3})?)?/",$useragent,$match)) {
				$os = "NetBSD"; $version = $match[1];
			} elseif (preg_match("/OpenBSD\s*([0-9\.]+)?/",$useragent,$match)) {
				$os = "OpenBSD"; $version = $match[1];
			} elseif (preg_match("/CYGWIN\s*((?:i[0-9]{3})?\s*(?:[0-9]\.[0-9]{1,2}\.[0-9]{1,2})?\s*(?:i[0-9]{3})?)?/",$useragent,$match)) {
				$os = "CYGWIN"; $version = $match[1];
			} elseif (preg_match("/SunOS\s*([0-9\.]+)?/",$useragent,$match)) {
				$os = "SunOS"; $version = $match[1];
			} elseif (preg_match("/IRIX\s*([0-9\.]+)?/",$useragent,$match)) {
				$os = "SGI IRIX"; $version = $match[1];
			} elseif (preg_match("/FreeBSD\s*((?:i[0-9]{3})?\s*(?:[0-9]\.[0-9]{1,2})?\s*(?:i[0-9]{3})?)?/",$useragent,$match)) {
				$os = "FreeBSD"; $version = $match[1];
			} elseif (preg_match("/SymbianOS\/([0-9.]+)/i",$useragent,$match)) {
				$os = "SymbianOS"; $version = $match[1];
			} elseif (preg_match("/Symbian\/([0-9.]+)/i",$useragent,$match)) {
				$os = "Symbian"; $version = $match[1];
			}
			
			
			
			$this->os = $os;
			$this->os_version = $version;
		}
		
		function check_browser($useragent) {
			$browser = "";
		
			if (preg_match("/^Mozilla(?:.*)compatible;\sMSIE\s(?:.*)Opera\s([0-9\.]+)/",$useragent,$match)) {
				$browser = "Opera";
			} elseif (preg_match("/^Opera\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "Opera";
			} elseif (preg_match("/Chrome\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "Chrome";
			} elseif (preg_match("/^Mozilla(?:.*)compatible;\siCab\s([0-9\.]+)/",$useragent,$match)) {
				$browser = "iCab";
			} elseif (preg_match("/^iCab\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "iCab";
			} elseif (preg_match("/^Mozilla(?:.*)compatible;\sMSIE\s([0-9\.]+)/",$useragent,$match)) {
				$browser = "MSIE";
			} elseif (preg_match("/^Mozilla(?:.*)\(Macintosh(?:.*)Safari\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "Safari";
			} elseif (preg_match("/^Mozilla(?:.*)\(Macintosh(?:.*)OmniWeb\/v([0-9\.]+)/",$useragent,$match)) {
				$browser = "Omniweb";
			} elseif (preg_match("/^Mozilla(?:.*)\(compatible;\sOmniWeb\/([0-9\.v-]+)/",$useragent,$match)) {
				$browser = "Omniweb";
			} elseif (preg_match("/^Mozilla(?:.*)Gecko(?:.*?)Netscape\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "Netscape";
			} elseif (preg_match("/^Mozilla(?:.*)Gecko(?:.*?)(?:Fire(?:fox|bird)|Phoenix)\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "Mozilla Firefox";
			} elseif (preg_match("/^Mozilla(?:.*)Gecko(?:.*?)Epiphany\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "Epiphany";
			} elseif (preg_match("/^Mozilla(?:.*)Galeon\/([0-9\.]+)\s(?:.*)Gecko/",$useragent,$match)) {
				$browser = "Galeon";
			} elseif (preg_match("/^Mozilla(?:.*)Gecko(?:.*?)K-Meleon\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "K-Meleon";
			} elseif (preg_match("/^Mozilla(?:.*)Gecko(?:.*?)(?:Camino|Chimera)\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "Camino";
			} elseif (preg_match("/^Mozilla(?:.*)rv:([0-9\.]+)\)\sGecko/",$useragent,$match)) {
				$browser = "Mozilla";
			} elseif (preg_match("/^Mozilla(?:.*)compatible;\sKonqueror\/([0-9\.]+);/",$useragent,$match)) {
				$browser = "Konqueror";
			} elseif (preg_match("/^Mozilla\/(?:[34]\.[0-9]+)(?:.*)AvantGo\s([0-9\.]+)/",$useragent,$match)) {
				$browser = "AvantGo";
			} elseif (preg_match("/^Mozilla(?:.*)NetFront\/([34]\.[0-9]+)/",$useragent,$match)) {
				$browser = "NetFront";
			} elseif (preg_match("/^Mozilla\/([34]\.[0-9]+)/",$useragent,$match)) {
				$browser = "Netscape";
			} elseif (preg_match("/Traveler\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "Traveler";
		    } elseif (preg_match("/TheWorld\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "TheWorld";
		    } elseif (preg_match("/Maxthon\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "Maxthon";
			} elseif (preg_match("/^curl\/([0-9\.]+)/",$useragent,$match)) {
				$browser = "curl";
			} elseif (preg_match("/^links\/([0-9\.]+)/i",$useragent,$match)) {
				$browser = "Links";
			} elseif (preg_match("/^links\s?\(([0-9\.]+)/i",$useragent,$match)) {
				$browser = "Links";
			} elseif (preg_match("/^lynx\/([0-9a-z\.]+)/i",$useragent,$match)) {
				$browser = "Lynx";
			} elseif (preg_match("/^Wget\/([0-9\.]+)/i",$useragent,$match)) {
				$browser = "Wget";
			} elseif (preg_match("/^Xiino\/([0-9\.]+)/i",$useragent,$match)) {
				$browser = "Xiino";
			} elseif (preg_match("/^W3C_Validator\/([0-9\.]+)/i",$useragent,$match)) {
				$browser = "W3C Validator";
			} elseif (preg_match("/^Jigsaw(?:.*) W3C_CSS_Validator_(?:[A-Z]+)\/([0-9\.]+)/i",$useragent,$match)) {
				$browser = "W3C CSS Validator";
			} elseif (preg_match("/^Dillo\/([0-9\.]+)/i",$useragent,$match)) {
				$browser = "Dillo";
			} elseif (preg_match("/^amaya\/([0-9\.]+)/i",$useragent,$match)) {
				$browser = "Amaya";
			} elseif (preg_match("/^DocZilla\/([0-9\.]+)/i",$useragent,$match)) {
				$browser = "DocZilla";
			} elseif (preg_match("/^fetch\slibfetch\/([0-9\.]+)/i",$useragent,$match)) {
				$browser = "FreeBSD libfetch";
			} elseif (preg_match("/^Nokia([0-9a-zA-Z\-.]+)\/([0-9\.]+)/i",$useragent,$match)) {
				$browser="Nokia";
			} elseif (preg_match("/^SonyEricsson([0-9a-zA-Z\-.]+)\/([a-zA-Z0-9\.]+)/i",$useragent,$match)) {
				$browser="SonyEricsson";
			}
			$version = $match[1];
			
			$this->browser = $browser;
			$this->browser_version = $version;
			
	}

}

?>