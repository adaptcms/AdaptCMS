<?php 
/*
 * Are You A Human
 * PHP Integration Library
 *
 * @version 1.1.3
 *
 *    - Documentation and latest version
 *	  http://portal.areyouahuman.com/help
 *    - Get an AYAH Publisher Key
 *	  https://portal.areyouahuman.com
 *    - Discussion group
 *	  http://getsatisfaction.com/areyouahuman
 *
 * Copyright (c) 2011 AYAH LLC -- http://www.areyouahuman.com
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

class AYAH {
	protected $ayah_publisher_key;
	protected $ayah_scoring_key;
	protected $ayah_web_service_host;
	protected $session_secret;

	protected $__debug_mode = FALSE;
	protected $__message_buffer = array();
	protected $__version_number = '1.1.3';

	/**
	 * Constructor
	 * If the session secret exists in input, it grabs it
	 * @param $params associative array with keys publisher_key, scoring_key, web_service_host
	 *
	 */
	public function __construct($params = array())
	{
		// Try to load the ayah_config.php file.
		if ( ! $this->__load_config_file())
		{
			$this->__log("DEBUG", __FUNCTION__, "The ayah_config.php file is missing.");
		}

		if(array_key_exists("session_secret", $_REQUEST)) {
			$this->session_secret = $_REQUEST["session_secret"];
		}

		// Set them to defaults
		$this->ayah_publisher_key = "";
		$this->ayah_scoring_key = "";
		$this->ayah_web_service_host = "ws.areyouahuman.com";

		// If the constants exist, override with those
		if (defined('AYAH_PUBLISHER_KEY')) {
			$this->ayah_publisher_key = AYAH_PUBLISHER_KEY;
		}

		if (defined('AYAH_SCORING_KEY')) {
			$this->ayah_scoring_key = AYAH_SCORING_KEY;
		}

		if (defined('AYAH_WEB_SERVICE_HOST')) {
			$this->ayah_web_service_host = AYAH_WEB_SERVICE_HOST;
		}

		// Lastly grab the parameters input and save them
		foreach (array_keys($params) as $key) {
			if (in_array($key, array("publisher_key", "scoring_key", "web_service_host"))) {
				$variable = "ayah_" . $key;
				$this->$variable = $params[$key];
			} else {
				$this->__log("ERROR", __FUNCTION__, "Unrecognized key for constructor param: '$key'");
			}
		}

		// Generate some warnings if a foot shot is coming
		if ($this->ayah_publisher_key == "") {
			$this->__log("ERROR", __FUNCTION__, "Warning: Publisher key is not defined.  This won't work.");
		}
		else
		{
			$this->__log("DEBUG", __FUNCTION__, "Publisher key: '$this->ayah_publisher_key'");
		}

		if ($this->ayah_scoring_key == "") {
			$this->__log("ERROR", __FUNCTION__, "Warning: Scoring key is not defined.  This won't work.");
		}
		else
		{
			$this->__log("DEBUG", __FUNCTION__, "Scoring key: '$this->ayah_scoring_key'");
		}

		if ($this->ayah_web_service_host == "") {
			$this->__log("ERROR", __FUNCTION__, "Warning: Web service host is not defined.  This won't work.");
		}
		else
		{
			$this->__log("DEBUG", __FUNCTION__, "AYAH Webservice host: '$this->ayah_web_service_host'");
		}
	}

	/**
	 * Returns the markup for the PlayThru
	 *
	 * @return string
	 */
	public function getPublisherHTML($config = array())
	{
		// Initialize.
		$session_secret = "";
		$fields = array('config' => $config);
		$webservice_url = '/ws/setruntimeoptions/' . $this->ayah_publisher_key;

		// If necessary, process the config data.
		if ( ! empty($config))
		{
			// Log it.
			$this->__log("DEBUG", __FUNCTION__, "Setting runtime options...config data='".implode(",", $config)."'");
			
			// Add the gameid to the options url.
			if (array_key_exists("gameid", $config))
			{
				$webservice_url .= '/' . $config['gameid'];
			}
		}

		// Call the webservice and get the response.
		$resp = $this->doHttpsPostReturnJSONArray($this->ayah_web_service_host, $webservice_url, $fields);
		if ($resp)
		{
			// Get the session secret from the response.
			$session_secret = $resp->session_secret;
		
			// Build the url to the AYAH webservice.
			$url = 'https://';						// The AYAH webservice API requires https.
			$url.= $this->ayah_web_service_host;				// Add the host.
			$url.= "/ws/script/";						// Add the path to the API script.
			$url.= urlencode($this->ayah_publisher_key);			// Add the encoded publisher key.
			$url.= (empty($session_secret))? "" : "/".$session_secret;	// If set, add the session_secret.

			// Build and return the needed HTML code.
			return "<div id='AYAH'></div><script src='". $url ."' type='text/javascript' language='JavaScript'></script>";
		}
		else
		{
			// Build and log a detailed message.
			$url = "https://".$this->ayah_web_service_host.$webservice_url;
			$message = "Unable to connect to the AYAH webservice server.  url='$url'";
			$this->__log("ERROR", __FUNCTION__, $message);

			// Build and display a helpful message to the site user.
			$style = "padding: 10px; border: 1px solid #EED3D7; background: #F2DEDE; color: #B94A48;";
			$message = "Unable to load the <i>Are You a Human</i> PlayThru&trade;.  Please contact the site owner to report the problem.";
			echo "<p style=\"$style\">$message</p>\n";
		}
	}

	/**
	 * Check whether the user is a human
	 * Wrapper for the scoreGame API call
	 *
	 * @return boolean
	 */
	public function scoreResult() {
		$result = false;
		if ($this->session_secret) {
			$fields = array(
				'session_secret' => urlencode($this->session_secret),
				'scoring_key' => $this->ayah_scoring_key
			);
			$resp = $this->doHttpsPostReturnJSONArray($this->ayah_web_service_host, "/ws/scoreGame", $fields);
			if ($resp) {
				$result = ($resp->status_code == 1);
			}
		}
		else
		{
			$this->__log("ERROR", __FUNCTION__, "Unable to score the result.  Please check that your ayah_config.php file contains your correct publisher key and scoring key.");
		}

		return $result;
	}

	/**
	 * Records a conversion
	 * Called on the goal page that A and B redirect to
	 * A/B Testing Specific Function
	 *
	 * @return boolean
	 */
	public function recordConversion() {
		// Build the url to the AYAH webservice..
		$url = 'https://';				// The AYAH webservice API requires https.
		$url.= $this->ayah_web_service_host;		// Add the host.
		$url.= "/ws/recordConversion/";			// Add the path to the API script.
		$url.= urlencode($this->ayah_publisher_key);	// Add the encoded publisher key.

		if( isset( $this->session_secret ) ){
			return '<iframe style="border: none;" height="0" width="0" src="' . $url . '"></iframe>';
		} else {
			$this->__log("ERROR", __FUNCTION__, 'AYAH Conversion Error: No Session Secret');
			return FALSE;
		}
	}

	/**
	 * Do a HTTPS POST, return some JSON decoded as array (Internal function)
	 * @param $host hostname
	 * @param $path path
	 * @param $fields associative array of fields
	 * return JSON decoded data structure or empty data structure
	 */
	protected function doHttpsPostReturnJSONArray($hostname, $path, $fields) {
		$result = $this->doHttpsPost($hostname, $path, $fields);

		if ($result) {
			$result = $this->doJSONArrayDecode($result);
		} else {
			$this->__log("ERROR", __FUNCTION__, "Post to https://$hostname$path returned no result.");
			$result = array();
		}

		return $result;
	}

	// Internal function; does an HTTPS post
	protected function doHttpsPost($hostname, $path, $fields) {
		$result = "";
		// URLencode the post string
		$fields_string = "";
		foreach($fields as $key=>$value) {
			if (is_array($value)) {
				if ( ! empty($value)) {
					foreach ($value as $k => $v) {
						$fields_string .= $key . '['. $k .']=' . $v . '&';
					}
				} else {
					$fields_string .= $key . '=&';
				}
			} else {
				$fields_string .= $key.'='.$value.'&';
			}
		}
		rtrim($fields_string,'&');

		// cURL or something else
		if (function_exists('curl_init'))
		{
			// Build the cURL url.
			$curl_url = "https://" . $hostname . $path;

			// Log it.
			$this->__log("DEBUG", __FUNCTION__, "Using cURl: url='$curl_url', fields='$fields_string'");

			// Initialize cURL session.
			if ($ch = curl_init($curl_url))
			{
				// Set the cURL options.
				curl_setopt($ch, CURLOPT_POST, count($fields));
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				// Execute the cURL request.
				$result = curl_exec($ch);

				// Close the curl session.
				curl_close($ch);
			}
			else
			{
				// Log it.
				$this->__log("DEBUG", __FUNCTION__, "Unable to initialize cURL: url='$curl_url'");
			}
		}
		else
		{
			$this->__log("DEBUG", __FUNCTION__, "No cURL support....using fsockopen()");

			// Build a header
			$http_request  = "POST $path HTTP/1.1\r\n";
			$http_request .= "Host: $hostname\r\n";
			$http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
			$http_request .= "Content-Length: " . strlen($fields_string) . "\r\n";
			$http_request .= "User-Agent: AreYouAHuman/PHP " . $this->get_version_number() . "\r\n";
			$http_request .= "Connection: Close\r\n";
			$http_request .= "\r\n";
			$http_request .= $fields_string ."\r\n";

			$result = '';
			$errno = $errstr = "";
			$fs = fsockopen("ssl://" . $hostname, 443, $errno, $errstr, 10);
			if( false == $fs ) {
				$this->__log("ERROR", __FUNCTION__, "Could not open socket");
			} else {
				fwrite($fs, $http_request);
				while (!feof($fs)) {
					$result .= fgets($fs, 4096);
				}

				$result = explode("\r\n\r\n", $result, 2);
				$result = $result[1];
			}
		}

		// Log the result.
		$this->__log("DEBUG", __FUNCTION__, "result='$result'");

		// Return the result.
		return $result;
	}

	// Internal function: does a JSON decode of the string
	protected function doJSONArrayDecode($string) {
		$result = array();

		if (function_exists("json_decode")) {
			try {
				$result = json_decode( $string);
			} catch (Exception $e) {
				$this->__log("ERROR", __FUNCTION__, "Exception when calling json_decode: " . $e->getMessage());
				$result = null;
			}
		} elseif (file_Exists("json.php")) {
			require_once('json.php');
			$json = new Services_JSON();
			$result = $json->decode($string);

			if (!is_array($result)) {
				$this->__log("ERROR", __FUNCTION__, "Expected array; got something else: $result");
				$result = array();
			}
		} else {
			$this->__log("ERROR", __FUNCTION__, "No JSON decode function available.");
		}

		return $result;
	}

	/**
	 * Get the current debug mode (TRUE or FALSE)
	 *
	 * @return boolean
	 */
	public function debug_mode($mode=null)
	{
		// Set it if the mode is passed.
		if (null !== $mode)
		{
			$this->__debug_mode = $mode;

			// Display a message if debug_mode is TRUE.
			if ($mode)
			{
				$version_number = $this->get_version_number();
				$this->__log("DEBUG", "", "Debug mode is now on. (ayah.php version=$version_number)");

				// Flush the buffer.
				$this->__flush_message_buffer();
			}
		}

		// If necessary, set the default.
		if ( ! isset($this->__debug_mode) or (null == $this->__debug_mode)) $this->__debug_mode = FALSE;

		// Return TRUE or FALSE.
		return ($this->__debug_mode)? TRUE : FALSE;
	}

	/**
	 * Get the current version number
	 *
	 * @return string
	 */
	public function get_version_number()
	{
		return (isset($this->__version_number))? $this->__version_number : FALSE;
	}

	/**
	 * Load the config file.
	 *
	 * @return boolean
	 */
	private function __load_config_file()
	{
		// Initialize.
		$name = 'ayah_config.php';
		$locations = array(
			'./',
			// __DIR__."/",
		);

		// Look for the config file in each location.
		foreach ($locations as $location)
		{
			if (file_exists($location.$name))
			{
				require_once($location.$name);
				return TRUE;
			}
		}

		// Could not find the config file.
		return FALSE;
	}

	/**
	 * Log a message
	 *
	 * @return null
	 */
	private function __log($type, $function, $message)
	{
		// Add a prefix to the message.
		$message = __CLASS__ . "::$function: " . $message;

		// Is it an error message?
		if (FALSE !== stripos($type, "error"))
		{
			error_log($message);
		}

		// Build the full message.
		$message_style = "padding: 10px; border: 1px solid #EED3D7; background: #F2DEDE; color: #B94A48;";
		$full_message = "<p style=\"$message_style\"><strong>$type:</strong> $message</p>\n";

		// Output to the screen too?
		if ($this->debug_mode())
		{
			echo "$full_message";
		}
		else
		{
			// Add the message to the buffer in case we need it later.
			$this->__message_buffer[] = $full_message;
		}
	}

	private function __flush_message_buffer()
	{		
		// Flush the buffer.
		if ( ! empty($this->__message_buffer))
		{
			foreach ($this->__message_buffer as $buffered_message)
			{
				// Print the buffered message.
				echo "$buffered_message";
			}
		}
	}
}

