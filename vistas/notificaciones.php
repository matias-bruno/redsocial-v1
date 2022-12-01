<?php

require "partials/session-logged.php";

$usuario_id = $usuario->__get("id");

$arrNotificaciones = Notificacion::getNotificationsArray($usuario_id, 1);

?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Notificaciones para " . $usuario->__get("usuario");
include "partials/head.php";
?>

<body>
    <div class="container">
		<?php
		include "partials/header.php";
		?>
		<div class="container-info">
			<h3 class="text-center mb-3">Notificaciones</h3>
			<?php foreach($arrNotificaciones as $arrNoti) : ?>
				<?php
					$emisor = User::findById($arrNoti["emisor_id"]);
					$imagenEmisor= Imagen::findById($emisor->__get("imagen_id"));
					if($imagenEmisor)
						$nombreImagen = $imagenEmisor->__get("nombre");
					else
						$nombreImagen = "default.png";
					$notificacion = Notificacion::findById($arrNoti["id"]);
					$notificacion->__set("status", 1);
					$notificacion->save();
				?>
				<div class="card-amigo"">
					<div class="image-card-amigo">
						<div><img class="rounded-circle user-img-mid" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $nombreImagen ?>" alt="Imagen del usuario que envió la solicitud"></div>
						<h5><a class="user-link" href="<?= SERVER_URL ?>/perfil/<?= $emisor->__get("usuario")?>"><?= $emisor->__get("usuario")?></a></h5>
					</div>
					<div class="info-card-amigo">
						<h6><?= $emisor->__get("nombre") . ' ' . $emisor->__get("apellido") ?></h6>
						<p><?= $arrNoti["contenido"] ?></p>
						<p class=""><small class="">Hace <?= mostrarDiferencia($arrNoti["created_at"])?></small></p>
						<a class="" href="<?= SERVER_URL ?>/publicacion/<?= $arrNoti["publicacion_id"] ?>">Ir a la publicación</a>
					</div>
				</div>

			<?php endforeach; ?>
		</div>
	</div>
	<?php
	include "partials/scriptsJS.php";
	?>
</body>

</html>