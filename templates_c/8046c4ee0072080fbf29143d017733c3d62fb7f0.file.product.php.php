<?php /* Smarty version Smarty-3.1.14, created on 2014-03-20 09:40:02
         compiled from "./tpl/theme1/product.php" */ ?>
<?php /*%%SmartyHeaderCode:1654572175532a5502d0bc87-38924400%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8046c4ee0072080fbf29143d017733c3d62fb7f0' => 
    array (
      0 => './tpl/theme1/product.php',
      1 => 1380436815,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1654572175532a5502d0bc87-38924400',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'bread_crumb' => 0,
    'product_group' => 0,
    'product_list' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_532a5502d6af85_95266547',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_532a5502d6af85_95266547')) {function content_532a5502d6af85_95266547($_smarty_tpl) {?><div id="product_wrapper">
	<?php echo $_smarty_tpl->tpl_vars['bread_crumb']->value;?>

	<?php echo $_smarty_tpl->tpl_vars['product_group']->value;?>
<?php echo $_smarty_tpl->tpl_vars['product_list']->value;?>

</div><?php }} ?>