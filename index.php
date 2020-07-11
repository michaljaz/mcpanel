
<?php
require "config.php";
if($_GET["action"]=="logout"){
	$_SERVER['PHP_AUTH_USER']="";
	$_SERVER['PHP_AUTH_PW']="";
}
if($_SERVER['PHP_AUTH_USER']==$panel_login && $_SERVER['PHP_AUTH_PW']==$panel_password){
	echo "<pre>$xd</pre>";
	?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Minecraft server panel</title>
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
	<!-- Google Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
	<!-- Bootstrap core CSS -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
	<!-- Material Design Bootstrap -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.16.0/css/mdb.min.css" rel="stylesheet">
		
	<meta name="description" content="Oficjalna strona serwera Meteor.happyminecraft.net">
  	<meta name="keywords" content="meteor, meteorcraft, meteor minecraft, meteor happyminecraft, meteor server,minecraft">
	<!-- JQuery -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!-- Bootstrap tooltips -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
	<!-- Bootstrap core JavaScript -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
	<!-- MDB core JavaScript -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.16.0/js/mdb.min.js"></script>
	<script src="https://rawgit.com/notifyjs/notifyjs/master/dist/notify.js"></script>
	<style>
body{
	background:#303030;
}
.btn {
    text-transform: none;
}

	</style>
</head>
<body>
	<div class="container mt-4">
		<a type="button" class="btn btn-dark" href="?temp=main">Panel Administracyjny</a>
		<a class="btn btn-dark" href="?temp=rcon">Konsola</a>
		<a class="btn btn-dark" href="?temp=plugins">WebFTP</a>
		<a href="?action=logout" class="btn btn-outline-danger">Wyloguj</a>
		
		
		<br><br>
		<?php 
$access=["index","rcon","plugins","settings","pluginedit","ace"];
$temp=$_GET["temp"];
if(!isset($temp)){
	echo "<script>document.location='?temp=index'</script>";
}else if(in_array($temp,$access,TRUE)){
	include("temp/".$temp.".php");
}else{
	header("Location: ?temp=index");
}
?>
	</div>
</body>
</html>
	<?php
}else{
	header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    ?>
    <!-- Nie uda ci się tutaj dostać
    	Wszystko zostało bardzo dobrze zabezpieczone-->
  <script>document.location='../'</script>
    <?php
}
?>
