<?php

require dirname(__FILE__)."/../config.php";

if($_SERVER['PHP_AUTH_USER']==$panel_login && $_SERVER['PHP_AUTH_PW']==$panel_password){
    function get_server_memory_usage(){

        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        $memory_usage = $mem[2]/$mem[1]*100;

        return $memory_usage;
    }
    function get_server_cpu_usage(){

        $load = sys_getloadavg();
        return $load[0];

    }
    header('Content-Type: application/json');
    $mapka=array(
        "cpu"=>get_server_cpu_usage(),
        "mem"=>get_server_memory_usage()
    );
    echo json_encode($mapka);
}else{
    http_response_code(404);
    die();
}
?>