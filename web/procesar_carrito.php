<?php
session_start();

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos del producto del cuerpo de la solicitud
        $data = json_decode(file_get_contents('php://input'), true);

        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['usuario'])) {
            http_response_code(401); // No autorizado
            echo json_encode(['error' => 'El usuario no está autenticado.']);
            exit;
        }

        // Obtener el ID del producto y la cantidad
        $idProducto = $data['idProducto'];
        $cantidad = $data['cantidad'];

        // Agregar el producto al carrito de compras en la sesión
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $productoEncontrado = false;
        // Buscar el producto en el carrito y actualizar su cantidad
        foreach ($_SESSION['carrito'] as $index => $producto) {
            if ($producto['idProducto'] === $idProducto) {
                // Producto encontrado, actualizar la cantidad
                $_SESSION['carrito'][$index]['cantidad'] += $cantidad;
                $productoEncontrado = true;
                break; // Salir del bucle una vez que se haya encontrado el producto
            }
        }

        if (!$productoEncontrado) {
            // Si el producto no está en el carrito, agregarlo
            $_SESSION['carrito'][] = [
                'idProducto' => $idProducto,
                'cantidad' => $cantidad
            ];
        }

        // Devolver una respuesta de éxito
        echo json_encode(['success' => true]);
    } else {
        http_response_code(405); // Método no permitido
        echo json_encode(['error' => 'Método no permitido']);
    }
} catch (Exception $e) {
    http_response_code(500); // Error interno del servidor
    echo json_encode(['error' => $e->getMessage()]);
}
