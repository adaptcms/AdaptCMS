<?php

class CodeMirrorHelper extends AppHelper
{
    /**
     * Name of the helper
     * 
     * @var string
     */
    public $name = 'CodeMirror';
    
    /**
     * Needed helpers
     * 
     * @var array
     */
    public $helpers = array(
        'Html'
    );

    /**
     * Renders the CodeMirror editor and specifies by element ID
     * 
     * @param type $template
     * @return string
     */
    public function editor($template)
    {
        $this->Html->script('/libraries/codemirror/lib/codemirror-compressed.js', false);
        echo $this->Html->css('/libraries/codemirror/lib/codemirror.css');
        echo $this->Html->css('/libraries/codemirror/theme/lesser-dark.css');

        echo "<script type='text/javascript'>$(document).ready(function() {";
        if (!is_array($template))
        {
            $template = array($template);
        }

        foreach($template as $id)
        {
            echo "
              var editor = CodeMirror.fromTextArea(document.getElementById('".$id."'), {
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
            ";
        }
        echo "
        });
        </script>";	
    }
}