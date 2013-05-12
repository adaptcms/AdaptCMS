<?php
/**
 * Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The LGPL License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2010, Cake Development Corporation (http://cakedc.com)
 * @license LGPL License (http://www.opensource.org/licenses/lgpl-2.1.php)
 */

/**
 * TinyMCE Helper
 *
 * @package tiny_m_c_e
 * @subpackage tiny_m_c_e.views.helpers
 */

class TinyMceHelper extends AppHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array('Html');

/**
 * Configuration
 *
 * @var array
 */
	public $configs = array();

/**
 * Default values
 *
 * @var array
 */
	protected $_defaults = array();

/**
 * Adds a new editor to the script block in the head
 *
 * @see http://wiki.moxiecode.com/index.php/TinyMCE:Configuration for a list of keys
 * @param mixed If array camel cased TinyMce Init config keys, if string it checks if a config with that name exists
 * @return void
 */
	public function editor($options = array()) {
		if (!empty($options['mode'])) {
			$mode = $options['mode'];
		} else {
			$mode = 'textareas';
		}

		if (!empty($options['elements'])) {
			$elements = $options['elements'];
		} else {
			$elements = 'abshosturls';
		}

		if (!empty($options['simple']))
		{
			$buttons1 = 'bold,italic,underline,|,formatselect,|,bullist,numlist,|,replace,preview,link,unlink,|,image,emoticons,';
			$buttons2 = '';
		} else {
			$buttons1 = 'undo redo | styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image';
			$buttons1 .= ' | preview media emoticons | code';
			$buttons2 = '';
		}

		// remove last comma from lines to avoid the editor breaking in Internet Explorer
		echo "<script type='text/javascript'>
		tinyMCE.init({
			// General options
			mode : '" . $mode . "',
			theme : 'modern',
			elements : '" . $elements . "',
			plugins : [
		        'autosave advlist autolink lists link image charmap print preview hr anchor pagebreak',
		        'searchreplace wordcount visualblocks visualchars code fullscreen',
		        'insertdatetime media nonbreaking save table contextmenu directionality',
		        'emoticons template paste code textcolor'
			],

			toolbar1 : '" . $buttons1 . "',
			toolbar2 : '" . $buttons2 . "',

			extended_valid_elements :'script[src|language|type|class]',

			remove_linebreaks : false,
	        force_p_newlines : false,
			debug : false,
			relative_urls : false,
			remove_script_host : false
		});
	</script>";
	$this->Html->script('/js/tinymce/tinymce.min.js', false);
	}

/**
 * beforeRender callback
 *
 * @return void
 */
	public function beforeRender() {
		$appOptions = Configure::read('TinyMCE.editorOptions');
		if ($appOptions !== false && is_array($appOptions)) {
			$this->_defaults = $appOptions;
		}	
	}
}