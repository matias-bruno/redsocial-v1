<?php

session_start();

// Si existe la sesión se redirecciona a la página principal
if (isset($_SESSION["usuario"])) {
	header("location:index.php");
}

// En este arreglo se guarda si hay errores en los campos del formulario detallado por campo como índice
$errores = [];

// Se usan estas variables para persistir los datos enviados por el método POST
$email = $_POST["email"] ?? "";
$usuario = $_POST["usuario"] ?? "";
$nombre = $_POST["nombre"] ?? "";
$apellido = $_POST["apellido"] ?? "";
$fecha_nacimiento = $_POST["fecha_nacimiento"] ?? "";
$genero = $_POST["genero"] ?? "";

// Cuando llegan los datos por POST
if($_POST) {
	if(!isset($_POST["tyc"])) {
		// Se descarta directamente si no acepta los términos
		$errores["tyc"] = "Se deben aceptar los términos y condiciones";
	} else {
		// Se copian los datos a un nuevo arreglo
		$data = $_POST;
		// Se cambia el nombre a formato con mayúsculas al principio seguido de minúsculas
		if(isset($data["nombre"])) {
			$data["nombre"] = ucwords(mb_strtolower($data["nombre"], "UTF-8"));
		}
		// Se cambia el apellido a formato con mayúsculas al principio seguido de minúsculas
		if(isset($data["apellido"])) {
			$data["apellido"] = ucwords(mb_strtolower($data["apellido"], "UTF-8"));
		}
		// Se comprueban los datos enviados en el formulario
		$userValidator = new UserValidator($data);
		$errores = $userValidator->validate();

		User::connect();

		// Se comprueba que el nombre de usuario esté disponible
		if(!isset($errores["usuario"]) && isset($data["usuario"])) {
			$usuarioExiste = (User::findByUsername($data["usuario"]) != null);
			if($usuarioExiste) {
				$errores["usuario"] = "El nombre de usuario ya existe";
			}
		}

		// Se comprueba que no se hayan registrado antes con este email
		if(!isset($errores["email"]) && isset($data["email"])) {
			$emailExiste = (User::findByEmail($data["email"]) != null);
			if($emailExiste) {
				$errores["email"] = "El email ya se encuentra registrado";
			}
		}
	}

	// Si no hay errores
	if(empty($errores)) {
		$usuario = new User($data);
		if($usuario->save()) {
			// Por ahora creamos aquí los albumes por defecto
			$albumFotosPerfil = new Album([
				"nombre" => "fotos_perfil",
				"usuario_id" => $usuario->__get("id")
			]);
			$albumFotosPerfil->save();

			$albumFotosPortada = new Album([
				"nombre" => "portadas",
				"usuario_id" => $usuario->__get("id")
			]);
			$albumFotosPortada->save();

			$albumFotosPublicaciones = new Album([
				"nombre" => "publicaciones",
				"usuario_id" => $usuario->__get("id")
			]);
			$albumFotosPublicaciones->save();

			$_SESSION["usuario"] = $usuario->__get("usuario");
			// Se redirecciona al usuario
			header("location:index.php");
			exit;
		}
	}
}

?>

<!DOCTYPE html>
<html>


<head>
<?php

$titulo = "Registro de Usuario";
include "partials/head.php";

?>

</head>

<body>
	<div class="container">
		<div>
		<form id="form-registro" action="<?= SERVER_URL ?>/registro" method="post">
			<h3 class="text-center m-2">Registrarse</h3>
			<small class="text-danger"><?= isset($errores["conexion"]) ? $errores["conexion"] : "" ?></small>
			<small class="text-danger"><?= isset($errores["conexion"]) ? $errores["conexion"] : "" ?></small>
			<div class="row">
				<div class="form-group col-sm-6">
					<label for="email">Correo Eléctronico</label>
					<input type="email" class="form-control" id="email" name="email" 
					value="<?= $email ?>" required>
					<small class="text-danger"><?= isset($errores["email"]) ? $errores["email"] : "" ?></small>
				</div>
				<div class="form-group col-sm-6">
					<label for="usuario">Nombre de Usuario</label>
					<input type="text" class="form-control" id="usuario" name="usuario" 
					value="<?= $usuario ?>" required>
					<small class="text-danger"><?= isset($errores["usuario"]) ? $errores["usuario"] : "" ?></small>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-sm-6">
					<label for="nombre">Nombre(s):</label>
					<input type="text" class="form-control" id="nombre" name="nombre" value="<?= $nombre ?>" required>
					<small class="text-danger"><?= isset($errores["nombre"]) ? $errores["nombre"] : "" ?></small>
				</div>
				<div class="form-group col-sm-6">
					<label for="apellido">Apellido(s):</label>
					<input type="text" class="form-control" id="apellido" name="apellido" value="<?= $apellido ?>" required>
					<small class="text-danger"><?= isset($errores["apellido"]) ? $errores["apellido"] : "" ?></small>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-sm-6 py-2">
					<p class="m-0"><label for="fecha_nacimiento">Fecha de Nacimiento: </label></p>
					<p class="m-0"><input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
					value="<?= $fecha_nacimiento ?>"></p>
					<small class="text-danger"><?= isset($errores["fecha_nacimiento"]) ? $errores["fecha_nacimiento"] : "" ?></small>
				</div>
				<div class="form-group col-sm-6 py-2">
					<label for="genero">Género:</label>
					<select class="form-control" id="genero" name="genero">
						<option value="">Seleccione una opción</option>
						<option value="Hombre" <?= $genero == "Hombre" ? "selected" : "" ?>>Hombre</option>
						<option value="Mujer" <?= $genero == "Mujer" ? "selected" : "" ?>>Mujer</option>
						<option value="Otro" <?= $genero == "Otro" ? "selected" : "" ?>>Otro</option>
					</select>
					<small class="text-danger"><?= isset($errores["genero"]) ? $errores["genero"] : "" ?></small>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-sm-6">
					<label for="password">Contraseña</label>
					<input type="password" class="form-control" id="password" name="password" required>
					<small class="text-danger"><?= isset($errores["password"]) ? $errores["password"] : "" ?></small>
				</div>
				<div class="form-group col-sm-6">
					<label for="password2">Repetir Contraseña</label>
					<input type="password" class="form-control" id="password2" name="password2" required>
					<small class="text-danger"><?= isset($errores["password2"]) ? $errores["password2"] : "" ?></small>
				</div>
			</div>
			<div class="form-group form-check">
				<input type="checkbox" id="tyc" name="tyc">
				<label for="tyc" id="tyc-text"><small>Acepto los términos y condiciones.</small></label>
				<p><small class="text-danger"><?= isset($errores["tyc"]) ? $errores["tyc"] : "" ?></small></p>
			</div>
			<div class="row">
				<button type="submit" class="btn btn-primary btn-block col-8 col-sm-6 offset-2 offset-sm-3 my-3">Registrarse</button>
			</div>
			<p style="text-align: center"><small>¿Tienes cuenta?<a href="<?= SERVER_URL ?>/login"> Ingresa aquí</a></small></p>
		</form>
		</div>
	</div>
</body>

</html>