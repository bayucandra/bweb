<?php
	if(isset($_GET['admin_logout'])){
		if($_GET['admin_logout']==='1'){
			$OUser_and_login->admin_logout("index.php");
		}
	}
?>