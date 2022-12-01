<?php

class Album extends Model {
    static protected $table = "albumes";
    static protected $columns = ["id","nombre","usuario_id","created_at","updated_at"];

    protected int $id;
    protected string $nombre;
    protected int $usuario_id;
    protected string $created_at;
    protected string $updated_at;


    public function __construct($data) {
        foreach($data as $key => $value) {
            if(in_array($key, self::$columns)) {
                $this->$key = $value;
            }
        }
    }

    public static function getAlbumsByUser($usuario_id) {
        try {
            $strConsulta = "SELECT * FROM albumes WHERE usuario_id = $usuario_id";
            $objConsulta = self::$database->query($strConsulta);
            return $objConsulta->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            return false;
        }
    }
    public function getAllImages() :?array {
        $album_id = $this->id;
        try {
            $strConsulta = "SELECT im.id, im.nombre, im.album_id, im.created_at, p.id AS publicacion_id
                            FROM imagenes AS im
                            JOIN publicaciones AS p ON p.imagen_id = im.id
                            WHERE album_id = $album_id";
            return self::$database->query($strConsulta)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $ex) {
            return null;
        }
    }
    public static function getByName($nombre, $usuario_id) {
        try {
            $usuario_id = intval($usuario_id);
            $strConsulta = "SELECT id FROM albumes WHERE nombre = :nombre AND usuario_id = $usuario_id";
            $objConsulta = self::$database->prepare($strConsulta);
            $objConsulta->bindValue(":nombre", $nombre);
            $objConsulta->execute();
            $row = $objConsulta->fetch(PDO::FETCH_ASSOC);
            if(!$row) {
                return false;
            }
            return $row["id"];
        } catch (PDOException $ex) {
            return false;
        }
    }
    public function getFirstImage() :?string {
        $album_id = $this->id;
        try {
            $strConsulta = "SELECT nombre FROM imagenes WHERE album_id = $album_id LIMIT 1";
            $objConsulta = self::$database->query($strConsulta);
            return $objConsulta->fetchColumn();
        } catch (PDOException $ex) {
            return null;
        }
    }
}