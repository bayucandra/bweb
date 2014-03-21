<?php
	//ini_set("display_errors","off");
	//ob_start();
	define("CONF_PATH_RELATIVE","../../..");
	include("../../../config.php");
	include("../../../include/php/functions/debugging.php");
	include("../../../include/php/functions/pic.php");
	include("../../../include/php/classes/bextjs.php");
	include("../../../include/php/connect/database.php");
	b_set_time_zone("Asia/Jakarta");
	$OBExtJs=new BextJs($db_link);
	//echo '{sucess:false,message:"Test",errors{"photo-path":"Return error"})';
	
	//echo '{success:false, message:"Faked error from server", errors:{"photo-path":"The server returned this"}}';
		
// 	$OBExtJs->log_insert("Test+".print_r($_REQUEST,true));

	switch($_REQUEST["act"]){
		case "slider-list":
			echo $OBExtJs->slider_image_list();
			break;
		case "slider-upload":
			$slider_upload_result=$OBExtJs->slider_image_upload("../../../images/header-slider",-1);
			echo $slider_upload_result;
			break;
		case "page-list":
			echo $OBExtJs->page_list();
			break;
		case "page-update":
			$slider_upload_result=$OBExtJs->page_update();
			echo $slider_upload_result;
			break;
		case "product-list":
			echo $OBExtJs->product_list();
			break;
		case "product-group":
			echo $OBExtJs->product_group();
			break;
		case "product-upload":
			$slider_upload_result=$OBExtJs->product_upload("../../../images/products",-1);
			echo $slider_upload_result;
			break;
		case "product-group-input":
			echo $OBExtJs->product_group_input();
			break;
	}
	//dump_data(ob_get_clean(),"debug_slider_upload.txt");
	//ob_end_clean();
?>