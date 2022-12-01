<?php

require "./partials/session-logged.php";

require "./partials/perfil.php";

//$amigos = new Amistad($usuarioPerfil->getId(), DB::connect());
//$amigos_datos = $amigos->getRequests('aceptada');

$albumesData = Album::getAlbumsByUser($usuarioPerfil->__get("id"));
$size = count($albumesData);
for($i = 0; $i < $size; $i++) {
	$album = new Album($albumesData[$i]);
	$imagenAlbum = $album->getFirstImage();
	if($imagenAlbum) {
		// Si es el album de fotos de perfil, no se muestra la miniatura sino la misma foto
		$prefijo = $albumesData[$i]["nombre"] == "fotos_perfil" ? "" : "mini-";
		$albumesData[$i]["imagen"] = SERVER_URL . "/assets/img/" . $albumesData[$i]["nombre"] . '/' . $prefijo . $imagenAlbum;
	} else {
		$albumesData[$i]["imagen"] = SERVER_URL . "/assets/img/default.png";
	}
}

?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Perfil de " . $nombreUsuario;
include "./partials/head.php";
?>

<body>
	<div class="container">
		<?php
		include "./partials/header.php";
		?>
		<?php
		include "./partials/header-perfil.php";
		?>
		<div class="profile-page">
			<div class="">
				<?php
				include "./partials/navbar-perfil.php";
				?>
				<div class="contenedor-fotos">
					<div class="barra-fotos">
                    	<h5>Albumes de Fotos</h5>
					</div>
					<div class="main-fotos">
						<?php foreach($albumesData as $album) : ?>
							<div class="cover-album">
								<div class="imagen-mini">
									<a href="<?= SERVER_URL ?>/album/<?= $album["id"] ?>">
										<img src="<?= $album["imagen"] ?>" class="album-img" alt="Una foto de este albúm">
									</a>
								</div>
								<a href="<?= SERVER_URL ?>/album/<?= $album["id"] ?>">
									<span class="nombre-album"><?= showAlbumName($album["nombre"]) ?></span>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
                </div>
			</div>
		</div>
	<?php
	include "./partials/scriptsJS.php";
	?>
	<script>
		let serverUrl = "<?= SERVER_URL ?>";
		$(document).ready(function() {
			loadEstadoAmistad();
			ajustarPortada();
		});
	</script>
    <script src="<?= SERVER_URL ?>/assets/js/portada.js"></script>
	<script src="<?= SERVER_URL ?>/assets/js/solicitudes.js"></script>
</body>

</html>