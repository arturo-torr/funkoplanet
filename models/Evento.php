<?php

class Evento
{
    private $id;
    private $id_usuario;
    private $nombre;
    private $descripcion;
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
