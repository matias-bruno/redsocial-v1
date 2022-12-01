<?php

class Imagen extends Model {
    static protected $table = "imagenes";
    static protected $columns = ["id","nombre", "album_id", "created_at", "updated_at"];

    protected int $id;
    protected string $nombre;
    protected int $album_id;
    protected string $created_at;
    protected string $updated_at;

    public function __construct($data) {
        foreach($data as $key => $value) {
            if(in_array($key, self::$columns)) {
                $this->$key = $value;
            }
        }
    }
}



?>