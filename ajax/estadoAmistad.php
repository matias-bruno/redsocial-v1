<?php

session_start();

$usuario = null;
User::connect();

if(isset($_SESSION["usuario"])) {
    $usuario = User::findByUsername($_SESSION["usuario"]);
}

if(!$usuario) {
    http_response_code(401);
    echo "Se debe estar autenticado para realizar esta petición";
    exit;
}

$usuario2_id = -1;

$ok = true;
$status = "";
$usuarioAmigo = "";

if($_POST["usuario"]) {
    $usuarioPerfil = User::findByUsername($_POST["usuario"]);
    if($usuarioPerfil) {
        // En este caso se está viendo un perfil que no es del usuario logueado
        $usuario1_id = $usuario->__get("id");
        $usuario2_id = $usuarioPerfil->__get("id");
        $usuarioAmigo = $usuarioPerfil->__get("usuario");
        $status = "agregar";
        $amistad = Amistad::getRequest($usuario1_id, $usuario2_id);
        if($amistad) {
            $status = $amistad->__get("status");
        }
    }
}

$respuesta["ok"] = $ok;
$respuesta["status"] = $status;
$respuesta["usuarioAmigo"] = $usuarioAmigo;

echo json_encode($respuesta);