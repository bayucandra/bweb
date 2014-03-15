<?php
	define("MYSQL_CONFIG",serialize(
		array(
			"host" => "localhost",
			"username" =>"t27605_bamboo",
			"password" => "Th3_k1nG",
			"db_name" => "t27605_bamboo"
			)
	));
	define("PATH_TEMPLATE","tpl");
	define("PATH_TEMPLATE_ADMIN","tpl");
	define("APP_FOLDER","");
	//define("PATH_LIBS",$_SERVER['DOCUMENT_ROOT' ]."/libs/include");//DEVELOPEMENT MODE
	define("PATH_LIBS","./include");//RUNNING MODE
	
	//define("PATH_LIBS_HTML","../libs/include");//DEVELOPMENT MODES
// 	define("PATH_LIBS_HTML","include");//SERVER READY MODE
	define("PATH_LIBS_CSS_GENERAL",serialize(
			array(
				array(
					array("href","include/css/general.css"),
					array("rel","stylesheet"),
					array("media","screen")
				)
			)
		));
	define("PATH_LIBS_JS_GENERAL",serialize(
			array(
				"PATHinclude/jquery-1.10.2.min.js",
			)
		));
	define("SITE_TITLE","Indonesia Bamboo Furniture");
	define("PATH_LIBS_PHP","");
	define("PATH_LIBS_SMARTY",PATH_LIBS."/smarty");
	define("PATH_LIBS_SMARTY_OBJ",PATH_LIBS_SMARTY."/libs/Smarty.class.php");
	
	define("PATH_PHP_MAILER",PATH_LIBS."/PHPMailer");
	define("PATH_PHP_MAILER_OBJ",PATH_PHP_MAILER."/class.phpmailer.php");
	
	define("USER_GROUP_ID",serialize(
		array(
			"admin" => 0,
			"member" =>1
		)
	));
	define("SESSION_CONF",serialize(
		array(
			"admin" => "admin",
			"member" => "member"
		)
	));
	define("ENC_PASSWORD","plain");
	define("ADMIN_URL_REDIRECT_HOME","home.php");
	define("ADMIN_URL_REDIRECT_LOGIN","index.php");
	define("PATH_IMAGE_HEADER_SLIDER","images/header-slider");
?>