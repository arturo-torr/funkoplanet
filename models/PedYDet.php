<?php

class PedYDet
{
    private $id_pedido;
    private $fecha;
    private $cantidad;
    private $precio_unitario;
    private $id_producto;
    //private $estado;

    public function __get($nombre)
    {
        return $this->$nombre;
    }
    public function __set($nombre, $valor)
    {
        $this->$nombre = $valor;
    }
}
