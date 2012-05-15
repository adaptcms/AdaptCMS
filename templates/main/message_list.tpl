<table cellpadding='5' cellspacing='0' border='0' width='100%' align='center' style='border: 2px solid #dddddd'><tr style='background:url({$siteurl}inc/images/topbg.jpg) repeat-x;'><td><b>Private Messages</b> - {$folder}</td><td align='right'>{$send_message}</td></tr>
<tr><td align="center" class="light"><b>{$folder}</b> - {$messages_num} messages with a total of {$max_messages} permitted. ({$messages_percent})<br />{$folder_dropdown}</td></tr></table><br>

<table cellpadding='5' cellspacing='0' border='0' width='100%' align='center' style='border: 2px solid #dddddd'><tr style='background:url({$siteurl}inc/images/topbg.jpg) repeat-x;'><td align='center'><b>Icon</b></td><td><b>Subject</b></td><td align='center' style='padding-right:10px'><b>Options</b></td></tr>

{section name=r loop=$messages}
<tr{$class}><td align='center'>{$icon[r]}</td><td>{$subject[r]}<br />From: {$sender[r]} @ {$date[r]}</td><td align='center' style='padding-right:10px'>{$options[r]}</td></tr>
{/section}

</table>