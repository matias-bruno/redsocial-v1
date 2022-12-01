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

$respuesta = [
    "ok" => false,
    "imagen" => "",
    "error" => ""
];

if(isset($_POST["accion"])) {
	if($_POST["accion"] == "cambiar" && isset($_FILES["file"]["name"])) {
		$file = null;
		$response = 0;
		if(validarPortada($_FILES["file"]["tmp_name"])) {;
			$nombreImagen = guardarImagen("file", "portadas", uniqid($usuario->__get("usuario") , true), true);
			if ($nombreImagen) {
				$album_id = Album::getByName("portadas", $usuario->__get("id"));
				$imagenPortada = new Imagen(["nombre" => $nombreImagen, "album_id" => $album_id]);
				if($imagenPortada->save()) {
					$portada_id = $imagenPortada->__get("id");
				} else {
					
				}
				$usuario->__set("portada_id", $portada_id);
				if($usuario->save()) {
					$respuesta["ok"] = true;
					$respuesta["imagen"] = SERVER_URL . "/assets/img/portadas/" . $nombreImagen;
					$publicacion = new Publicacion([
						"contenido" => "",
						"usuario_id" => $usuario->__get("id"),
						"descripcion" => "Cambió su foto de portada",
						"imagen_id" => $imagenPortada->__get("id")
					]);
					$publicacion->save();
				}
			} else {
				$respuesta["error"] = "Ocurrió un error al intentar guardar el archivo.";
			}
		} else {
			$respuesta["error"] = "El archivo no es un formato aceptado de imagen o no tiene las dimensiones correctas. Debe ser al menos de 1140 x 300.";
		}
	} else if($_POST["accion"] == "quitar") {
		$usuario->__set("portada_id", NULL);
		if($usuario->save()) {
			$respuesta["ok"] = true;
			$respuesta["imagen"] = SERVER_URL . "/assets/img/portadas/default.jpg";
		} else {
			$respuesta["error"] = "No se pudo actualizar el dato correctamente";
		}
	} else {
		http_response_code(400);
		$respuesta["error"] = "Faltan los datos requeridos para la operación";
	}
} else {
	http_response_code(400);
	$respuesta["error"] = "Faltan los datos requeridos para la operación";
}



// Esta parte puede necesitar alguna modificación
function validarPortada($imageFile) {
	if(exif_imagetype($imageFile)) {
		$datos = getimagesize($imageFile);
		// Los 2 primeros elementos del arreglo son el ancho y el alto de la imagen
		return $datos[0] >= 1140 && $datos[1] >= 300;
	}
	return false;
}
echo json_encode($respuesta);
