<?php
	class BExtJs{
		private $db_link;
		function __construct($p_db_link){
			$this->db_link=$p_db_link;
		}
		public function log_insert($p_str){
			log_insert($this->db_link,$p_str);
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
					,IFNULL(seo_ps.title,'') AS seo_title, IFNULL(seo_ps.keywords,'') AS seo_keywords, IFNULL(seo_ps.description,'') AS seo_description
				FROM pages p
					LEFT JOIN user u ON(p.iduser=u.iduser)
					LEFT JOIN ".TABLE_PREFIX_SEO."pages seo_ps ON(p.idpages=seo_ps.idpages)";
			$res_page=mysql_query($qry_page,$this->db_link);
			while($fa_page=mysql_fetch_assoc($res_page)){
				$pages[]=array('idpages'=>$fa_page['idpages'],'post_date'=>$fa_page['post_date'],'modified_date'=>$fa_page['modified_date'],'content'=>$fa_page['content'],'title'=>$fa_page['title'],'username'=>$fa_page['username'], 'seo_title'=>$fa_page['seo_title'], 'seo_keywords'=>$fa_page['seo_keywords'], 'seo_description'=>$fa_page['seo_description']);
			}
			$o=array('pages'=>$pages);
			return json_encode($o);
		}
		public function page_update(){
			$arr_state=array("db_error"=>false,"db_message"=>'');
			
			$iduser_updater=$_REQUEST['frm_page_iduser'];
			$page_content=$_REQUEST['frm_page_content'];
			$page_title=$_REQUEST['frm_page_title'];
			//BEGIN GET idpages==========================
			$qry_get_idpages="SELECT `idpages` FROM `pages` WHERE `title`='$page_title' LIMIT 1";
			$idpages=-1;
			$res_get_idpages=mysql_query($qry_get_idpages,$this->db_link);
			if(!$res_get_idpages){
				$arr_state["db_error"]=true;
				$arr_state["db_message"]="Error when getting idpages".mysql_query();
			}else{
				$fa_get_idpages=mysql_fetch_assoc($res_get_idpages);
				$idpages=$fa_get_idpages['idpages'];
			}
			//END GET idpages*************************
			if(!$arr_state["db_error"]){
				mysql_query("BEGIN",$this->db_link);
					$qry_upd_page="UPDATE `pages` SET `iduser`=$iduser_updater,`modified_date`=CURRENT_TIMESTAMP,`content`='$page_content' WHERE `title`='$page_title' LIMIT 1";
					$res_upd_page=mysql_query($qry_upd_page,$this->db_link);
					
					if(!$res_upd_page){
						$arr_state["db_error"]=true;
						$arr_state["db_message"]="Error when updating pages database record: ".mysql_error();
					}
					//BEGIN SEO INPUT============================
					if(!$arr_state["db_error"]){
						$seo_title=$_REQUEST["seo_title"];
						$seo_keywords=$_REQUEST["seo_keywords"];
						$seo_description=$_REQUEST["seo_description"];
						if(!empty($seo_title)||!empty($seo_keywords)||!empty($seo_description)){
							$arr_seo_val=array('rel'=>$idpages, 'title'=>$seo_title, 'keywords'=>$seo_keywords, 'description'=>$seo_description);
							
							if($this->is_seo_exist_rel('pages','idpages',$idpages)){
								$update_seo=$this->seo_update_rel('pages','idpages',$arr_seo_val);
								if(!$update_seo["success"]){
									$arr_state["db_error"]=true;
									$arr_state["db_message"]=$update_seo["message"];
								}
							}else{
								$insert_seo=$this->seo_insert_rel('pages','idpages',$arr_seo_val);
								if(!$insert_seo["success"]){
									$arr_state['db_error']=true;
									$arr_state['db_message']=$insert_seo['message'];
								}
							}
						}else{
							if($this->is_seo_exist_rel('pages','idpages',$idpages)){
								$del_seo=$this->seo_delete_rel('pages','idpages',$idpages);
								if(!$del_seo){
									$arr_state['db_error']=true;
									$arr_state['db_message']="Error when deleting SEO pages: ".mysql_query();
								}
							}
						}
					}
					//END SEO INPUT*****************************
				if($arr_state["db_error"]){
					mysql_query("ROLLBACK",$this->db_link);
				}else{
					mysql_query("COMMIT",$this->db_link);
				}
			}
			
			if($arr_state["db_error"])
				return json_encode(array("success"=>false,"message"=>$arr_state["db_message"]));
			else
				return json_encode(array("success"=>false,"message"=>"Success update."));
		}

		public function product_group(){
			$product_group=array();
			$qry_product_group="SELECT `pg`.`idproduct_group`,`pg`.`description`,`pg`.`parent`
					,RAND() AS img_rand
					,IFNULL(seo_pg.`title`,'') AS seo_title, IFNULL(seo_pg.`keywords`,'') AS seo_keywords, IFNULL(seo_pg.`description`,'') AS seo_description
				FROM `product_group` `pg`
					LEFT JOIN ".TABLE_PREFIX_SEO."product_group `seo_pg`
						ON(pg.idproduct_group=seo_pg.idproduct_group)";
			$res_product_group=mysql_query($qry_product_group,$this->db_link);
			while($fa_product_group=mysql_fetch_assoc($res_product_group)){
				$product_group[]=array('idproduct_group'=>$fa_product_group["idproduct_group"],'description'=>$fa_product_group["description"],'parent'=>$fa_product_group["parent"],'img_rand'=>$fa_product_group["img_rand"],'seo_title'=>$fa_product_group['seo_title'], 'seo_keywords'=>$fa_product_group['seo_keywords'],'seo_description'=>$fa_product_group['seo_description']);
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
			$qry_product_list="SELECT p.idproduct,p.code,p.name,p.idproduct_group,pg.description AS group_name,RAND() AS img_rand
					,IFNULL(seo_p.`title`,'') AS seo_title, IFNULL(seo_p.`keywords`,'') AS `seo_keywords`, IFNULL(seo_p.`description`,'') AS `seo_description`
				FROM product p
					LEFT JOIN product_group pg ON(p.idproduct_group=pg.idproduct_group)
					LEFT JOIN ".TABLE_PREFIX_SEO."product `seo_p` ON(p.idproduct=seo_p.idproduct)
				$filter_id_pg
				ORDER BY p.idproduct DESC
				LIMIT $start,$limit";
			$res_product_list=mysql_query($qry_product_list,$this->db_link);
			
			$qry_count_products="SELECT COUNT(p.idproduct) AS num_products FROM product p$filter_id_pg";
			$res_count_products=mysql_query($qry_count_products,$this->db_link);
			$fa_count_products=mysql_fetch_assoc($res_count_products);
			$num_products=$fa_count_products['num_products'];
			while($fa_product_list=mysql_fetch_assoc($res_product_list)){
				$product_list[]=array('idproduct'=>$fa_product_list["idproduct"],'code'=>$fa_product_list["code"],'name'=>$fa_product_list["name"],'idproduct_group'=>$fa_product_list["idproduct_group"],'group_name'=>$fa_product_list["group_name"],'img_rand'=>$fa_product_list["img_rand"], 'seo_title'=>$fa_product_list['seo_title'], 'seo_keywords'=>$fa_product_list['seo_keywords'], 'seo_description'=>$fa_product_list['seo_description']);
			}
			$o=array('product_list'=>$product_list,'totalCount'=>$num_products);
			return json_encode($o);
		}
		public function product_upload(){
			switch($_REQUEST['input_mode']){
				case "new":
					return json_encode($this->product_new());
					break;
				case "edit":
					return json_encode($this->product_edit());
					break;
				case "delete":
					return json_encode($this->product_delete());
					break;
			}
		}//End function slider_image_upload
		private function product_new(){
			$ret_arr=array();
			$arr_state=array("db_error"=>false,"db_message"=>"","img_error"=>false,"img_message"=>"");
			
			$idproduct_group=$_REQUEST['idproduct_group'];
// 			$idproduct=$_REQUEST["idproduct"];
			$product_code=$_REQUEST["product_code"];
			$product_name=$_REQUEST["product_name"];
			$product_group_description=$this->get_product_group_desc($idproduct_group);
			$idproduct=-1;
			
			$new_base_path=$this->path_image_product_base()."/".$product_group_description;
			$new_image_path=$new_base_path."/".$_REQUEST['product_name'].".jpg";
			//BEGIN INSERT DATA==========================
			mysql_query("BEGIN",$this->db_link);
				$qry_add_product="INSERT INTO `product`(`code`,`name`,`idproduct_group`)
					SELECT '".$product_code."','".$product_name."','".$idproduct_group."'";
				$res_add_product=mysql_query($qry_add_product,$this->db_link);
				if(!$res_add_product){
					$arr_state['db_error']=true;
					$arr_state['db_message']="Error when inserting data: ".mysql_error($this->db_link);
				}else{
					$idproduct=bmysql_insert_id($this->db_link);
					if(!$idproduct){
						$arr_state['db_error']=true;
						$arr_state['db_message'].="Error when getting last insert id: ".mysql_error();
					}else{
						//BEGIN UPLOADING IMAGE====================
						$product_image_file=$_FILES['product_image'];
						if(!empty($product_image_file['tmp_name'])&&($product_image_file['tmp_name']!='none')){
							$dir_base_path=true;
							if(!file_exists($new_base_path)){//IF FOLDER NOT EXIST PROCEDURE BEFORE UPLOAD
								if(!mkdir($new_base_path,0777,true)){
									$dir_base_path=false;
									$arr_state["img_error"]=true;
									$arr_state["img_message"].="Error when creating directory for product image";
								}
							}
							if($dir_base_path){
								//BEGIN IMAGE VALIDATION VARIABLES==========================
								$image_size=getimagesize($product_image_file['tmp_name']);
								$image_error_msg=get_image_upload_error($product_image_file['error']);
								
								$is_image_valid=($image_size[0]!=0)&&($image_size[1]!=0);
								$is_image_no_error=empty($image_error_msg);
								$is_image_jpeg=exif_imagetype($product_image_file['tmp_name'])==IMAGETYPE_JPEG;
								//END IMAGE VALIDATION VARIABLES************************
								if($is_image_valid&&$is_image_no_error&&$is_image_jpeg){
									$product_upload_process=image_resize($product_image_file['tmp_name'],SIZE_IMAGE_PRODUCTS, array("str_dest_path"=>$new_image_path));
									if(!$product_upload_process){
										$arr_state['img_error']=true;
										$arr_state['img_message'].="Error when uploading image to the destination path";
									}
								}else{
									$arr_state['img_error']=true;
									if(!$is_image_valid)$arr_state['img_message'].="The image you have uploaded is not valid.";
									if(!$is_image_no_error)$arr_state['img_message'].=$image_error_msg;
									if(!$is_image_jpeg)$arr_state['img_message'].="Please upload JPEG file only";
								}
							}
						}else{
							$arr_state['img_error']=true;
							$arr_state['img_message']="Please don't left image field empty.";
						}
						//END UPLOADING IMAGE********************
					}
				}
				//BEGIN SEO INPUT=============================
				if(!$arr_state['db_error']&&!$arr_state['img_error']){
					if(isset($_REQUEST['p-seo-checkbox'])){
						if($_REQUEST['p-seo-checkbox']=='on'){
							$seo_title=$_REQUEST['seo_title'];
							$seo_keywords=$_REQUEST['seo_keywords'];
							$seo_description=$_REQUEST['seo_description'];
							
							$arr_seo_val=array('rel'=>$idproduct, 'title'=>$seo_title, 'keywords'=>$seo_keywords, 'description'=>$seo_description);
							$insert_seo=$this->seo_insert_rel('product','idproduct',$arr_seo_val);
							if(!$insert_seo["success"]){
								$arr_state["db_error"]=true;
								$arr_state["db_message"]=$insert_seo["message"];
							}
						}
					}
				}
				//END SEO INPUT=============================
			if($arr_state['db_error']||$arr_state['img_error'])
				mysql_query("ROLLBACK",$this->db_link);
			else
				mysql_query("COMMIT",$this->db_link);
			//END INSERT DATA********************
			
			//BEGIN RETURN===============================
			if($arr_state['db_error']||$arr_state['img_error']){
				return array("success"=>false,"message"=>"There is error when adding new product. Message: ".$arr_state['db_message']."-".$arr_state['img_message']);
			}else{
				return array("success"=>true,"message"=>"Success adding new product.");
			}
			//END RETURN*********************************
		}
		private function product_edit(){
			$arr_state=array("db_error"=>false,"db_message"=>"","img_error"=>false,"img_message"=>"");
			
			$idproduct_group=$_REQUEST['idproduct_group'];
			$idproduct=$_REQUEST["idproduct"];
			$product_code=$_REQUEST["product_code"];
			$product_name=$_REQUEST["product_name"];
			
			$product_group_description=$this->get_product_group_desc($idproduct_group);
			$new_base_path=$this->path_image_product_base()."/".$product_group_description;
			$new_image_path=$new_base_path."/".$_REQUEST['product_name'].".jpg";
			
			//BEGIN GET CURRENT DATA===========================
			$qry_product="SELECT * FROM `product` WHERE idproduct=$idproduct";
			$res_product=mysql_query($qry_product,$this->db_link);
			$old_product_name="";
			$old_idproduct_group=-1;
			if(!$res_product){
				$arr_state["db_error"]=true;
				$arr_state["db_message"]="Error when trying to get last product name:".mysql_error($this->db_link);
			}else{
				$fa_product=mysql_fetch_assoc($res_product);
				$old_product_name=$fa_product["name"];
				$old_idproduct_group=$fa_product["idproduct_group"];
			}
			$old_product_group_description=$this->get_product_group_desc($old_idproduct_group);
			$old_base_path=$this->path_image_product_base()."/".$old_product_group_description;
			$old_image_path=$old_base_path."/".$old_product_name.".jpg";
			//END GET CURRENT DATA*******************************
			//BEGIN UPDATE WITH NEW DATA=================================
			$qry_edit_product="UPDATE `product` SET `code`='$product_code',`name`='$product_name',`idproduct_group`=$idproduct_group WHERE `idproduct`=$idproduct LIMIT 1";

			mysql_query("BEGIN",$this->db_link);
				$res_edit_product=mysql_query($qry_edit_product,$this->db_link);
				if(!$res_edit_product){
					$arr_state['db_error']=true;
					$arr_state['db_message'].="Error when updating product:".mysql_error($this->db_link);
				}else{
					if($old_image_path!=$new_image_path){//BEGIN IF CATEGORY OR PRODUCT NAME HAVE BEEN CHANGED===============
						if(file_exists($old_image_path)){
							$new_base_path_exist=true;
							if(!file_exists($new_base_path)){
								if(!mkdir($new_base_path,0777,true))
									$new_base_path_exist=false;
							}
							if($new_base_path_exist){
								rename($old_image_path,$new_image_path);
							}else{
								$arr_state['img_error']=true;
								$arr_state['message'].="Error, can not move image to new category.";
							}
						}
					}//END IF CATEGORY OR PRODUCT NAME HAVE BEEN CHANGED***********
					//BEGIN UPLOADING IMAGE==================
					$product_image_file=$_FILES['product_image'];
					if(!empty($product_image_file['tmp_name'])&&($product_image_file['tmp_name']!='none')){//IF USER SET IMAGE TO UPLOAD AT INPUT FIELD
						$dir_base_path=true;
						if(!file_exists($new_base_path)){//IF FOLDER NOT EXIST PROCEDURE BEFORE UPLOAD
							if(!mkdir($new_base_path,0777,true)){
								$dir_base_path=false;
								$arr_state["img_error"]=true;
								$arr_state["img_message"].="Error when creating directory for product image";
							}
						}
						if($dir_base_path){
							//BEGIN IMAGE VALIDATION VARIABLES=================
							$image_size=getimagesize($product_image_file['tmp_name']);
							$image_error_msg=get_image_upload_error($product_image_file['error']);
							
							$is_image_valid=($image_size[0]!=0)&&($image_size[1]!=0);
							$is_image_no_error=empty($image_error_msg);
							$is_image_jpeg=exif_imagetype($product_image_file['tmp_name'])==IMAGETYPE_JPEG;
							//END IMAGE VALIDATION VARIABLES**************
							if($is_image_no_error&&$is_image_valid&&$is_image_jpeg){
								@unlink($old_image_path);//Deleting old image
								$product_upload_process=image_resize($product_image_file["tmp_name"],SIZE_IMAGE_PRODUCTS, array("str_dest_path"=>$new_image_path));
								@unlink($product_image_file);//delete temporary image file
								if(!$product_upload_process){
									$arr_state['img_error']=true;
									$arr_state['img_message'].="Error when uploading image to the destination path.";
								}
							}else{
								$arr_state['img_error']=true;
								if(!$is_image_no_error)$arr_state['img_message'].=$image_error_msg;
								if(!$is_image_valid)$arr_state['img_message'].="The image you have uploaded is not valid.";
								if(!$is_image_jpeg)$arr_state['img_message'].="Please upload JPEG file only.";
							}
						}
					}
					//END UPLOADING IMAGE************************
				}
				//BEGIN SEO INPUT=============================
				if(!$arr_state['db_error']&&!$arr_state['img_error']){
					if(isset($_REQUEST['p-seo-checkbox'])){
						if($_REQUEST['p-seo-checkbox']=='on'){
							$seo_title=$_REQUEST['seo_title'];
							$seo_keywords=$_REQUEST['seo_keywords'];
							$seo_description=$_REQUEST['seo_description'];
							
							$arr_seo_val=array('rel'=>$idproduct, 'title'=>$seo_title, 'keywords'=>$seo_keywords, 'description'=>$seo_description);
							if($this->is_seo_exist_rel('product','idproduct',$idproduct)){
								$update_seo=$this->seo_update_rel('product','idproduct',$arr_seo_val);
								if(!$update_seo["success"]){
									$arr_state['db_error']=true;
									$arr_state['db_message'].=$update_seo["message"];
								}
							}else{
								$insert_seo=$this->seo_insert_rel('product','idproduct',$arr_seo_val);
								if(!$insert_seo["success"]){
									$arr_state['db_error']=true;
									$arr_state['db_message'].=$insert_seo['message'];
								}
							}
						}
					}
				}
				//END SEO INPUT*******************
			if($arr_state['img_error']||$arr_state['db_error'])//BEGIN COMMIT / ROLLBACK
				mysql_query("ROLLBACK",$this->db_link);
			else
				mysql_query("COMMIT",$this->db_link);
			
			//END UPDATE WITH NEW DATA**********************************
			
			//BEGIN RETURN======================
			if($arr_state['db_error']||$arr_state['img_error']){
				return array("success"=>false,"message"=>"There is error when editing product. Message :".$arr_state['db_message']."-".$arr_state['img_message']);
			}else{
				return array("success"=>true,"message"=>"Success editing product");
			}
			//END RETURN*************************
		}
		private function product_delete(){
			$arr_state=array("db_error"=>false,"db_message"=>"","img_error"=>false,"img_message"=>"");
			$idproduct=$_REQUEST["idproduct"];
			$idproduct_group=$_REQUEST["idproduct_group"];
			$product_name=$_REQUEST["product_name"];
			
			$product_group_description=$this->get_product_group_desc($idproduct_group);
			if(!$product_group_description){
				$arr_state["db_error"]=true;
				$arr_state["db_message"]="Error when getting product group description";
			}
			$base_path=$this->path_image_product_base().'/'.$product_group_description;
			$image_path=$base_path."/".$product_name.".jpg";
			
			mysql_query("BEGIN",$this->db_link);
				//BEGIN DELETE RELATION==========================
				$del_seo_rel=array();
				if($this->is_seo_exist_rel('product','idproduct',$idproduct)){
					$del_seo_rel=$this->seo_delete_rel('product','idproduct',$idproduct);
				}
				//END DELETE RELATION**************************
				if(!$del_seo_rel["success"]){
					$arr_state["db_error"]=true;
					$arr_state["db_message"]=$del_seo_rel["message"];
				}else{
					if(!$arr_state["db_error"]&&!$arr_state["img_error"]){
						$qry_del_product="DELETE FROM `product` WHERE `idproduct`=$idproduct LIMIT 1";
						log_insert($this->db_link,$qry_del_product);
						$res_del_product=mysql_query($qry_del_product,$this->db_link);
						if(!$res_del_product){
							$arr_state["db_error"]=true;
							$arr_state["db_message"]="There is problem when deleting selected product from database: ".mysql_error();
						}else{
							if(!unlink($image_path)){
								$arr_state["img_error"]=true;
								$arr_state["img_message"]="There is problem when deleting product image.";
							}
						}
					}
				}
			if($arr_state["db_error"]&&$arr_state["img_error"]){
				mysql_query("ROLLBACK",$this->db_link);
				return array("success"=>false,"message"=>"Error when deleting product: ".mysql_error());
			}else{
				mysql_query("COMMIT",$this->db_link);
				return array("success"=>true,"message"=>"<b>".$product_name."</b> deleted successfully.");
			}
		}
		private function get_product_group_desc($p_idpg){
			$qry_product_group="SELECT `description` FROM `product_group` WHERE `idproduct_group`=$p_idpg LIMIT 1";
			$res_product_group=mysql_query($qry_product_group,$this->db_link);
			if(!$res_product_group){
				log_insert($this->db_link,"Error when getting product group:".mysql_error());
				return false;
			}
			$fa_product_group=mysql_fetch_assoc($res_product_group);
			return $fa_product_group['description'];
		}
		private function path_image_product_base(){
			return PATH_IMAGE_PRODUCTS;
		}
		public function product_group_input(){
			log_insert($this->db_link,print_r($_REQUEST,true));
			switch($_REQUEST['input_mode']){
				case "new":
					return json_encode($this->product_group_new());
					break;
				case "edit":
					return json_encode($this->product_group_edit());
					break;
				case "delete":
					return json_encode($this->product_group_delete());
					break;
			}
		}
		private function product_group_new(){
			$arr_state=array("db_error"=>false,"db_message"=>"","img_error"=>false,"img_message"=>"");
			$group_description=$_REQUEST['description'];
			
			$new_base_path=$this->path_image_product_base()."/".$group_description;
			$new_image_path=$new_base_path."/_icon.jpg";
			mysql_query("BEGIN",$this->db_link);
				$qry_new_product_group="INSERT INTO `product_group`(`description`,`parent`)VALUES('$group_description',0)";
				$res_new_product_group=mysql_query($qry_new_product_group,$this->db_link);
				$idproduct_group=-1;
				if(!$res_new_product_group){
					$arr_state["db_error"]=true;
					$arr_state["db_message"]="Error when inserting new product group to database: ".mysql_error();
				}else{
					$idproduct_group=bmysql_insert_id($this->db_link);//GET LAST INSERT ID=====================
					if(!$idproduct_group){
						$arr_state['db_error']=true;
						$arr_state['db_message'].="Error when select inserted id of product group".mysql_error();
					}else{
						//BEGIN UPLOADING IMAGE=========================
						$group_image_file=$_FILES['product_group_image'];
						if(!empty($group_image_file["tmp_name"])&&($group_image_file['tmp_name']!='none')){
							$dir_base_path=true;
							if(!file_exists($new_base_path)){
								if(!mkdir($new_base_path,0777,true)){
									$dir_base_path=false;
									$arr_state["img_error"]=true;
									$arr_state["img_message"].="Error when creating directory for group image";
								}
							}
							if($dir_base_path){
								//BEGIN IMAGE VALIDATION VARIABLES=========================
								$image_size=getimagesize($group_image_file["tmp_name"]);
								$image_error_msg=get_image_upload_error($group_image_file["error"]);
								
								$is_image_valid=($image_size[0]!=0)&&($image_size[1]!=0);
								$is_image_no_error=empty($image_error_msg);
								$is_image_jpeg=exif_imagetype($group_image_file["tmp_name"])==IMAGETYPE_JPEG;
								//END IMAGE VALIDATION VARIABLES************************
								if($is_image_valid&&$is_image_no_error&&$is_image_jpeg){
									@unlink($new_image_path);
									$group_upload_process=image_resize($group_image_file["tmp_name"],SIZE_IMAGE_GROUP, array("str_dest_path"=>$new_image_path));
									if(!$group_upload_process){
										$arr_state["img_error"]=true;
										$arr_state["img_message"].="Error when uploading new group image to destination path";
									}
								}else{
									$arr_state["img_error"]=true;
									if(!$is_image_valid)$arr_state["img_message"].="The image you have uploaded is not a valid image";
									if(!$is_image_no_error)$arr_state["img_message"].=$image_error_msg;
									if(!$is_image_jpeg)$arr_state["img_message"].="Please upload JPEG file only";
								}
							}
						}else{
							$arr_state['img_error']=true;
							$arr_state['img_message']="Please don't left image field empty";
						}
						//END UPLOADING IMAGE***********************
					}
				}
				//BEGIN SEO INPUT========================
				if(!$arr_state['db_error']&&!$arr_state['img_error']){
					if(isset($_REQUEST['pg-seo-checkbox'])){
						if($_REQUEST['pg-seo-checkbox']=='on'){
							$seo_title=$_REQUEST['seo_title'];
							$seo_keywords=$_REQUEST['seo_keywords'];
							$seo_description=$_REQUEST['seo_description'];
							
							$arr_seo_val=array('rel'=>$idproduct_group, 'title'=>$seo_title, 'keywords'=>$seo_keywords, 'description'=>$seo_description);
							$insert_seo=$this->seo_insert_rel('product_group','idproduct_group',$arr_seo_val);
							if(!$insert_seo["success"]){
								$arr_state['db_error']=true;
								$arr_state['db_message']=$insert_seo["message"];
							}
						}
					}
				}
				//END SEO INPUT************************
			if($arr_state["db_error"]||$arr_state["img_error"]){
				mysql_query("ROLLBACK",$this->db_link);
				return array("success"=>false,"message"=>"Error when updating data: ".$arr_state["db_message"]."-".$arr_state["img_message"]);
			}else{
				mysql_query("COMMIT",$this->db_link);
				return array("success"=>true,"message"=>"Success uploading image.");
			}
		}
		private function product_group_edit(){
			$arr_state=array("db_error"=>false,"db_message"=>"","img_error"=>false,"img_message"=>"");
			$idproduct_group=$_REQUEST['idproduct_group'];
			$new_description=$_REQUEST['description'];
			//BEGIN GET OLD DATA===========================
			$old_description=$this->get_product_group_desc($idproduct_group);
			$old_base_path=$this->path_image_product_base()."/".$old_description;
			$old_image_path=$old_base_path."/_icon.jpg";
			//END GET OLD DATA**********************
			//BEGIN GENERATE NEW PATH=============================
			$new_base_path=$this->path_image_product_base()."/".$new_description;
			$new_image_path=$new_base_path."/_icon.jpg";
			//END GENERATE NEW PATH******************************
			mysql_query("BEGIN",$this->db_link);
				$qry_edit_product_group="UPDATE `product_group` SET `description`='$new_description'
					WHERE `idproduct_group`=$idproduct_group
					LIMIT 1";
				$res_edit_product_group=mysql_query($qry_edit_product_group,$this->db_link);

				if(!$res_edit_product_group){//IF FAILED UPDATE GROUP DATABASE TABLE
					$arr_state["db_error"]=true;
					$arr_state["db_message"].="Error when updating database: ".mysql_error();
				}else{
					//BEGIN UPLOADING IMAGE========================
					$group_image_file=$_FILES['product_group_image'];
					$dir_base_path=true;
					if(!file_exists($old_base_path)){
						if(!mkdir($old_base_path,0777,true)){//CREATE OLD BASE PATH FIRST. IF NEW PATH IS DIFFERENT/UPDATED WILL BE RENAMED BY PROCEDURE BELOW
							$dir_base_path=false;
							$arr_state["img_error"]=true;
							$arr_state["img_message"].="Error when creating directory for group images";
						}
					}
					if($dir_base_path){
						if($old_base_path!=$new_base_path){
							if(!rename($old_base_path,$new_base_path)){
								$dir_base_path=false;
								$arr_state["img_error"]=true;
								$arr_state["img_message"]="Error when renaming group directory of image.";
							}
						}
					}
					if(!empty($group_image_file["tmp_name"])&&($group_image_file["tmp_name"]!="none")){
						if($dir_base_path){
							//BEGIN IMAGE VALIDATION VARIABLES==================
							$image_size=getimagesize($group_image_file['tmp_name']);
							$image_error_msg=get_image_upload_error($group_image_file["error"]);
							
							$is_image_valid=($image_size[0]!=0)&&($image_size[1]!=0);
							$is_image_no_error=empty($image_error_msg);
							$is_image_jpeg=exif_imagetype($group_image_file['tmp_name'])==IMAGETYPE_JPEG;
							//END IMAGE VALIDATION VARIABLES******************
							if($is_image_valid&&$is_image_no_error&&$is_image_jpeg){
								@unlink($old_image_path);
								$group_upload_process=image_resize($group_image_file["tmp_name"],SIZE_IMAGE_GROUP, array("str_dest_path"=>$new_image_path));
								if(!$group_upload_process){
									$arr_state['img_error']=true;
									$arr_state['img_message'].="Error when uploading edited group image to the destination path.";
								}
							}else{
								$arr_state["img_error"]=true;
								if(!$is_image_valid)$arr_state["img_message"].="The image you have uploaded is not a valid image";
								if(!$is_image_no_error)$arr_state["img_message"].=$image_error_msg;
								if(!$is_image_jpeg)$arr_state["img_message"].="Please upload JPEG file only";
							}
						}
					}
					//END UPLOADING IMAGE******************
				}
				//BEGIN SEO INPUT=================
				if(!$arr_state['db_error']&&!$arr_state['img_error']){
					if(isset($_REQUEST['pg-seo-checkbox'])){
						if($_REQUEST['pg-seo-checkbox']=='on'){
							$seo_title=$_REQUEST['seo_title'];
							$seo_keywords=$_REQUEST['seo_keywords'];
							$seo_description=$_REQUEST['seo_description'];
							
							$arr_seo_val=array('rel'=>$idproduct_group, 'title'=>$seo_title, 'keywords'=>$seo_keywords, 'description'=>$seo_description);
							if($this->is_seo_exist_rel('product_group','idproduct_group',$idproduct_group)){
								$update_seo=$this->seo_update_rel('product_group','idproduct_group',$arr_seo_val);
								if(!$update_seo["success"]){
									$arr_state['db_error']=true;
									$arr_state['db_message']=$update_seo["message"];
								}
							}else{
								$insert_seo=$this->seo_insert_rel('product_group','idproduct_group',$arr_seo_val);
								if(!$insert_seo["success"]){
									$arr_state['db_error']=true;
									$arr_state['db_message']=$insert_seo["message"];
								}
							}
						}
					}
				}
				//END SEO INPUT*****************
			if($arr_state["db_error"]||$arr_state["img_error"]){
				mysql_query("ROLLBACK",$this->db_link);
				return array("success"=>false,"message"=>"Error when updating data: ".$arr_state["db_message"]."-".$arr_state["img_message"]);
			}else{
				mysql_query("COMMIT",$this->db_link);
				return array("success"=>true,"message"=>"Success updating product group.");
			}
		}
		private function product_group_delete(){
			$arr_state=array('db_error'=>false,'db_message'=>'','img_error'=>false,'img_message'=>'');
			$idproduct_group=$_REQUEST["idproduct_group"];
			$product_group_description=$this->get_product_group_desc($idproduct_group);
			if(!$product_group_description){
				return array("success"=>false,"message"=>"Error when getting group description:".mysql_error());
			}
			
// 			$del_failed=false;
			mysql_query("BEGIN",$this->db_link);
				//BEGIN DELETE RELATION=======================
				$del_seo_rel=array();
				if($this->is_seo_exist_rel('product_group','idproduct_group',$idproduct_group)){
					$del_seo_rel=$this->seo_delete_rel('product_group','idproduct_group',$idproduct_group);
				}
				//END DELETE RELATION**********************
				if(!$del_seo_rel["success"]){
					$arr_state['db_error']=true;
					$arr_state['db_message']=$del_seo_rel['message'];
				}else{
					$qry_product_group_del="DELETE FROM `product_group` WHERE `idproduct_group`=$idproduct_group LIMIT 1";
					$res_product_group_del=mysql_query($qry_product_group_del,$this->db_link);
					if(!$res_product_group_del){
						$arr_state['db_error']=true;
						$arr_state['db_message'].="Error when deleting selected product group from database.".mysql_error();
					}else{
						$path_group=$this->path_image_product_base()."/".$product_group_description;
						if(file_exists($path_group)){
							if(!delTree($path_group)){
								$arr_state['img_error']=true;
								$arr_state['img_message'].="Error when deleting group directory.";
							}
						}
					}
				}
			if($arr_state['db_error']||$arr_state['img_error']){
				mysql_query("ROLLBACK",$this->db_link);
				return array("success"=>false,"message"=>"Error when deleting group:".$arr_state['db_message']."-".$arr_state['img_message']);
			}else{
				mysql_query("COMMIT",$this->db_link);
				return array("success"=>true,"message"=>" <b>".$product_group_description."</b> have been deleted successfully.");
			}
		}
		private function seo_insert_rel($p_table_name,$p_field_rel,$p_arr_val){
			$seo_table_name=TABLE_PREFIX_SEO.$p_table_name;
			$val_rel=$p_arr_val['rel'];
			$val_title=$p_arr_val['title'];
			$val_keywords=$p_arr_val['keywords'];
			$val_description=$p_arr_val['description'];
			
			$qry_ins_seo="INSERT INTO `".$seo_table_name."`(`$p_field_rel`,`title`,`keywords`,`description`)
				VALUES($val_rel,'$val_title','$val_keywords','$val_description')";
			$res_ins_seo=mysql_query($qry_ins_seo,$this->db_link);
			if(!$res_ins_seo){
				return array("success"=>false,"message"=>"Error when inserting SEO for table:".$seo_table_name.", ".mysql_error());
			}else{
				return array("success"=>true,"message"=>"Success insert SEO for table:".$seo_table_name);
			}
		}
		private function seo_update_rel($p_table_name,$p_field_rel,$p_arr_val){
			$seo_table_name=TABLE_PREFIX_SEO.$p_table_name;
			$val_rel=$p_arr_val['rel'];
			$val_title=$p_arr_val['title'];
			$val_keywords=$p_arr_val['keywords'];
			$val_description=$p_arr_val['description'];
			
// 			$qry_ins_seo="INSERT INTO `".$seo_table_name."`(`$p_field_rel`,`title`,`keywords`,`description`)
// 				VALUES($val_rel,'$val_title','$val_keywords','$val_description')";
			$qry_upd_seo="UPDATE `".$seo_table_name."`
				SET `title`='$val_title', `keywords`='$val_keywords', `description`='$val_description'
				WHERE `$p_field_rel`=$val_rel LIMIT 1";
			$res_upd_seo=mysql_query($qry_upd_seo,$this->db_link);
			if(!$res_upd_seo){
				return array("success"=>false,"message"=>"Error when updating SEO for table:".$seo_table_name.", ".mysql_error());
			}else{
				return array("success"=>true,"message"=>"Success update SEO for table:".$seo_table_name);
			}
		}
		private function seo_delete_rel($p_table_name,$p_field_rel,$p_val_rel){
			$seo_table_name=TABLE_PREFIX_SEO.$p_table_name;
			$qry_del_seo="DELETE FROM $seo_table_name WHERE `$p_field_rel`=$p_val_rel LIMIT 1";
			$res_del_seo=mysql_query($qry_del_seo,$this->db_link);
			if(!$res_del_seo){
				return array("success"=>false,"message"=>"Error when deleting SEO table related to $seo_table_name :".mysql_error());
			}else{
				return array("success"=>true,"message"=>"Success delete SEO for table:".$seo_table_name);
			}
		}
		private function is_seo_exist_rel($p_table_name,$p_field_rel,$p_val_rel){
			$seo_table_name=TABLE_PREFIX_SEO.$p_table_name;
			$qry_check_seo="SELECT * FROM `$seo_table_name` WHERE `$p_field_rel`=$p_val_rel";
			log_insert($this->db_link,$qry_check_seo);
			$res_check_seo=mysql_query($qry_check_seo,$this->db_link);
			if(!$res_check_seo){
				return false;
			}else{
				if(mysql_num_rows($res_check_seo)==1){
					return true;
				}else{
					return false;
				}
			}
		}
	}
?>