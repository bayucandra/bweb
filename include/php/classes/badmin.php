<?php
//1. Init page, 2.Post login, 3. Home page
require_once(PATH_LIBS_SMARTY_OBJ);
class BAdmin extends Smarty{
	private $db_config;
	private $db_link;
	private $errors;
	private $idadmingroup;
	private $session_name;
	private $tpls;
	private $user_group_id;
	public function __construct($p_db_link,$p_user_group_id){
		$errors=array();
		parent::__construct();
		$this->setTemplateDir("./".PATH_TEMPLATE_ADMIN);

		$this->db_link = $p_db_link;
		$idadmingroup=@unserialize($p_user_group_id);
		$this->idadmingroup=$idadmingroup ["admin"];//Get Admin group id
		$session_name=@unserialize(SESSION_CONF);
		$this->session_name=$session_name ["admin"];
		$this->post_check();
		$this->get_check();
		$this->init_page();
		$this->load_tpl_footer();
	}
	public function get_check(){
		$this->get_check_logout();
	}
	public function get_check_logout(){
		if(isset($_GET)){
			if(isset($_GET["admin_logout"])){
				$this->logout("index.php");
			}
		}
	}
	public function init_page(){//RETURN STRING OF FILE TO INCLUDE
		if($this->logged_in()===true){
			$this->load_tpl_home();
		}else{
			$this->load_tpl_login();
		}
	}
	public function init_session($p_iduser){
		$qry_user="SELECT * FROM `user` WHERE `iduser`=$p_iduser LIMIT 1";
		$res_user=mysql_query($qry_user,$this->db_link);
		$fa_user=mysql_fetch_assoc($res_user);
			$_SESSION[$this->session_name]["first_name"]=$fa_user["first_name"];
			$_SESSION[$this->session_name]["last_name"]=$fa_user["last_name"];
	}
	public function load_tpl_footer(){
		$error_messages="";
		if(isset($this->errors)&&count($this->errors)>0){
			foreach($this->errors as $error){
				$error_messages.="<h4 class=\"alert_error\">$error</h4><br />";
			}
		}
		$this->assign("error_message",$error_messages);
		
		$this->tpls.=$this->fetch("footer.php");
	}
	public function load_tpl_header($p_header_title){
		$this->assign("header_title",$p_header_title);
		$action="";
		if(isset($_GET)){
			if(isset($_GET['act'])){
				$action=$_GET['act'];
			}
		}
		$extjs_app="";

		//BEGIN TRANSLATE SESSION DATA TO JSON==========================
		$session_data=array();
		$session_data['iduser']="";
		if(isset($_SESSION[$this->session_name]["iduser"]))
			$session_data['iduser']=$_SESSION[$this->session_name]["iduser"];
		$json_session=array("session_data"=>$session_data);
		$json_data=json_encode($json_session);
		$json_session="<script type=\"text/javascript\">
				var json_session=JSON.parse('$json_data');//root=session_data
		</script>";
		//END TRANSLATE SESSION DATA TO JSON**************************
		
		switch($action){
			case 'slider':
				$extjs_app="extjsapp/slider.js";
				break;
			case 'pages':
				$extjs_app="extjsapp/pages.js";
				break;
			case 'products':
				$extjs_app="extjsapp/products.js";
				break;
			default:
				$extjs_app="";
				break;
		}
		$extjs_app_url="";
		if(!empty($extjs_app))
			$extjs_app_url='<script type="text/javascript" src="'.$extjs_app.'"></script>';
		$this->assign('extjson_session',$json_session);
		$this->assign("extjs_app",$extjs_app_url);
		$this->tpls.=$this->fetch("header.php");
	}
	public function load_tpl_home(){
		$this->load_tpl_header("Indonesia Bamboo Furniture");
		$this->assign("sidebar",$this->load_tpl_data_sidebar());
		$this->assign("admin_view",$this->load_tpl_data_admin_view());
		$this->tpls.=$this->fetch("home.php");
	}
	public function load_tpl_login(){
		$this->load_tpl_header("Indonesia Bamboo Furniture");
		$this->assign("login_action_form",$_SERVER["PHP_SELF"]);
		$this->tpls.=$this->fetch("login.php");
	}
	public function load_tpl_data_sidebar(){
		$admin_full_name=$_SESSION[$this->session_name]["first_name"]." ".$_SESSION[$this->session_name]["last_name"];
		$this->assign("admin_full_name",$admin_full_name);
		$this->assign("current_page_name",$this->current_page_name());
		return $this->fetch("sidebar.php");
	}
	public function load_tpl_data_admin_view(){
		if(isset($_GET)){
			if(isset($_GET["act"])){
				switch($_GET["act"]){
					case "slider":
						return $this->load_tpl_admin_view_slider();
						break;
					case "pages":
						return $this->load_tpl_admin_view_pages();
						break;
					case "products":
						return $this->load_tpl_admin_view_products();
					case "option":
						return $this->load_tpl_admin_view_option();
					case "tools":
						return $this->load_tpl_admin_view_tools();
					default:
						return $this->fetch("admin_view/welcome.php");
						break;
				}
			}else{
				return $this->fetch("admin_view/welcome.php");
			}
		}else{
			return $this->fetch("admin_view/welcome.php");
		}
	}
	public function load_tpl_admin_view_slider(){
		return $this->fetch("admin_view/slider.php");
	}
	public function load_tpl_admin_view_pages(){
		return $this->fetch("admin_view/pages.php");
	}
	public function load_tpl_admin_view_products(){
		return $this->fetch("admin_view/products.php");
	}
	public function load_tpl_admin_view_option(){
		return $this->fetch("admin_view/option.php");
	}
	public function load_tpl_admin_view_tools(){
		return $this->fetch("admin_view/tools.php");
	}
	public function logged_in(){
		return isset($_SESSION[$this->session_name]["iduser"]) ? true:false;
	}
	//BEGIN: ADMIN MODE REDIRECT=====================================
	public function logged_in_protect($p_url_redirect){
		if($this->logged_in()===true){
			header("Location: $p_url_redirect");
			exit();
		}
	}
	public function logged_out_protect($p_url_redirect){
		if($this->logged_in()===false){
			header("Location: $p_url_redirect");
			exit();
		}
	}
	//END: ADMIN MODE REDIRECT**********************************************************
	public function login($p_username,$p_password){
		$qry_user=sprintf("SELECT * FROM `user` WHERE `username`='%s' AND `usergroup`=%d",
				mysql_real_escape_string($p_username),
				mysql_real_escape_string($this->idadmingroup)
			);
		$res_user=mysql_query($qry_user,$this->db_link);
		$fa_user=mysql_fetch_assoc($res_user);

		$record_password = $fa_user['password'];
		$record_iduser = $fa_user['iduser'];
		if($record_password===get_enc_password($p_password,ENC_PASSWORD)){
			return $record_iduser;
		}else{
			return false;
		}
	}
	public function logout($p_url_redirect){
		unset($_SESSION[$this->session_name]);
		header("Location: $p_url_redirect");
	}
	public function post_check(){
		$this->post_check_login();
	}
	public function post_check_login(){
		if(empty($_POST)===false){
			if(isset($_POST["admin_login"])){
				$username=trim($_POST["username"]);
				$password=trim($_POST["password"]);
				if(empty($username)===true || empty($password)===true){
					$this->errors[]="Username or password can't be empty";
				}else{
					$login=$this->login($username,$password);//return iduser for admin
					if($login===false){
						$this->errors[]="Sorry, username/password is incorrect. Please check again.";
					}else{
						$_SESSION[$this->session_name]["iduser"]=$login;
						$this->init_session($login);
					}
				}
			}
		}
	}
	public function user_exist($p_username){
		$query=$this->db->prepare();
		$qry_user_check="SELECT COUNT(`iduser`) FROM user WHERE `username`='$p_username'";
		$res_user_check=mysql_query($qry_user_check,$this->db_link);
		$numr_user_check=mysql_num_rows($res_user_check);
		
		if($numr_user_check==1){
			return true;
		}else{
			return false;
		}
	}
	private function current_page_name(){
		$act=$_REQUEST['act'];
		switch($act){
			case 'pages':
				return "Pages";
				break;
			case 'slider':
				return "Slider";
				break;
			case 'products':
				return "Products";
				break;
			default:
				return "";
				break;
		}
	}
	public function __destruct(){
		echo $this->tpls;
		unset($this->tpls);
	}
}
?>