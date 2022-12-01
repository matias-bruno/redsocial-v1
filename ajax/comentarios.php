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

if($_POST["accion"] == "comentar") {
    $objectData["publicacion_id"] = $_POST["id"] ?? false;
    $objectData["contenido"] = $_POST["comentario"] ?? false;
    $objectData["usuario_id"] = $usuario->__get("id");

    if($objectData["publicacion_id"] == false || $objectData["contenido"] == false) {
        http_response_code(400);
        $responseData["ok"] = false;
        $responseData["error"] = "Faltan los datos requeridos";
        sendResponse($responseData);
    }
    
    $publicacion = Publicacion::findById($objectData["publicacion_id"]);
    if(!$publicacion) {
        http_response_code(400);
        $responseData["ok"] = false;
        $responseData["error"] = "No existe publicación con ese id";
        sendResponse($responseData);
    }

    $comentario = new Comentario($objectData);
    $guardado = $comentario->save();
    if($guardado == false) {
        http_response_code(500);
        $responseData["ok"] = false;
        $responseData["error"] = "No se pudo guardar el comentario";
        sendResponse($responseData);
    }

    $responseData["comentario"] = [
        "id" => $comentario->__get("id"),
        "nombre_usuario" => $usuario->__get("usuario"),
        "total_comentarios" => $publicacion->getCommentsCount(),
        "time_stamp" => "0 segundos"
    ];
    $responseData["comentario"] = array_merge($responseData["comentario"], $objectData);
    $imagenUsuario = Imagen::findById($usuario->__get("imagen_id"));
    if($imagenUsuario) {
        $responseData["comentario"]["imagen_usuario"] = $imagenUsuario->__get("nombre");
    } else {
        $responseData["comentario"]["imagen_usuario"] = "default.png";
    }

    // Enviar notificación del comentario
    if($usuario->__get("id") != $publicacion->__get("usuario_id")) {
        $notificacion = new Notificacion([
            "emisor_id" => $usuario->__get("id"),
            "receptor_id" => $publicacion->__get("usuario_id"),
            "contenido" => $usuario->__get("usuario") . " comentó tu publicación",
            "publicacion_id" => $publicacion->__get("id"),
            "status" => 0
        ]);
        $notificacion->save();
    }

    http_response_code(201);
    sendResponse($responseData);

} elseif($_POST["accion"] == "eliminar") {
    $comentario = isset($_POST["id"]) ? Comentario::findById($_POST["id"]) : "";
    if(!$comentario) {
        http_response_code(400);
        $responseData["ok"] = false;
        $responseData["error"] = "Comentario no encontrado";
        sendResponse($responseData);
    }
    if($comentario->__get("usuario_id") != $usuario->__get("id")) {
        http_response_code(403);
        $responseData["ok"] = false;
        $responseData["error"] = "Solo el usuario que hizo el comentario puede eliminarlo";
        sendResponse($responseData);
    }
    $eliminado = $comentario->delete();
    if($eliminado == false) {
        http_response_code(500);
        $responseData["ok"] = false;
        $responseData["error"] = "No se pudo eliminar el comentario";
        sendResponse($responseData);
    }
    $publicacion_id = $comentario->__get("publicacion_id");
    $publicacion = Publicacion::findById($publicacion_id);
    if($publicacion) {
        $responseData["publicacion_id"] = $publicacion_id;
        $responseData["comments_count"] = $publicacion->getCommentsCount();
    }
    sendResponse($responseData);
} elseif($_POST["accion"] == "mostrar") {
    $publicacion_id = $_POST['publicacion_id'] ?? false;
    if($publicacion_id == false || is_numeric($publicacion_id) == false) {
        http_response_code(400);
        $responseData["ok"] = false;
        $responseData["error"] = "Faltan los datos requeridos";
        sendResponse($responseData);
    }
    $comentarios = Comentario::getCommentsArray($publicacion_id);
    if($comentarios == false) {
        http_response_code(400);
        $responseData["ok"] = false;
        $responseData["error"] = "No se encontraron los comentarios para esa publicación";
        sendResponse($responseData);
    }
    $size = count($comentarios);
    for($i = 0; $i < $size; $i++) {
        $usuarioComentario = User::findById($comentarios[$i]["usuario_id"]);
        $comentarios[$i]["nombre_usuario"] = $usuarioComentario->__get("usuario");
        $imagenUsuario = Imagen::findById($usuarioComentario->__get("imagen_id"));
        $comentarios[$i]["imagen_usuario"] = $imagenUsuario ? $imagenUsuario->__get("nombre") : "default.png";
        $comentarios[$i]["time_stamp"] = mostrarDiferencia($comentarios[$i]["created_at"]);
    }
    $responseData["comentarios"] = $comentarios;
    $responseData["usuario_id"] = $usuario->__get("id");
    sendResponse($responseData);
}
