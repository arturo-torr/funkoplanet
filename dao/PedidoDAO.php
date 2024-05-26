<?php

require_once '../includes/libreriaPDO.php';
require_once '../models/Pedido.php';

class DaoPedidos extends DB
{
    public $pedidos = array();

    // Al instanciar el DAO, especicamos sobre que BBDD trabajaremos
    public function __construct($base)
    {
        $this->dbname = $base;
    }

    // Método para insertar una situación que obtiene por parámetro
    public function insertar($pedido)
    {
        // Consulta para evitar inyección de SQL
        $consulta = "INSERT INTO pedido VALUES (NULL, :id_usuario, :fecha, :total)";
        $this->pedidos = array();
        $param = array();

        // Asignamos los valores del objeto que hemos recibido por parámetro
        $param[":id_usuario"] = $pedido->__get("id_usuario");
        $param[":fecha"] = $pedido->__get("fecha");
        $param[":total"] = $pedido->__get("total");

        // Ejecutamos la consulta
        $this->ConsultaSimple($consulta, $param);
    }

    public function obtenerUltimoID($id, $fecha)
    {
        $consulta = "SELECT * FROM pedido WHERE id_usuario = :id_usuario AND fecha = :fecha";
        $param = array(":id_usuario" => $id, ":fecha" => $fecha);

        $this->ConsultaDatos($consulta, $param);

        // Se inicializa a nulo la variable que almacenará el objeto de retorno
        $pedido = null;

        // Cómo solo puede devolver una fila, hacemos la comprobación
        $fila = $this->filas[0];

        // Creamos un nuevo producto
        $pedido = new Pedido();

        // Asignamos las propiedades correspondientes al nuevo objeto
        $pedido->__set("id_pedido", $fila['id_pedido']);
        $pedido->__set("id_usuario", $fila['id_usuario']);
        $pedido->__set("fecha", $fila['fecha']);
        $pedido->__set("total", $fila['total']);
        // Devolvemos el objeto
        return $pedido;
    }
}
