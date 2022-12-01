<?php

require "./partials/session-logged.php";

?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Página de Inicio";
include "./partials/head.php";
?>

<body>
	<div class="container">
		<?php
		include "./partials/header.php";
		?>
		<div class="row">
			<div class="col-lg-4">
				<div class="card" id="user-info">
					<img class="card-img-top rounded-circle" id="user-img" src="<?= SERVER_URL . "/assets/img/fotos_perfil/" . $imagenUsuario ?>" alt="Imagen del Usuario">
					<div class="card-body">
						<h5 class="card-title text-center"><?= $usuario->__get("usuario")?></h5>
						<p class="card-text"><?= $usuario->__get("nombre") ?> <?= $usuario->__get("apellido") ?></p>
						<p class="card-text"><a href="mailto:<?= $email = $usuario->__get("email") ?>"><?= $email ?></a></p>
						<p class="card-text">Edad: <?= mostrarDiferencia($usuario->__get("fecha_nacimiento")) ?></p>
						<a href="<?= SERVER_URL ?>/perfil" class="btn btn-primary">Ver perfil</a>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="d-flex justify-content-center">
					<form action="" id="form-publicar" method="post" enctype="multipart/form-data">
						<textarea id="publicar" name="contenido" rows="4" placeholder="¿En que estás pensando?"></textarea>
						<p class="my-1"><input type="file" name="imagen" id="imagen"></p>
						<button type="submit" class="btn btn-primary d-block">Enviar</button>
					</form>
				</div>
				<div id="publicaciones">

				</div>
				<div id="mas"></div>
			</div>
		</div>
	</div>
	<?php
	include "./partials/scriptsJS.php";
	?>
	<script src="<?= SERVER_URL ?>/assets/js/publicaciones.js"></script>
	<script src="<?= SERVER_URL ?>/assets/js/comentarios.js"></script>
	<script>
		let block = false;
		let page = 0;
		const serverUrl = "<?= SERVER_URL ?>";

		$(document).ready(function() {
			loadMore();
		});

		$(window).on("scroll", function() {
			const scrollHeight = this.scrollY;
			const viewportHeight = document.documentElement.clientHeight;
			const moreScroll = document.getElementById('mas').offsetTop;
			const currentScroll = scrollHeight + viewportHeight;

			if((currentScroll >= moreScroll) && block === false){ //cargar más contenido
				block = true;

				this.setTimeout(() =>{
					//loadItems();
					loadMore();
					block = false;
				}, 1000);
			}
		});
	</script>
</body>

</html>