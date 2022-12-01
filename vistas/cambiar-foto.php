<?php

require "partials/session-logged.php";

?>

<!DOCTYPE html>
<html>

<?php
$titulo = "Cambiar foto de perfil";
include("partials/head.php");
?>

<body>
    <div class="container">
        <?php
        include("partials/header.php");
        ?>
        <div class="cambiar-foto p-2">
            <h3 class="my-2 text-center">Cambiar Foto de Perfil</h3>
            <div class="cardbody p-3">
                <div class="text-center">
                    <strong class="d-block p-3">Seleccione una imagen:</strong>
                    <div id="upload-demo"></div>
                </div>
                <div class="text-center">
                    <p><input type="file" id="image"></p>
                </div>
                <div class="d-flex justify-content-around">
                    <button class="btn btn-primary btn-upload-image">Cambiar Imagen</button>
					<a href="<?= SERVER_URL ?>/editar-perfil" class="btn btn-secondary">Volver</a>
				</div>
            </div>
        </div>
    </div>
    <?php
    include("partials/scriptsJS.php");
    ?>
    <script>
        const serverUrl = "<?= SERVER_URL ?>";
    </script>
    <script src="<?= SERVER_URL ?>/assets/js/croppie.min.js"></script>
    <script src="<?= SERVER_URL ?>/assets/js/croppie-app.js"></script>
</body>

</html>