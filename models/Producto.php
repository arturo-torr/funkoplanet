<?php

class Producto
{
    private $id;
    private $id_categoria;
    private $id_usuario;
    private $nombre;
    private $descripcion;
    private $precio;
    private $uds_disponibles;
    private $estado;
    private $fecha_subida;

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
            'id_categoria' => $this->id_categoria,
            'id_usuario' => $this->id_usuario,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => $this->precio,
            'uds_disponibles' => $this->uds_disponibles,
            'estado' => $this->estado,
            'fecha_subida' => $this->fecha_subida
        );
    }
}
