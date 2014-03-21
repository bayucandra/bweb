<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
		setTimeout("$('#loading_bg').hide()",3000);

	});
    </script>
    <script type="text/javascript">
    $(function(){
        $('.column').equalHeight();
    });
</script>

</head>
<body>
	<div id="loading_bg" style="width:100%;height:100%;position:absolute;z-index=-99999;text-align:center;">
		<img style="margin:auto;margin-top:20%;" src="images/loading.gif"/>
	</div>

	<script type="text/javascript" src="extjs/ext-all.js"></script>
	<script type="text/javascript" src="../include/ckeditor/ckeditor.js"></script>
	{$extjson_session}
	{$extjs_app}

	<header id="header">
		<hgroup>
			<h1 class="site_title"><a href="javascript:void(0);">Web Admin</a></h1>
			<h2 class="section_title">{$header_title}</h2><div class="btn_view_site"><a href="../">View Site</a></div>
		</hgroup>
	</header> <!-- end of header bar -->