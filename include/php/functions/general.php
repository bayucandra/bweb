<?php
	function get_enc_password($p_input_password,$p_enc_password){
		switch($p_enc_password){
			case "md5":
				return md5($p_input_password);
				break;
			case "plain":
				return $p_input_password;
				break;
			case "sha1":
				return sha1($p_input_password);
				break;
			default:
				return false;
		}
	}
	function urlGetReinsert($p_url_str,$p_str_get_var_name,$p_str_get_var_val){
		$url_reinserted="";
		$arr_each_get=explode('&',$p_url_str);
		//BEGIN VALIDATING====================
		if(count($arr_each_get)<1)return "";
		//END VALIDATING******************
		foreach($arr_each_get as $each_get){
			$arr_each_get_pair=explode("=",$each_get);
			if($arr_each_get_pair[0]!=$p_str_get_var_name){//IF NOT THE KEY NEED TO REINSERT
				$url_reinserted.=$each_get.'&';
			}
		}
		$url_reinserted.=$p_str_get_var_name.'='.$p_str_get_var_val;
		return $url_reinserted;/*
		for(var i=0;i<arr_each_get.length;i++){
			var arr_each_get_pair=explode("=",arr_each_get[i]);
			if(arr_each_get_pair[0]!=p_str_get_var_name){//IF NOT THE KEY NEED TO REINSERT
				url_reinserted=url_reinserted+arr_each_get[i]+'&';
			}
		}
		url_reinserted=url_reinserted+p_str_get_var_name+'='+p_str_get_var_val;
		return url_reinserted;*/
	}
	function getUrlReconstruct($p_arr_url_get){
		$get_url="";
		if(!isset($p_arr_url_get))
			return $get_url;
		foreach($p_arr_url_get AS $key=>$item){
			$get_url.=$key.'='.$item.'&';
		}
		$get_url=substr($get_url,0,strlen($get_url)-1);
		return $get_url;
	}
	function getUrlRemoveSelected($p_arr_url_get,$p_var_name){
		$url_removed_selected="";
		$arr_each_get=explode('&',$p_arr_url_get);
	
		//BEGIN VALIDATING====================
		if(count($arr_each_get)<1)return "";
		//END VALIDATING******************
		foreach($arr_each_get as $each_get){
			$arr_each_get_pair=explode("=",$each_get);
			if($arr_each_get_pair[0]!=$p_var_name){//IF NOT THE KEY NEED TO REMOVE
				$url_removed_selected.=$each_get.'&';
			}
		}
		$url_removed_selected=substr($url_removed_selected,0,strlen($url_removed_selected)-1);
		return $url_removed_selected;
	}
	function is_email_valid($p_email){
		if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $p_email)){
			return true;
		}else{
			return false;
		}
	}
	function b_set_time_zone($p_time_zone){
		$php_version=phpversion();
		$php_version_sufix=explode(".",$php_version);
		if($php_version_sufix[0]>4){
			date_default_timezone_set($p_time_zone);
		}
	}
	function log_insert($p_db,$p_log_str){
		$log_str=substr(mysql_real_escape_string($p_log_str),0,253);
		$current_date=new DateTime();
		$current_date_str=$current_date->format("Y-m-d H:i:s");
		
		$qry_str_log="INSERT INTO `logs`(`date`,`message`)VALUES('$current_date_str','$log_str')";
		$res_log=mysql_query($qry_str_log,$p_db);
	}
	function delTree($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	} 
	//BEGIN URL FUNCTIONS==================================
	function path_base_script(){
		$script_file_name=$_SERVER['SCRIPT_FILENAME'];
		$last_hash_pos=strrpos($script_file_name,'/');
		$path_base=substr($script_file_name,0,$last_hash_pos);
		return $path_base;
	}
	function url_base($p_no_com_protocol=false){
		$ret="";
		if($p_no_com_protocol)$ret=$_SERVER["HTTP_HOST"].url_dir_base();
		else $ret=COM_PROTOCOL.$_SERVER["HTTP_HOST"].url_dir_base();
		return $ret;
	}
	function url_dir_base($p_no_first_slash=false){
		$str_php_self=$_SERVER['PHP_SELF'];
		$str_self_trim=substr($str_php_self,1,strlen($str_php_self)-1);
		$exp_self_trim=explode("/",$str_self_trim);
		$dir_base="";
		$count_exp_self_trim=count($exp_self_trim);
		if($count_exp_self_trim>1){
			for($i=0;$i<$count_exp_self_trim-1;$i++){
				if(!(($i==0)&&$p_no_first_slash))$dir_base.='/';
				$dir_base.=$exp_self_trim[$i];
			}
		}
		return $dir_base;
	}
	function arr_request(){
		$str_request_uri=$_SERVER['REQUEST_URI'];
		$str_request_uri_trim=substr($str_request_uri,1,strlen($str_request_uri)-1);
		$exp_request_uri_trim=explode("/",$str_request_uri_trim);
		$count_request_uri_trim=count($exp_request_uri_trim);
		$arr_request=array();
		for($i=0;$i<$count_request_uri_trim;$i++){
			$request_val=$exp_request_uri_trim[$i];
			if(($i==0)&&($request_val==url_dir_base(true)))continue;
			if($i==$count_request_uri_trim-1)$request_val=preg_replace("/.html$/i","",$request_val);
			$arr_request[]=$request_val;
		}
		return $arr_request;
	}
	function array_search_compat($p_key,$p_arr){
		$res=array_search($p_key,$p_arr);
		$is_not_found=(is_null($res))||($res===false);
		if($is_not_found)return false;
		else return true;
	}
	//END URL FUNCTIONS*************************
	function get_image_upload_error($p_error_no,$p_arr_exception_no=array()){//$p_arr_exception_no is array of exception number.
	//Return empty if no error
		if(empty($p_error_no))return "";
		
		$image_error="";
		switch($p_error_no){
			case '1':
				if(!array_search_compat($p_error_no,$p_arr_exception_no))
					$error_msg=$image_error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				if(!array_search_compat($p_error_no,$p_arr_exception_no))
					$image_error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				if(!array_search_compat($p_error_no,$p_arr_exception_no))
					$image_error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				if(!array_search_compat($p_error_no,$p_arr_exception_no))
					$image_error = 'No file was uploaded.';
				break;

			case '6':
				if(!array_search_compat($p_error_no,$p_arr_exception_no))
					$image_error = 'Missing a temporary folder';
				break;
			case '7':
				if(!array_search_compat($p_error_no,$p_arr_exception_no))
					$image_error = 'Failed to write file to disk';
				break;
			case '8':
				if(!array_search_compat($p_error_no,$p_arr_exception_no))
					$image_error = 'File upload stopped by extension';
				break;
			case '999':
				if(!array_search_compat($p_error_no,$p_arr_exception_no))
					$image_error = "Unknown error of image";
				break;
			default:
				$image_error = "";
				break;
		}
		return $image_error;
	}
	function bmysql_insert_id($p_db_link){
		$qry_sel_ai="SELECT LAST_INSERT_ID() AS b_insert_id";
		$res_sel_ai=mysql_query($qry_sel_ai,$p_db_link);
		if(!$res_sel_ai){
			return false;
		}else{
			$fa_sel_ai=mysql_fetch_assoc($res_sel_ai);
			return $fa_sel_ai['b_insert_id'];
		}
	}
?>