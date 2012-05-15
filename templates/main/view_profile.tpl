<table cellpadding="3" cellspacing="0" width="90%" align="center">
<tr><td>Group: {$group}</td></tr>
<tr><td>Level: {$level}</td></tr>
<tr><td>Last Login: {$last_login}</td></tr>
</table>

<div align="center"><h3>Updates</h3></div>
<table cellpadding="3" cellspacing="2" width="90%" align="center">

{section name=r loop=$statuses}
<tr><td><img src="{$status_avatar[r]}" width="48"></td><td><b>{$status_username[r]}</b> {$status_data[r]}<br /><small>{$status_date[r]}</td></tr>
{/section}
</table>