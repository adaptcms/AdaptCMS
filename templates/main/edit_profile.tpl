<title>{$sitename} - Edit Profile</title><a href='index.php'>Directory</a>&nbsp;&nbsp;-&nbsp;&nbsp;Social / Edit Profile<br /><br />
{$form_start}<table>
<tr><td>Username</td><td>{$username_input}</td></tr>
<tr><td>New Password</td><td>{$password_input}</td></tr>
<tr><td>Password Confirm</td><td>{$password_input2}</td></tr>
<tr><td>E-Mail</td><td>{$email_input}</td></tr>
<tr><td>Skin</td><td>{$skin_input}</td></tr>
<tr><td>Timezone</td><td>{$timezone_input}</td></tr>
<tr><td>Avatar</td><td>{$avatar_select}</td></tr>
<tr><td></td><td></td></tr>
{section name=r loop=$fields}
<tr><td>{$field_name[r]}</td><td>{$field_input[r]}</td><td>{$field_info[r]}</td></tr>
{/section}
<tr><td><input type='submit' value='Update' class='input'></td></tr></table></form>