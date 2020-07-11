<?php

require dirname(__FILE__)."/../config.php";
if($_SERVER['PHP_AUTH_USER']==$panel_login && $_SERVER['PHP_AUTH_PW']==$panel_password){
  //
}else{
  http_response_code(404);
  die();
  exit;
}
?>
<?php
$i_s = parse_ini_file($serverPath."server.properties");
?>
<div class="row">
	<div class="col-md-6">
		<div class="card text-light" style="background:#292929">
			<div class="card-header">
			    Informacje
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-5 text-right p-1">
						Adres IP:<br>
						Limit graczy:<br>
						Port gry:<br>
						Port RCON:<br>
					</div>
					<div class="col-md-7 p-1">
						<font color="blue"><?php echo $serverHost;  ?></font>
						<br>
						<font color="blue"><?php echo $i_s["max-players"];?></font>
						<br>
						<font color="blue"><?php echo $i_s["server-port"];?></font>
						<br>
						<font color="blue"><?php echo $i_s["rcon.port"];?></font>
						<br>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card text-light" style="background:#292929">
			<div class="card-header">
			    Status serwera
			</div>
			<div class="card-body">
				<center>
					<div class="status"></div>
				</center>
			    RAM:
			    <div class="progress bg-dark">
				  	<div class="progress-bar x1" role="progressbar" style="width: 0%"></div>
				</div>
				<?php echo (int)(rtrim(shell_exec('nproc'))); ?> vCPU:
				<div class="progress bg-dark">
				  	<div class="progress-bar x2 bg-info" role="progressbar" style="width: 0%"></div>
				</div>
				<hr>
				<div class="gracze">
					Gracze:
					<div class="progress bg-dark">
					  	<div class="progress-bar x3 bg-success" role="progressbar" style="width: 0%"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
setInterval(function (){
	$.get("api/usage.php",null,function (r){
		$(".x1").css("background","rgb("+((r.mem)/100*255)+","+((100-r.mem)/100*255)+",0)")
		r.mem=Math.floor(r.mem*10)/10
		$(".x1").text(r.mem+"%")
		$(".x1").css("width",r.mem+"%")
		r.cpu=Math.floor(r.cpu*1000)/10
		$(".x2").text(r.cpu+"%")
		$(".x2").css("width",r.cpu+"%")
	})
	$.get("query/status.php",null,function (r){
		console.log(r)
		if(r.status==1){
			$(".status").html("<font color='green'>Serwer jest online</font>")
			$(".x3").text(r.gracze_online)
			$(".x3").css("width",r.gracze_online/r.gracze_max*100+"%")
		}else{
			$(".status").html("<font color='red'>Serwer jest offline</font>")
		}
		
	})
},500)
</script>