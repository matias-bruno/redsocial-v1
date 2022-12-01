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

$respuesta = [
    "ok" => false,
    "mensaje" => "",
    "error" => ""
];

$amigo = "";
$usuario1_id = -1;
$usuario2_id = -1;


if(isset($_POST["usuario"])) {
    $amigo = User::findByUsername($_POST["usuario"]);
    $usuario1_id = intval($usuario->__get("id"));
    $usuario2_id = intval($amigo->__get("id"));
} else {
    $respuesta["error"] = "Falta el nombre de usuario";
    echo json_encode($respuesta);
    exit;
}
if($usuario && $amigo && $usuario != $amigo) {
    if($_POST["accion"] == "enviar") {
        // Si no existe solicitud ni amistad entre usuario 1 y usuario 2
        $amistad_1 = Amistad::getRequest($usuario1_id, $usuario2_id);
        $amistad_2 = Amistad::getRequest($usuario2_id, $usuario1_id);
        if(!$amistad_1 && !$amistad_2) {
            $amistad_1 = new Amistad(["usuario1_id" => $usuario1_id, "usuario2_id" => $usuario2_id, "status" => "enviada", "seen" => 0]);
            $amistad_2 = new Amistad(["usuario1_id" => $usuario2_id, "usuario2_id" => $usuario1_id, "status" => "recibida", "seen" => 0]);

            if($amistad_1->save() && $amistad_2->save()) {
                $respuesta["ok"] = true;
                $respuesta["mensaje"] = "Solicitud enviada";
            } else {
                $respuesta["error"] = "No se puede enviar la solicitud";
            }

        } else {
            $respuesta["error"] = "No se puede enviar la solicitud";
        }
    } elseif($_POST["accion"] == "aceptar") {
        $amistad_1 = Amistad::getRequest($usuario1_id, $usuario2_id);
        $amistad_2 = Amistad::getRequest($usuario2_id, $usuario1_id);
        if($amistad_1 && $amistad_1->__get("status") == "recibida" &&
            $amistad_2 && $amistad_2->__get("status") == "enviada") {

            $amistad_1->__set("status", "aceptada");
            $amistad_1->save();

            $amistad_2->__set("status", "aceptada");
            $amistad_2->save();

            $respuesta["ok"] = true;
            $respuesta["mensaje"] = "Solicitud aceptada";
        } else {
            $respuesta["error"] = "No se puede completar la accion";
        }
    } elseif($_POST["accion"] == "cancelar") {
        $amistad_1 = Amistad::getRequest($usuario1_id, $usuario2_id);
        $amistad_2 = Amistad::getRequest($usuario2_id, $usuario1_id);
        if($amistad_1 && $amistad_1->__get("status") == "enviada" &&
            $amistad_2 && $amistad_2->__get("status") == "recibida") {
            if($amistad_1->delete() && $amistad_2->delete()) {
                $respuesta["ok"] = true;
                $respuesta["mensaje"] = "Solicitud cancelada";
            } else {
                $respuesta["error"] = "No se puede completar la accion";
            }
        }
    } elseif($_POST["accion"] == "rechazar") {
        $amistad_1 = Amistad::getRequest($usuario1_id, $usuario2_id);
        $amistad_2 = Amistad::getRequest($usuario2_id, $usuario1_id);
        if($amistad_1 && $amistad_1->__get("status") == "recibida" &&
            $amistad_2 && $amistad_2->__get("status") == "enviada") {
            if($amistad_1->delete() && $amistad_2->delete()) {
                $respuesta["ok"] = true;
                $respuesta["mensaje"] = "Solicitud rechazada";
            } else {
                $respuesta["error"] = "No se puede completar la accion";
            }
        }
    } elseif ($_POST["accion"] == "quitar") {
        $amistad_1 = Amistad::getRequest($usuario1_id, $usuario2_id);
        $amistad_2 = Amistad::getRequest($usuario2_id, $usuario1_id);
        if($amistad_1 && $amistad_1->__get("status") == "aceptada" &&
            $amistad_2 && $amistad_2->__get("status") == "aceptada") {
            if($amistad_1->delete() && $amistad_2->delete()) {
                $respuesta["ok"] = true;
                $respuesta["mensaje"] = "Amistad eliminada";
            } else {
                $respuesta["error"] = "No se puede completar la accion";
            }
        }
    } else {
        $respuesta["error"] = "Los datos para la operación no son válidos";
    }
}

echo json_encode($respuesta);