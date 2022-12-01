<?php

class User extends Model {
    static protected $table = "usuarios";
    static protected $columns = ["id","usuario","nombre","apellido","email","fecha_nacimiento","genero","contrasenia","imagen_id", "portada_id", "estado","tipo","created_at","updated_at"];

    protected int $id;
    protected string $usuario;
    protected string $nombre;
    protected string $apellido;
    protected string $email;
    protected string $fecha_nacimiento;
    protected string $genero;
    protected $imagen_id;
    protected $portada_id;
    protected string $password;
    protected string $password2;
    protected string $contrasenia;
    protected int $estado = 1;
    protected int $tipo = 1;
    protected string $created_at;
    protected string $updated_at;

    public $errors = [];

    // Se puede llamar de dos maneras:
    // 1- Con un arreglo que representa un usuario para registrar
    // 2- Con un arreglo que tiene los datos de un usuario que ya está registrado
    public function __construct($data) {
        foreach($data as $key => $value) {
            if(in_array($key, self::$columns)) {
                $this->$key = $value;
            } else if($key == "password" || $key == "password2") {
                $this->$key = $value;
                if(!isset($this->contrasenia)) {
                    $this->contrasenia = password_hash($value, PASSWORD_BCRYPT);
                }
            }
        }
    }

    // Buscar por nombre de usuario
    public static function findByUsername($username) {
        return self::findBy("usuario", $username);
    }

    // Buscar por dirección de correo electrónico
    public static function findByEmail($email) {
        return self::findBy("email", $email);
    }

    // Esta versión trabaja con las contraseñas
    protected function update() {
        
        $this->validate();
        if(!empty($this->errors)) { return false; }

        $attributes = $this->attributes();
        $attribute_keys = [];
        foreach($attributes as $key => $value) {
            $attribute_keys[] = "{$key}=:{$key}";
        }

        $sql = "UPDATE " . static::$table . " SET ";
        $sql .= join(', ', $attribute_keys);
        $sql .= ", updated_at = now()";
        $sql .= " WHERE id=" . $this->id;

        try {
            $stmt = self::$database->prepare($sql);
            foreach($attributes as $key => $value) {
                $param = is_null($value) ? PDO::PARAM_NULL : PDO::PARAM_STR;
                if($key == "contrasenia" && !empty($this->password)) {
                    $value = password_hash($this->password, PASSWORD_BCRYPT);
                }
                $stmt->bindValue(":{$key}", $value, $param);
            }
            return $stmt->execute();
        } catch(PDOException $ex) {
            return "Ocurrió una excepción";
        }
    }
    public static function search(string $value) {
        $value = "%" . $value . "%";
        try {
            $sql = "SELECT us.usuario, us.nombre, us.apellido, im.nombre AS imagen FROM usuarios AS us
            LEFT JOIN imagenes AS im ON us.imagen_id = im.id
            WHERE us.usuario LIKE :value OR us.nombre LIKE :value OR us.apellido LIKE :value";
            $stmt = self::$database->prepare($sql);
            $stmt->bindValue(":value", $value);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $Exception) {
            return false;
        }
    }
    public function validate() {
        return true;
    }
}