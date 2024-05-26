<?php

class Pedido
{
    private $id_pedido;
    private $id_usuario;
    private $fecha;
    private $total;

    public function __get($nombre)
    {
        return $this->$nombre;
    }
    public function __set($nombre, $valor)
    {
        $this->$nombre = $valor;
    }
}
