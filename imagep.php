<?php

require_once("config.php");
$path_base=PATH_IMAGE_PRODUCTS;
$group_name=$_GET["gname"];
$product_name=$_GET["pname"];

$img_path=$path_base."/".$group_name."/".$product_name.".jpg";

if(!is_file($img_path))$img_path=PATH_IMAGES."/nopicture.jpg";

header("content-type: image/jpg");
header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($img_path)).' GMT', true, 200);
require_once(path_base_script()."/include/php/functions/pic.php");
//$img_path=$_GET['path'];
$img_size=$_GET['sz'];
return image_resize($img_path,$img_size,array(
		"no_pic"=>PATH_IMAGES."/nopicture.jpg",
		"watermark"=>array("path"=>PATH_IMAGES."/watermark.png","percent"=>95)
	)
);
?>
