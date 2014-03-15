	<br />
	<br />
	<article class="module" style="width:300px;margin:auto;text-align:center;">
		<header><h3>Login</h3></header>
		<div class="module_content">
			<form method="post" action="{$login_action_form}">
				<table style="text-align:left;">
					<tr>
						<td><label>Username</label></td>
						<td>:</td>
						<td><input name="username" maxlength="20" type="text" /></td>
					</tr>
					<tr>
						<td>Password</td>
						<td>:</td>
						<td><input name="password" maxlength="20" type="password" /></td>
					</tr>
					<tr>
						<td colspan="3" align="right"><input name="admin_login" type="submit" value="Login" /></td>
					</tr>
				</table>
			</form>
		</div>
	</article>