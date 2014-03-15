<?php

$path_base="images/products";
$group_name=$_GET["gname"];
$product_name=$_GET["pname"];

$img_path=$path_base."/".$group_name."/".$product_name.".jpg";

if(!is_file($img_path))$img_path="images/nopicture.jpg";

header("content-type: image/jpg");
header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($img_path)).' GMT', true, 200);
require_once("include/php/functions/pic.php");
//$img_path=$_GET['path'];
$img_size=$_GET['sz'];
return image_resize($img_path,$img_size,array(
		"no_pic"=>"images/nopicture.jpg",
		"watermark"=>array("path"=>"images/watermark.png","percent"=>95)
	)
);
?>
