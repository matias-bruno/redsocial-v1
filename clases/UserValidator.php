<?php

class UserValidator {

    private $data = [];
    private $errors = [];

    public function __construct($data) {
        $this->data = $data;
    }

    public function validate() {
        foreach($this->data as $key => $value) {
            if(empty($value)) {
                $this->addError($key, "El campo $key no puede estar vacío");
            }
        }
        foreach($this->data as $key => $value) {
            switch($key) {
                case "nombre":
                    if(!preg_match('/^[A-ZÑÁÉÍÓÚa-zñáéíóú]{3,12}( [A-ZÑÁÉÍÓÚa-zñáéíóú]{3,12})?$/', $value)) {
                        $this->addError($key, "Se debe ingresar uno o dos nombres que contengan de 3 a 12 letras");
                    }
                    break;
                case "apellido":
                    if(!preg_match('/^[A-ZÑÁÉÍÓÚa-zñáéíóú]{3,12}( [A-ZÑÁÉÍÓÚa-zñáéíóú]{3,12})?$/', $value)) {
                        $this->addError($key, "Se debe ingresar uno o dos apellidos que contengan de 3 a 12 letras");
                    }
                    break;
                case "fecha_nacimiento":
                    if(!validateDate($value)) {
                        $this->addError($key, "La fecha ingresada no es válida");
                    } elseif(calcularDiferencia($value)->y < 13) {
                        $this->addError($key, "Debe ser mayor de 13 años");
                    }
                    break;
                case "genero":
                    if(!in_array($value, ["Hombre", "Mujer", "Otro"])) {
                        $this->addError($key, "El género solo puede ser una de las opciones disponibles");
                    }
                    break;
                case "usuario":
                    if(!preg_match('/^(?=.{3,15}$)(?![_])(?!.*[_]{2})[a-zA-Z0-9_]+(?<![_])$/', $value)) {
                        $this->addError($key, "El nombre de usuario debe tener entre 3 y 15 caracteres sin espacios, contener letras, números y el cáracter '_' (guión bajo) solo como separador");
                    }
                    break;
                case "password":
                    if(!preg_match('/^(?=.{8,}$)/', $value)) {
                        $this->addError($key, "La contraseña debe tener por los menos 8 caracteres");
                    }
                    break;
                case "password2":
                    if(!isset($this->data["password"]) || $value !== $this->data["password"]) {
                        $this->addError($key, "Las contraseñas no coinciden");
                    }
                    break;
                case "email":
                    if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->addError($key, "El dato debe tener un formato válido de email");
                    }
                    break;
            }
        }
        return $this->errors;
    }

    private function addError($key, $value){
        $this->errors[$key] = $value;
    }
}