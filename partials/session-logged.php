<?php

session_start();

User::connect();

if(isset($_SESSION["usuario"])) {
    $usuario = User::findByUsername($_SESSION["usuario"]);
}

if(!$usuario) {
    header("location:" . SERVER_URL . "/login");
	exit;
};

$imagenUsuario = "default.png";

$imagen_id = $usuario->__get("imagen_id");

if($imagen_id) {
	$objImagen = Imagen::findById($imagen_id);
	if($objImagen) {
		$imagenUsuario = $objImagen->__get("nombre");
	}
}


$usuario_id = $usuario->__get("id");
$mensajesSinLeer = Mensaje::getUnreadNumber($usuario_id);
$solicitudesNuevas = Amistad::countNew($usuario_id);
$notificacionesNuevas = Notificacion::getUnseenNumber($usuario_id);

?>