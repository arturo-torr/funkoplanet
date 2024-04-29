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

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'id_usuario' => $this->id_usuario,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'fecha' => $this->fecha
        );
    }
}
