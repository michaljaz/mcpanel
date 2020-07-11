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
// error_reporting(E_ALL);
// ini_set("display_errors", 1);
if (strpos($_GET["path"],'/') === false) {
    $back="?temp=plugins";
}else{
	$i=strlen($_GET["path"]);
	while($_GET["path"][$i]!="/"){
		$i-=1;
	}
	$back="?temp=plugins&path=".substr($_GET["path"],0,$i);
	// $back=$i;
}
?>
<style>
.xdxd .btn{
	margin:0px;
	padding:10px;
}

</style>

<div class="card text-light" style="background:#292929">
	<div class="card-header">
		<a class="btn btn-primary" href="<?php echo $back; ?>">
			<i class="fas fa-arrow-left"></i>
		</a>
		<!-- Split button -->
		<button class="btn btn-dark" onclick="del_files()">
			Usuń zaznaczone
		</button>
		<div class="float-right">
			<button class="btn btn-dark" onclick="new_file()">
				Nowy plik
			</button>
			<button class="btn btn-dark" onclick="new_folder()">
				Nowy folder
			</button>
		</div>
		<div></div>
		<br>
		
		<div class="row">
			<div class="col-md-1 mt-2">
				<center>
    				<!-- Default unchecked -->
					<div class="custom-control custom-checkbox">
					    <input type="checkbox" class="custom-control-input" id="uall" onchange="check_a(this)">
					    <label class="custom-control-label" for="uall"></label>
					</div>
    			</center>
			</div>
			<div class="col-md-3">
				<div class="text-left mt-2">
	    			Nazwa
	    		</div>
			</div>
			<div class="col-md-4">
				Opcje
				<!-- onclick="document.location='?temp=pluginedit&plugin=<?php echo $plugins[$i]; ?>'" -->
			</div>
			<div class="col-md-2">
				Rozmiar
				<!-- onclick="document.location='?temp=pluginedit&plugin=<?php echo $plugins[$i]; ?>'" -->
			</div>
			<div class="col-md-2">
				Chmod
				<!-- onclick="document.location='?temp=pluginedit&plugin=<?php echo $plugins[$i]; ?>'" -->
			</div>
		</div>
	</div>
	<div class="card-body" style="overflow:auto;height:600px;">
		<?php
function FC($bytes)
{
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}
function dirToArray($dir) {
  
   $result = array();

   $cdir = scandir($dir);
   foreach ($cdir as $key => $value)
   {
      if (!in_array($value,array(".","..")))
      {
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
         {
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
         }
         else
         {
            $result[] = $value;
         }
      }
   }
  	arsort($result);
   return $result;
}
if(!isset($_GET["path"])){
	$path=$serverPath;
}else{
	$path=$serverPath.$_GET["path"]."/";
}
function gu() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}
$files=dirToArray($path);
$uuids=array();
	    foreach($files as $i=>$j){
	    	$uuid=gu();
	    	array_push($uuids,$uuid);
	    	if(is_array($j)){
	    	?>
	    	<div class="row mb-2">
    			<div class="col-md-1 mt-2">
    				<center>
						<!-- Default unchecked -->
						<div class="custom-control custom-checkbox">
						    <input type="checkbox" class="custom-control-input" id="u<?php echo $uuid;?>" onchange="check('<?php echo $i."/";?>',this)">
						    <label class="custom-control-label" for="u<?php echo $uuid;?>"></label>
						</div>
					</center>
    			</div>
    			<div class="col-md-3 waves waves-light" onclick="document.location='?temp=plugins&path=<?php echo $_GET["path"]."/".$i; ?>'">
    				<div class="text-left mt-2 text-primary">
		    			<?php
		    			echo $i."<br>";
		    			?>
		    		</div>
    			</div>
    			<div class="col-md-4 xdxd">
    				<button class="btn btn-outline-success" onclick="changeName('<?php echo $i;?>/');">
    					Zmień nazwę
    				</button>
    				<!-- onclick="document.location='?temp=pluginedit&plugin=<?php echo $plugins[$i]; ?>'" -->
    			</div>
    			<div class="col-md-2">
					<div class="ml-3">
    					<?php echo "<font color='blue'>".(count(scandir($path.$i."/"))-2)." items</font>"; ?>
    				</div>
				</div>
    			<div class="col-md-2">
    				<div class="ml-3">
    					<?php echo substr(decoct(fileperms($path.$i)),-4); ?>
    				</div>
					
				</div>
    			
    		</div>
    		<hr>
	    	<?php
	    	}else{
	    	?>
	    	<div class="row mb-2">
    			<div class="col-md-1 mt-2">
    				<center>
	    				<!-- Default unchecked -->
						<div class="custom-control custom-checkbox">
						    <input type="checkbox" class="custom-control-input" id="u<?php echo $uuid;?>" onchange="check('<?php echo $j;?>',this)">
						    <label class="custom-control-label" for="u<?php echo $uuid;?>"></label>
						</div>
	    			</center>
    			</div>
    			<div class="col-md-3">
    				<div class="text-left mt-2">
		    			<?php
		    			echo $j."<br>";
		    			?>
		    		</div>
    			</div>
    			<div class="col-md-4 xdxd">
    				<button class="btn btn-outline-warning" onclick="document.location='?temp=ace&file=<?php echo $j; ?>&loc=<?php echo $path.$j; ?>&back=<?php echo $_GET["path"]; ?>'">
    					Edytuj
    				</button>
    				<button class="btn btn-outline-success" onclick="changeName('<?php echo $j;?>');">
    					Zmień nazwę
    				</button>
    				<!-- onclick="document.location='?temp=pluginedit&plugin=<?php echo $plugins[$i]; ?>'" -->
    			</div>
    			<div class="col-md-2">
					<div class="ml-3">
    					<?php echo FC(filesize($path.$j)); ?>
    				</div>
				</div>
    			<div class="col-md-2">
    				<div class="ml-3">
    					<?php echo substr(decoct(fileperms($path.$j)),-4); ?>
    				</div>
					
				</div>
    		</div>
    		<hr>
	    	<?php
	    	}
	    	
	    }
	    // echo [3];
	    ?>
<!-- 	    <button class="btn btn-primary">
	    	Dodaj			
	    </button> -->
	</div>
</div>
<script>
function new_file(){
	var name=prompt("Nazwa pliku:")
	if(name!=null){
		$.post("api/new_.php",{
			type:"file",
			loc:"<?php echo $path;?>/"+name
		},function (r){
			console.log(r)
			document.location.reload()
		})
	}
}
function new_folder(){
	var name=prompt("Nazwa folderu:")
	if(name!=null){
		$.post("api/new_.php",{
			type:"folder",
			loc:"<?php echo $path;?>/"+name
		},function (r){
			console.log(r)
			document.location.reload()
		})
	}
	
}
var checked={}
function check(name,_this){
	checked[name]=_this.checked
	// updateStatus()
}
function check_a(_this){
	console.log(_this.checked)
	if(_this.checked){
		<?php
		foreach($uuids as $i=>$j){
			echo "$('#u".$j."').click();\n";
		}
		?>
	}else{
		<?php
		foreach($uuids as $i=>$j){
			echo "$('#u".$j."').click();\n";
		}
		?>
	}
	
}
function changeName(old){
	var name=prompt(`Zmień nazwę`,old)
	if(name!=null){
		$.post("api/chname.php",
			{
				from:old,
				to:name,
				path:"<?php echo $path;?>"
			},function (r){
				console.log(r)
				document.location.reload()
			}
		)
	}
	console.log(name)
}
function isEmpty(obj) {
    return Object.keys(obj).length === 0;
}
function del_files(){
	if(isEmpty(checked)==false){
		$.post("api/del_files.php",
			{
				files:checked,
				path:"<?php echo $path;?>"
			},
			function (r){
				console.log(r)
				document.location.reload()
			}
		)
	}else{
		alert("Musisz zaznaczyć co najmniej jeden element!")
	}
	
}
// function updateStatus(){
// 	var html=""
// 	Object.keys(checked).forEach(function (z){
// 		if(checked[z]){
// 			html+=`[${z}] `
// 		}
// 	})
// 	$(".status").html(html)
// }

	</script>
