<?php
session_start();

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos del producto del cuerpo de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);

        // Comprueba si el usuario está autenticado
        if (!isset($_SESSION['usuario'])) {
            http_response_code(401);
            echo json_encode(['necesitaAutenticacion' => 'El usuario no está autenticado.']);
            exit;
        }

        // Obtener el ID del producto y la cantidad
        $idProducto = $data['idProducto'];
        $cantidad = $data['cantidad'];

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $productoEncontrado = false;
        // Busca si el producto se encuentra en el carrito
        foreach ($_SESSION['carrito'] as $index => $producto) {
            // Si lo encuentra, actualiza la cantidad y pone la vriable a true
            if ($producto['idProducto'] === $idProducto) {
                $_SESSION['carrito'][$index]['cantidad'] += $cantidad;
                $productoEncontrado = true;
                break;
            }
        }

        // Si el producto no estaba anteriormente lo añade al carrito
        if (!$productoEncontrado) {
            $_SESSION['carrito'][] = [
                'idProducto' => $idProducto,
                'cantidad' => $cantidad,
            ];
        }
        echo json_encode(['success' => true]);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
