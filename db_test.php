<?php
	$db=mysql_connect("127.0.0.1","t27605_bay","Th3_k1nG");
	mysql_select_db('t27605_bay');
	if(mysql_errno!=0){
		echo "There is error: ".mysql_error();
	}
	$qry_mysql=mysql_real_escape_string("SELECT * FROM pages",$db);
	$res_mysql=mysql_query($qry_mysql);
	while($fa_mysql=mysql_fetch_array($res_mysql,MYSQL_ASSOC)){
		echo $fa_mysql["idpages"]." ".$fa_mysql["title"]."<br />";
	}
?>