<?php

session_start();
session_unset();
session_destroy();

if(isset($_COOKIE["usuario"])) {
	borrarCookies();
}

header("Location:login");

?>