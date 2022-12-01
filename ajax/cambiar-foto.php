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

// En este arreglo se guarda si hay errores en los campos del formulario detallado por campo como índice
$errores = [];

// Si se enviaron los datos se actualizara la foto de perfil, si todo está correcto
if ($_POST) {
    $response = [];
    $image = $_POST['image'];
    list($type, $image) = explode(';', $image);
    list(, $image) = explode(',', $image);
    $image = base64_decode($image);
    // Cambiar a un nombre de archivo aleatorio, con el nombre de usuario como prefijo
    $image_name = uniqid($usuario->__get("usuario"), true) . '.png';
    try {
        if(file_put_contents(BASE_DIR . '/assets/img/fotos_perfil/' . $image_name, $image)) {
            $album_id = Album::getByName("fotos_perfil", $usuario->__get("id"));
            $imagen = new Imagen(["nombre" => $image_name, "album_id" => $album_id]);
            $imagen->save();
            $response["imagen_id"] = $imagen->__get("id");
            if($usuario->__set("imagen_id", $imagen->__get("id"))) {
                $usuario->save();
                $publicacion = new Publicacion([
                    "contenido" => "",
                    "usuario_id" => $usuario->__get("id"),
                    "descripcion" => "Cambió su foto de perfil",
                    "imagen_id" => $imagen->__get("id")
                ]);
                $publicacion->save();
            }
        }
    } catch (PDOException $Exception) {
        $errores["principal"] = "Error en el servidor, no se pudo cambiar la foto";
    }
    echo json_encode($response);
}