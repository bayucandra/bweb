<?php /* Smarty version Smarty-3.1.14, created on 2014-03-20 09:37:45
         compiled from "./tpl/theme1/home.php" */ ?>
<?php /*%%SmartyHeaderCode:292882650532a5479405104-96467742%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5dc6899c4925290bc107c4b56aeda0021b4b9384' => 
    array (
      0 => './tpl/theme1/home.php',
      1 => 1380593963,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '292882650532a5479405104-96467742',
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
  'unifunc' => 'content_532a5479410199_28573913',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_532a5479410199_28573913')) {function content_532a5479410199_28573913($_smarty_tpl) {?>
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