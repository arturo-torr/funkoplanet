<?php

class FotoPro
{
    private $id_foto;
    private $id_producto;
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
