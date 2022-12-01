<?php

require "partials/session-logged.php";

$nombreUsuario = $usuario->__get("usuario");

if(isset($url[1])) {
	if($url[1] !== $nombreUsuario) {
		$amigo = User::findByUsername($url[1]);
		if(!$amigo) {
			header("Location:" . SERVER_URL . "/" . $url[0]);
		}
	}
}
if(!$amigo || $amigo == $usuario) {
	header("location:" . SERVER_URL . "/chats");
	exit;
}

$imagen_id = $amigo->__get("imagen_id");

if($imagen_id) {
	$imagen = Imagen::findById($imagen_id);
	$imagenAmigo = $imagen->__get("nombre");
} else {
	$imagenAmigo = "default.png";
}

?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Chat con " . $amigo->__get("usuario");
include("partials/head.php");
?>

<body>
	<div class="container">
		<?php
		include("partials/header.php");
		?>
    </div>
    <?php
	include("partials/scriptsJS.php");
	?>
	<div class="container">
		<div class="usuarios-mensajes d-flex justify-content-between">
			<div class="d-flex align-items-center">
				<img class="rounded-circle user-img-mini" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $imagenAmigo ?>">
				&nbsp;
				<?= $amigo->__get("usuario") ?>
			</div>
			<div class="d-flex align-items-center">
				<?= $usuario->__get("usuario") ?>
				&nbsp;
				<img class="rounded-circle user-img-mini" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $imagenUsuario ?>">
			</div>
		</div>
		<div class="d-flex">
			<div id="mensajes" class="flex-fill">
				
			</div>
		</div>
		<div class="enviar-mensaje">
			<form id="form-mensaje" class="d-flex align-items-center" action="">
				<textarea id="contenido-mensaje"class="flex-fill" name="" id="" rows="2"></textarea>
				<button type="submit" class="btn btn-primary">Enviar</button>
			</form>
		</div>
	</div>
	<script src="<?= SERVER_URL ?>/assets/js/chat.js"></script>
</body>