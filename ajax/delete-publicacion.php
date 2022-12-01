<?php

session_start();

$usuario = null;
User::connect();

if( isset($_POST['id']) ) {

    if(isset($_SESSION["usuario"])) {
        $usuario = User::findByUsername($_SESSION["usuario"]);
    }

    if(!$usuario) {
        http_response_code(401);
        echo "Se debe estar autenticado para realizar esta petición";
        exit;
    }

	$id = intval($_POST['id']);

    $publicacion = Publicacion::findById($id);

    if(!$publicacion) {
        http_response_code(400);
        echo "No existe publicación con ese id";
        exit;
    }

    if($publicacion->__get("usuario_id") == $usuario->__get("id")) {
        if($publicacion->delete($id)) {
            echo "La publicación se eliminó";
        } else {
            http_response_code(500);
            echo "La publicación no se pudo eliminar";
        }
    } else {
        http_response_code(403);
        echo "Solo el usuario que creó la publicación puede borrarla";
    }
} else {
    http_response_code(400);
    echo "No se recibieron los datos requeridos";
}