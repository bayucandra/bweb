<?php /* Smarty version Smarty-3.1.14, created on 2014-03-18 11:54:16
         compiled from "./tpl/sidebar.php" */ ?>
<?php /*%%SmartyHeaderCode:66677421552575855650fe0-35431074%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '89090d8b35d7a20a34cc1340e6dd800e5fbc2fd8' => 
    array (
      0 => './tpl/sidebar.php',
      1 => 1395118420,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '66677421552575855650fe0-35431074',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5257585572ad45_62215475',
  'variables' => 
  array (
    'admin_full_name' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5257585572ad45_62215475')) {function content_5257585572ad45_62215475($_smarty_tpl) {?>	<section id="secondary_bar">
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
			<li class="icn_tags"><a href="?act=tools">Tools</a></li>
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