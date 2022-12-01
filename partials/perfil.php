<?php

$usuarioPerfil = $usuario;
$nombreUsuario = $usuario->__get("usuario");

if(isset($url[1])) {
	if($url[1] !== $nombreUsuario) {
		$usuarioPerfil = User::findByUsername($url[1]);
		if(!$usuarioPerfil) {
			header("Location:" . SERVER_URL . "/" . $url[0]);
		}
		$nombreUsuario = $usuarioPerfil->__get("usuario");
	} else {
		header("Location:". SERVER_URL . "/" . $url[0]);
	}

}

$imagenUsuarioPerfil = "default.png";

$imagen_id = $usuarioPerfil->__get("imagen_id");

if($imagen_id) {
	$imagen = Imagen::findById($imagen_id);
	if($imagen) {
		$imagenUsuarioPerfil = $imagen->__get("nombre");
	}
}

$portada = "default.jpg";

$portada_id = $usuarioPerfil->__get("portada_id");

if($portada_id) {
	$imagenPortada = Imagen::findById($portada_id);
	if($imagenPortada) {
		$portada = $imagenPortada->__get("nombre");
	}
}

$perfilUsuarioLogueado = $usuario == $usuarioPerfil;
$amistadBloqueada = false;


?>