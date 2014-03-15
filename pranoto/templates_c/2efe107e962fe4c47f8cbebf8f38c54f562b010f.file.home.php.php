<?php /* Smarty version Smarty-3.1.14, created on 2013-10-11 01:45:57
         compiled from "./tpl/home.php" */ ?>
<?php /*%%SmartyHeaderCode:53307884452575855780bf5-79696194%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2efe107e962fe4c47f8cbebf8f38c54f562b010f' => 
    array (
      0 => './tpl/home.php',
      1 => 1380351698,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '53307884452575855780bf5-79696194',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sidebar' => 0,
    'admin_view' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_52575855799a50_41148554',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52575855799a50_41148554')) {function content_52575855799a50_41148554($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['sidebar']->value;?>

<section id="main" class="column test" style="margin:auto;height:auto;">
<?php echo $_smarty_tpl->tpl_vars['admin_view']->value;?>

</section><?php }} ?>