<?php
session_start();
require_once "../dao/UsuarioDAO.php";
require_once "../models/Usuario.php";
require_once "../dao/ProductoDAO.php";
require_once "../models/Producto.php";
require_once "../models/Pedido.php";
require_once "../models/DetPedido.php";
require_once "../dao/PedidoDAO.php";
require_once "../dao/DetPedidoDAO.php";
require_once "../dao/ReservaDAO.php";
require_once "../models/Reserva.php";

$db = "funkoplanet";
$daoUsuarios = new DaoUsuarios($db);
$daoProductos = new DaoProductos($db);
$daoPedidos = new DaoPedidos($db);
$daoDetPedidos = new DaoDetPedidos($db);
$daoReservas = new DaoReservas($db);

// Recupera el usuario que está en la sesión
$user = new Usuario();
$username = $_SESSION['usuario']['username'];
$user = $daoUsuarios->obtenerPorUsername($username);


// Recorre la sesión para obtener el precio total de los productos de la sesión
// De esta forma calcula sobre la sesión y no coge ningún elemento HTML para que no haya problemas con el precio
$totalProductos = 0;
foreach ($_SESSION['carrito'] as $index => $producto) {
    $prod = new Producto();
    $prod = $daoProductos->obtener($producto['idProducto']);
    $totalProductos += $prod->__get("precio") * $producto['cantidad'];

    $cantidadNueva = $prod->__get("uds_disponibles") - $producto['cantidad'];
    if ($cantidadNueva <= 0) {
        $prod->__set("estado", "Agotado");
        $cantidadNueva = 0;
    }
    $prod->__set("uds_disponibles", $cantidadNueva);
    $daoProductos->actualizar($prod);
}

$totalProductos = number_format($totalProductos, 2);

// Creamos un nuevo pedido
$pedido = new Pedido();
$pedido->__set("id_usuario", $user->__get("id"));
$pedido->__set("fecha", time());
$pedido->__set("total", $totalProductos);
// Inserta el nuevo pedido
$daoPedidos->insertar($pedido);

// Recuperamos ese pedido para poder tratarlo con su ID
$ultimoPedido = new Pedido();
$ultimoPedido = $daoPedidos->obtenerUltimoID($user->__get("id"), $pedido->__get("fecha"));

// Recorre el carrito de nuevo para hacer el Detalle Pedido
foreach ($_SESSION['carrito'] as $index => $producto) {
    $detpedido = new DetPedido();
    $prod = new Producto();
    $prod = $daoProductos->obtener($producto['idProducto']);
    $detpedido->__set("id_pedido", $ultimoPedido->__get("id_pedido"));
    $detpedido->__set("id_producto", $prod->__get("id"));
    $detpedido->__set("cantidad", $producto['cantidad']);
    $detpedido->__set("precio_unitario", $prod->__get("precio"));

    // Se inserta el detalle pedido
    $daoDetPedidos->insertar($detpedido);

    // Lógica para realizar las reservas de los productos
    if (strtoupper($prod->__get("estado")) == "RESERVA") {
        $reserva = new Reserva();
        $reserva->__set("id_usuario", $user->__get("id"));
        $reserva->__set("id_producto", $prod->__get("id"));
        $reserva->__set("cantidad", $producto['cantidad']);
        $reserva->__set("fecha", $pedido->__get("fecha"));

        // Inserta en BBDD la reserva
        $daoReservas->insertar($reserva);
    }

    // Array de devolverá a javascript para obtener un resumen del pedido
    $detallesPedido[] = [
        "id_pedido" => $ultimoPedido->__get("id_pedido"),
        "id_producto" => $prod->__get("id"),
        "cantidad" => $producto['cantidad'],
        "precio_unitario" => $prod->__get("precio")
    ];
}


// Vaciado de carrito
$_SESSION['carrito'] = [];

echo json_encode([
    "detalles_pedido" => $detallesPedido
]);
