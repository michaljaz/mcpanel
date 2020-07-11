<?php


require dirname(__FILE__)."/../config.php";

if($_SERVER['PHP_AUTH_USER']==$panel_login && $_SERVER['PHP_AUTH_PW']==$panel_password){
	$path=$_POST["path"];
	$from=$path.$_POST["from"];
	$to=$path.$_POST["to"];
	if(file_exists($to))
	{ 
	   echo "Error While Renaming $from" ;
	}
	else
	{
	   if(rename( $from, $to))
	   { 
	   echo "Successfully Renamed $from to $to" ;
	   }
	  else
	  {
	   echo "A File With The Same Name Already Exists" ;
	  }
	}
}else{
	http_response_code(404);
	die();
}

?>