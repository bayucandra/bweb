<?php
$db_config=unserialize(MYSQL_CONFIG);
$db_link=mysql_connect($db_config["host"],$db_config["username"],$db_config["password"])
	or die("Cannot connect to mysql server:");
mysql_select_db($db_config["db_name"]);
?>