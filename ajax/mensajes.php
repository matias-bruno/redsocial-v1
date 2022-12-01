<?php

session_start();

$usuario = null;
Mensaje::connect();

if(isset($_SESSION["usuario"])) {
    $usuario = User::findByUsername($_SESSION["usuario"]);
}

if(!$usuario) {
    http_response_code(401);
    echo "Se debe estar autenticado para realizar esta petición";
    exit;
}

$response = [];

if($usuario) {
    if(isset($_POST["usuario"]) && isset($_POST["accion"])) {
        $receptor = User::findByUsername($_POST["usuario"]);
        if(!$receptor) {
            http_response_code(400);
            return false;
        }
        $emisor_id = intval($usuario->__get("id"));
        $receptor_id = intval($receptor->__get("id"));
        // Si están validados los 2 usuarios, se puede seguir
        if($_POST["accion"] == "new") {
            if(isset($_POST["contenido"])) {
                $contenido = str_replace('\r\n','\n', $_POST["contenido"]);
                $contenido = nl2br($contenido);
                $mensaje = new Mensaje(
                    [
                        "emisor_id" => $usuario->__get("id"),
                        "receptor_id" => $receptor_id,
                        "contenido" => $contenido
                    ]);
                Chat::connect();
                $chat = Chat::findChat($emisor_id, $receptor_id);
                if(!$chat) {
                    $chat = new Chat(
                        [
                            "usuario1_id" => $emisor_id,
                            "usuario2_id" => $receptor_id,
                        ]);
                    if(!$chat->save()) {
                        $chat = null;
                    }
                }
                if($chat) {
                    $mensaje->__set("chat_id", $chat->__get("id"));
                    if($mensaje->save()) {
                        $response = $mensaje->attributes();
                        $response["id"] = $mensaje->__get("id");
                        $response["usuario"] = $usuario->__get("usuario");
                    } else {
                        $mensaje = null;
                    }
                }
                if(!$mensaje) {
                    http_response_code(500);
                }
            } else {
                http_response_code(400);
            }
        } elseif($_POST["accion"] == "load") {
            if(isset($_POST["next"])) {
                $next = intval($_POST["next"]);
                $response = Mensaje::getMessages($usuario->__get("id"), $receptor_id, 8, $next);
                if($response) {
                    $size = count($response);
                    for($i = 0; $i < $size; $i++) {
                        if($response[$i]["emisor_id"] == $usuario->__get("id")) {
                            $response[$i]["usuario"] = $usuario->__get("usuario");
                        } else {
                            $response[$i]["usuario"] = $receptor->__get("usuario");
                        }
                    }
                }
            }
        } elseif($_POST["accion"] == "loadNew") {
            if(isset($_POST["lastId"])) {
                $id = $_POST["lastId"];
                $response = Mensaje::getNewMessages($usuario->__get("id"), $receptor_id, $id);
                if($response) {
                    $size = count($response);
                    for($i = 0; $i < $size; $i++) {
                        if($response[$i]["emisor_id"] == $usuario->__get("id")) {
                            $response[$i]["usuario"] = $usuario->__get("usuario");
                        } else {
                            $response[$i]["usuario"] = $receptor->__get("usuario");
                        }
                    }
                }
            }
        } elseif($_POST["accion"] == "mark") {
            Mensaje::markAsRead($usuario->__get("id"), $receptor_id);
        }
        else {
            http_response_code(400);
        }
    } else {
        http_response_code(400);
    }
} else {
    http_response_code(401);
}

echo json_encode($response);