<?php

class DetPedido
{
    private $id_pedido;
    private $id_producto;
    private $cantidad;
    private $precio_unitario;

    public function __get($nombre)
    {
        return $this->$nombre;
    }
    public function __set($nombre, $valor)
    {
        $this->$nombre = $valor;
    }
}
