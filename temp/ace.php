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
<style type="text/css" media="screen">
   #editor {
       height: 500px;
   }
</style>
<script src="src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<a class="btn btn-primary" href="?temp=plugins&path=<?php echo $_GET["back"]; ?>">
  <i class="fas fa-arrow-left"></i>
</a>
<button class="btn btn-outline-success" onclick="save()">
  Zapisz
</button>
<button class="btn btn-outline-primary" onclick="document.location.reload()">
  Odśwież
</button>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
             <div class="btn btn-dark text-light"><?php echo $_GET["file"]; ?></div>
        </div>
        <div class="panel-body">
            <div id="editor"><?php echo htmlspecialchars(file_get_contents($_GET["loc"])); ?></div>
        </div>
    </div>
    <br>
    <div class="text-center alert btn-success xxdd">
      SAVED!
    </div>
</div>
	        
<script>
var editor = ace.edit("editor");
editor.setTheme("ace/theme/monokai");
editor.session.setMode("ace/mode/yaml");
editor.setOptions({
  // fontFamily: "tahoma",
  fontSize: "15pt",
  tabSize: 2
});
$(".xxdd").slideUp(0)
function save(){
  var code=editor.getValue();
  $.post("api/savefile.php",{
    code:code,
    plugin:"<?php echo $_GET["loc"]; ?>"
  },function (r){
    $(".xxdd").slideDown(100)
    setTimeout(function (){
      $(".xxdd").slideUp(100)
    },1000)
  })
}
$(window).bind('keydown', function(event) {
    if (event.ctrlKey || event.metaKey) {
        switch (String.fromCharCode(event.which).toLowerCase()) {
        case 's':
            event.preventDefault();
            save()
            break;
        // case 'f':
        //     event.preventDefault();
        //     alert('ctrl-f');
        //     break;
        // case 'g':
        //     event.preventDefault();
        //     alert('ctrl-g');
        //     break;
        }
    }
});</script>