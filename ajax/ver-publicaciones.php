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

if( isset($_POST['next']) && isset($_POST['urlActual']) ) {
	$next = (int)$_POST['next'];
    $urlActual = $_POST['urlActual'];

    // Por defecto el usuario del perfil, es el usuario logueado
    $usuario_id = $usuario->__get("id");
    $usuario_perfil_id = $usuario_id;

    $pos = strpos($urlActual, 'perfil');
    $friends = true;
    $usuarioPerfil = null;

    // Si estamos en el perfil la vista la pueden ver los amigos
    // El home es privado
    if($pos !== false) {
        $friends = false;
        $tokens = explode('/', $urlActual);
        $nombreUsuario = end($tokens);
        if($nombreUsuario) {
            $usuarioPerfil = User::findByUsername($nombreUsuario);
            if($usuarioPerfil) {
                // En este caso se está viendo un perfil que no es del usuario logueado
                $usuario_perfil_id = $usuarioPerfil->__get("id");
            }
        }
    }

    if(!$usuarioPerfil) { $usuarioPerfil = $usuario; }

    $publicaciones = Publicacion::getPosts($next, $usuarioPerfil->__get("id"), $friends);
    $responsePublicaciones = [];
    $size = count($publicaciones);

    foreach($publicaciones as $publicacion) {
        $publicacionData = $publicacion->attributes();
        $publicacionData["id"] = $publicacion->__get("id");

        $usuarioPublicacion = User::findById($publicacion->__get("usuario_id"));
        
        $publicacionData["imagen_usuario"] = "default.png";
        $imagenUsuario = Imagen::findById($usuarioPublicacion->__get("imagen_id"));
        if($imagenUsuario) {
            $publicacionData["imagen_usuario"] = $imagenUsuario->__get("nombre");
        }
        $publicacionData["nombre_usuario"] = $usuarioPublicacion->__get("usuario");
        $publicacionData["usuario_id"] = $publicacion->__get("usuario_id");
        $imagen = Imagen::findById($publicacion->__get("imagen_id"));
        if($imagen) {
            $publicacionData["imagen"] = $imagen->__get("nombre");
            $album_id = $imagen->__get("album_id");
            $publicacionData["album"] = Album::findById($album_id)->__get("nombre");
        }
        $publicacionData["likes_count"] = $publicacion->getLikesCount();
        $publicacionData["comments_count"] = $publicacion->getCommentsCount();
        $publicacionData["liked"] = $publicacion->isLiked($usuario_id);
        $publicacionData['time_stamp'] = mostrarDiferencia($publicacion->__get("created_at"));
        
        array_push($responsePublicaciones, $publicacionData);
    }
    $response = [];

    array_push($response, $responsePublicaciones);
    array_push($response, ['userId' => $usuario_id]);
    array_push($response, ['next' => ($next + $size)]);

	echo json_encode($response);
} else {
    http_response_code(400);
    echo "No se recibieron los datos requeridos";
}
