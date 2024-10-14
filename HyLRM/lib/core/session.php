<?php


    function session_ready() {
        session_start();
        date_default_timezone_set("Asia/Seoul");

        $request_uri = explode("?", $_SERVER['REQUEST_URI'])[0];
    
        define("HyLRM_Data", [
            "URI" => $request_uri,
            "U" => $request_uri,
            "METHOD" => $_SERVER['REQUEST_METHOD'],
            "M" => $_SERVER['REQUEST_METHOD'],
            "GET" => @$_GET,
            "G" => @$_GET,
            "POST" => $_POST,
            "P" => @$_POST,
            "FILES" => @$_FILES,
            "F" => @$_FILES
        ]);
    }


?>