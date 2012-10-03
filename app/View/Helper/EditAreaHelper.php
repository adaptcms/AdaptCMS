<?php

class EditAreaHelper extends AppHelper{
	public $name = 'EditArea';
	public $helpers = array('Html');

	public function editor($template)
	{
		$this->Html->script('/js/edit_area/edit_area_full.js', false);
		echo "<script language='Javascript' type='text/javascript'>
			editAreaLoader.init({
				id: '".$template."'	// id of the textarea to transform		
				,start_highlight: true	// if start with highlight
				,allow_resize: 'both'
				,allow_toggle: true
				,toolbar: 'search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, highlight, reset_highlight, |, help'
				,syntax_selection_allow: 'css,html,js,php'
				,word_wrap: true
				,language: 'en'
				,syntax: 'html'	
			});
		</script>";
	}
}