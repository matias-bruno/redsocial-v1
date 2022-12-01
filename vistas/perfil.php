<?php

require "./partials/session-logged.php";

require "./partials/perfil.php";

$amistad = Amistad::getRequest($usuario->__get("id"), $usuarioPerfil->__get("id"));

$verAmigos = false;
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
		<div class="./profile-page">
			<?php
			include "./partials/navbar-perfil.php";
			?>
			<div class="row">
				<div  class="col-lg-3">
					<div class="card" id="user-info">
						<div class="card-body">
							<h5 class="card-title text-center"><?= $usuarioPerfil->__get("usuario")?></h5>
							<p class="card-text">Nombre: <?= $usuarioPerfil->__get("nombre") ?></p>
							<p class="card-text">Apellido: <?= $usuarioPerfil->__get("apellido") ?></p>
							<p class="card-text">Email: <a href="mailto:<?= $email = $usuarioPerfil->__get("email") ?>"><?= $email ?></a></p>
							<p class="card-text">Edad: <?= mostrarDiferencia($usuarioPerfil->__get("fecha_nacimiento")) ?></p>
						</div>
					</div>
				</div>
				<div class="col-lg-9">
					<?php if($perfilUsuarioLogueado) : ?>
						<div class="d-flex justify-content-center">
							<form action="" id="form-publicar" method="post" enctype="multipart/form-data">
								<textarea id="publicar" name="contenido" rows="4" placeholder="¿En que estás pensando?"></textarea>
								<p class="my-1"><input type="file" name="imagen" id="imagen"></p>
								<button type="submit" class="btn btn-primary d-block">Enviar</button>
							</form>
						</div>
					<?php endif; ?>
					<?php if($perfilUsuarioLogueado || ($amistad && $amistad->__get("status") == "aceptada")) : ?>
						<?php $verAmigos = true; ?>
						<div id="publicaciones">

						</div>
						<div id="mas"></div>
					<?php else : ?>
						<h5 class="text-center"> Solo los amigos de <?= $usuarioPerfil->__get("usuario") ?> pueden ver sus publicaciones</h5>
						<h5 class="text-center">Enviale una solicitud de amistad</h5>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php
	include "./partials/scriptsJS.php";
	?>
	
		<script>
			let block = false;
			let page = 0;
			let serverUrl = "<?= SERVER_URL ?>";

			$(document).ready(function() {
				<?php if($verAmigos) : ?>
				loadMore();
				<?php endif; ?>
				loadEstadoAmistad();
				ajustarPortada();
			});

			<?php if($verAmigos) : ?>
			$(window).on("scroll", function() {
				// const scrollHeight = this.scrollY;
				// const viewportHeight = document.documentElement.clientHeight;
				// const moreScroll = document.getElementById('mas').offsetTop;
				// const currentScroll = scrollHeight + viewportHeight;

				// if((currentScroll >= moreScroll) && block === false){ //cargar más contenido
				// 	block = true;

				// 	this.setTimeout(() =>{
				// 		loadItems();

				// 		block = false;
				// 	}, 1000);
				// }
				let container = $("#publicaciones")[0];
				let content_height = container.offsetHeight;
				let current_y = window.innerHeight + window.pageYOffset;
				// console.log(current_y + '/' + content_height);
				if(current_y >= content_height) {
					loadMore();
				}
			});
			<?php endif; ?>
		</script>
	<script src="<?= SERVER_URL ?>/assets/js/publicaciones.js"></script>
	<script src="<?= SERVER_URL ?>/assets/js/comentarios.js"></script>
	<script src="<?= SERVER_URL ?>/assets/js/portada.js"></script>
	<script src="<?= SERVER_URL ?>/assets/js/solicitudes.js"></script>
</body>

</html>