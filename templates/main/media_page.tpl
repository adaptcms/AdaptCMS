<title>{$sitename} - {$media_name}</title>

<h2>{$media_name}</h2>

<table cellpadding="5" cellspacing="1"><tr>
{section name=r loop=$file}
<td><a href='{$file_view[r]}'>{$file_code[r]}</a></td>
{if $smarty.section.r.iteration % 3 == 0}
</tr><tr>
{/if}
{/section}
</tr></table>