<?php

require "./autoload.php";

$query = $_SERVER["QUERY_STRING"];

$query = explode("&", $query)[0];

$url = explode("/", $query);

//dd($_SERVER);

if(empty($url[0])) {
    $url[0] = "inicio";
}

// Si es una petición ajax
if($url[0] == "ajax") {
    $file = "./ajax/" . $url[1] . ".php";
    if(file_exists($file)) {
        require $file;
    }
    exit;
}

$vista = "./vistas/" . $url[0] . ".php";

if(file_exists($vista)) {
    require $vista;
} else {
    require "./vistas/404.php";
}
