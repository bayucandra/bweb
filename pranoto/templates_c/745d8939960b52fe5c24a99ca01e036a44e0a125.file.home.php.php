<?php /* Smarty version Smarty-3.1.14, created on 2013-09-29 17:31:00
         compiled from ".\tpl\home.php" */ ?>
<?php /*%%SmartyHeaderCode:11844524847b47172a1-37936367%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '745d8939960b52fe5c24a99ca01e036a44e0a125' => 
    array (
      0 => '.\\tpl\\home.php',
      1 => 1380351698,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11844524847b47172a1-37936367',
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
  'unifunc' => 'content_524847b47654a3_43871882',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_524847b47654a3_43871882')) {function content_524847b47654a3_43871882($_smarty_tpl) {?><?php echo $_smarty_tpl->tpl_vars['sidebar']->value;?>

<section id="main" class="column test" style="margin:auto;height:auto;">
<?php echo $_smarty_tpl->tpl_vars['admin_view']->value;?>

</section><?php }} ?>