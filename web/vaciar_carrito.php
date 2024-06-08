<?php
session_start();

// Verificar si la sesión del carrito existe
if (isset($_SESSION['carrito'])) {
    // Vaciar el carrito
    unset($_SESSION['carrito']);
}

// Responder con un mensaje de éxito
header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Carrito vaciado correctamente']);
