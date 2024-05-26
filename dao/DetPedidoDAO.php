<?php

require_once '../includes/libreriaPDO.php';
require_once '../models/DetPedido.php';

class DaoDetPedidos extends DB
{
    public $detpedidos = array();

    // Al instanciar el DAO, especicamos sobre que BBDD trabajaremos
    public function __construct($base)
    {
        $this->dbname = $base;
    }

    // Método para insertar una situación que obtiene por parámetro
    public function insertar($detpedido)
    {

        // Consulta para evitar inyección de SQL
        $consulta = "INSERT INTO detpedido (id_pedido, id_producto, cantidad, precio_unitario) 
                         VALUES (:id_pedido, :id_producto, :cantidad, :precio_unitario)";
        $param = array();

        // Asignamos los valores del objeto que hemos recibido por parámetro
        $param[":id_pedido"] = $detpedido->__get("id_pedido");
        $param[":id_producto"] =  $detpedido->__get("id_producto");
        $param[":cantidad"] = $detpedido->__get("cantidad");
        $param[":precio_unitario"] =  $detpedido->__get("precio_unitario");

        // Ejecutamos la consulta
        $this->ConsultaSimple($consulta, $param);
    }

    // Método que permite listar el contenido de la tabla
    public function listar($idPedido)
    {
        $consulta = "SELECT * FROM detpedido WHERE id_pedido = :id_pedido";
        $param = array();

        // Se realiza para vaciar el array de las tiendas entre consulta y consulta
        $this->detpedidos = array();
        $param[":id_pedido"] = $idPedido;

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $detpedido = new DetPedido();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $detpedido->__set("id_pedido", $idPedido);
            $detpedido->__set("id_producto", $fila['id_producto']);
            $detpedido->__set("cantidad", $fila['cantidad']);
            $detpedido->__set("precio_unitario", $fila['precio_unitario']);
            // Se inserta el objeto que acabamos de crear en el Array de objetos tiendas
            $this->detpedidos[] = $detpedido;
        }
    }
}
