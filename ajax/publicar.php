<?php

session_start();

User::connect();

if(isset($_SESSION["usuario"])) {
    $usuario = User::findByUsername($_SESSION["usuario"]);
}

if(!$usuario) {
    http_response_code(401);
    echo "Se debe estar autenticado para realizar esta petición";
    exit;
}

// Si tiene imagen
$imagen = NULL;
$imagen_id = NULL;
if (isset($_FILES["imagen"]["name"])) {
    $nombreImagen = guardarImagen("imagen", "publicaciones/", uniqid($usuario->__get("usuario"), true), true);
    if($nombreImagen) {
        $album_id = Album::getByName("publicaciones", $usuario->__get("id"));
        $imagenData = [
            "nombre" => $nombreImagen,
            "album_id" => $album_id
        ];
        $imagen = new Imagen($imagenData);
        if($imagen->save()) {
            $imagen_id = $imagen->__get("id");
        } else {
            $imagen = null;
        }
    } else {
        http_response_code(400);
        echo "Error en la imagen enviada";
        exit;
    }
}

// Si tiene texto
$contenido = "";
if(isset($_POST["contenido"])) {
    $contenido = str_replace('\r\n','\n', $_POST["contenido"]);
    $contenido = nl2br($contenido);
}

// Detectar si hay algún enlace para mostrar el hipervínculo
$contenido = makeUrltoLink($contenido);

$publicacionData = [
    "contenido" => $contenido,
    "usuario_id" => $usuario->__get("id"),
    "descripcion" => "",
    "imagen_id" => $imagen_id
];

$publicacion = new Publicacion($publicacionData);

if($publicacion->save()) {
    $publicacion_id = $publicacion->__get("id");
}

if($publicacion_id) {
    // Reemplazamos el objeto por el que tiene todos los datos que vienen de la base de datos
    $publicacion = Publicacion::findById($publicacion_id);
    if($publicacion) {
        $imagenUsuario = Imagen::findById($usuario->__get("imagen_id"));
        if($imagenUsuario) {
            $publicacionData["imagen_usuario"] = $imagenUsuario->__get("nombre");
        } else {
            $publicacionData["imagen_usuario"] = "default.png";
        }
        if($imagen) {
            $publicacionData["album"] = "publicaciones";
            $publicacionData["imagen"] = $imagen->__get("nombre") ?? "";
        }
        $publicacionData["id"] = $publicacion_id;
        $publicacionData["nombre_usuario"] = $usuario->__get("usuario");
        $dateTime = (new DateTime($publicacion->__get("created_at")))->format('c');
        $publicacionData["time_stamp"] = mostrarDiferencia($dateTime);
        $publicacionData["likes_count"] = 0;
        $publicacionData["comments_count"] = 0;
        $publicacionData["liked"] = 0;

        echo json_encode($publicacionData);
    }
}

if($publicacion == null) {
    http_response_code(500);
    echo "Error en el servidor";
    exit;
}
