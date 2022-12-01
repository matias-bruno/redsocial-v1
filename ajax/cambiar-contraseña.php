<?php

session_start();

$usuario = null;
User::connect();

if(isset($_SESSION["usuario"])) {
    $usuario = User::findByUsername($_SESSION["usuario"]);
}

if(!$usuario) {
    http_response_code(401);
    echo "Se debe estar autenticado para realizar esta petición";
    exit;
}

// En este arreglo se guarda si hay errores en los campos del formulario detallado por campo como índice
$errores = [];

if ($_POST) {
    $response = "OK";
    $errores = [];
    
    $passwordVerify = $_POST["password-verify"] ?? "";
    $password = $_POST['password'] ?? "";
    $password2 = $_POST['password2'] ?? "";

    if (!password_verify($passwordVerify, $usuario->__get("contrasenia"))) {
        $errores["password-verify"] = "La contraseña no es correcta";
        $response = "error";
    }
    if(!$errores) {
        $data["password"] = $password;
        $data["password2"] = $password2;
        $userValidator = new UserValidator($data);
	    $errores = $userValidator->validate();
        if(!$errores) {
            $usuario->__set("contrasenia", password_hash($password, PASSWORD_BCRYPT));
            $usuario->save();
        } else {
            $response = "error";
        }
        
    }
    $data = ["response" => $response, "errores" => $errores];
    echo json_encode($data);
}