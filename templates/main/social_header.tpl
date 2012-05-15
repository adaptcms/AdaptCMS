<table width='95%' cellpadding='2' cellspacing='0' style='padding-left:10px'><tr><td valign="top">

<table width='100%' cellpadding='1' cellspacing='0'><tr><td width="28%"><img src="{$avatar}"><br />{$status}<br>@ <i>{$status_time}</i></td><td width="72%"><h2>{$username}</h2> {$status_update}

<div id='js_menu'>
	<ul>
	{if $username == $user_name}
	<li><a href='{$edit_profile_url}'>Edit</a></li>
	{/if}
		<li><a href='{$profile_url}'>Profile</a>
		<ul>
				<li><a href='{$status_url}'>Status Page</a></li>
				</ul>	
		</li>
		<li><a href='{$friends_url}'>Friends</a>			
			<ul>
						<li><a href='{$friends_url}'>View all</a></li> 
						<li><a href='{$friends_url_req}'>View Requests</a></li>
			</ul>
		</li>
		<li><a href='{$blogs_url}'>Blogs</a>
			<ul>
				<li><a href='{$blogs_url_add}'>Add Blog</a></li>
				<li><a href='{$blogs_url}'>Manage Blogs</a></li>
			</ul>	
		</li>
		<li><a href='{$messages_url}'>Messages</a>
	</ul>
	</div>

</td></tr></table>

</td></tr><tr><td>

<table width='100%' cellpadding='2' cellspacing='0'><tr><td width="28%">&nbsp;</td><td width="72%">