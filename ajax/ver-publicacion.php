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

if( isset($_POST['id']) ) {
    $publicacion_id = $_POST['id'];
    $publicacion = Publicacion::findById($publicacion_id);
    if(!$publicacion) {
        http_response_code(400);
        echo "No existe ninguna publicación con ese id";
        exit;
    }

    $publicacionData = $publicacion->attributes();
    $publicacionData["id"] = $publicacion->__get("id");

    $usuarioPublicacion = User::findById($publicacion->__get("usuario_id"));
    $imagenUsuario = Imagen::findById($usuarioPublicacion->__get("imagen_id"));
    if($imagenUsuario) {
        $publicacionData["imagen_usuario"] = $imagenUsuario->__get("nombre");
    } else {
        $publicacionData["imagen_usuario"] = "default.png";
    }
    $publicacionData["nombre_usuario"] = $usuarioPublicacion->__get("usuario");

    $imagen = Imagen::findById($publicacion->__get("imagen_id"));
    if($imagen) {
        $publicacionData["imagen"] = $imagen->__get("nombre");
        $album_id = $imagen->__get("album_id");
        $publicacionData["album"] = Album::findById($album_id)->__get("nombre");
    }
    $publicacionData["likes_count"] = $publicacion->getLikesCount();
    $publicacionData["comments_count"] = $publicacion->getCommentsCount();
    $publicacionData["liked"] = $publicacion->isLiked($usuario->__get("id"));
    $publicacionData['time_stamp'] = mostrarDiferencia($publicacion->__get("created_at"));
    $publicacionData['usuario_id'] = $usuarioPublicacion->__get("id");

    $response = [];

    array_push($response, $publicacionData);
    array_push($response, ['userId' => $usuario->__get("id")]);

    echo json_encode($response);
}