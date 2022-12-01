<?php

class Amistad extends Model {
    static protected $table = "amistades";
    static protected $columns = ["id","usuario1_id","usuario2_id","status","seen","created_at","updated_at"];

    protected $id;
    protected $usuario1_id;
    protected $usuario2_id;
    protected $status;
    protected $seen;
    protected $created_at;
    protected $updated_at;

    public function __construct($data) {
        foreach($data as $key => $value) {
            if(in_array($key, self::$columns)) {
                $this->$key = $value;
            }
        }
    }

    public static function getRequests($usuario_id, $status, $count = -1, $start = 0) {
        $count = intval($count);
        $start = intval($start);
        try {
            $strConsulta = "SELECT id, usuario2_id, created_at FROM amistades WHERE usuario1_id = " . $usuario_id . " AND status = '$status'";
            if($count != -1) {
                $strConsulta .= " LIMIT $start, $count";
            }
            $objConsulta = self::$database->query($strConsulta);
            $objConsulta->execute();
        } catch(PDOException $ex) {
            return null;
        }
        return $objConsulta->fetchALL(PDO::FETCH_ASSOC);
    }
    public static function countNew($usuario_id) :?int {
        $count = 0;
        try {
            $strConsulta = "SELECT COUNT(*) FROM amistades WHERE usuario1_id = $usuario_id AND status = 'recibida' AND seen = 0";
            $count = self::$database->query($strConsulta)->fetchColumn();
        } catch(PDOException $ex) {
            return null;
        }
        return $count;
    }
    public static function getRequest($usuario1_id, $usuario2_id) :?object {
        // No puede haber amistad de un solo usuario
        if($usuario1_id == $usuario2_id) { return null; }
        try {
            $sql = "SELECT * FROM amistades ";
            $sql .= "WHERE (usuario1_id = :usuario1_id AND ";
            $sql .= "usuario2_id = :usuario2_id)";
            $stmt = self::$database->prepare($sql);
            $stmt->bindValue(":usuario1_id", $usuario1_id);
            $stmt->bindValue(":usuario2_id", $usuario2_id);
            $stmt->execute();
            if($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return new Amistad($row);
            }
        } catch(PDOException $ex) {
            return null;
        }
        return null;
    }
}