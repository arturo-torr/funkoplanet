<?php

require_once '../includes/libreriaPDO.php';
require_once '../models/Pedido.php';
require_once "../models/PedYDet.php";

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

    // Método que permite listar el contenido de la tabla
    public function listar($idUsuario)
    {
        $consulta = "SELECT * FROM pedido WHERE id_usuario = :id_usuario";
        $param = array();

        // Se realiza para vaciar el array de las tiendas entre consulta y consulta
        $this->pedidos = array();
        $param[":id_usuario"] = $idUsuario;

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $pedido = new Pedido();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $pedido->__set("id_pedido", $fila['id_pedido']);
            $pedido->__set("id_usuario", $idUsuario);
            $pedido->__set("fecha", $fila['fecha']);
            $pedido->__set("total", $fila['total']);
            // Se inserta el objeto que acabamos de crear en el Array de objetos tiendas
            $this->pedidos[] = $pedido;
        }
    }

    public function listarPedidoyDetPedido($idUsuario)
    {
        $consulta = "SELECT DISTINCT
        ped.id_pedido, 
        ped.fecha, 
        det.cantidad, 
        det.precio_unitario,
        res.id_producto
    FROM 
        pedido ped
    INNER JOIN 
        detpedido det ON ped.id_pedido = det.id_pedido
    INNER JOIN 
        reserva res ON det.id_producto = res.id_producto AND ped.id_usuario = res.id_usuario
    WHERE 
        ped.id_usuario = :id_usuario AND res.id_usuario = :id_usuario
    ORDER BY ped.id_pedido";


        //     $consulta = "SELECT 
        //     ped.id_pedido, 
        //     ped.fecha, 
        //     det.cantidad, 
        //     det.precio_unitario,
        //     res.id_producto
        // FROM 
        //     pedido ped
        // INNER JOIN 
        //     detpedido det ON ped.id_pedido = det.id_pedido
        // INNER JOIN 
        //     reserva res ON det.id_producto = res.id_producto
        // WHERE 
        //     ped.id_usuario = :id_usuario";

        $param = array();

        // Se realiza para vaciar el array de las tiendas entre consulta y consulta
        $this->pedidos = array();
        $param[":id_usuario"] = $idUsuario;

        // Realiza la consulta;
        $this->ConsultaDatos($consulta, $param);

        foreach ($this->filas as $fila) {
            // Creamos una nueva situación
            $pedido = new PedYDet();

            // Asignamos las propiedades correspondientes al nuevo objeto
            $pedido->__set("id_pedido", $fila['id_pedido']);
            $pedido->__set("fecha", $fila['fecha']);
            $pedido->__set("cantidad", $fila['cantidad']);
            $pedido->__set("precio_unitario", $fila['precio_unitario']);
            $pedido->__set("id_producto", $fila['id_producto']);
            // Se inserta el objeto que acabamos de crear en el Array de objetos tiendas
            $this->pedidos[] = $pedido;
        }
    }
}
