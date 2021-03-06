<?php
require_once(PATH_LIBS_SMARTY_OBJ);
require_once(PATH_PHP_MAILER_OBJ);

class BViews extends Smarty{
	private $db_link,$errors_fields_class_catalog,$errors_fields_message_catalog,$values_last_fields_catalog;//,$success_messages_catalog;
	public $debugging=true;
	public $caching=false;
	//public $cache_lifetime=120;
	public $tpls="";
	function __construct($p_db_link){
		parent::__construct();
		$this->errors_fields_class_catalog=array("fullname"=>"","address"=>"","country"=>"","phone"=>"","email"=>"");
		$this->errors_fields_message_catalog=array();
		$this->values_last_fields_catalog=array("title_mr"=>"selected","title_mrs"=>"","fullname"=>"","address"=>"","country"=>"","phone"=>"","fax"=>"","email"=>"","website"=>"");
		$this->db_link=$p_db_link;
// 		$this->success_messages_catalog="";
		$this->setTemplateDir(PATH_TEMPLATE);
		$php_self_url=$_SERVER['PHP_SELF']."?".getUrlReconstruct($_GET);
		$this->assign("php_self",$php_self_url);
	}

	function first_request(){
		$page="";
		$arr_request=arr_request();
		if(isset($arr_request[0])){
			$page=$arr_request[0];
		}
		return $page;
	}
	function show_header($p_title_str){
		//=============BEGIN HEAD VARS==============
		$google_tracking="
			<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

				ga('create', 'UA-47992778-6', 'indonesiabamboofurniture.com');
				ga('send', 'pageview');

			</script>";
		$site_title=SITE_TITLE."-".$p_title_str;
		$this->assign("site_title",$site_title);
		$this->assign("google_tracking",$google_tracking);
		$this->assign("url_home",url_base()."/home.html");
		$this->assign("url_about",url_base()."/page/about.html");
		$this->assign("url_products",url_base()."/products.html");
		$this->assign("url_catalog",url_base()."/catalog.html");
		$this->assign("url_contact",url_base()."/page/contact.html");
		$this->assign("url_link",url_base()."/page/link.html");
		//*************END HEAD VARS****************
		//============BEGIN CSS PATH================
		$us_paths_css=unserialize(PATH_LIBS_CSS_GENERAL);
		$path_css="";
		foreach($us_paths_css as $val_path_css){
			$tmp_path_css="<link";

			foreach($val_path_css as $val_path_css_detail){
				$tmp_css_detail_attr="";
				$inc_css_detail_attr=0;
				$tmp_path_css.=" ";
				foreach($val_path_css_detail as $val_path_css_detail_attr){
					$tmp_path_css.="$val_path_css_detail_attr";
					if($inc_css_detail_attr==0){
						$tmp_path_css.="=\"";
						if($val_path_css_detail_attr=="href")$tmp_path_css.=url_base()."/";
					}
					$inc_css_detail_attr++;
				}
				$tmp_path_css.="\" ".$tmp_css_detail_attr."";
			}
			$tmp_path_css.="/>\n";
			$path_css.=$tmp_path_css;
		}
		$this->assign("paths_css",$path_css);
		//************END CSS PATH******************
		//BEGIN MENU IS ACTIVE?===============================
		$page_is_home="";$page_is_about="";$page_is_products="";$page_is_contact="";$page_is_link="";$page_is_catalog="";
		$first_request=$this->first_request();
		if(($first_request=="")||($first_request=="home"))
			$page_is_home=' class="active"';
		if(($first_request=="page")&&($p_title_str=="about"))
			$page_is_about=' class="active"';
		if($first_request=="products")
			$page_is_products=' class="active"';
		if($first_request=="catalog")
			$page_is_catalog=' class="active"';
		if(($first_request=="page")&&($p_title_str=="contact"))
			$page_is_contact=' class="active"';
		if(($first_request=="page")&&($p_title_str=="link"))
			$page_is_link=' class="active"';
		$this->assign("page_is_home",$page_is_home);
		$this->assign("page_is_about",$page_is_about);
		$this->assign("page_is_products",$page_is_products);
		$this->assign("page_is_catalog",$page_is_catalog);
		$this->assign("page_is_contact",$page_is_contact);
		$this->assign("page_is_link",$page_is_link);
		//END MENU IS ACTIVE********************************
		
		//============BEGIN JS PATH=================
		$us_paths_js=unserialize(PATH_LIBS_JS_GENERAL);
		$paths_js="";
		foreach($us_paths_js as $val_path_lib_js){
			if(substr($val_path_lib_js,0,7)=="COMMENT"){
				$paths_js.=substr($val_path_lib_js,7,strlen($val_path_lib_js)-7)."\n";
			}elseif(substr($val_path_lib_js,0,4)=="PATH"){
				$paths_js.="<script src=\"".url_base()."/".substr($val_path_lib_js,4,strlen($val_path_lib_js)-4)."\"></script>"."\n";
			}
		}
		$this->assign("paths_js",$paths_js);
		//**********************END JS PATH******************
		$this->tpls.=$this->fetch("header.php");
	}
	function show_home(){
		$nivo_images=$this->nivo_img_path();
		$this->assign("nivo_images",$nivo_images);
		$this->assign("random_product",$this->random_product());
		$this->tpls.=$this->fetch('home.php');
	}
	function show_footer(){
		//$this->assign("",);
		$this->assign("url_base",url_base());
		$this->tpls.=$this->fetch("footer.php");
	}
	function nivo_img_path(){
		$nivo_images="";
		$qry_nivo="SELECT * FROM `slider` ORDER BY short_order ASC";
		$res_nivo=mysql_query($qry_nivo,$this->db_link);
		while($fa_slider=mysql_fetch_assoc($res_nivo)){
			$slider_filename=$fa_slider["filename"];
			$description=$fa_slider["description"];
			$nivo_images.="<img src=\"".url_base()."/image-slider.php?sz=-1&&fname=$slider_filename&&dmy=Bamboo set\" data-thumb=\"image-slider.php?sz=-1&&fname=$slider_filename\" alt=\"$description\" title=\"$description\"/>";
		}

		return $nivo_images;
	}
	function random_product(){
		$ret="
			<div id=\"random_product\">
			<div class=\"header ftahoma f14\">
				<div class=\"line left\"></div>
				<div class=\"label\">RANDOM PRODUCTS</div>
				<div class=\"line right\"></div>
			</div>";
		$total_record=0;
		$qry_random_product="SELECT p.idproduct,p.code,p.name,p.idproduct_group,pg.description AS group_name
			FROM product p
			LEFT JOIN product_group pg ON(p.idproduct_group=pg.idproduct_group)
			ORDER BY RAND() LIMIT 5";
		$res_random_product=mysql_query($qry_random_product,$this->db_link);
		$total_record=mysql_num_rows($res_random_product);

		$i=0;
		while($fa_random_product=mysql_fetch_assoc($res_random_product)){
			$margin_class=" margin_normal";
			$group_name=$fa_random_product["group_name"];
			$product_name=$fa_random_product["name"];
			if($i==0)
				$margin_class=" margin_first";
			if($i==$total_record-1)
				$margin_class=" margin_last";
			$ret.="
				<a href=\"".url_base()."/products/".$group_name.".html\" class=\"sh$margin_class\">
					
					<table>
					<tr><td valign=\"middle\" align=\"center\">
						<img src=\"".url_base()."/imagep.php?gname=$group_name&&pname=$product_name&&sz=155&&dmy=Bamboo furniture\" alt=\"Bamboo $group_name > $product_name\" />
					</td></tr>
					</table>
					
				</a>";
			$i++;
		}
			
		$ret.="</div>";//End of <div id=\"random_product\">
		return $ret;
	}
	function show_products(){
		$arr_class=array("wrapper"=>"bc_wrapper f15 farial","root"=>"root fbold","active"=>"active fbold","current"=>"current","separator"=>"separator");
		$arr_separator=array("type"=>"html","value"=>"»");
		
		$product_group_html="";
		$product_list_html="";
		$arr_request=arr_request();
		if(isset($arr_request[1])){
			$category=urldecode($arr_request[1]);
			$arr_loc_product_list=array(
				array("type"=>"root","url"=>url_base()."/home.html","text"=>"Home"),
				array("type"=>"active","url"=>url_base()."/products.html","text"=>"Product Categories"),
				array("type"=>"current","url"=>"","text"=>$category)
			);
			$bread_crumb=$this->gen_bread_crumb($arr_loc_product_list,$arr_class,$arr_separator);
			$this->assign("bread_crumb",$bread_crumb);
			$product_list_html=$this->show_product_list($category);
		}else{
			$arr_loc_product_group=array(
				array("type"=>"root","url"=>url_base()."/home.html","text"=>"Home"),
				array("type"=>"current","url"=>"","text"=>"Product Categories")
			);
			$bread_crumb=$this->gen_bread_crumb($arr_loc_product_group,$arr_class,$arr_separator);
			$this->assign("bread_crumb",$bread_crumb);
			$product_group_html=$this->show_product_group();
		}
		$this->assign("product_group",$product_group_html);
		$this->assign("product_list",$product_list_html);
		$this->tpls.=$this->fetch('product.php');
	}
	function show_product_group(){
		$ret_product_group="";
		$qry_product_group="SELECT * FROM product_group ORDER BY `description` ASC";
		$res_product_group=mysql_query($qry_product_group,$this->db_link);
		
		while($fa_product_group=mysql_fetch_assoc($res_product_group)){
			$html_text_products="";
			$idproduct_group=$fa_product_group["idproduct_group"];
			$qry_product_rand="SELECT p.`name`
				FROM product p
				WHERE p.idproduct_group=$idproduct_group
				ORDER BY RAND()
				LIMIT 5";
			$res_product_rand=mysql_query($qry_product_rand,$this->db_link);
			
			while($fa_product_rand=mysql_fetch_assoc($res_product_rand)){
				$html_text_products.=$fa_product_rand["name"].", ";
			}
			$html_text_products=substr($html_text_products,0,strlen($html_text_products)-2);
			
			$group_name=$fa_product_group["description"];
			$ret_product_group.="
				<div class=\"group\">
					<div class=\"image\">
						<a href=\"".url_base()."/products/".$group_name.".html\">
						<img src=\"".url_base()."/imagep.php?gname=".$group_name."&&pname=_icon&&sz=200\" alt=\"$group_name\" />
						</a>
					</div>
					<div class=\"detail\">
						<div class=\"category farial f20 fbold\">
							<a href=\"".url_base()."/products/".$group_name.".html\">
								$group_name
							</a>
						</div>
						<div class=\"contain f13\">
							<u>Contains:</u><br />
							$html_text_products
						</div>
					</div>
				</div>";
		}

		return $ret_product_group;
	}
	function show_product_list($p_group_name){
		$ret_product_list="";
		$qry_product_list="SELECT p.idproduct,p.code,p.name,p.idproduct_group,pg.description AS group_name
			FROM product p LEFT JOIN product_group pg ON(p.idproduct_group=pg.idproduct_group)
			WHERE pg.description='$p_group_name'";
		$res_product_list=mysql_query($qry_product_list,$this->db_link);

		$i=0;
		while($fa_product_list=mysql_fetch_assoc($res_product_list)){
			$group_name=$fa_product_list["group_name"];
			$product_name=$fa_product_list["name"];
			
			$class_margin=" margin_normal";
			$class_border=" border_normal";
			if(($i%4)==0){
				$class_margin=" margin_first";
				$class_border=" border_first";
			}
			$ret_product_list.="
				<div class=\"thumb $class_border\">
					<table class=\"$class_margin\"><tr><td valign=\"bottom\" style=\"text-align:center;height:200px;width:200px;\">
						<img src=\"".url_base()."/imagep.php?gname=".$group_name."&&pname=".$product_name."&&sz=200\" alt=\"$product_name\" />
					</td></tr></table>
					<div class=\"label_thumb ftahoma f14\">$product_name</div>
				</div>
			";/*
			$ret_product_list.="
				<div class=\"thumb $class_border\">
					<table class=\"$class_margin\"><tr><td valign=\"bottom\" style=\"height:200px;width:200px;\">
						<img src=\"imagep.php?gname=".$group_name."&&pname=".$product_name."&&sz=200&&rand=".mt_rand()."\" />
					</td></tr></table>
					<div class=\"label_thumb ftahoma f14\">$product_name</div>
				</div>
			";*/
			$i++;
		}
		if(mysql_num_rows($res_product_list)==0)
			$ret_product_list="<h1 style=\"color:#999999\">No products available yet. Please come back later.</h1>";
		
		return $ret_product_list;
	}
	function show_page($p_str_type){
		$page_content="";
		$qry_page="SELECT * FROM `pages` WHERE LOWER(`title`)='$p_str_type' LIMIT 1";
		$res_page=mysql_query($qry_page,$this->db_link);
		if(!$res_page){
			$page_content="<h1 class=\"msg_null_content\">There was error when parsing page Database.</h1>";
		}else{
			$fa_page=mysql_fetch_assoc($res_page);
			$page_content=$fa_page["content"];
		}
		
		//******TO BE CONTINUE**********/
		$this->assign("page_content",$page_content);
		$this->tpls.=$this->fetch("pages.php");
	}
	function show_catalog(){
		$tpl_catalog="";
		if(isset($_POST['submit_catalog'])){
			$this->submit_catalog_request();
			if(count($this->errors_fields_message_catalog)>0)
				$tpl_catalog=$this->show_catalog_form();
			else
				$tpl_catalog=$this->show_catalog_greeting();
		}else{
			$tpl_catalog=$this->show_catalog_form();
		}
		$this->assign("content",$tpl_catalog);
		$this->tpls.=$this->fetch("catalog.php");
	}
	function show_catalog_form(){
		$catalog_form_html="";
		$error_message_html="";
		if(count($this->errors_fields_message_catalog)>0){
			$error_message_html.="<br />";
			foreach($this->errors_fields_message_catalog as $error_message){
				$error_message_html.="<h4 class=\"alert_error\">$error_message</h4>";
			}
		}
		$this->assign("val",$this->values_last_fields_catalog);
		$this->assign("error_messages",$error_message_html);
// 		$this->assign("success_messages",$this->success_messages_catalog);
		$this->assign("errors",$this->errors_fields_class_catalog);
		$catalog_form_html=$this->fetch("catalog_form.php");
		return $catalog_form_html;
	}
	function show_catalog_greeting(){
		$title=$_POST["title"];
		$fullname=$_POST["fullname"];
		$greeting_html="";
		$this->assign("title",$title);
		$this->assign("fullname",$fullname);
		$greeting_html=$this->fetch("catalog_greeting.php");
		return $greeting_html;
	}
	function submit_catalog_request(){//MUST DO VALIDATION OF GET VARIABLE FIRST BEFORE DO SUBMITTING
		//$this->errors_fields_class=array("fullname"=>"","address"=>"","country"=>"","phone"=>"","email"=>"");
		$title=$_POST["title"];
		$fullname=$_POST["fullname"];
		$address=$_POST["address"];
		$country=$_POST["country"];
		$phone=$_POST["phone"];
		$fax=$_POST["fax"];
		$email=$_POST["email"];
		$website=$_POST["website"];
		
		$title_mr_val="";
		$title_mrs_val="";
		if($title=="Mr.")
			$title_mr_val="selected";
		else
			$title_mrs_val="selected";

		$this->values_last_fields_catalog=array("title_mr"=>$title_mr_val,"title_mrs"=>$title_mrs_val,"fullname"=>$fullname,"address"=>$address,"country"=>$country,"phone"=>$phone,"fax"=>$fax,"email"=>$email,"website"=>$website);
		if(empty($fullname)){
			$this->errors_fields_class_catalog["fullname"]=" invalid_field";
			$this->errors_fields_message_catalog[]="Field <b>'Full Name'</b> can't be empty.";
		}
		if(empty($address)){
			$this->errors_fields_class_catalog["address"]=" invalid_field";
			$this->errors_fields_message_catalog[]="Field <b>'Address'</b> can't be empty.";
		}
		if(empty($country)){
			$this->errors_fields_class_catalog["country"]=" invalid_field";
			$this->errors_fields_message_catalog[]="Field <b>'Country'</b> can't be empty.";
		}
		if(empty($phone)){
			$this->errors_fields_class_catalog["phone"]=" invalid_field";
			$this->errors_fields_message_catalog[]="Field <b>'Phone'</b> can't be empty";
		}
		if(empty($email)){
			$this->errors_fields_class_catalog["email"]=" invalid_field";
			$this->errors_fields_message_catalog[]="Field <b>'Email Address' can't be empty</b>";
		}else{
			if(!is_email_valid($email)){
				$this->errors_fields_class_catalog["email"]=" invalid_field";
				$this->errors_fields_message_catalog[]="Please put valid email address.";
			}
		}
		if(count($this->errors_fields_message_catalog)==0){//CHECK IF ALL FIELD IS VALID (NO ERROR MESSAGES)
			//BEGIN CHECK IF EMAIL EXIST==================================
			$qry_check_email_exist="SELECT * FROM `catalog_request` WHERE `email`='$email'";
			$res_check_email_exist=mysql_query($qry_check_email_exist,$this->db_link);
			if(mysql_num_rows($res_check_email_exist)>0){
				$this->errors_fields_message_catalog[]="The email you have submitted is already in our record. Please wait response from our sales person. Or you can send an email to <a href=\"mailto:info@indonesiabamboofurniture.com\">info@indonesiabamboofurniture.com</a> for further information.";
			}else{
			//END CHECK IF EMAIL EXIST*************************************
				$qry_ins_catalog="INSERT INTO `catalog_request`(`title`,`fullname`,`address`,`country`,`phone`,`fax`,`email`,`website`)
					VALUES('$title','$fullname','$address','$country','$phone','$fax','$email','$website')";
				mysql_query($qry_ins_catalog,$this->db_link);
				$idcatalog_request=mysql_insert_id($this->db_link);
				if(mysql_errno()!=0)
					$this->errors_fields_message_catalog[]="There are database error. Please contact web admin".mysql_error();
				else{
					$subject="Catalog Request: $fullname ($email)";
					$message_html="
						<html>
							<head>
								<title>Catalog Request from : $title $fullname ($email)</title>
							</head>
							<body>
								<span style=\"font-family:arial,helvetica,sans-serif,sans;font-size:13px;\">There is catalog request awaiting for response from following person:</span><br /><br />
								<table style=\"font-family:arial,helvetica,sans-serif,sans;font-size:13px;\">
									<tr><td><b>Fullname</b></td><td>:</td><td>$title $fullname</td></tr>
									<tr><td><b>Address</b></td><td>:</td><td>$address</td></tr>
									<tr><td><b>Country</b></td><td>:</td><td>$country</td></tr>
									<tr><td><b>Phone</b></td><td>:</td><td>$phone</td></tr>
									<tr><td><b>Fax</b></td><td>:</td><td>$fax</td></tr>
									<tr><td><b>Email Address</b></td><td>:</td><td>$email</td></tr>
									<tr><td><b>Website</b></td><td>:</td><td>$website</td></tr>
								</table>
							<body>
							
						</html>";
$message_plain="There is catalog request awaiting for response from following person:
Fullname:$title $fullname
Address:$address
Country:$country
Phone:$phone
Fax:$fax
Email Address:$email
Website:$website
";
					$mail=new PHPMailer(true);
					$mail->IsSMTP();
					try{
// 						$mail->Host       = "mail.indonesiabamboofurniture.com"; // SMTP server
						//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
						$mail->SMTPAuth   = true;                  // enable SMTP authentication
						$mail->Host       = "mail.indonesiabamboofurniture.com"; // sets the SMTP server
						$mail->Port       = 587;                    // set the SMTP port for the GMAIL server
						$mail->Username   = "web@indonesiabamboofurniture.com"; // SMTP account username
						$mail->Password   = "webibf";        // SMTP account password
						$mail->AddReplyTo('web@indonesiabamboofurniture.com', 'IBF Web System');
						$mail->SetFrom('web@indonesiabamboofurniture.com', 'IBF Web System');
						$mail->AddAddress('info@indonesiabamboofurniture.com', "Info");//switch addAddress1
// 						$mail->AddAddress('it@jeparagreenfurniture.com', "IT WJB");//switch addAddress2
						$mail->AddCC('web@indonesiabamboofurniture.com',"IBF Web System");
						//$mail->AddReplyTo('name@yourdomain.com', 'First Last');
						$mail->Subject = $subject;
						$mail->MsgHTML($message_html);
						$mail->AltBody=$message_plain;
						if($mail->Send()){
							$qry_upd_email_notified="UPDATE `catalog_request` SET notified=1 WHERE `idcatalog_request`=$idcatalog_request LIMIT 1";
							mysql_query($qry_upd_email_notified);
							if(mysql_errno()!=0)
								$this->errors_fields_message_catalog[]="Error when mark 'notified' at record:".mysql_error();
							//$this->success_messages_catalog="<br /><h4 class=\"alert_success\">Thank you for your submission. Your catalog request have been sent to our marketing person. We will response very soon, please wait.</h4><br />";
						}else{
							$this->errors_fields_message_catalog.=" Error when trying to send the email.";
						}
						//BEGIN GREETING EMAIL=====================================================
						$subject_greeting="Catalog request have been received";
						$message_greeting_html="Dear $title $fullname
							<br />
							<br />Thank you so much for contacting us. One of our sales staff will send the requested catalog to your e-mail address. For further information, please send direct e-mail to info@indonesiabamboofurniture.com.
							<br /><br />
							<br />The Management
							<br />Wisanka - Jepara Branch ";
						$message_greeting_plain="Dear $title $fullname

Thank you so much for contacting us. One of our sales staff will send the requested catalog to your e-mail address. For further information, please send direct e-mail to info@indonesiabamboofurniture.com.


The Management
Wisanka - Jepara Branch ";
						$mail->ClearAllRecipients();
						$mail->ClearAttachments();
						$mail->AddAddress($email, $fullname);
						$mail->Subject=$subject_greeting;
						$mail->MsgHTML($message_greeting_html);
						$mail->AltBody=$message_greeting_plain;
						if(!$mail->Send()){
							$this->errors_fields_message_catalog.=" Error when trying to send greeting email.";
						}
						//END GREETING EMAIL*******************************************************
						
					}catch(phpmailerException $e){
						echo $e->errorMessage();
					}catch(Exception $e){
						echo $e->getMessage();
					}
				}
			}
		}
		
	}
	function gen_bread_crumb($p_arr_loc,$p_arr_class,$p_arr_separator){
		/*
			$p_arr_loc array that contain array of "type" "url" "text". "url" can be empty ("") for current. Type option is "root", "active" and "current"
			$p_arr_class contain string of classes. Classes name are: "wrapper" "root" "active" "current"
			$p_arr_separator contain string, index "type" and "value", "value" can be a text if type "html" or image path if type "path"
		*/
		$class_wrapper=$p_arr_class["wrapper"];
		$class_root=$p_arr_class["root"];
		$class_active=$p_arr_class["active"];
		$class_current=$p_arr_class["current"];
		$class_separator=$p_arr_class["separator"];
		$ret_bread_crumb="<div class=\"$class_wrapper\">";
		$i=0;
		foreach($p_arr_loc as $loc){
			$loc_url=$loc["url"];
			$loc_text=$loc["text"];
			switch($loc["type"]){
				case "root":
					$ret_bread_crumb.="<span class=\"".$class_root."\"><a href=\"".$loc_url."\">".$loc_text."</a></span>";
					break;
				case "active":
					$ret_bread_crumb.="<span class=\"".$class_active."\"><a href=\"".$loc_url."\">".$loc_text."</a></span>";
					break;
				case "current":
					$ret_bread_crumb.="<span class=\"".$class_current."\">".$loc_text."</span>";
					break;
			}
			if($i<(count($p_arr_loc)-1)){
				if($p_arr_separator["type"]=="html")
					$ret_bread_crumb.="<span class=\"$class_separator\">".$p_arr_separator["value"]."</span>";
			}
			$i++;
		}
		$ret_bread_crumb.="</div>";//end of <div class=\"".$p_arr_class["wrapper"]."\">
		return $ret_bread_crumb;
	}
	function __destruct(){
		echo $this->tpls;
		unset($this->tpls);
	}
}
?>