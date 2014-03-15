<?php /* Smarty version Smarty-3.1.14, created on 2013-09-29 17:29:58
         compiled from ".\tpl\login.php" */ ?>
<?php /*%%SmartyHeaderCode:8344524847762633f9-46998025%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a18e07e32ffe27696df57c759732fe52bc0abb6' => 
    array (
      0 => '.\\tpl\\login.php',
      1 => 1379566055,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8344524847762633f9-46998025',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'login_action_form' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_524847762c4e93_01103946',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_524847762c4e93_01103946')) {function content_524847762c4e93_01103946($_smarty_tpl) {?>	<br />
	<br />
	<article class="module" style="width:300px;margin:auto;text-align:center;">
		<header><h3>Login</h3></header>
		<div class="module_content">
			<form method="post" action="<?php echo $_smarty_tpl->tpl_vars['login_action_form']->value;?>
">
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
	</article><?php }} ?>