<?php
class Login
{
    private $username;
    private $fecha;
    private $clave;
    private $acceso;

    public function __get($nombre)
    {
        return $this->$nombre;
    }
    public function __set($nombre, $valor)
    {
        $this->$nombre = $valor;
    }
}
