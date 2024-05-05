<?php
header('Content-Type: application/json');
require_once '../dao/CategoriaDAO.php';

$parametro = "";
if (isset($_GET['parametro'])) {
    $parametro = $_GET['parametro'];
}
if (isset($_POST['parametro'])) {
    $parametro = $_POST['parametro'];
}

$id = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
$daoCategorias = new DaoCategorias("funkoplanet");
$daoCategorias->listar();

switch ($parametro) {
    case 'categoriesMenu':
        echo json_encode($daoCategorias->categorias, JSON_UNESCAPED_UNICODE);
        break;
    case 'categoriesCentral':
        require_once '../views/categories_view.php';
        break;
    case 'categoryClicked':
        header("Location: ../views/products.php?category=$id");
        exit();
        break;
    default:
        # code...
        break;
}
