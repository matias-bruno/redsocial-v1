<?php

require "partials/session-logged.php";

if(isset($_POST["quitar-imagen"])) {
	$image_name = "default.png";
	if($imagen != $image_name && file_exists("assets/img/fotos_perfil/" . $image_name)) {
		if($usuario->__set("imagen_id", NULL)) {
			$usuario->save();
		}
		header("Location:" . SERVER_URL .  "/editar-perfil");
    }
} else if($_POST) {
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

	foreach($data as $key => $value) {
		if(!isset($errores[$key])) {
			$usuario->__set($key, $value);
		}
	}
	$usuario->save();
}
?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Editar Perfil";
include("./partials/head.php");
?>

<body>
	<div class="container">
		<?php
		include("./partials/header.php");
		?>

		<div id="form-edit-profile">
			<form action="" method="post" enctype="multipart/form-data">
				<h3 class="my-3 text-center">Editar Perfil</h3>
				<div class="row">
					<div class="form-group col-sm-6">
						<label for="nombre">Nombre(s):</label>
						<input type="text" class="form-control" id="nombre" name="nombre" value="<?= $usuario->__get("nombre") ?>">
						<small class="text-danger"><?= isset($errores["nombre"]) ? $errores["nombre"] : "" ?></small>
					</div>
					<div class="form-group col-sm-6">
						<label for="apellido">Apellido(s):</label>
						<input type="text" class="form-control" id="apellido" name="apellido" value="<?= $usuario->__get("apellido") ?>">
						<small class="text-danger"><?= isset($errores["apellido"]) ? $errores["apellido"] : "" ?></small>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-6 py-2">
						<p class="m-0"><label for="fecha_nacimiento">Fecha de Nacimiento: </label></p>
						<p class="m-0"><input type="date" name="fecha_nacimiento" id="fecha_nacimiento"
						value="<?= $usuario->__get("fecha_nacimiento")  ?>"></p>
						<small class="text-danger"><?= isset($errores["fecha_nacimiento"]) ? $errores["fecha_nacimiento"] : "" ?></small>
					</div>
					<div class="form-group col-sm-6 py-2">
						<label for="genero">Género:</label>
						<select class="form-control" id="genero" name="genero">
							<option value="">Seleccione una opción</option>
							<option value="Hombre"<?= $usuario->__get("genero") == "Hombre" ? "selected" : "" ?>>Hombre</option>
							<option value="Mujer"<?= $usuario->__get("genero") == "Mujer" ? "selected" : "" ?>>Mujer</option>
							<option value="Otro"<?= $usuario->__get("genero")== "Otro" ? "selected" : "" ?>>Otro</option>
						</select>
						<small class="text-danger"><?= isset($errores["genero"]) ? $errores["genero"] : "" ?></small>
					</div>
				</div>
				<div class="mb-3">
					<button type="submit" class="btn btn-primary">Actualizar</button>
				</div>
				<div class="form-group">
					<p class=""><label for="imagen">Imagen de perfil</label></p>
					<img class="card-img-top" id="user-img" src="<?= SERVER_URL ?>/assets/img/fotos_perfil/<?= $imagenUsuario ?>" alt="Imagen del Usuario">
					<div>
						<a href="<?= SERVER_URL ?>/cambiar-foto" class="btn btn-primary">Cambiar Imagen</a>
						<button type="submit" name="quitar-imagen" class="btn btn-danger">Quitar Imagen</button>
					</div>
				</div>
				<div class="d-flex justify-content-between">
					<a href="<?= SERVER_URL ?>/cambiar-contraseña" class="btn btn-primary my-2">Cambiar Contraseña</a>
				</div>
				<div>
					<a href="<?= SERVER_URL ?>/inicio" class="btn btn-secondary">Volver</a>
				</div>
			</form>
		</div>
	</div>
	<?php
	include("./partials/scriptsJS.php");
	?>
</body>

</html>