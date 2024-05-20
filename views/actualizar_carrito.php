<?php
session_start();
require_once "../dao/ProductoDAO.php";
require_once "../models/Producto.php";

$db = "funkoplanet";
$daoProductos = new DaoProductos($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $idProducto = $input['idProducto'];
    // nueva cantidad, es +1 o -1
    $cambio = $input['cambio'];

    // Busca el producto en el carrito
    foreach ($_SESSION['carrito'] as $key => &$item) {
        // Si encuentra el producto actualiza su cantidad
        if ($item['idProducto'] == $idProducto) {
            $item['cantidad'] += $cambio;

            // Si la cantidad es menor o igual a 0, elimina el producto del carrito
            if ($item['cantidad'] <= 0) {
                unset($_SESSION['carrito'][$key]);
            }

            // Si el producto no fue eliminado, calcula el nuevo precio
            if (isset($_SESSION['carrito'][$key])) {
                $producto = $daoProductos->obtener($idProducto);
                $nuevoPrecio = $producto->__get("precio") * $item['cantidad'];
            } else {
                $nuevoPrecio = 0;
            }

            // Calcular el total del carrito y el total de cantidades
            $total = 0;
            $totalCantidades = 0;
            foreach ($_SESSION['carrito'] as $prod) {
                $productoTemp = $daoProductos->obtener($prod['idProducto']);
                $total += $productoTemp->__get("precio") * $prod['cantidad'];
                $totalCantidades += $prod['cantidad'];
            }

            // Devolver la nueva cantidad y el nuevo precio
            echo json_encode([
                'success' => true,
                'nuevaCantidad' => isset($_SESSION['carrito'][$key]) ? $item['cantidad'] : 0,
                'nuevoPrecio' => $nuevoPrecio,
                'total' => $total,
                'totalCantidades' => $totalCantidades
            ]);
            exit;
        }
    }

    echo json_encode(['success' => false]);
}
