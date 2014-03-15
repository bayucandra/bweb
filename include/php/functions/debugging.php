<?php
	function dump_data($p_content,$p_filename){
		$filename=$p_filename;
		$content=$p_content;
		if(is_writeable($filename)){
			if(!$handle=fopen($filename,'a')){
				echo "Cant open file";
				exit;
			}
			if(fwrite($handle,$content)===FALSE){
				echo "Can't write file";
				exit;
			}
			fclose($handle);
		}else{
			echo "The file $filename is not writeable";
		}
	}
?>