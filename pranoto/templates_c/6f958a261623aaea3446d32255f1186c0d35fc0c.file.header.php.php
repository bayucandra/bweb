<?php /* Smarty version Smarty-3.1.14, created on 2013-09-29 17:29:57
         compiled from ".\tpl\header.php" */ ?>
<?php /*%%SmartyHeaderCode:2683524847756c4568-81917282%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6f958a261623aaea3446d32255f1186c0d35fc0c' => 
    array (
      0 => '.\\tpl\\header.php',
      1 => 1380467706,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2683524847756c4568-81917282',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'extjson_session' => 0,
    'extjs_app' => 0,
    'header_title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_524847761e2572_67418518',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_524847761e2572_67418518')) {function content_524847761e2572_67418518($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head profile="http://gmpg.org/xfn/11">
	<meta charset="utf-8"/>
	<title>Web Admin Page</title>
	
	<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="css/html5.js"></script>
	<![endif]-->
	<script src="../include/jquery-1.10.2.min.js" type="text/javascript"></script>
	<script src="js/hideshow.js" type="text/javascript"></script>
	<script src="js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery.equalHeight.js"></script>

	<!--Begin: Ext2Js ================================ -->
	<link rel="stylesheet" type="text/css" href="extjs/resources/css/ext-all.css" />
	<link rel="stylesheet" type="text/css" href="extjsinc/css/data-view.css" />
	<link rel="stylesheet" type="text/css" href="extjsinc/css/data-view-product.css" />
	<link rel="stylesheet" type="text/css" href="extjsinc/css/toolbar.css" />
	<link rel="stylesheet" type="text/css" href="extjsinc/css/general.css" />
	<script type="text/javascript" src="extjs/ext-all.js"></script>
	<script type="text/javascript" src="../include/ckeditor/ckeditor.js"></script>
	<?php echo $_smarty_tpl->tpl_vars['extjson_session']->value;?>

	<?php echo $_smarty_tpl->tpl_vars['extjs_app']->value;?>


	<!-- End: Ext2Js ================================ -->
	
	<script type="text/javascript">
	$(document).ready(function() 
    	{ 
      	  $(".tablesorter").tablesorter(); 
   	 } 
	);
	$(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
    </script>
    <script type="text/javascript">
    $(function(){
        $('.column').equalHeight();
    });
</script>

</head>
<body>
	<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="javascript:void(0);">Website Admin</a></h1>
			<h2 class="section_title"><?php echo $_smarty_tpl->tpl_vars['header_title']->value;?>
</h2><div class="btn_view_site"><a href="../">View Site</a></div>
		</hgroup>
	</header> <!-- end of header bar --><?php }} ?>