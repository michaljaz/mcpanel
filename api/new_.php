<?php
require dirname(__FILE__)."/../config.php";
if($_SERVER['PHP_AUTH_USER']==$panel_login && $_SERVER['PHP_AUTH_PW']==$panel_password){
	if($_POST["type"]=="file"){
		$myfile = fopen($_POST["loc"], "w");
	}else{
		mkdir($_POST["loc"]);
	}
	echo "OK";
}else{
	http_response_code(404);
	die();
}
?>