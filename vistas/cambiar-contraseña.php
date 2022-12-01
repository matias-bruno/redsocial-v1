<?php

require "partials/session-logged.php";

?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Cambiar Contraseña";
include("partials/head.php");
?>

<body>
	<div class="container">
		<?php
		include("partials/header.php");
		?>

		<div class="form-container-password">
			<div id="message"></div>
			<form id="form-passwords" action="cambiar-contraseña.php" method="post">
				<h3>Cambiar Contraseña</h3>
				<div class="form-group py-2">
					<label for="password-verify">Contraseña</label>
					<input type="password" class="form-control input-password" id="password-verify" name="password-verify" placeholder="Confirme su contraseña" required>
					<small class="text-danger error-password-verify"><?= isset($errores["password-verify"]) ? $errores["password-verify"] : "" ?></small>
				</div>
                <div class="form-group py-2">
					<label for="password">Nueva Contraseña</label>
					<input type="password" class="form-control input-password" id="password" name="password" placeholder="Ingrese su nueva contraseña" required>
					<small class="text-danger error-password"><?= isset($errores["password"]) ? $errores["password"] : "" ?></small>
				</div>
                <div class="form-group py-2">
					<label for="password2">Repita Contraseña</label>
					<input type="password" class="form-control input-password" id="password2" name="password2" placeholder="Repita su nueva contraseña" required>
					<small class="text-danger error-password2"><?= isset($errores["password2"]) ? $errores["password2"] : "" ?></small>
                    <small class="text-danger"><?= isset($errores["principal"]) ? $errores["principal"] : "" ?></small>
				</div>
				<div class="d-flex justify-content-between">
					<button type="submit" class="btn btn-primary">Actualizar</button>
					<a href="<?= SERVER_URL ?>/editar-perfil" class="btn btn-secondary">Volver</a>
				</div>
			</form>
		</div>
	</div>
	<?php
	include("partials/scriptsJS.php");
	?>
	<script>
		$('#form-passwords').on('submit', function(event) {
			event.preventDefault();

			$.ajax({
				url: "ajax/cambiar-contraseña.php",
				type: this.method,
				data: $(this).serialize(),
				dataType: "json",
				success:
					function(data) {
						let mensaje = "";
						if(data.response === "OK") {
							mensaje = '<div class="alert alert-success" role="alert">El cambio de contraseña se realizó correctamente</div>';
							$('.input-password').val("");
						}
						else {
							if(data.errores["password-verify"]) {
								$('.error-password-verify').html(data.errores["password-verify"]);
							} else {
								$('.error-password-verify').html("");
							}
							if(data.errores.password) {
								$('.error-password').html(data.errores.password);
							} else {
								$('.error-password').html("");
							}
							if(data.errores.password2) {
								$('.error-password2').html(data.errores.password2);
							} else {
								$('.error-password2').html("");
							}
						}
						$('#message').html(mensaje);
					}
			});
		});
	</script>
</body>

</html>