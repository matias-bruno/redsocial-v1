<?php

// Función que sirve para depurar
function dd($algo) {
	echo '<pre>';
	var_dump($algo);
	echo '</pre>';
    exit;
}

// Esta función cambia el nombre de lo que sería una carpeta a un nombre de album más elegante
function showAlbumName($albumName) {
    $albumName = str_replace("_", " ", $albumName);
    $albumName = ucwords(mb_strtolower($albumName, "UTF-8"));
    return $albumName;
}

// Detectando con una expresión regular las urls y se les agrega el html para que se muestren como hipervínculo
function makeUrltoLink($string) {
    $reg_pattern = "/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/";
    return preg_replace($reg_pattern, '<a href="$0" target="_blank" rel="noopener noreferrer">$0</a>', $string);
}


?>