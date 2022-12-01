<?php

require "partials/session-logged.php";

$usuario_id = $usuario->__get("id");

$recibidas = Amistad::getRequests($usuario_id, "recibida");

$enviadas =  Amistad::getRequests($usuario_id, "enviada");

?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Solicitudes de Amistad";
include("partials/head.php");

?>

<body>
	<div class="container">
		<?php
		include("partials/header.php");
		?>
		<div class="container-info">
			<h3 class="text-center mb-3">Solicitudes Recibidas</h3>
			<div id="recibidas">
				<?php if(count($recibidas) == 0) : ?>
					<div class="sin-solicitudes">No hay solicitudes pendientes</div>
				<?php else : ?>
					<?php foreach($recibidas as $solicitud) : ?>
						<?php
							$amigo = User::findById($solicitud["usuario2_id"]);
							$imagenAmigo = Imagen::findById($amigo->__get("imagen_id"));
							if($imagenAmigo)
								$nombreImagen = $imagenAmigo->__get("nombre");
							else
								$nombreImagen = "default.png";
							$amistad = Amistad::findById($solicitud["id"]);
							$amistad->__set("seen", 1);
							$amistad->save();
						?>
						<div class="card-amigo" id="amigo<?= $amigo->__get("id")?>">
							<div class="image-card-amigo">
								<div><img class="rounded-circle user-img-mid" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $nombreImagen ?>" alt="Imagen del usuario que envió la solicitud"></div>
								<h5><a class="user-link" href="<?= SERVER_URL ?>/perfil/<?= $amigo->__get("usuario") ?>"><?= $amigo->__get("usuario")?></a></h5>
							</div>
							<div class="info-card-amigo">
								<h6><?= $amigo->__get("nombre") . ' ' . $amigo->__get("apellido") ?></h6>
								<p class=""><small class="">Solicitud recibida hace <?= mostrarDiferencia($solicitud["created_at"])?></small></p>
								<div class="opcion-solicitud">
									<a class="btn btn-primary" href="javascript:void(0)" onclick="responderSolicitud('aceptar', '<?= $amigo->__get("usuario") ?>', <?= $amigo->__get("id") ?>)">Aceptar</a>
									<a class="btn btn-secondary" href="javascript:void(0)" onclick="responderSolicitud('rechazar', '<?= $amigo->__get("usuario") ?>', <?= $amigo->__get("id") ?>)">Rechazar</a>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<h3 class="text-center mb-3">Solicitudes Enviadas</h3>
			<div id="enviadas">
				<?php if(count($enviadas) == 0) : ?>
						<div class="sin-solicitudes">No hay solicitudes pendientes</div>
				<?php else : ?>
					<?php foreach($enviadas as $solicitud) : ?>
						<?php
							$amigo = User::findById($solicitud["usuario2_id"]);
							$imagenAmigo = Imagen::findById($amigo->__get("imagen_id"));
							if($imagenAmigo)
								$nombreImagen = $imagenAmigo->__get("nombre");
							else
								$nombreImagen = "default.png";
						?>
						<div class="card-amigo" id="amigo<?= $amigo->__get("id") ?>">
							<div class="image-card-amigo">
								<div><img class="rounded-circle user-img-mid" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $nombreImagen ?>" alt="Imagen del usuario que envió la solicitud"></div>
								<h5><a class="user-link" href="<?= SERVER_URL ?>/perfil/<?= $amigo->__get("usuario") ?>"><?= $amigo->__get("usuario") ?></a></h5>
							</div>
							<div class="info-card-amigo">
								<h6><?= $amigo->__get("nombre") . ' ' . $amigo->__get("apellido") ?></h6>
								<p class=""><small class="">Solicitud enviada hace <?= mostrarDiferencia($solicitud["created_at"])?></small></p>
								<a class="btn btn-primary" href="javascript:void(0)" onclick="cancelarSolicitud('<?= $amigo->__get("usuario") ?>', <?= $amigo->__get("id") ?>)">Cancelar</a>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	include("partials/scriptsJS.php");
	?>
	<script>
		const serverUrl = "<?= SERVER_URL ?>";
		function responderSolicitud(accion, usuario, id) {
			if(confirm("Desea " + accion + " la solicitud de " + usuario)) {
				$.ajax({
					url: serverUrl + "/ajax/acciones-amigos",
					type: "post",
					data: {usuario: usuario, accion: accion},
					dataType: "json",
					success:
						function(data) {
							if(data["ok"]) {
								$('#amigo' + id).remove();
								if($('#recibidas').children('card').length == 0) {
									$('#recibidas').html("<div class='sin-solicitudes'>No hay solicitudes pendientes</div>");
								}
							}
						}
				});
			}
		};
		function cancelarSolicitud(usuario, id) {
			if(confirm("Desea cancelar la solicitud enviada a " + usuario)) {
				$.ajax({
					url: serverUrl + "/ajax/acciones-amigos",
					type: "post",
					data: {usuario: usuario, accion: 'cancelar'},
					dataType: "json",
					success:
						function(data) {
							if(data["ok"]) {
								$('#amigo' + id).remove();
								if($('#enviadas').children('card').length == 0) {
									$('#enviadas').html("<div class='sin-solicitudes'>No hay solicitudes pendientes</div>");
								}
							}
						}
				});
			}
		}
	</script>
</body>

</html>