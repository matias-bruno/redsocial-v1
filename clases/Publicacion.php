<?php

class Publicacion extends Model {
    static protected $table = "publicaciones";
    static protected $columns = ["id","contenido", "usuario_id", "descripcion", "imagen_id", "created_at", "updated_at"];

    protected int $id;
    protected string $contenido;
    protected int $usuario_id;
    protected string $descripcion;
    protected $imagen_id;
    protected string $created_at;
    protected string $updated_at;

    public function __construct($data) {
        foreach($data as $key => $value) {
            if(in_array($key, self::$columns)) {
                $this->$key = $value;
            }
        }
    }

    public static function getPosts($next, $usuario_id, $friends = false) : ?array {
        if($friends) {
            $strConsulta = "SELECT p.id, p.contenido, p.usuario_id, p.imagen_id, p.created_at, p.descripcion, p.updated_at FROM publicaciones AS p
                            WHERE (p.usuario_id IN (
                                SELECT usuario2_id FROM amistades WHERE usuario1_id = :usuario_perfil_id AND status = 'aceptada'
                            ) OR p.usuario_id = :usuario_perfil_id) 
                            ORDER BY p.id DESC LIMIT :limit OFFSET :next";
        } else {
            $strConsulta = "SELECT p.id, p.contenido, p.usuario_id, p.imagen_id, p.created_at, p.descripcion, p.updated_at FROM publicaciones AS p
                            WHERE p.usuario_id = :usuario_perfil_id
                            ORDER BY p.id DESC LIMIT :limit OFFSET :next";
        }
        try {
            $objConsulta = self::$database->prepare($strConsulta);
            $objConsulta->bindValue(":usuario_perfil_id", $usuario_id, PDO::PARAM_INT);
            $objConsulta->bindValue(":limit", ITEMS_PER_PAGE, PDO::PARAM_INT);
            $objConsulta->bindValue(":next", $next, PDO::PARAM_INT);
            $objConsulta->execute();
        } catch(PDOException $ex) {
            http_response_code(500);
            return null;
        }
        $arrayRegistros = $objConsulta->fetchAll(PDO::FETCH_ASSOC);
        $publicaciones = [];
        foreach($arrayRegistros as $registro) {
            array_push($publicaciones, new Publicacion($registro));
        }
        return $publicaciones;
    }
    public function getLikesCount() :int {
        try {
            $publicacion_id = $this->__get("id");
            return self::$database->query("SELECT COUNT(*) FROM likes WHERE publicacion_id = $publicacion_id")->fetchColumn();
        } catch (Exception $ex) {
            return 0;
        }
    }
    public function getCommentsCount() :int {
        try {
            $publicacion_id = $this->__get("id");
            $commentsCount = self::$database->query("SELECT COUNT(*) FROM comentarios WHERE publicacion_id = $publicacion_id")->fetchColumn();
            return intval($commentsCount);
        } catch (Exception $ex) {
            return 0;
        }
    }
    public function isLiked($usuario_id) :bool {
        $liked = false;
        try {
            $publicacion_id = $this->__get("id");
            $liked = self::$database->query("SELECT COUNT(*) FROM likes WHERE publicacion_id = $publicacion_id AND usuario_id = $usuario_id")->fetchColumn();
        } catch (Exception $ex) {
            return $liked;
        }
        return $liked;
    }
}