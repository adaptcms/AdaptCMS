<?php
App::uses('AppHelper', 'Helper');
class ViewHelper extends AppHelper
{
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
}