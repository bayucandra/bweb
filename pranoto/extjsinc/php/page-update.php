<?php
	//ini_set("display_errors","off");
	//ob_start();

	include("../../../config.php");
	include("../../../include/php/functions/pic.php");
	include("../../../include/php/functions/debugging.php");
	include("../../../include/php/classes/bextjs.php");
	include("../../../include/php/connect/database.php");
	$OBextJs=new BextJs($db_link);
	//echo '{sucess:false,message:"Test",errors{"photo-path":"Return error"})';
	
	//echo '{success:false, message:"Faked error from server", errors:{"photo-path":"The server returned this"}}';
		
	//dump_data(print_r($_REQUEST,true),"debug_slider_upload.txt");
	$slider_upload_result=$OBextJs->page_update();
	echo $slider_upload_result;
	//dump_data(ob_get_clean(),"debug_slider_upload.txt");
	//ob_end_clean();
?>