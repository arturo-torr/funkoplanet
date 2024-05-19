<?php
session_start();

header('Content-Type: application/json');

// Verificar si existe un carrito en la sesión
if (!isset($_SESSION['carrito'])) {
    // Si no hay un carrito, devolver un arreglo vacío
    echo json_encode([]);
    exit;
}

echo "<div>";
foreach ($_SESSION['carrito'] as $key => $value) {
    var_dump($value);
    echo "<p></p>";
}

echo "</div>";
