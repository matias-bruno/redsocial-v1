<?php

require "partials/session-logged.php";

http_response_code(404);

?>

<!DOCTYPE html>
<html>

<?php
$titulo = "No Encontrado";
include("partials/head.php");
?>

<body>
	<div class="container text-center vh-100">
		<?php
		include("partials/header.php");
		?>
        <h1>404</h1>
        <h1>Lo sentimos, no se encontró la página</h1>
    </div>
    <?php
	include("partials/scriptsJS.php");
	?>
</body>
</html>