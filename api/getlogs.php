<?php
require dirname(__FILE__)."/../config.php";
if($_SERVER['PHP_AUTH_USER']==$panel_login && $_SERVER['PHP_AUTH_PW']==$panel_password){
	echo file_get_contents($serverPath."logs/latest.log");
}else{
	http_response_code(404);
	die();
}

?>