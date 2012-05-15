{if $i == 0}
<div align='center'><h3>Friends List</h3></div>

<table cellpadding='5' cellspacing='2' border='0' width='90%'>
{/if}

<tr><td>{$friend_username}</td><td>{$friend_last_login}</td><td>{$friend_status}</td></tr>

{if $i == 0}
</table><br />
{/if}