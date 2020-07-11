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
require "config.php";
$servprop = parse_ini_file($serverPath."server.properties");


		?>

<div class="card text-light" style="background:#292929">
  	<div class="card-header">
  		<center>
	    	Konsola RCON
	    </center>
  	</div>
  	<div class="card-body" style="overflow:auto; display:flex; flex-direction:column-reverse;;height:300px;">
  		<div id="commands">

    	</div>
  	</div>
  	<!-- Material input -->
  	<form onsubmit="return xd(event);">
		<div class="md-form m-3">
		  <input type="text" id="command" class="form-control text-light" placeholder="/ aby wywołać komendę">
		  <label for="command">Komenda RCON</label>
		</div> 
	</form>
</div>
<br>
<style>
.offline-2, .notloading-2{
	display:none;
}
	</style>
<div class="row">
	<div class="col-md-4">
		<div class="card text-light" style="background:#292929">
		  	<div class="card-header">
		  		<center>
			    	Operatorzy
			    </center>
		  	</div>
		  	<div class="card-body" style="overflow:auto;height:200px;">
		  		<div id="ops_x">

		    	</div>
		  	</div>
		</div>
	</div>
</div>


<script>
var byteRead=0;
function xd(e){
	e.preventDefault();
	if(command.value[0]=="/"){
		command.value=command.value.substr(1,command.value.length)
	}else{
		command.value="say "+command.value
	}
	$.get("rcon/server.php",{cmd:command.value},function (resp){
		// console.log(resp)
		command.value=""
		// resp.response=resp.response.replace(/\n/g,"<br>");
		if(resp.response.length==2){
			$("#command").notify("OK","success")
		}else{
			$("#command").notify(resp.response,"info")
		}	
	})
}
function readdata(data){
	
	if(data!=""){
		// console.log(data)
		data=data.replace(/\[\d\d:\d\d:\d\d\]/g,'<font color="red">\$&</font>')
		data=data.replace(/\[RCON Listener........./g,'<font color="orange">\$&</font>')
		data=data.replace(/\[Server thread....../g,'<font color="darkgreen">\$&</font>')
		data=data.replace(/\[Query Listener........./g,'<font color="darkgreen">\$&</font>')
		data=data.replace(/\[Craft Scheduler Thread........../g,'<font color="darkgreen">\$&</font>')
		$("#commands").append(data)
		$('#commands').scrollTop(($('#commands').height()*2));	
	}
	
	
	

}
reloadConsole()
var war=true;
function reloadConsole(){
	$.get("api/getlogs.php?_=" + new Date().getTime(),function (data){
		data=data.replace(/\n/g,"<br>")
		readdata(data.substr(byteRead,data.length));
		byteRead=data.length;
		// console.log(byteRead,data.length)
		setTimeout(function (){
			reloadConsole()
		},500)
	})
}

var htmlx2="";
var htmlx="";
setInterval(function (){
	$.get("api/get_live_info.php",function (resp){
		// console.log(resp)
		var bans=resp.bans;
		var wl=resp.wl;
		var banips=resp.banips;
		var ops=resp.ops;
		var html=""

		//BANS
		// console.log(bans.length)
		for(var i=0;i<bans.length;i++){
			html+=`
			<div class="mb-3">
			<img src="https://minotar.net/avatar/${bans[i]["name"]}/30.png">
			<span class="p-2">${bans[i]["name"]}</span>
			</div>`;
		}
		if(html!=htmlx2){
			htmlx2=html;
			$("#bans_x").html(html)
		}

		//OPS
		html=""
		for(var i=0;i<ops.length;i++){
			html+=`
			<div class="mb-3">
			<img src="https://minotar.net/avatar/${ops[i]["name"]}/30.png">
			<span class="p-2">${ops[i]["name"]}</span>
			</div>`;
		}
		if(html!=htmlx){
			htmlx=html;
			$("#ops_x").html(html)
		}

		//BANIPS
		html=""
		for(var i=0;i<banips.length;i++){
			html+=`
			<div class="mb-3">
			<img src="https://minotar.net/avatar/${banips[i]["name"]}/30.png">
			<span class="p-2">${banips[i]["name"]}</span>
			</div>`;
		}
		if(html!=htmlx){
			htmlx=html;
			$("#banips_x").html(html)
		}

		//WHITELIST
		html=""
		for(var i=0;i<wl.length;i++){
			html+=`
			<div class="mb-3">
			<img src="https://minotar.net/avatar/${wl[i]["name"]}/30.png">
			<span class="p-2">${wl[i]["name"]}</span>
			</div>`;
		}
		if(html!=htmlx){
			htmlx=html;
			$("#wl_x").html(html)
		}
		
	})
},1000)
	</script>