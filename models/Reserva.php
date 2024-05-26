<?php

class Reserva
{
    private $id;
    private $id_usuario;
    private $id_producto;
    private $cantidad;
    private $fecha;

    public function __get($nombre)
    {
        return $this->$nombre;
    }
    public function __set($nombre, $valor)
    {
        $this->$nombre = $valor;
    }
}
