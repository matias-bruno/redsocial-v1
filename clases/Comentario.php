<?php

class Comentario extends Model {
    static protected $table = "comentarios";
    static protected $columns = ["id","contenido","usuario_id","publicacion_id","created_at","updated_at"];

    protected $id;
    protected $contenido;
    protected $usuario_id;
    protected $publicacion_id;
    protected $created_at;
    protected $updated_at;

    public function __construct($data) {
        foreach($data as $key => $value) {
            if(in_array($key, self::$columns)) {
                $this->$key = $value;
            }
        }
    }

    public static function getCommentsArray($publicacion_id) {
        try {
            $sql = "SELECT * FROM comentarios WHERE publicacion_id = :publicacion_id";
            $stmt = self::$database->prepare($sql);
            $stmt->bindValue(":publicacion_id", $publicacion_id, PDO::PARAM_INT);
            if($stmt->execute()) {
                $commentsArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $commentsArray;
            }
        } catch(PDOException $ex) {
            return false;
        }
        return false;
    }
}