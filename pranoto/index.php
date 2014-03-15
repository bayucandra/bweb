<?php
//	require_once('init.php');
	//require_once('logout.php');
	//ob_start();
	session_start();
	require_once('../config.php');
	require_once('../include/php/functions/general.php');
	require_once('../include/php/connect/database.php');
	require('../include/php/classes/badmin.php');

	$OAdmin=new BAdmin($db_link,USER_GROUP_ID);
?>