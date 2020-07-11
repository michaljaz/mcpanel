<?php
require dirname(__FILE__)."/../config.php";
if($_SERVER['PHP_AUTH_USER']==$panel_login && $_SERVER['PHP_AUTH_PW']==$panel_password){
	print_r($_POST);
	$files=$_POST["files"];
	$path=$_POST["path"];
	foreach($files as $i=>$j){
		if($i[-1]=='/'){
			echo "FOLDER";
			rmdir($path.$i);
		}else{
			echo "FILE";
			unlink($path.$i);
		}
	}
}else{
	http_response_code(404);
	die();
}
?>
