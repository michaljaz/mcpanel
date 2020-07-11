<?php
require dirname(__FILE__)."/../config.php";
if($_SERVER['PHP_AUTH_USER']==$panel_login && $_SERVER['PHP_AUTH_PW']==$panel_password){

	header('Content-Type: application/json');
	$bans=json_decode(file_get_contents($serverPath."banned-players.json"));
	$ops=json_decode(file_get_contents($serverPath."ops.json"));
	$banips=json_decode(file_get_contents($serverPath."banned-ips.json"));
	$wl=json_decode(file_get_contents($serverPath."whitelist.json"));
	echo json_encode(array(
		"bans"=>$bans,
		"ops"=>$ops,
		"banips"=>$banips,
		"wl"=>$wl
	));
}else{
	http_response_code(404);
	die();
}
?>