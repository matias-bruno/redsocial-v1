<?php

require "./partials/session-logged.php";

require "./partials/perfil.php";

$amigos_datos = Amistad::getRequests($usuarioPerfil->__get("id"), 'aceptada');

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
				<div class="contenedor-amigos">
					<div class="barra-amigos">
                    	<h5 class="box-title">Amigos (<?= count($amigos_datos) ?>)</h5>
					</div>
					<div class="main-amigos">
                    <?php foreach($amigos_datos as $amigo) : ?>
						<?php 
							$usuarioAmigo = User::findById($amigo["usuario2_id"]);
							$imagen = Imagen::findById($usuarioAmigo->__get("imagen_id"));
							if($imagen) {
								$imagenAmigo = $imagen->__get("nombre");
							} else{
								$imagenAmigo = "default.png";
							}
						?>
                        <div class="info-amigo">
							<img class="rounded-circle user-friend-img" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $imagenAmigo ?>" alt="Imagen del Usuario">
							<h5 class="card-title text-center"><?= $usuarioAmigo->__get("usuario") ?></h5>
							<a href="perfil/<?= $usuarioAmigo->__get("usuario") ?>" class="btn btn-primary">Ver perfil</a>
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