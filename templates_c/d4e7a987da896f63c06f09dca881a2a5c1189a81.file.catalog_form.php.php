<?php /* Smarty version Smarty-3.1.14, created on 2014-03-15 14:01:42
         compiled from "./tpl/theme1/catalog_form.php" */ ?>
<?php /*%%SmartyHeaderCode:16448609185323fad6b76ad6-94252218%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd4e7a987da896f63c06f09dca881a2a5c1189a81' => 
    array (
      0 => './tpl/theme1/catalog_form.php',
      1 => 1384402876,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16448609185323fad6b76ad6-94252218',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'error_messages' => 0,
    'php_self' => 0,
    'val' => 0,
    'errors' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5323fad6bca641_89640344',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5323fad6bca641_89640344')) {function content_5323fad6bca641_89640344($_smarty_tpl) {?>	<?php echo $_smarty_tpl->tpl_vars['error_messages']->value;?>

	<br /><span class="ftimes f 13" style="margin-top:10px;">Please fill form below in order to receive copy of our catalog. The fields with asterisk marks <span class="fred">(*)</span> are required to be filled.</span>
	<form action="<?php echo $_smarty_tpl->tpl_vars['php_self']->value;?>
" method="post">
		<table class="farial f14" cellpadding="5">
			<tr>
				<td colspan="3" class="header fbold ftahoma f12 funderline">Catalog form request</td>
			</tr>
			<tr>
				<td class="label">Full Name<span class="fred">*</span></td>
				<td>:</td>
				<td>
					<select name="title">
						<option value="Mr."<?php echo $_smarty_tpl->tpl_vars['val']->value['title_mr'];?>
>Mr.</option>
						<option value="Mrs."<?php echo $_smarty_tpl->tpl_vars['val']->value['title_mrs'];?>
>Mrs.</option>
					</select>
					<input class="inp_text<?php echo $_smarty_tpl->tpl_vars['errors']->value['fullname'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['fullname'];?>
" type="text" name="fullname" maxlength="50" />
				</td>
			</tr>
			<tr>
				<td class="label">Address<span class="fred">*</span></td>
				<td>:</td>
				<td><textarea class="inp_text<?php echo $_smarty_tpl->tpl_vars['errors']->value['address'];?>
" type="text" name="address" maxlength="255"><?php echo $_smarty_tpl->tpl_vars['val']->value['address'];?>
</textarea></td>
			</tr>
			<tr>
				<td class="label">Country<span class="fred">*</span></td>
				<td>:</td>
				<td><input class="inp_text<?php echo $_smarty_tpl->tpl_vars['errors']->value['country'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['country'];?>
" type="text" name="country" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="label">Phone<span class="fred">*</span></td>
				<td>:</td>
				<td><input class="inp_text<?php echo $_smarty_tpl->tpl_vars['errors']->value['phone'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['phone'];?>
" type="text" name="phone" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="label">Fax</td>
				<td>:</td>
				<td><input class="inp_text" type="text" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['fax'];?>
" name="fax" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="label">Email Address<span class="fred">*</span></td>
				<td>:</td>
				<td><input class="inp_text<?php echo $_smarty_tpl->tpl_vars['errors']->value['email'];?>
" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['email'];?>
" type="text" name="email" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="label">Website</td>
				<td>:</td>
				<td><input class="inp_text" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['website'];?>
" type="text" name="website" maxlength="50" /></td>
			</tr>
			<tr>
				<td class="label"></td>
				<td></td>
				<td>
					<input type="submit" name="submit_catalog" value="Submit Request" />
					&nbsp;<input type="reset" value="Reset"/>
				</td>
			</tr>
		</table>
	</form><?php }} ?>