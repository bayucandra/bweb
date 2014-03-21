<?php /* Smarty version Smarty-3.1.14, created on 2014-03-20 09:07:09
         compiled from "/media/sda3/Projects/Web/bamboo/tpl/theme1/home.php" */ ?>
<?php /*%%SmartyHeaderCode:1367820989532a4d4d083752-83292641%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5c1d690d9b1f59ba853618c602561e8d68949920' => 
    array (
      0 => '/media/sda3/Projects/Web/bamboo/tpl/theme1/home.php',
      1 => 1380593963,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1367820989532a4d4d083752-83292641',
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
  'unifunc' => 'content_532a4d4d090ec5_83245790',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_532a4d4d090ec5_83245790')) {function content_532a4d4d090ec5_83245790($_smarty_tpl) {?>
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