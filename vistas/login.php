<?php

session_start();

// Si existe la sesión se redirecciona a la página principal
if (isset($_SESSION["usuario"])) {
	header("location:index.php");
}

// En este arreglo se guarda si hay errores en los campos del formulario detallado por campo como índice
$errores = [];


if ($_POST) {
	// Se inicia la sesión si los datos ingresados son válidos
	// Sino el arreglo contendrá los errores
	User::connect();
	$usuario = null;
	if (isset($_POST['email'])) {
		// Verificar que la dirección de email tenga un formato válido
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			// La cadena contiene una dirección de email válida
			// Se busca al usuario en la base de datos
			$usuario = User::findByEmail($_POST['email']);
			if (!$usuario) {
				$errores['email'] = "Esta dirección de email no está registrada";
			} elseif (!password_verify($_POST['password'], $usuario->__get("contrasenia"))) {
				$errores['password'] = "La contraseña no es correcta";
			}
		} else {
			$errores['email'] = "La dirección ingresada no es un formato válido de email";
		}
	}

	if (!$errores) {
		$_SESSION["usuario"] = $usuario->__get("usuario");
		// Si tenemos los datos del usuario en el arreglo, la sesión ya está iniciada
		// Se redirecciona al usuario
		header("location:" . SERVER_URL);
	}
}
?>

<!DOCTYPE html>
<html>

<?php

$titulo = "Iniciar Sesión";
include "partials/head.php";

?>

<body>
	<div class="form-container">
		<form id="form-login" action="login" method="post">
			<h3 class="text-center md-2">Iniciar Sesión</h3>
			<div class="form-group">
				<label for="email">Correo Eléctronico</label>
				<input type="email" class="form-control" id="email" name="email" 
				value="<?= $errores && !isset($errores["email"]) ? $_POST["email"] : "" ?>" required>
				<small class="text-danger"><?= isset($errores["email"]) ? $errores["email"] : "" ?></small>
			</div>
			<div class="form-group">
				<label for="password">Contraseña</label>
				<input type="password" class="form-control" id="password" name="password" required>
				<small class="text-danger"><?= isset($errores["password"]) ? $errores["password"] : "" ?></small>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" class="form-check-input" id="recordar" name="recordar">
				<label class="form-check-label" for="recordar"><small>Recordarme</small></label>
			</div>
			<button type="submit" class="btn btn-primary btn-block my-3">Iniciar Sesión</button>
			<small>¿No tienes cuenta?<a href="<?= SERVER_URL ?>/registro"> Regístrate aquí</a></small>
		</form>
	</div>
</body>

</html>