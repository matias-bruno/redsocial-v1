<?php

// Dirección del sitio web
define('SERVER_URL', 'http://localhost/redsocial2');
//define('SERVER_URL', '/redsocial2');

// Constantes de la base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'red_social');
define('DB_USER', 'root');
define('DB_PASSWORD', "");
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

// Otras constantes
define("BASE_DIR", dirname(dirname(__FILE__)));
define("PROJECT_NAME", "RED SOCIAL");

// Zona horaria
date_default_timezone_set("America/Argentina/Buenos_Aires");

// Items por página
define("ITEMS_PER_PAGE", 5);
?>
