<?php
	//ob_start();
	
	include("../../../config.php");
	include("../../../include/php/functions/debugging.php");
	require_once("../../../include/php/classes/bextjs.php");
	require_once("../../../include/php/connect/database.php");
	$OBExtJs=new BExtJs($db_link);
	echo $OBExtJs->page_list();
	
	//dump_data(ob_get_clean(),"debug_slider_upload.txt");
	//ob_end_clean();
?>