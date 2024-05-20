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
    foreach ($_SESSION['carrito'] as &$item) {
        // Si encuentra el producto actualiza su cantidad
        if ($item['idProducto'] == $idProducto) {
            $item['cantidad'] += $cambio;

            // Comprueba que la cantidad no sea menor a 1
            if ($item['cantidad'] < 1) {
                $item['cantidad'] = 1;
            }

            // Calcula el nuevo precio
            $producto = $daoProductos->obtener($idProducto);
            $nuevoPrecio = $producto->__get("precio") * $item['cantidad'];

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
                'nuevaCantidad' => $item['cantidad'],
                'nuevoPrecio' => $nuevoPrecio,
                'total' => $total,
                'totalCantidades' => $totalCantidades
            ]);
            exit;
        }
    }

    echo json_encode(['success' => false]);
}
