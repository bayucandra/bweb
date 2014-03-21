<?php
require_once("include/php/functions/pic.php");
require_once("config.php");
$path_base=PATH_IMAGE_HEADER_SLIDER;
$file_name=$_GET["fname"];

$img_path=$path_base."/".$file_name;
header("content-type: image/jpg");
header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($img_path)).' GMT', true, 200);

$img_size=$_GET['sz'];

// header("content-type: image/jpg");
return image_resize($img_path,$img_size,array(
		"no_pic"=>"images/nopicture.jpg"
	)
);
?>
