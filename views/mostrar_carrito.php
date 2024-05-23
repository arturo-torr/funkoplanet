<?php
session_start();

header('Content-Type: application/json');

// Comprobar si existe un carrito en la sesión
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<div class='text-center'>";
    echo "<p class='fw-bold'>¡Ooops! Parece que todavía no tienes nada en el carrito.</p>";
    echo "<img src='/funkoplanet/assets/img/funkocarrito.png' class='img-fluid w-75' alt='Carrito-Funko'>";
    echo "</div>";
    exit;
}

$total = 0;
$totalCantidades = 0;
echo "<div class='container-fluid'>";
foreach ($_SESSION['carrito'] as $key => $prod) {
    $producto = new Producto();
    $producto = $daoProductos->obtener($prod['idProducto']);
    $daoFotosProductos->listarPorId($prod["idProducto"]);

    $conte = $daoFotosProductos->fotosPro[0]->__get("foto");

    echo "<div class='row align-items-center mb-3'>";

    echo "<div class='col-auto'>";
    echo "<img src='data:image/jpg;base64,$conte' class='img-fluid' style='max-width: 100px;'>";
    echo "</div>";

    echo "<div class='col text-center'>";
    $totalCantidades += $prod['cantidad'];
    echo "<span class='fw-bold'>x" . $prod['cantidad'] . "</span>";
    echo "</div>";

    echo "<div class='col-auto text-end'>";
    $precio = $producto->__get("precio") * $prod['cantidad'];
    $total += $precio;
    echo "<span class='fw-bold'>" . number_format($precio, 2) . "€</span>";
    echo "</div>";
    echo "</div>";
}

echo "<div class='col-auto text-end'><span class='fw-bold'>Total: " . number_format($total, 2) . "€</span></div>";
echo "<span id='span_cantidades' style='display: none'>$totalCantidades</span>";
echo "<hr>";
echo "<div class='d-flex justify-content-center'>";
echo "<button class='btn btn_purple text-white fw-bold' id='btn_finalizar'>Finalizar compra</button>";
echo "</div>";
echo "</div>";
