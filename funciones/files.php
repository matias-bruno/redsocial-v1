<?php

function guardarImagen($index, $nameFolder, $nameFile, $saveThumbnail = false) {
    if($_FILES[$index]["error"] === UPLOAD_ERR_OK) {
        $tempFile = $_FILES[$index]["tmp_name"];
        $typeArray = explode('/', $_FILES[$index]["type"]);
        if(count($typeArray) < 2 || $typeArray[0] !== 'image')
            return false;
        $ext = strtolower($typeArray[1]);
        if(in_array($ext, ["jpg", "jpeg", "png", "gif"])) {
            $dir = BASE_DIR . "/assets/img/";
            if($nameFolder)
                $dir .= $nameFolder . '/';
            $file = $nameFile . "." . $ext;
            if(move_uploaded_file($tempFile, $dir . $file)) {
                if($saveThumbnail) {
                    crearMiniatura($dir, $file);
                }
                return $file;
            }
        }
    }
    return false;
}

function crearMiniatura($dir, $nameFile) {

    $imageFile = $dir . $nameFile;

    // Se valida que el archivo de la imagen exista
    if(!file_exists($imageFile)) { return false; }

    // Obtenemos el tamaño de la imagen original
    list( $width, $height ) = getimagesize($imageFile);

    // Se valida que el ancho original no sea menor al que queremos para la miniatura
    if($width < 200) { return false; }

    // Definimos el tamaño de la miniatura
    $miniWidth = 200;
    $miniHeight = floor($height * ($miniWidth / $width));

    // Creamos la variable que contendrá la imagen original según su tipo
    $origin = null;

    if(preg_match('/[.](jpe?g)$/', $imageFile)) {
        $origin = imagecreatefromjpeg($imageFile);
    } else if (preg_match('/[.](gif)$/', $imageFile)) {
        $origin = imagecreatefromgif($imageFile);
    } else if (preg_match('/[.](png)$/', $imageFile)) {
        $origin = imagecreatefrompng($imageFile);
    } else {
        return false;
    }

    // creamos la variable que contendrá la imagen de la miniatura
    $thumb = imagecreatetruecolor($miniWidth, $miniHeight);

    // Llamamos a la función que copia de una variable a otra con el nuevo tamaño
    imagecopyresized( $thumb, $origin, 0,0,0,0, $miniWidth, $miniHeight, $width, $height );
    
    // Guardamos el contenido de la imagen en un archivo
    // Nos sirve tener la ubicación y el nombre separados
    // para agregarle un prefijo
    imagejpeg($thumb, $dir . "mini-" . $nameFile);
}
