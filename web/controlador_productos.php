<?php
$db = "funkoplanet";
$ruta1_ProductoDAO = 'dao/ProductoDAO.php';
$ruta2_ProductoDAO = '../dao/ProductoDAO.php';
$ruta1_FotoProDAO = 'dao/FotoProDAO.php';
$ruta2_FotoProDAO = '../dao/FotoProDAO.php';

if (file_exists($ruta1_ProductoDAO)) {
    require_once $ruta1_ProductoDAO;
}

if (file_exists($ruta2_ProductoDAO)) {
    require_once $ruta2_ProductoDAO;
}

if (file_exists($ruta1_FotoProDAO)) {
    require_once $ruta1_FotoProDAO;
}

if (file_exists($ruta2_FotoProDAO)) {
    require_once $ruta2_FotoProDAO;
}

if (isset($_GET['parametro'])) {
    $parametro = $_GET['parametro'];
}

if (isset($_POST['parametro'])) {
    $parametro = $_POST['parametro'];
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$daoProductos = new DaoProductos($db);
$daoFotosProductos = new DaoFotosProductos($db);

switch ($parametro) {
    case 'nuevosProductos':
        $daoProductos->listarNovedades();
        require_once 'views/nuevosproductos_view.php';
        break;
    case 'productClicked':
        header("Location: /funkoplanet/views/individualproduct.php?product=$id");
        exit();
        break;
    case 'productoCarrito':
        require_once '../views/mostrar_carrito.php';
        break;
    default:
        # code...
        break;
}