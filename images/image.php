<?php
require_once("../include/php/functions/pic.php");
$img_path=$_GET['path'];
$img_size=$_GET['sz'];
header("content-type: image/jpg");
header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime($img_path)).' GMT', true, 200);
return image_resize($img_path,$img_size,array("watermark"=>array("path"=>"../images/watermark.png","percent"=>95)));
?>
