<?php

class CodeMirrorHelper extends AppHelper{
	public $name = 'CodeMirror';
	public $helpers = array(
		'Html'
	);

	public function editor($template)
	{
		$this->Html->script('/libraries/codemirror/lib/codemirror.js', false);
		echo $this->Html->css('/libraries/codemirror/lib/codemirror.css');
		echo $this->Html->css('/libraries/codemirror/theme/lesser-dark.css');

		$this->Html->script('/libraries/codemirror/mode/javascript/javascript.js', false);
		$this->Html->script('/libraries/codemirror/mode/scheme/scheme.js', false);
		echo "<script type='text/javascript'>
		$(document).ready(function() {
		  var editor = CodeMirror.fromTextArea(document.getElementById('".$template."'), {
		    mode: 'scheme',
		    tabMode: 'indent',
		    theme: 'lesser-dark',
		    lineNumbers: true
		  });
		  editor.on('change', function() {
		    clearTimeout(pending);
		    setTimeout(update, 400);
		  });
		  var pending;
		  function looksLikeScheme(code) {
		    return !/^\s*\(\s*function\b/.test(code) && /^\s*[;\(]/.test(code);
		  }
		  function update() {
		    editor.setOption('mode', looksLikeScheme(editor.getValue()) ? 'scheme' : 'javascript');
		  }
		  update();
		});
		</script>";	
	}
}