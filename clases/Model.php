<?php

abstract class Model {
    static protected $database;
    static protected $table = "";
    static protected $columns = [];
    public $errors = [];

    public function __get($field) {
        return $this->$field ?? null;
    }

    public function __set($field, $value) {
        if(property_exists($this, $field)) {
            $this->$field = $value;
            return true;
        }
        return false;
    }

    // Se debe llamar primero a este método para establecer la conexión
    public static function connect() {
        if(!self::$database) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port:" . DB_PORT;
            self::$database = new PDO($dsn, DB_USER, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        }
    }

    // Mostrar todos los registros de una tabla
    static public function findAll() {
        $sql = "SELECT * FROM " . static::$table;
        try {
            $stmt = self::$database->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex) {
            // Hacer algo con $ex, mostrarlo por ejemplo
            return false;
        }
    }

    // Mostrar cantidad total de registros de una tabla
    static public function countAll() {
        $sql = "SELECT COUNT(*) FROM " . static::$table;
        try {
            $stmt = self::$database->query($sql);
            return $stmt->fetchColumn();
        } catch(PDOException $ex) {
            // Hacer algo con $ex, mostrarlo por ejemplo
            return false;
        }
    }

    // Método que sirve como base para buscar por campo en una tabla
    static protected function findBy($name, $value) {
        if(!in_array($name, static::$columns) || $value == null) {
            return null;
        }
        $sql = "SELECT * FROM " . static::$table . " WHERE $name = :value";
        try {
            $stmt = self::$database->prepare($sql);
            if($stmt->execute([":value" => $value])) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                return $data ? new static($data) : null;
            }
        } catch(PDOException $ex) {
            return null;
        }
        return null;
    }

    // Método para buscar por id
    static public function findById($id) {
        return self::findBy("id", $id);
    }

    protected function create() {
        $this->validate();
        if(!empty($this->errors)) { return false; }

        $attributes = $this->attributes();

        $sql = "INSERT INTO " . static::$table . " (";
        $sql .= join(', ', array_keys($attributes));
        $sql .= ", created_at, updated_at";
        $sql .= ") VALUES (:";
        $sql .= join(", :", array_keys($attributes));
        $sql .= ", now(), now()";
        $sql .= ")";
        try {
            $stmt = self::$database->prepare($sql);
            foreach($attributes as $key => $value) {
                $param = is_null($value) ? PDO::PARAM_NULL : PDO::PARAM_STR;
                $stmt->bindValue(":$key", $value, $param);
            }
            $result = $stmt->execute();
            if($result) {
                $this->id = self::$database->lastInsertId();
            }
            return $result;
        } catch(PDOException $ex) {
            return false;
        }
    }

    protected function update() {
        $this->validate();
        if(!empty($this->errors)) { return false; }

        $attributes = $this->attributes();
        $attribute_pairs = [];
        foreach($attributes as $key => $value) {
            $attribute_pairs[] = "{$key}=:{$key}";
        }

        $sql = "UPDATE " . static::$table . " SET ";
        $sql .= join(', ', $attribute_pairs);
        $sql .= ", updated_at = now()";
        $sql .= " WHERE id=" . $this->id . " LIMIT 1";

        try {
            $stmt = self::$database->prepare($sql);
            foreach($attributes as $key => $value) {
                $param = is_null($value) ? PDO::PARAM_NULL : PDO::PARAM_STR;
                $stmt->bindValue(":{$key}", $value, $param);
            }
            return $stmt->execute();
        } catch(PDOException $ex) {
            return false;
        }
        return false;
    }

    public function save() {
        // Si el registro es nuevo, no tendrá id
        if(isset($this->id)) {
            return $this->update();
        } else {
            return $this->create();
        }
    }

    public function delete() {
        $sql = "DELETE FROM " . static::$table . " WHERE id=" . $this->id . " LIMIT 1";
        $result = self::$database->query($sql);
        return $result;
    }
    // Después de borrar sigue existiendo en memoria el objeto, puede servir para informar sobre la operación
    
    // Propiedades que tienen columnas en la base de datos, excluyendo id y timestamps
    public function attributes() {
        $attributes = [];
        foreach(static::$columns as $column) {
            if($column == 'id' || $column == 'created_at' || $column == 'updated_at') { continue; }
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    public function toArray() {
        $objectData = [];
        foreach(static::$columns as $column) {
            $attributes[$column] = $this->$column;
        }
        return $objectData;
    }

    public function validate() {
        return true;
    }

}