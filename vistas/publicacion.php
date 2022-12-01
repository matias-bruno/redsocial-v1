<?php

require "./partials/session-logged.php";

$publicacion = null;
if(isset($url[1]) && !empty($url[1])) {
    $id = intval($url[1]);
	$publicacion = Publicacion::findById($id);
}

if(!$publicacion) {
    header("Location:" . SERVER_URL . "/inicio");
    exit;
}

$usuarioPublicacion = User::findById($publicacion->__get("usuario_id"));
// $imagenUsuario = Imagen::findById($usuario->__get("imagen_id"));
$time_stamp = mostrarDiferencia($publicacion->__get("created_at"));

$publicacion_id = $publicacion->__get("id");
$imagenUsuarioPublicacion = Imagen::findById($usuarioPublicacion->__get("imagen_id"));

?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Publicación de " . $usuarioPublicacion->__get("usuario");
include "./partials/head.php";
?>

<body>
	<div class="container">
		<?php
		include "./partials/header.php";
		?>
		<div id="publicaciones">
            
        </div>
	<?php
	include "./partials/scriptsJS.php";
	?>
	<script src="<?= SERVER_URL ?>/assets/js/publicaciones.js"></script>
    <script src="<?= SERVER_URL ?>/assets/js/comentarios.js"></script>

    <script>
        let serverUrl = "<?= SERVER_URL ?>";
        $(document).ready(function() {
			$.ajax({
                    url: serverUrl + "/ajax/ver-publicacion",
                    type: "POST",
                    data: {id: <?= $id ?>},
                    dataType: "json",
                    success:
                        function(data) {
                            if(data) {
                                const userId= data[1].userId;
                                const element = data[0];
                                $('#publicaciones').append(renderPublicacion(element, userId));
                            }
                        }
            });
		});
    </script>
</body>
	
</html>