<?php

class CaptchaHelper extends AppHelper
{
    /**
     * Name of the helper
     * 
     * @var string
     */
    public $name = 'Captcha';

    public $helpers = array(
        'Form'
    );
    
    /**
     * Kind of ugly, loads the SecurImage captcha
     * 
     * @return string
     */
    public function form($name = null)
    {
        $input_options = array(
            'type' => 'text',
            'class' => 'captcha',
            'size' => 12,
            'maxlength' => 8,
            'div' => false,
            'label' => false
        );

        if (empty($name))
        {
            $input_options['name'] = 'captcha';
        }
        else
        {
            $input_options['name'] = $name;
        }

        return '<img id="siimage" style="border: 1px solid #000; margin-right: 15px" src="'. $this->params->webroot.'libraries/captcha/securimage_show.php?sid='.md5(uniqid()).'" alt="CAPTCHA Image" align="left">
<object type="application/x-shockwave-flash" data="'. $this->params->webroot.'libraries/captcha/securimage_play.swf?audio_file='. $this->params->webroot.'libraries/captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000" height="32" width="32">
<param name="movie" value="'. $this->params->webroot.'libraries/captcha/securimage_play.swf?audio_file='. $this->params->webroot.'libraries/captcha/securimage_play.php&amp;bgColor1=#fff&amp;bgColor2=#fff&amp;iconColor=#777&amp;borderWidth=1&amp;borderColor=#000">
</object>
&nbsp;
<a tabindex="-1" style="border-style: none;" href="#" title="Refresh Image"><img class="refresh" src="'. $this->params->webroot.'libraries/captcha/images/refresh.png" alt="Reload Image" onclick="this.blur()" align="bottom" border="0"></a><br />
<strong>Enter Code*:</strong><br />' . $this->Form->input('captcha', $input_options);
    }
}