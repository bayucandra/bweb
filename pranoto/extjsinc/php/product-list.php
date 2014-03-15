<?php
	//ob_start();
	include("../../../config.php");
	include("../../../include/php/functions/debugging.php");
	require_once("../../../include/php/classes/bextjs.php");
	require_once("../../../include/php/connect/database.php");
	$OBExtJs=new BExtJs($db_link);
	echo $OBExtJs->product_list();
	//dump_data(print_r($_GET,true),"debug_product_list.txt");
	//ob_end_clean();
?>