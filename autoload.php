<?php

spl_autoload_register(function($nombreClase) {
    if(file_exists(BASE_DIR . "/clases/" . $nombreClase . ".php")) {
        require_once BASE_DIR . "/clases/" . $nombreClase . ".php";
    }
});

require_once("funciones/helpers.php");
require_once("funciones/fechas.php");
require_once("funciones/files.php");
require_once("config/config.php");

?>