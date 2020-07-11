<?php
require dirname(__FILE__)."/../config.php";
if($_SERVER['PHP_AUTH_USER']==$panel_login && $_SERVER['PHP_AUTH_PW']==$panel_password){
	$fp = fopen($_POST["plugin"], 'w');
	fwrite($fp, $_POST["code"]);
	fclose($fp);
	echo "OK";
}else{
	http_response_code(404);
	die();
}
?>