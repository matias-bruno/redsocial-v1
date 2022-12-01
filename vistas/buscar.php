<?php

require "partials/session-logged.php";

$nombreUsuario = $usuario->__get("usuario");

$perfilUsuarioLogueado = true;
$usuarioPerfil = $usuario;
$query = "";
$resultados = [];

if(isset($_GET["query"])) {
    $query = $_GET["query"];
}

if($query) {
    $resultados = User::search($query);
}
?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Perfil de " . $nombreUsuario;
include "partials/head.php";
?>

<body>
	<div class="container">
		<?php
		include "partials/header.php";
		?>
		<div class="container-info">
			<h3 class="text-center mb-3">Resultados</h3>
			<div id="resultados">
				<?php if(count($resultados) == 0) : ?>
					<p>No hay ningún resultado que coincida con el texto ingresado</p>
				<?php else : ?>
					<?php foreach($resultados as $resultado) : ?>
						<?php if(!$resultado["imagen"]) $resultado["imagen"] = "default.png" ?>
						<div class="card-amigo" id="amigo<?= $resultado["id"]?>">
							<div class="image-card-amigo">
								<div><img class="rounded-circle user-img-mid" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $resultado["imagen"] ?>" alt="Imagen del usuario que envió la solicitud"></div>
								<h5><a class="user-link" href="perfil/<?= $resultado["usuario"]?>"><?= $resultado["usuario"] ?></a></h5>
							</div>
							<div class="info-card-amigo">
								<h6><?= $resultado["nombre"] . ' ' . $resultado["apellido"] ?></h6>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
		</div>
	</div>
	<?php
	include "partials/scriptsJS.php";
	?>
</body>

</html>