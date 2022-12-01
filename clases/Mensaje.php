<?php

class Mensaje extends Model {
    static protected $table = "mensajes";
    static protected $columns = ["id","emisor_id","receptor_id","contenido","status","chat_id","created_at","updated_at"];

    protected int $id;
    protected int $emisor_id;
    protected int $receptor_id;
    protected string $contenido;
    protected int $status = 0;
    protected $chat_id;
    protected string $created_at;
    protected string $updated_at;


    public function __construct($data) {
        foreach($data as $key => $value) {
            if(in_array($key, self::$columns)) {
                $this->$key = $value;
            }
        }
    }

    public static function getMessages($usuario1_id, $usuario2_id, $count, $start) {
        try {
            $strConsulta = "SELECT * FROM mensajes 
                            WHERE (emisor_id = :usuario1_id AND receptor_id = :usuario2_id)
                            OR (emisor_id = :usuario2_id AND receptor_id = :usuario1_id)
                            ORDER BY id DESC
                            LIMIT $start, $count";
            $objConsulta = self::$database->prepare($strConsulta);
            $objConsulta->bindValue(":usuario1_id", $usuario1_id, PDO::PARAM_INT);
            $objConsulta->bindValue(":usuario2_id", $usuario2_id, PDO::PARAM_INT);
            if($objConsulta->execute()) {
                return $objConsulta->fetchALL(PDO::FETCH_ASSOC);
            }
        } catch(PDOException $ex) {
            return false;
        }
        return false;
    }
    public static function markAsRead($emisor_id, $receptor_id) {
        try {
            $strConsulta = "UPDATE mensajes SET status = 1
                            WHERE (emisor_id = " . $emisor_id . " AND receptor_id = $receptor_id)
                            OR (emisor_id = $receptor_id AND receptor_id = $emisor_id)";
            $objConsulta = self::$database->prepare($strConsulta);
            return $objConsulta->execute();
        } catch(PDOException $ex) {
            return false;
        }
        return false;
    }
    public function countMessages($receptor_id) {
        $total = 0;
        try {
            $objConsulta = $this->conexion->query("SELECT COUNT(*) FROM mensajes WHERE emisor_id = $this->usuario_id AND receptor_id = $receptor_id");
            $objConsulta->execute();
            $total = $objConsulta->fetchColumn();
        } catch (PDOException $ex) {
            return false;
        }
        return $total;
    }
    public static function getNewMessages($emisor_id, $receptor_id, $last_id) {
        $last_id = intval($last_id);
        try {
            $strConsulta = "SELECT * FROM mensajes 
                            WHERE (emisor_id = $receptor_id AND receptor_id = $emisor_id) AND id > $last_id
                            ORDER BY id";
            $objConsulta = self::$database->query($strConsulta);
            if($objConsulta->execute()) {
                return $objConsulta->fetchALL(PDO::FETCH_ASSOC);
            }
        } catch(PDOException $ex) {
            return false;
        }
        return false;
    }
    public static function getPreviewChats($usuario_id) :?array {
        try {
            $strConsulta = "SELECT * FROM mensajes
                WHERE id IN
                (SELECT Max(id) FROM mensajes
                WHERE emisor_id = $usuario_id OR receptor_id = $usuario_id
                GROUP BY chat_id)
                ORDER BY id DESC";
            $objConsulta = self::$database->query($strConsulta);
            if($objConsulta->execute()) {
                return $objConsulta->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch(PDOException $ex) {
            return null;
        }
        return null;
    }
    public static function getUnreadNumber($usuario_id) :?int {
        try {
            $strConsulta = "SELECT COUNT(*) FROM mensajes 
                            WHERE status = 0 AND receptor_id = $usuario_id";
            $objConsulta = self::$database->query($strConsulta);
            if($objConsulta->execute()) {
                return $objConsulta->fetchColumn();
            }
        } catch(PDOException $ex) {
            return null;
        }
        return null;
    }
    public static function getCountNew($usuario_id) {
        try {
            $strConsulta = "SELECT COUNT(*) AS cantidad, chat_id FROM mensajes 
                            WHERE status = 0 AND receptor_id = $usuario_id
                            GROUP BY chat_id";
            $objConsulta = self::$database->query($strConsulta);
            if($objConsulta->execute()) {
                $dataChat = $objConsulta->fetchAll(PDO::FETCH_ASSOC);
                $result = [];
                foreach($dataChat as $chat) {
                    $result[$chat["chat_id"]] = $chat["cantidad"];
                }
                return $result;
            }
        } catch(PDOException $ex) {
            return null;
        }
        return null;
    }
}