<?php
App::uses('AppHelper', 'View/Helper');
/**
 * Class ViewHelper
 *
 * @property HtmlHelper $Html
 */
class ViewHelper extends AppHelper
{
	private $routes = array();
	public $helpers = array('Html');

	/**
	 * Plugin Exists
	 * Returns if supplied plugin exists or not
	 *
	 * @param $string
	 *
	 * @return boolean
	 */
	public function pluginExists($string)
	{
		return in_array($string, Configure::read('Plugins.list'));
	}

	public function setRoutes($data)
	{
		$this->routes = json_decode($data, true);
	}

	public function getRoutes()
	{
		return $this->routes;
	}

	/**
	 * URL
	 *
	 * @param array|null|string $name
	 * @param array $data
	 * @return string
	 */
	public function url($name, $data = array())
	{
		$url = '';
		$routes = Configure::read('current_routes');

		if (empty($routes))
			$routes = $this->getRoutes();

		if (!empty($routes[$name]['route'])) {
			$full_route = $routes[$name]['route'];

			if (!empty($routes[$name]['params'])) {
				foreach($routes[$name]['params'] as $key => $param) {
					if (!is_numeric($param) && !empty($routes[$name]['key']) && !empty($data[$routes[$name]['key']][$param])) {
						$full_route[$key] = $data[$routes[$name]['key']][$param];
					} else {
						if (is_array($data) && !empty($data[$key])) {
							$full_route[$key] = $data[$key];
						} elseif (!empty($data)) {
							$full_route[$key] = $data;
						}
					}
				}
			}

			$url = $this->Html->url($full_route, true);
		}

		return $url;
	}

	/**
	 * Obfuscate Email
	 * @copyright http://www.codeforest.net/obfuscate-your-email-address-with-php-javascript-and-css
	 *
	 * @param $email
	 * @param bool $mailto
	 * @return string
	 */
	public function obfuscateEmail( $email, $mailto = false ) {
		if (!empty($email) && is_array($email)) {
			if (!empty($email['email']) && is_string($email['email'])) {
				$email = $email['email'];
			} elseif (!empty($email['User']['email']) && is_string($email['User']['email'])) {
				$email = $email['User']['email'];
			}
		}

		if (empty($email))
			return '';

		if ($mailto && !strstr($email, 'mailto:'))
			$email = 'mailto:' . $email;

		//We will work with UTF8 characters, just to be safe that we won't mess up any address.
		$emailLetters = preg_split( '//u', $email, null, 1 );
		$obfuscatedEmail = '';

		//Reversing the string (e-mail).
		$emailLetters = array_reverse( $emailLetters );

		//Characters that are to be used when obfuscating email address.
		//If you change this, make sure you change the characters string in JavaScript as well.
		//And please note that the string must have even number of characters for this to work.
		$characters = '123456789qwertzuiopasdfghjklyxcvbnmMNBVCXYLKJHGFDSAPOIUZTREWQ';

		//Get the number of characters dynamically.
		$charactersLength = strlen( $characters ) - 1;

		//Obfuscate string letter by letter.
		foreach( $emailLetters as $letter ) {

			//Get the current letter position in the string.
			$letterPos = strpos($characters, $letter);

			//If the character is present in our string of characters,
			//we'll switch it; if not, we'll leave it as is.
			if( $letterPos !== false ) {

				$letterPos += $charactersLength / 2;

				//For letters that are in our characters string positioned
				//after the total number of characters, we'll start from beginning.
				//For example, "v" becomes "1", "b" becomes "2"
				$letterPos = $letterPos > $charactersLength ? $letterPos - $charactersLength - 1 : $letterPos;

				//Obfuscated letter.
				$newLetter = substr($characters, $letterPos, 1);

			} else {

				//Characters that aren't in our list will be left unchanged.
				$newLetter = $letter;

			}

			//We append obfuscated letter to the result variable.
			$obfuscatedEmail .= $newLetter;

		}

		//Sign @ is a control letter. Since more than one @ sign is illegal
		//in email address, we're going to use two @ symbols to know when
		//the string has been obfuscated (and needs deobfuscation).
		//That way you can use obfuscated e-mail only in href attribute,
		//while the link text can be something entirely different.
		//An example: <a href="mailto:myemail@gmail.com">This is my email</a>.
		return $obfuscatedEmail . '@';
	}
}