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
?>