<?php /* Smarty version Smarty-3.1.14, created on 2014-03-15 09:40:49
         compiled from "./tpl/product.php" */ ?>
<?php /*%%SmartyHeaderCode:19630946995323bdb16914a2-41670826%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dc8025398c11ae1d630b06d777ebbf19b86bdc6c' => 
    array (
      0 => './tpl/product.php',
      1 => 1380436815,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '19630946995323bdb16914a2-41670826',
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
  'unifunc' => 'content_5323bdb16edff5_51558684',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5323bdb16edff5_51558684')) {function content_5323bdb16edff5_51558684($_smarty_tpl) {?><div id="product_wrapper">
	<?php echo $_smarty_tpl->tpl_vars['bread_crumb']->value;?>

	<?php echo $_smarty_tpl->tpl_vars['product_group']->value;?>
<?php echo $_smarty_tpl->tpl_vars['product_list']->value;?>

</div><?php }} ?>