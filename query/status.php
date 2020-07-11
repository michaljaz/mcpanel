<?php
require dirname(__FILE__)."/../config.php";
require 'src/mcp.php';
require 'src/mcpe.php';
use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;
if($_SERVER['PHP_AUTH_USER']==$panel_login && $_SERVER['PHP_AUTH_PW']==$panel_password){
	
	try
	{
		$Query = new MinecraftPing($serverHost, 25565 );
		$dane=$Query->Query();
		header('Content-Type: application/json');
		$dane["description"]=str_replace("§0","<font color='black'>",$dane["description"]);
		$dane["description"]=str_replace("§1","<font color='darkblue'>",$dane["description"]);
		$dane["description"]=str_replace("§2","<font color='darkgreen'>",$dane["description"]);
		$dane["description"]=str_replace("§3","<font color='darkaqua'>",$dane["description"]);
		$dane["description"]=str_replace("§4","<font color='darkred'>",$dane["description"]);
		$dane["description"]=str_replace("§5","<font color='darkpurple'>",$dane["description"]);
		$dane["description"]=str_replace("§6","<font color='gold'>",$dane["description"]);
		$dane["description"]=str_replace("§o","<i>",$dane["description"]);
		$dane["description"]=str_replace("§l","<b>",$dane["description"]);
		$dane["description"]=str_replace("§n","<u>",$dane["description"]);
		$mapka=["gracze_max"=>$dane["players"]["max"],"gracze_online"=>$dane["players"]["online"],"wersja"=>$dane["version"]["name"],"hostname"=>$dane["description"]["text"],"status"=>1];
		echo json_encode($mapka);
	}
	catch( MinecraftPingException $e )
	{
		// echo $e->getMessage();
		echo "ERROR";
	}
	finally
	{
		if( $Query )
		{
			$Query->Close();
		}
	}
}else{
	http_response_code(404);
	die();
}
?>