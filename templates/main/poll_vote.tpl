{$poll_header}
<table cellpadding='0' cellspacing='0' border='0' align='center' width='200'><tr><td><b>{$question}</b></td></tr></table><br>

<table cellpadding='0' cellspacing='0' border='0' align='center' width='200'>

{section name=sec loop=$options}
<tr><td>{$options_data[sec]}</td><td>{$options[sec]}</td></tr>
{/section}

<tr><td><br><br>{$submit}</td><td><a href="{$siteurl}poll-results">Results</a></td></tr></table></form>