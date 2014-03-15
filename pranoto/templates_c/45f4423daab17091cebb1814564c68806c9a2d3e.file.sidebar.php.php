<?php /* Smarty version Smarty-3.1.14, created on 2013-10-23 10:28:09
         compiled from ".\tpl\sidebar.php" */ ?>
<?php /*%%SmartyHeaderCode:25814524847b45afc81-00159401%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '45f4423daab17091cebb1814564c68806c9a2d3e' => 
    array (
      0 => '.\\tpl\\sidebar.php',
      1 => 1381462123,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '25814524847b45afc81-00159401',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_524847b4624f97_63270592',
  'variables' => 
  array (
    'admin_full_name' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_524847b4624f97_63270592')) {function content_524847b4624f97_63270592($_smarty_tpl) {?>	<section id="secondary_bar">
		<div class="user">
			<p><?php echo $_smarty_tpl->tpl_vars['admin_full_name']->value;?>
</p>
			<a class="logout_user" href="?admin_logout=1" title="Logout">Logout</a>
		</div>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><a href="javascript:void(0);">Admin Page</a> <div class="breadcrumb_divider"></div> <a class="current">Dashboard</a></article>
		</div>
	</section><!-- end of secondary bar -->
	
	<aside id="sidebar" class="column" style="height:900px;">
		<!--
		<form class="quick_search">
			<input type="text" value="Quick Search" onfocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
		</form>
		-->
		<hr/>
		<h3>Content</h3>
		<ul class="toggle">
			<li class="icn_photo"><a href="?act=slider">Slider</a></li>
			<li class="icn_edit_article"><a href="?act=pages">Pages</a></li>
			<li class="icn_categories"><a href="?act=products">Products</a></li>
		</ul>
		<h3>Users And Account</h3>
		<ul class="toggle">
			<li class="icn_view_users"><a href="#">Member</a></li>
			<li class="icn_view_users"><a href="#">Admin</a></li>
		</ul>
		<h3>Admin</h3>
		<ul class="toggle">
			<li class="icn_settings"><a href="?act=option">Options</a></li>
			<li class="icn_jump_back"><a href="?admin_logout=1">Logout</a></li>
		</ul>
		
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2013 PT.WISANKA</strong></p>
		</footer>
	</aside><!-- end of sidebar -->
<?php }} ?>