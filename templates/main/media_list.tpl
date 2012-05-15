<table cellpadding="5" cellspacing="2"><tr>
{section name=med loop=$media}
<td><a href='{$media_url[med]}'>{$media_image[med]}</a><br />{$media_name[med]}</td>
{if $smarty.section.med.iteration % 3 == 0}
</tr><tr>
{/if}
{/section}
</tr></table>