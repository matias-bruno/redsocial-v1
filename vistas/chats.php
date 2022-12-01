<?php

require "partials/session-logged.php";

$nombreUsuario = $usuario->__get("usuario");

$perfilUsuarioLogueado = true;
$usuarioPerfil = $usuario;

$dataChats = Mensaje::getPreviewChats($usuario->__get("id"));
$countNew = Mensaje::getCountNew($usuario->__get("id"));

if($dataChats == null) {
	$dataChats = [];
}

for($i = 0; $i < count($dataChats); $i++) {
	if(strlen($dataChats[$i]["contenido"]) > 50) {
		$dataChats[$i]["contenido"] = substr($dataChats[$i]["contenido"], 0, 47) . "...";
	}
}

?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Lista de Chats de " . $nombreUsuario;
include "partials/head.php";
?>

<body>
    <div class="container">
		<?php
		include "partials/header.php";
		?>
		<div class="container-info">
			<h3 class="text-center mb-3">Lista de Chats</h3>
			<?php foreach($dataChats as $data) : ?>
				<?php
					$usuarioChatId = $data["emisor_id"] != $usuario->__get("id") ? $data["emisor_id"] : $data["receptor_id"];
					$usuarioChat = User::findById($usuarioChatId);
					$imagen_id = $usuarioChat->__get("imagen_id");
					if($imagen_id) {
						$imagenUsuarioChat = Imagen::findById($imagen_id)->__get("nombre");
					} else {
						$imagenUsuarioChat = "default.png";
					}
				?>
				<div class="card-amigo">
					<div class="image-card-amigo">
						<div><img class="rounded-circle user-img-mid" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $imagenUsuarioChat ?>" alt="Imagen del usuario del chat"></div>
						<h5 class="card-title"><a href="<?= SERVER_URL ?>/perfil/<?= $usuarioChat->__get("usuario") ?>" class="user-link"><?= $usuarioChat->__get("usuario")  ?></a></h5>
					</div>
					<div class="message-card-amigo">
						<?php if(isset($countNew[$data["chat_id"]])) : ?>
							<a class="" href="<?= SERVER_URL ?>/chat/<?= $usuarioChat->__get("usuario") ?>">
								<small class="text-bold">Hay nuevos mensajes (<?= $countNew[$data["chat_id"]]?>)</small>
							</a>
						<?php endif; ?>
						<?php
							$emisor = $data["emisor_id"] === $usuario->__get("id") ? "Tú:" : $usuarioChat->__get("usuario") . ":";
						?>
						<p class="card-text"><small class="text-muted"><?= $emisor ?></small></p>
						<p class="card-text"><small class="text-muted"><?= $data["contenido"] ?></small></p>
						<a class="" href="<?= SERVER_URL ?>/chat/<?= $usuarioChat->__get("usuario") ?>">Ir al Chat</a>
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