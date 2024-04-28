<?php

class Usuario
{
    private $id;
    private $username;
    private $email;
    private $password;
    private $tipo;
    private $monedero;
    private $foto;

    public function __get($nombre)
    {
        return $this->$nombre;
    }
    public function __set($nombre, $valor)
    {
        $this->$nombre = $valor;
    }
}
