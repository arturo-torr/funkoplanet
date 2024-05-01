<?php
header('Content-Type: application/json');
require_once '../dao/ProductoDAO.php';
require_once "../dao/FotoProDAO.php";
$db = "funkoplanet";
$parametro = "";
if (isset($_GET['parametro'])) {
    $parametro = $_GET['parametro'];
}
if (isset($_POST['parametro'])) {
    $parametro = $_POST['parametro'];
}
$daoProductos = new DaoProductos($db);
$daoFotosProductos = new DaoFotosProductos($db);

switch ($parametro) {
    case 'nuevosProductos':
        $daoProductos->listarNovedades();
        require_once '../views/nuevosproductos_view.php';
        break;
    default:
        # code...
        break;
}