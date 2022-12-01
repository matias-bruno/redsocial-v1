<?php

session_start();

$usuario = null;
User::connect();

$responseData = [
    "ok" => true,
    "error" => ""
];

function sendResponse($responseData) {
    echo json_encode($responseData);
    exit;
}

if(isset($_SESSION["usuario"])) {
    $usuario = User::findByUsername($_SESSION["usuario"]);
}

if(!$usuario) {
    http_response_code(401);
    $responseData["ok"] = false;
    $responseData["error"] = "Se debe estar autenticado para realizar esta petición";
    sendResponse($responseData);
}

$publicacion = Publicacion::findById($_POST["id"]);
$usuario_id = $usuario->__get("id");
$icono = "";

if($publicacion) {
    $liked = $publicacion->isLiked($usuario_id);
    $likesCount = $publicacion->getLikesCount();
    if($liked) {
        $like = Like::getLike($usuario_id, $publicacion->__get("id"));
        $like->delete();
        $likesCount--;
        $icono = "<i class=\"far fa-thumbs-up\"></i>";
    } else {
        $like = new Like(["usuario_id" => $usuario_id, "publicacion_id" => $publicacion->__get("id")]);
        $like->save();
        $likesCount++;
        $icono = "<i class=\"fas fa-thumbs-up\"></i>";

        // Enviar notificación del like
        if($usuario_id != $publicacion->__get("usuario_id")) {
            $notificacion = new Notificacion([
                "emisor_id" => $usuario_id,
                "receptor_id" => $publicacion->__get("usuario_id"),
                "contenido" => $usuario->__get("usuario") . " indicó que le gusta tu publicación",
                "publicacion_id" => $publicacion->__get("id"),
                "status" => 0
            ]);
            $notificacion->save();
        }
    }

}

$responseData["icono"] = $icono;
$responseData["likes"] = $likesCount;

echo json_encode($responseData);