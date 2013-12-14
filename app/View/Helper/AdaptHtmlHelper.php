<?php
App::uses('HtmlHelper', 'Helper');
class AdaptHtmlHelper extends HtmlHelper
{
	/**
	 * Script
	 *
	 * @param array|string $url
	 * @param array $options
	 * @return mixed|void
	 */
	function script($url, $options = array())
	{
		if (!isset($options['block']))
			$options['block'] = 'script';

		parent::script($url, $options);
	}

	/**
	 * Css
	 *
	 * @param array|string $path
	 * @param array $options
	 * @return string|void
	 */
	public function css($path, $options = array())
	{
//		if (!isset($options['block']))
//			$options['block'] = 'css';

		parent::css($path, $options);
	}
}