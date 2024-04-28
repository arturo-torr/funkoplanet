<?php

class Categoria
{
    private $id;
    private $nombre;
    private $descripcion;
    private $foto;

    public function __get($nombre)
    {
        return $this->$nombre;
    }
    public function __set($nombre, $valor)
    {
        $this->$nombre = $valor;
    }

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'foto' => $this->foto
        );
    }
}
