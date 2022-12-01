<?php

class Notificacion extends Model {
    static protected $table = "notificaciones";
    static protected $columns = ["id","emisor_id","receptor_id","contenido","status","publicacion_id","created_at","updated_at"];

    protected int $id;
    protected int $emisor_id;
    protected int $receptor_id;
    protected string $contenido;
    protected int $status = 0;
    protected int $publicacion_id;
    protected string $created_at;
    protected string $updated_at;

    public function __construct($data) {
        foreach($data as $key => $value) {
            if(in_array($key, self::$columns)) {
                $this->$key = $value;
            }
        }
    }
    public static function getUnseenNumber($usuario_id) :?int {
        try {
            $strConsulta = "SELECT COUNT(*) FROM notificaciones 
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

    public static function getNotificationsArray($usuario_id, $page) {
        $start = ($page - 1) * ITEMS_PER_PAGE;
        $notificaciones = [];
        try {
            $sql = "SELECT * FROM notificaciones WHERE receptor_id = :usuario_id ORDER BY id DESC ";
            $sql .= " LIMIT " . ITEMS_PER_PAGE . " OFFSET " . $start;
            $stmt = self::$database->prepare($sql);
            $stmt->bindValue(":usuario_id", $usuario_id);
            $stmt->execute();
            $notificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            return null;
        }
        return $notificaciones;
    }
}