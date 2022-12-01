<?php

require "./partials/session-logged.php";

$nombreUsuario = $usuario->__get("usuario");

$perfilUsuarioLogueado = true;
$usuarioPerfil = $usuario;

if(isset($url[1]) && !empty($url[1])) {
	$album = Album::findById($url[1]);
	if($album) {
		$imagenes = $album->getAllImages();
	}
}
if(!$album) {
	echo "Redirección aquí";
	exit;
}

?>

<!DOCTYPE html>
<html>

<?php
$titulo = showAlbumName($album->__get("nombre")) ;
include "./partials/head.php";
?>

<body>
	<div class="container">
		<?php
		include "./partials/header.php";
		?>
		<div class="profile-page">
			<div class="">
				<div class="contenedor-fotos">
					<div class="barra-fotos">
                    	<h5><?= showAlbumName($album->__get("nombre")) ?></h5>
					</div>
					<div class="main-fotos">
						<?php foreach($imagenes as $imagen) : ?>
							<div class="cover-album">
								<div class="imagen-mini">
									<a href="<?= SERVER_URL ?>/publicacion/<?= $imagen["publicacion_id"] ?>">
										<?php $prefijo = $album->__get("nombre") == "fotos_perfil" ? "" : "mini-" ?>
										<img src="<?= SERVER_URL ?>/assets/img/<?= $album->__get("nombre") ?>/<?= $prefijo . $imagen["nombre"]?>" class="album-img" alt="Una foto de este albúm">
									</a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
                </div>
			</div>
		</div>
	<?php
	include "./partials/scriptsJS.php";
	?>
</body>

</html>