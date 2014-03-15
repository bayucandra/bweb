<?php
	class BExtJs{
		private $db_link;
		function __construct($p_db_link){
			$this->db_link=$p_db_link;
		}
		public function slider_image_list(){
			$images=array();
			$qry_slider_image="SELECT * FROM `slider` ORDER BY `short_order`";
			$res_slider_image=mysql_query($qry_slider_image,$this->db_link);			
			
			while($fa_slider_image=mysql_fetch_assoc($res_slider_image)){
				$images[]=array('name'=>$fa_slider_image["filename"],'description'=>$fa_slider_image["description"],'short_order'=>$fa_slider_image["short_order"]);
			}
			$o=array('images'=>$images);
			return json_encode($o);
		}
		public function slider_image_upload($p_upload_path,$p_img_max_dim){
			//BEGIN DELETE==============================================================
			if($_REQUEST['input_mode']=='delete'){
				$image_path=$p_upload_path."/".$_REQUEST['last_file_name'];
				$result="";
				unlink($image_path);
				$qry_delete_slider="DELETE FROM `slider` WHERE filename='".$_REQUEST['last_file_name']."' LIMIT 1";
				
				mysql_query("BEGIN",$this->db_link);
				$res_delete_slider=mysql_query($qry_delete_slider,$this->db_link);
				
				if(mysql_affected_rows()==1){
					mysql_query("COMMIT",$this->db_link);
					$result='{success:true,message:"Success deleting Slider"}';
				}else{
					mysql_query("ROLLBACK",$this->db_link);;
					$result='{success:false,message:"Something wrong when deleting database record: '.mysql_error().'"}';
				}
				return $result;
			}
			//END DELETE****************************************************************

			$slider_image_element_name='slider-image';
			$slider_image_file=$_FILES[$slider_image_element_name];
			$msg_notification="";
			$image_error="";
			$db_error="";
			$image_size=0;
			if(!empty($slider_image_file['tmp_name']))
				$image_size=getimagesize($slider_image_file['tmp_name']);
			$new_file_name=$slider_image_file['name'];
			$image_path=$p_upload_path."/".$slider_image_file['name'];

			if($_REQUEST['input_mode']=='new'){//NEW
				//BEGIN DB INSERT OPERATION=============================================
				$qry_insert_slider="INSERT INTO `slider`(`filename`,`description`,`short_order`,`upload_date`)
										SELECT '".$slider_image_file['name']."','".$_REQUEST['description']."',IFNULL(MAX(short_order)+1,1),'".date("Y-m-d H:i:s")."'
											FROM `slider` LIMIT 1;";
				mysql_query("BEGIN",$this->db_link);
				$res_insert_slider=mysql_query($qry_insert_slider,$this->db_link);
				$msg_notification.=" Added successfully";
				if(!$res_insert_slider)
					$db_error.=mysql_error();
				//END DB INSERT OPERATION*************************************
			}
			if($_REQUEST['input_mode']=='edit'){//EDIT==================
				if(empty($slider_image_file['name'])||($slider_image_file['name']=='none')){
					$new_file_name=$_REQUEST['last_file_name'];
				}
				
				$qry_update_slider="UPDATE `slider` 
					SET `filename`='".$new_file_name."',`description`='".$_REQUEST['description']."' 
					WHERE `filename`='".$_REQUEST['last_file_name']."'
					LIMIT 1;";
				mysql_query("BEGIN",$this->db_link);
				$res_update_slider=mysql_query($qry_update_slider,$this->db_link);
				if(!$res_update_slider)
					$db_error.=mysql_error();
				$msg_notification.=" Edited Successfully.";
			}

			if(!empty($slider_image_file['error'])){
				switch($slider_image_file['error']){
					case '1':
						$image_error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
						break;
					case '2':
						$image_error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
						break;
					case '3':
						$image_error = 'The uploaded file was only partially uploaded';
						break;
					case '4':
						if($_REQUEST['input_mode']=='new')
							$image_error = 'No file was uploaded.';
						break;

					case '6':
						$image_error = 'Missing a temporary folder';
						break;
					case '7':
						$image_error = 'Failed to write file to disk';
						break;
					case '8':
						$image_error = 'File upload stopped by extension';
						break;
					case '999':
						$image_error = "Unknown error of image";
						break;
					default:
						$image_error = 'No error code avaiable';
						break;
				}
			}elseif((empty($slider_image_file['tmp_name']) || $slider_image_file['tmp_name'] == 'none')
				&&($_REQUEST['input_mode']=='new')){
				$image_error = 'No file was uploaded..';
			}elseif(($image_size[0]==0)||($image_size[1]==0)){
				$image_error="File is not correct photo/image format!!!";
			}elseif(!preg_match("/.jpg/i",$_FILES[$slider_image_element_name]['name'])){
				$image_error="Please upload JPEG only";
			}elseif($db_error!=""){
				$image_error.="Error Before upload, while inserting slider data. Message: ".$db_error;
			}elseif(empty($slider_image_file['tmp_name'])||($slider_image_file['tmp_name']=="none")){
				$msg_notification.="No Image selected to upload.";
			}else{//IF NO ERROR START UPLOADING IMAGE
				$tmp_path=$slider_image_file['tmp_name'];
				$slider_upload_copy=image_resize($tmp_path,$p_img_max_dim,array("str_dest_path"=>$image_path));
				@unlink($slider_image_file);
				if(!$slider_upload_copy){
					$image_error.="Error when try creating uploaded image to the server";
				}
			}

			//BEGIN: ERROR OR SUCCESS===================================
			$all_error="";
			if($image_error!=""){//IF ERROR
				$all_error=$image_error;
				mysql_query("ROLLBACK",$this->db_link);
			}else{//IF SUCCESS
				mysql_query("COMMIT",$this->db_link);
			}
			if(empty($all_error)){//IF SUCCESS
					return json_encode(array(
						"success" => true,
						"message" => $new_file_name.$msg_notification
					));
			}else{//IF ERROR
					return '{success:false, message:"'.$image_error.'"}';
			}
			//END: ERROR OR SUCCESS*******************************
		}//End function slider_image_upload
		public function page_list(){
			$pages=array();
			$qry_page="SELECT p.idpages,p.post_date,p.modified_date,p.content,p.title,u.username
				FROM pages p LEFT JOIN user u ON(p.iduser=u.iduser)";
			$res_page=mysql_query($qry_page,$this->db_link);
			while($fa_page=mysql_fetch_assoc($res_page)){
				$pages[]=array('idpages'=>$fa_page['idpages'],'post_date'=>$fa_page['post_date'],'modified_date'=>$fa_page['modified_date'],'content'=>$fa_page['content'],'title'=>$fa_page['title'],'username'=>$fa_page['username']);
			}
			$o=array('pages'=>$pages);
			return json_encode($o);
		}
		public function page_update(){
			$ret="";
			
			$iduser_updater=$_REQUEST['frm_page_iduser'];
			$page_content=$_REQUEST['frm_page_content'];
			$page_title=$_REQUEST['frm_page_title'];
			$qry_upd_page="UPDATE `pages` SET `iduser`=$iduser_updater,`modified_date`=CURRENT_TIMESTAMP,`content`='$page_content' WHERE `title`='$page_title' LIMIT 1";
			$res_upd_page=mysql_query($qry_upd_page,$this->db_link);
			if($res_upd_page)
				$ret='{success:true,message:"Page updated successfully"}';
			else
				$ret='{success:false,message:"Error updating page:'.mysql_error().'"}';
			return $ret;
		}

		public function product_group(){
			$product_group=array();
			$qry_product_group="SELECT * FROM `product_group`";
			$res_product_group=mysql_query($qry_product_group,$this->db_link);
			while($fa_product_group=mysql_fetch_assoc($res_product_group)){
				$product_group[]=array('idproduct_group'=>$fa_product_group["idproduct_group"],'description'=>$fa_product_group["description"],'parent'=>$fa_product_group["parent"]);
			}
			$o=array('product_group'=>$product_group);
			return json_encode($o);
		}
		public function product_list(){
			$page=$_REQUEST['page'];$start=$_REQUEST['start'];$limit=$_REQUEST['limit'];
			$filter_id_pg="";
			if(isset($_REQUEST["id_pg"]))
				$filter_id_pg=" WHERE p.idproduct_group=".$_REQUEST['id_pg'];
			$product_list=array();
			$qry_product_list="SELECT p.idproduct,p.code,p.name,p.idproduct_group,pg.description AS group_name
				FROM product p
				LEFT JOIN product_group pg ON(p.idproduct_group=pg.idproduct_group)
				$filter_id_pg
				LIMIT $start,$limit";
			$res_product_list=mysql_query($qry_product_list,$this->db_link);
			
			$qry_count_products="SELECT COUNT(p.idproduct) AS num_products FROM product p$filter_id_pg";
			$res_count_products=mysql_query($qry_count_products,$this->db_link);
			$fa_count_products=mysql_fetch_assoc($res_count_products);
			$num_products=$fa_count_products['num_products'];
			while($fa_product_list=mysql_fetch_assoc($res_product_list)){
				$product_list[]=array('idproduct'=>$fa_product_list["idproduct"],'code'=>$fa_product_list["code"],'name'=>$fa_product_list["name"],'idproduct_group'=>$fa_product_list["idproduct_group"],'group_name'=>$fa_product_list["group_name"]);
			}
			$o=array('product_list'=>$product_list,'totalCount'=>$num_products);
			return json_encode($o);
		}
		public function product_upload($p_upload_path,$p_img_max_dim){

			$db_error="";
			//BEGIN GET PRODUCT GROUP DESC===================================================
			$idproduct_group=$_REQUEST['idproduct_group'];
			$product_group_description='undefined';
			$qry_product_group="SELECT * FROM product_group WHERE idproduct_group=$idproduct_group LIMIT 1";
			$res_product_group=mysql_query($qry_product_group,$this->db_link);
			if(!$res_product_group)
				$db_error.="[line:217]".mysql_error();
			$fa_product_group=mysql_fetch_assoc($res_product_group);
			$product_group_description=$fa_product_group["description"];
			//END GET PRODUCT GROUP DESC************************************
			$slider_image_element_name='product_image';
			$product_image_file=$_FILES[$slider_image_element_name];
			$msg_notification="";
			$image_error="";
			$image_size=0;
			if(!empty($product_image_file['tmp_name']))
				$image_size=getimagesize($product_image_file['tmp_name']);
			$new_file_name=$_REQUEST['product_name'];
			$base_path=$p_upload_path."/$product_group_description";
			$image_path=$base_path."/".$_REQUEST['product_name'].".jpg";

			if($_REQUEST['input_mode']=='new'){//NEW
				//BEGIN DB INSERT OPERATION=============================================
				$qry_add_product="INSERT INTO `product`(`code`,`name`,`idproduct_group`)
					SELECT '".$_REQUEST['product_code']."','".$_REQUEST['product_name']."','".$_REQUEST['idproduct_group']."'
				";
				mysql_query("BEGIN",$this->db_link);
				$res_add_product=mysql_query($qry_add_product,$this->db_link);
				$msg_notification.=" Added successfully";
				if(!$res_add_product)
					$db_error.="[line:241]".mysql_error();
				//END DB INSERT OPERATION*************************************
			}

			$last_product_name="";
			$idproduct=$_REQUEST["idproduct"];
			$product_code=$_REQUEST["product_code"];
			$product_name=$_REQUEST["product_name"];
			$idproduct_group=$_REQUEST["idproduct_group"];
			if($_REQUEST['input_mode']=='edit'){//EDIT
				//BEGIN GET CURRENT DATA===========================
				$qry_product="SELECT * FROM `product` WHERE idproduct=$idproduct";
				$res_product=mysql_query($qry_product,$this->db_link);
				$fa_product=mysql_fetch_assoc($res_product);
				$last_product_name=$fa_product["name"];
				//END GET CURRENT DATA*******************************
				//BEGIN UPDATE WITH NEW DATA=================================
				$qry_edit_product="UPDATE `product` SET `code`='$product_code',`name`='$product_name',`idproduct_group`=$idproduct_group WHERE `idproduct`=$idproduct LIMIT 1";
				mysql_query("BEGIN",$this->db_link);
				$res_edit_product=mysql_query($qry_edit_product,$this->db_link);
				$msg_notification.=" Edited successfully";
				if(!$res_edit_product)
					$db_error.="[line:263]".mysql_error();
				//END UPDATE WITH NEW DATA**********************************
			}
			if(!empty($product_image_file['error'])){
				switch($product_image_file['error']){
					case '1':
						$image_error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
						break;
					case '2':
						$image_error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
						break;
					case '3':
						$image_error = 'The uploaded file was only partially uploaded';
						break;
					case '4':
						if($_REQUEST['input_mode']=='new')
							$image_error = 'No file was uploaded.';

						if($_REQUEST['input_mode']=='edit')
							@rename($base_path."/".$last_product_name.".jpg",$base_path."/".$product_name.".jpg");
						break;

					case '6':
						$image_error = 'Missing a temporary folder';
						break;
					case '7':
						$image_error = 'Failed to write file to disk';
						break;
					case '8':
						$image_error = 'File upload stopped by extension';
						break;
					case '999':
						$image_error = "Unknown error of image";
						break;
					default:
						$image_error = 'No error code avaiable';
						break;
				}
			}elseif((empty($product_image_file['tmp_name']) || ($product_image_file['tmp_name'] == 'none'))
				&&($_REQUEST['input_mode']=='new')){//IF No file uploaded not allowed for new input
				$image_error = 'No file was uploaded..';
			}elseif(($image_size[0]==0)||($image_size[1]==0)){
				$image_error="File is not correct photo/image format!!!";
			}elseif(!preg_match("/.jpg/i",$_FILES[$slider_image_element_name]['name'])){
				$image_error="Please upload JPEG only";
			}elseif($db_error!=""){
				$image_error.="Error Before upload, while inserting `product` data. Message: ".$db_error;
			}elseif(empty($product_image_file['tmp_name'])||($product_image_file['tmp_name']=="none")){
				$msg_notification.="No Image selected to upload.";
			}else{//IF NO ERROR AND HAVE FILE TO UPLOAD START UPLOADING IMAGE
				if($_REQUEST['input_mode']=='edit')//IF Editing, delete old image first
					@unlink($base_path."/".$last_product_name.".jpg");
				$tmp_path=$product_image_file['tmp_name'];
				$product_upload_copy=image_resize($tmp_path,$p_img_max_dim,array("str_dest_path"=>$image_path));
				@unlink($product_image_file);
				if(!$product_upload_copy){
					$image_error.="Error when try creating uploaded image to the server";
				}
			}

			//BEGIN: ERROR OR SUCCESS===================================
			$all_error="";
			if($image_error!=""){//IF ERROR
				$all_error=$image_error;
				mysql_query("ROLLBACK",$this->db_link);
			}else{//IF SUCCESS
				mysql_query("COMMIT",$this->db_link);
			}
			
			if(empty($all_error)){//IF SUCCESS
					return json_encode(array(
						"success" => true,
						"message" => $new_file_name.$msg_notification
					));
			}else{//IF ERROR
					return '{success:false, message:"'.$image_error.'"}';
			}
			//END: ERROR OR SUCCESS*******************************
		}//End function slider_image_upload
	}
?>