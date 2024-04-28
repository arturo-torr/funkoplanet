<?php

class Producto
{
    private $id;
    private $id_categoria;
    private $id_usuario;
    private $nombre;
    private $descripcion;
    private $precio;
    private $estado;

    public function __get($nombre)
    {
        return $this->$nombre;
    }
    public function __set($nombre, $valor)
    {
        $this->$nombre = $valor;
    }
}