<?php

Class Chat extends Model {
    static protected $table = "chats";
    static protected $columns = ["id","usuario1_id","usuario2_id","created_at","updated_at"];

    protected int $id;
    protected int $usuario1_id;
    protected int $usuario2_id;
    protected string $created_at;
    protected string $updated_at;

    public function __construct($data) {
        foreach($data as $key => $value) {
            if(in_array($key, self::$columns)) {
                $this->$key = $value;
            }
        }
    }

    public static function findChat($usuario1_id, $usuario2_id) {
        $strConsulta = "SELECT id FROM chats 
                        WHERE (usuario1_id = :usuario1_id AND usuario2_id = :usuario2_id)
                        OR (usuario1_id = :usuario2_id AND usuario2_id = :usuario1_id)";
        try {
            $objConsulta = self::$database->prepare($strConsulta);
            $objConsulta->bindValue(":usuario1_id", $usuario1_id, PDO::PARAM_INT);
            $objConsulta->bindValue(":usuario2_id", $usuario2_id, PDO::PARAM_INT);
            $objConsulta->execute();
            $chat = $objConsulta->fetch(PDO::FETCH_ASSOC);
            if($objConsulta->rowCount() > 0) return Chat::findById($chat["id"]);
        } catch(PDOException $ex) {
            return null;
        }
        return null;
    }
    // private static function createChat($usuario1_id, $usuario2_id) {
    //     $conexion = self::connect();
    //     $strConsulta = "INSERT INTO chats (usuario1_id, usuario2_id) 
    //                     VALUES (:usuario1_id, :usuario2_id)";
    //     try {
    //         $objConsulta = $conexion->prepare($strConsulta);
    //         $objConsulta->bindValue(":usuario1_id", $usuario1_id, PDO::PARAM_INT);
    //         $objConsulta->bindValue(":usuario2_id", $usuario2_id, PDO::PARAM_INT);
    //         return $objConsulta->execute();
    //     } catch(PDOException $ex) {
    //         return false;
    //     }
    //     return false;
    // }
}