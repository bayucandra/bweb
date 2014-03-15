<?php /* Smarty version Smarty-3.1.14, created on 2014-03-15 09:40:37
         compiled from "./tpl/home.php" */ ?>
<?php /*%%SmartyHeaderCode:6099336805323bda5a43c06-30859673%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2efe107e962fe4c47f8cbebf8f38c54f562b010f' => 
    array (
      0 => './tpl/home.php',
      1 => 1380593963,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6099336805323bda5a43c06-30859673',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'nivo_images' => 0,
    'random_product' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5323bda5a4f265_52524141',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5323bda5a4f265_52524141')) {function content_5323bda5a4f265_52524141($_smarty_tpl) {?>
    <div id="nivo-wrapper">
        <div class="slider-wrapper theme-default">
            <div id="slider-item" class="nivoSlider">
                <?php echo $_smarty_tpl->tpl_vars['nivo_images']->value;?>

            </div>
        </div>

    </div>
    <script type="text/javascript" src="include/nivo-slider/jquery.nivo.slider.js"></script>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider-item').nivoSlider();
    });
    </script>
	<?php echo $_smarty_tpl->tpl_vars['random_product']->value;?>
<?php }} ?>