<?php

class Like extends Model {
    static protected $table = "likes";
    static protected $columns = ["id","usuario_id", "publicacion_id", "created_at", "updated_at"];

    protected int $id;
    protected int $usuario_id;
    protected $publicacion_id;
    protected string $created_at;
    protected string $updated_at;

    public function __construct($data) {
        foreach($data as $key => $value) {
            if(in_array($key, self::$columns)) {
                $this->$key = $value;
            }
        }
    }

    public static function getLike($usuario_id, $publicacion_id) {
        $sql = "SELECT * FROM likes WHERE usuario_id = :usuario_id AND publicacion_id = :publicacion_id";
        try {
            $stmt = self::$database->prepare($sql);
            $stmt->bindValue(":usuario_id", $usuario_id);
            $stmt->bindValue(":publicacion_id", $publicacion_id);
            if($stmt->execute()) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                if($data)
                    return new Like($data);
            }
        } catch(PDOException $ex) {
            return null;
        }
        return null;
    }

}