<table cellpadding='0' cellspacing='0' border='0' align='center' width='75%'><tr><td><b>{$question}</b></td></tr></table><br clear='all'>

<table cellpadding='0' cellspacing='0' border='0' align='center' width='75%'>

{section name=sec loop=$options}
<tr><td>{$options[sec]}</td><td>{$options_data[sec]}</td></tr>
{/section}

<tr><td><b>Votes:</b> {$vote_total}</td></tr></table><br clear='all'>