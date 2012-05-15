<?php
/**
 * bit.ly adapter
 *
 * @author Guillermo Rauch
 * @version $Id$
 * @copyright Devthought, 24 April, 2009
 * @package phpshortener
 **/

class PHPShortenerBitLy extends PHPShortenerService {
	
	var $service = 'bit.ly';
	
	var $login = 'bitlyapidemo';
	
	var $apikey = 'R_0da49e0a9118ff35f52f629d2d71bf07';
	
	/**
	 * Encode function
	 *
	 * @param string $url URL to encode
	 * @return mixed Encoded URL or false (if failed)
	 * @author Guillermo Rauch
	 */
	function encode($url){
		$url = sprintf('http://api.bit.ly/shorten?version=%s&longUrl=%s&login=%s&apiKey=%s&format=%s', '2.0.1', urlencode($url), $this->login, $this->apikey, 'xml');
		$response = $this->fetch($url);
		if (preg_match('/<shortUrl>([^<]*)/', $response, $results)) return $results[1];
		return false;
	}
	
}
?>